<?php

namespace Opportus\WebScraper;

use Goutte\Client as HttpClient;

/**
 * The web scraper.
 *
 * @version 2.0.0
 * @package Opportus\WebScraper
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/web-scraper/blob/master/LICENSE MIT
 */
final class WebScraper implements WebScraperInterface
{
    /**
     * {@inheritdoc}
     */
    public function scrap(array $uris, array $queries) : DataInterface
    {
        $this->validateUris($uris);
        $this->validateQueries($queries);

        $uris = \array_combine($uris, $uris);
        $queries = \array_combine($queries, $queries);
        $data = array();

        try {
            // Prepares the HTTP client to fetch documents...
            $httpClient = new HttpClient();
        } catch (\Exception $exception) {
            throw new InvalidOperationException(
                \sprintf(
                    'Invalid "%s" operation"',
                    __METHOD__
                ),
                0,
                $exception
            );
        }

        foreach ($uris as $uri) {
            try {
                // Fetches the document...
                $domCrawler = $httpClient->request('GET', $uri);
            } catch (\Exception $exception) {
                throw new InvalidOperationException(
                    \sprintf(
                        'Invalid "%s" operation"',
                        __METHOD__
                    ),
                    0,
                    $exception
                );
            }

            foreach ($queries as $query) {
                try {
                    // Performs the XPath query on the current document...
                    $queryResults = $domCrawler->evaluate($query);
                } catch (\Exception $exception) {
                    throw new InvalidOperationException(
                        \sprintf(
                            'Invalid "%s" operation"',
                            __METHOD__
                        ),
                        0,
                        $exception
                    );
                }

                $serializedQueryResults = array();

                foreach ($queryResults as $queryResult) {
                    // Serializes the query result if it is a node...
                    if (\is_object($queryResult) && $queryResult instanceof \DOMNode) {
                        if ($queryResult instanceof \DOMCharacterData) {
                            $queryResult = $queryResult->nodeValue;
                        } else {
                            $childNodes = $queryResult->childNodes;
                            $queryResult = '';
                            foreach ($childNodes as $childNode) {
                                $queryResult .= $childNode->ownerDocument->saveXml($childNode);
                            }
                        }
                    }

                    $serializedQueryResults[] = $queryResult;
                }

                $data[$uri][$query] = $serializedQueryResults;
            }
        }

        return new Data($data);
    }

    /**
     * Validates URLs.
     *
     * @param array $uris
     * @throws Opportus\WebScraper\InvalidArgumentException If the argument is empty or contains anything else than valid URLs
     */
    private function validateUris(array $uris)
    {
        if (empty($uris)) {
            throw new InvalidArgumentException('Invalid "uris" argument: expecting it to contain valid URIs, got none');
        }

        foreach ($uris as $uri) {
            if (false === \filter_var($uri, \FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "uris" argument: expecting it to contain valid URIs, got "%s"',
                        $uri
                    )
                );
            }
        }
    }

    /**
     * Validates queries.
     *
     * @param array $queries
     * @throws Opportus\WebScraper\InvalidArgumentException If the argument is empty or contains anything else than correctly syntaxed XPath queries
     */
    private function validateQueries(array $queries)
    {
        if (empty($queries)) {
            throw new InvalidArgumentException('Invalid "queries" argument: expecting it to contain correctly syntaxed XPath queries, got none');
        }

        $xPath = new \DOMXPath(new \DOMDocument());
        foreach ($queries as $query) {
            if (false === $xPath->query($query)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "queries" argument: expecting it to contain correctly syntaxed XPath queries, got "%s"',
                        $query
                    )
                );
            }
        }
    }
}

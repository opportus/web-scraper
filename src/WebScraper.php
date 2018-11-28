<?php

namespace Opportus\WebScraper;

use Goutte\Client as HttpClient;

/**
 * The web scraper.
 *
 * @version 1.0.0
 * @package Opportus\WebScraper
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/web-scraper/blob/master/LICENSE MIT
 */
final class WebScraper implements WebScraperInterface
{
    /**
     * {@inheritdoc}
     */
    public function scrap(array $urls, array $queries) : DataInterface
    {
        $this->validateUrls($urls);
        $this->validateQueries($queries);

        // Prepares data to be returned...
        $data = new Data();

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

        foreach ($urls as $documentId => $url) {
            try {
                // Fetches the document...
                $domCrawler = $httpClient->request('GET', $url);
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

            foreach ($queries as $queryId => $query) {
                try {
                    //Performs the XPath query on the current document...
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

                foreach ($queryResults as $queryResult) {
                    if (\is_object($queryResult) && $queryResult instanceof \DOMNode) {
                        if ($queryResult instanceof \DOMCharacterData) {
                            $result = $queryResult->nodeValue;
                        } else {
                            $result = '';
                            foreach ($queryResult->childNodes as $childNode) {
                                $result .= $childNode->ownerDocument->saveXml($childNode);
                            }
                        }
                    }

                    // Adds the query result to the data...
                    $data->addQueryResult((string)$documentId, (string)$queryId, $result);
                }
            }
        }

        return $data;
    }

    /**
     * Validates URLs.
     *
     * @param array $urls
     * @throws Opportus\WebScraper\InvalidArgumentException If the argument is empty or contains anything else than valid URLs
     */
    private function validateUrls(array $urls)
    {
        if (empty($urls)) {
            throw new InvalidArgumentException('Invalid "urls" argument: expecting it to contain valid URLs, got none');
        }

        foreach ($urls as $url) {
            if (false === \filter_var($url, \FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "urls" argument: expecting it to contain valid URLs, got "%s"',
                        $url
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

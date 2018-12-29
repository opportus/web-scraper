<?php

namespace Opportus\WebScraper;

/**
 * The data.
 *
 * @version 2.0.0
 * @package Opportus\WebScraper
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/web-scraper/blob/master/LICENSE MIT
 */
final class Data implements DataInterface
{
    /**
     * @var array $data Structured as follow:
     * [
     *     'document_1' => [
     *         'query_1' => [
     *             'document_1_query_1_result',
     *             'document_1_query_1_result',
     *         ],
     *         'query_2' => [
     *             'document_1_query_2_result',
     *             'document_1_query_2_result',
     *         ],
     *     ],
     *     'document_2' => [
     *         'query_1' => [
     *             'document_2_query_1_result',
     *             'document_2_query_1_result',
     *         ],
     *         'query_2' => [
     *             'document_2_query_2_result',
     *             'document_2_query_2_result',
     *         ],
     *     ],
     * ]
     */
    private $data;

    /**
     * @var array $combinedData Structured as follow:
     * [
     *     'query_1' => [
     *         'document_1_query_1_result',
     *         'document_1_query_1_result',
     *     ],
     *     'query_2' => [
     *         'document_1_query_2_result',
     *         'document_1_query_2_result',
     *         'document_2_query_2_result',
     *         'document_2_query_2_result',
     *     ],
     * ]
     */
    private $combinedData;

    /**
     * Constructs the data.
     *
     * @param array $data
     * @throws Opportus\WebScraper\InvalidArgumentException If the data array is not structured as follow:
     * [
     *     'document_1' => [
     *         'query_1' => [
     *             'document_1_query_1_result',
     *         ],
     *         'query_2' => [
     *             'document_1_query_2_result',
     *             'document_1_query_2_result',
     *         ],
     *     ],
     *     'document_2' => [
     *         'query_1' => [
     *             'document_2_query_1_result',
     *         ],
     *         'query_2' => [
     *             'document_2_query_2_result',
     *             'document_2_query_2_result',
     *         ],
     *     ],
     * ]
     */
    public function __construct(array $data)
    {
        if (empty($data)) {
            throw new InvalidArgumentException('Invalid "data" argument: expecting a non empty array');
        }

        $xPath = new \DOMXPath(new \DOMDocument());

        foreach ($data as $uri => $queries) {
            if (!\is_string($uri)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "data" argument: expecting it to contain strings as keys of first level elements, got the key "%d"',
                        $uri
                    )
                );
            }

            if (false === \filter_var($uri, \FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "data" argument: expecting it to contain valid URIs as keys of first level elements, got "%s"',
                        $uri
                    )
                );
            }

            if (!\is_array($queries)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "data" argument: expecting it to contain arrays as first level elements, got an element of type "%s"',
                        \gettype($queries)
                    )
                );
            }

            if (empty($queries)) {
                throw new InvalidArgumentException('Invalid "data" argument: expecting it to contain non empty arrays as first level elements');
            }

            foreach ($queries as $query => $queryResults) {
                if (!\is_string($query)) {
                    throw new InvalidArgumentException(
                        \sprintf(
                            'Invalid "data" argument: expecting it to contain strings as keys of second level elements, got the key "%d"',
                            $query
                        )
                    );
                }

                if (false === $xPath->query($query)) {
                    throw new InvalidArgumentException(
                        \sprintf(
                            'Invalid "data" argument: expecting it to contain correctly syntaxed XPath queries as keys of second level elements, got "%s"',
                            $query
                        )
                    );
                }

                if (!\is_array($queryResults)) {
                    throw new InvalidArgumentException(
                        \sprintf(
                            'Invalid "data" argument: expecting it to contain arrays as second level elements, got an element of type "%s"',
                            \gettype($queryResults)
                        )
                    );
                }

                foreach ($queryResults as $key => $queryResult) {
                    if (!\is_int($key)) {
                        throw new InvalidArgumentException(
                            \sprintf(
                                'Invalid "data" argument: expecting it to contain integers as keys of third level elements, got the key "%s"',
                                $key
                            )
                        );
                    }

                    if (!\is_string($queryResult)) {
                        throw new InvalidArgumentException(
                            \sprintf(
                                'Invalid "data" argument: expecting it to contain strings as third level elements, got an element of type "%s"',
                                \gettype($queryResult)
                            )
                        );
                    }
                }
            }
        }

        $querySequenceModel = \array_values($data)[0];

        foreach ($data as $uri => $queries) {
            if (\array_keys($querySequenceModel) !== \array_keys($queries)) {
                throw new InvalidArgumentException('Invalid "data" argument: expecting it to contain the same query sequence across each document');
            }

            foreach ($queries as $query => $queryResults) {
                if (\count($querySequenceModel[$query]) !== \count($queryResults)) {
                    throw new InvalidArgumentException('Invalid "data" argument: expecting it to contain the same query sequence across each document');
                }
            }
        }

        $this->data = $data;
        $this->combinedData = array();

        foreach ($this->data as $uri => $queries) {
            foreach ($queries as $query =>$queryResults) {
                if (isset($this->combinedData[$query])) {
                    $this->combinedData[$query] = \array_merge($this->combinedData[$query], $queryResults);
                } else {
                    $this->combinedData[$query] = $queryResults;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryResults(string $query, string $uri = null) : array
    {
        if (isset($uri) && !isset($this->data[$uri])) {
            throw new InvalidArgumentException(
                \sprintf(
                    'Invalid "uri" argument: expecting a known URI, got "%s"',
                    $uri
                )
            );
        }
        
        if (!isset(\array_values($this->data)[0][$query])) {
            throw new InvalidArgumentException(
                \sprintf(
                    'Invalid "query" argument: expecting a known query, got "%s"',
                    $query
                )
            );
        }

        if (isset($uri)) {
            return $this->data[$uri][$query];
        }

        return $this->combinedData[$query];
    }

    /**
     * {@inheritdoc}
     */
    public function toJson() : string
    {
        return \json_encode($this->combinedData);
    }

    /**
     * {@inheritdoc}
     */
    public function toCsv(array $headers = array(), array $cellGenerators = array(), array $columns = array()) : string
    {
        foreach ($headers as $column => $header) {
            if (!isset($this->combinedData[$column])) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "headers" argument: expecting the elements to have a query as key, got "%s"',
                        $column
                    )
                );
            }

            if (!\is_string($header)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "headers" argument: expecting the array to contain string elements, got an element of type "%s" for the query "%s"',
                        \gettype($header),
                        $column
                    )
                );
            }
        }

        foreach ($cellGenerators as $column => $cellFormatter) {
            if (!isset($this->combinedData[$column])) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "cellGenerators" argument: expecting the elements to have a query as key, got "%s"',
                        $column
                    )
                );
            }

            if (!\is_callable($cellFormatter)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "cellGenerators" argument: expecting the array to contain callable elements, got an element of type "%s" for the query "%s"',
                        \gettype($cellFormatter),
                        $column
                    )
                );
            }
        }

        foreach ($columns as $column) {
            if (!isset($this->combinedData[$column])) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "columns" argument: expecting the elements to be a query, got "%s"',
                        $column
                    )
                );
            }

            if (!\is_string($column)) {
                throw new InvalidArgumentException(
                    \sprintf(
                        'Invalid "columns" argument: expecting the array to contain string elements, got an element of type "%s"',
                        \gettype($column)
                    )
                );
            }
        }

        $rows = array();

        foreach ($this->combinedData as $column => $values) {
            if ($columns && !\in_array($column, $columns)) {
                continue;
            }

            if (isset($headers[$column])) {
                if (isset($rows[-1])) {
                    $rows[-1] .= \sprintf(',"%s"', $headers[$column]);
                } else {
                    $rows[-1] = \sprintf('"%s"', $headers[$column]);
                }
            }

            foreach ($values as $row => $value) {
                if (isset($cellGenerators[$column])) {
                    $value = $cellGenerators[$column]($value, $row);

                    if (!\is_string($value)) {
                        throw new InvalidArgumentException(
                            \sprintf(
                                'Invalid "cellFormaters" argument: expecting the "%s" callbacks to return a string',
                                $column
                            )
                        );
                    }
                }

                $value = \str_replace('"', '""', $value);

                if (isset($rows[$row])) {
                    $rows[$row] .= \sprintf(',"%s"', $value);
                } else {
                    $rows[$row] = \sprintf('"%s"', $value);
                }
            }
        }

        return \implode(\PHP_EOL, $rows);
    }
}

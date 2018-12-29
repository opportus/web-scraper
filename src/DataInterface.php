<?php

namespace Opportus\WebScraper;

/**
 * The data interface.
 *
 * @version 2.0.0
 * @package Opportus\WebScraper
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/web-scraper/blob/master/LICENSE MIT
 */
interface DataInterface
{
    /**
     * Gets the query results.
     *
     * @param string $query
     * @param string $uri
     * @return array
     * @throws Opportus\WebScraper\InvalidArgumentException If eighter of the query or URI are unknown
     */
    public function getQueryResults(string $query, string $uri = null) : array;

    /**
     * Returns this as a JSON string.
     *
     * @return string Default JSON representation of an array structured as follow:
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
    public function toJson() : string;

    /**
     * Returns this as a CSV string.
     *
     * @param array $headers Structured as follow:
     * [
     *     'query_1' => 'Column Name',
     *     'query_2' => 'Column Name',
     * ]
     * @param array $cellGenerators Structured as follow:
     * [
     *     'query_1' => function ($value, $row) : string {},
     *     'query_2' => function ($value, $row) : string {},
     * ]
     * @param array $columns Structured as follow:
     * [
     *     'query_1',
     *     'query_2',
     * ]
     * @return string
     * @throws Opportus\WebScraper\InvalidArgumentException
     */
    public function toCsv(array $headers = array(), array $cellGenerators = array(), array $columns = array()) : string;
}

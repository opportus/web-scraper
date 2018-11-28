<?php

namespace Opportus\WebScraper;

/**
 * The data interface.
 *
 * @version 1.0.0
 * @package Opportus\WebScraper
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/web-scraper/blob/master/LICENSE MIT
 */
interface DataInterface
{
    /**
     * Adds a query result.
     *
     * @param string $documentId The document ID the query result is fetched from
     * @param string $queryId The query ID the query result is obtained from
     * @param string $queryResult
     */
    public function addQueryResult(string $documentId, string $queryId, string $queryResult);

    /**
     * Checks whether this is empty.
     *
     * @return bool
     */
    public function isEmpty() : bool;

    /**
     * Returns this as a PHP array.
     *
     * @return array Structured as follow:
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
    public function toArray() : array;

    /**
     * Returns this as a JSON string.
     *
     * @return string JSON representation of an array structured as follow:
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
    public function toJson() : string;
}

<?php

namespace Opportus\WebScraper;

/**
 * The data.
 *
 * @version 1.0.0
 * @package Opportus\WebScraper
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/web-scraper/blob/master/LICENSE MIT
 */
final class Data implements DataInterface
{
    /**
     * @var array $data Represented as follow:
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
    private $data = array();

    /**
     * {@inheritdoc}
     */
    public function addQueryResult(string $documentId, string $queryId, string $queryResult)
    {
        $this->data[$documentId][$queryId][] = $queryResult;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() : bool
    {
        return empty($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function toJson() : string
    {
        return \json_encode($this->data);
    }
}

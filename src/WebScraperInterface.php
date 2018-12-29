<?php

namespace Opportus\WebScraper;

/**
 * The web scraper interface.
 *
 * @version 2.0.0
 * @package Opportus\WebScraper
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/web-scraper/blob/master/LICENSE MIT
 */
interface WebScraperInterface
{
    /**
     * Scraps.
     *
     * @param array $urls
     * @param array $queries
     * @return Opportus\WebScraper\DataInterface
     * @throws Opportus\WebScraper\InvalidArgumentexception If the "urls" argument is empty or contain anything else than valid URLs and if the "queries" argument is empty or contains anything else than correctly syntaxed XPath queries
     * @throws Opportus\WebScraper\InvalidOperationException If the underlying system throws any type of exception
     */
    public function scrap(array $urls, array $queries) : DataInterface;
}

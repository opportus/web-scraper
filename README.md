# Web Scraper

A web scraper taking as arguments a list of URIs and a list of XPath queries to perform on each document. Returns an instance of [`DataInterface`](https://github.com/opportus/web-scraper/blob/master/src/DataInterface.php).

## Installation

```bash
$ composer require opportus/web-scraper
```

## Usage

```php
Use Opportus\WebScraper\WebScraper;

$uris = [
    'https://en.wikipedia.org/wiki/Web_scraping',
    'https://en.wikipedia.org/wiki/XPath',
];

$queries = [
    '//p[1][node()]',
    '//div[@id="mw-normal-catlinks"]/ul//li[node()]',
];

$scraper = new WebScraper();

$data = $scraper->scrap($uris, $queries); // @see https://github.com/opportus/web-scraper/blob/master/src/DataInterface.php
```
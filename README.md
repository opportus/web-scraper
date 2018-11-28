# Web Scraper

A web scraper taking as arguments a list of URLs and a list of XPath queries to perform on each document. Returns an instance of [`DataInterface`](https://github.com/opportus/web-scraper/blob/master/src/DataInterface.php).

Wraps [FriendsOfPHP/Goutte](https://github.com/FriendsOfPHP/Goutte).

## Usage

```php
Use Opportus\WebScraper\WebScraper;

$urls = [
    'about_web_scraping' => 'https://en.wikipedia.org/wiki/Web_scraping',
    'about_xpath'        => 'https://en.wikipedia.org/wiki/XPath',
];

$queries = [
    'description' => '//p[1][node()]',
    'categories'  => '//div[@id="mw-normal-catlinks"]/ul//li[node()]',
];

$scraper = new WebScraper();

echo $scraper->scrap($urls, $queries)->toJson();
```

Outputs (interpreted HTML):

> {  
    &nbsp;&nbsp;&nbsp;&nbsp;"about_web_scraping":{  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description":[  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"<b>Web scraping</b>, <b>web harvesting</b>, or <b>web data extraction</b> is <a href="/wiki/Data_scraping" title="Data scraping">data scraping</a> used for <a href="/wiki/Data_extraction" title="Data extraction">extracting data</a> from <a href="/wiki/Website" title="Website">websites</a>.<sup id="cite_ref-Boeing2016JPER_1-0" class="reference"><a href="#cite_note-Boeing2016JPER-1">[1]</a></sup> Web scraping software may access the World Wide Web directly using the <a href="/wiki/Hypertext_Transfer_Protocol" title="Hypertext Transfer Protocol">Hypertext Transfer Protocol</a>, or through a web browser. While web scraping can be done manually by a software user, the term typically refers to automated processes implemented using a <a href="/wiki/Internet_bot" title="Internet bot">bot</a> or <a href="/wiki/Web_crawler" title="Web crawler">web crawler</a>. It is a form of copying, in which specific data is gathered and copied from the web, typically into a central local <a href="/wiki/Database" title="Database">database</a> or spreadsheet, for later <a href="/wiki/Data_retrieval" title="Data retrieval">retrieval</a> or <a href="/wiki/Data_analysis" title="Data analysis">analysis</a>.\n"  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"categories":[  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"<a href="/wiki/Category:Web_scraping" title="Category:Web scraping">Web scraping</a>"  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]  
    &nbsp;&nbsp;&nbsp;&nbsp;},  
    &nbsp;&nbsp;&nbsp;&nbsp;"about_xpath":{  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description":[  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"<b>XPath</b> (<b>XML Path Language</b>) is a <a href="/wiki/Query_language" title="Query language">query language</a> for selecting <a href="/wiki/Node_(computer_science)" title="Node (computer science)">nodes</a> from an <a href="/wiki/XML" title="XML">XML</a> document. In addition, XPath may be used to compute values (e.g., <a href="/wiki/String_(computer_science)" title="String (computer science)">strings</a>, numbers, or <a href="/wiki/Boolean_datatype" class="mw-redirect" title="Boolean datatype">Boolean</a> values) from the content of an XML document. XPath was defined by the <a href="/wiki/World_Wide_Web_Consortium" title="World Wide Web Consortium">World Wide Web Consortium</a> (W3C).<sup id="cite_ref-timelinehistory_1-0" class="reference"><a href="#cite_note-timelinehistory-1">[1]</a></sup>\n"  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"categories":[  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"<a href="/wiki/Category:Query_languages" title="Category:Query languages">Query languages</a>",  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"<a href="/wiki/Category:XML_data_access" title="Category:XML data access">XML data access</a>"  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]  
    &nbsp;&nbsp;&nbsp;&nbsp;}  
}
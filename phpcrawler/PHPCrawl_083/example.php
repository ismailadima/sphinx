<?php

// It may take a whils to crawl a site ...
set_time_limit(100000);

// Inculde the phpcrawl-mainclass
include("libs/PHPCrawler.class.php");
include("simple_html_dom.php");

// Extend the class and override the handleDocumentInfo()-method 
class MyCrawler extends PHPCrawler 
{
  private $urls = [];
  private $index = 0;

  function handleDocumentInfo($DocInfo) 
  {
    // Just detect linebreak for output ("\n" in CLI-mode, otherwise "<br>").
    if (PHP_SAPI == "cli") $lb = "\n";
    else $lb = "<br />";
    $line = '------------------------------------------------------------------';

    $pageUrl = $DocInfo->url;
    $status = $DocInfo->http_status_code;
    $source = $DocInfo->source;

    if($status == 200 && $source != ''){
      array_push($this->urls, $pageUrl);
      
      echo $pageUrl.$lb;
      
      $html = str_get_html($source);
      if(is_object($html)){
        $f = fopen('../'.$this->index.'.txt', 'w');

        $title = $html->find('title', 0);
        if($title){
          fwrite($f, $title->innertext);
          fwrite($f, $lb);
        }
        $body = $html->find("div[id=specs-list]", 0);
        if($body){
          fwrite($f, $body->innertext);
          fwrite($f, $lb);
        }
        fclose($f);
      }

      echo $line.$lb;

      $this->index++;
    }
    




    // Print the URL and the HTTP-status-Code
    // echo "Page requested: ".$DocInfo->url." (".$DocInfo->http_status_code.")".$lb;
    
    // // Print the refering URL
    // echo "Referer-page: ".$DocInfo->referer_url.$lb;
    
    // // Print if the content of the document was be recieved or not
    // if ($DocInfo->received == true)
    //   echo "Content received: ".$DocInfo->bytes_received." bytes".$lb;
    // else
    //   echo "Content not received".$lb; 
    
    // // Now you should do something with the content of the actual
    // // received page or file ($DocInfo->source), we skip it in this example 
    
    // echo $lb;
    
    flush();
  } 

  public function getUrls(){
    return $this->urls;
  }

}

// Now, create a instance of your class, define the behaviour
// of the crawler (see class-reference for more options and details)
// and start the crawling-process.

$crawler = new MyCrawler();

// URL to crawl
$crawler->setURL("www.gsmarena.com/samsung-phones-9.php");

// Only receive content of files with content-type "text/html"
$crawler->addContentTypeReceiveRule("#text/html#");

// Ignore links to pictures, dont even request pictures
$crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");

// Store and send cookie-data like a browser does
$crawler->enableCookieHandling(true);

// Set the traffic-limit to 1 MB (in bytes,
// for testing we dont want to "suck" the whole site)
$crawler->setTrafficLimit(1000 * 4096);

// Thats enough, now here we go
$crawler->go();

// var_dump($crawler->getUrls());

// At the end, after the process is finished, we print a short
// report (see method getProcessReport() for more information)
$report = $crawler->getProcessReport();

if (PHP_SAPI == "cli") $lb = "\n";
else $lb = "<br />";
    
echo "Summary:".$lb;
echo "Links followed: ".$report->links_followed.$lb;
echo "Documents received: ".$report->files_received.$lb;
echo "Bytes received: ".$report->bytes_received." bytes".$lb;
echo "Process runtime: ".$report->process_runtime." sec".$lb; 
?>
<?php
class WebScraping { // Declaring class variables and arrays public $url;
    public $source;
    // Construct method called on instantiation of object
     function __construct($url) {
    // Setting URL attribute
     $this->url = $url;
    //passing the url to our function
     $this->source = $this->getCurl($this->url);
    // passing the return value from getCurl function $this->pathObj= $this->getXPathObj($this->source);
    }
    // Method for making a GET request using cURL
     public function getCurl($url) {
    // Initialising cURL session $ch = curl_init();
    // Setting cURL options
    // Returning transfer as a string
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // Setting URL
     curl_setopt($ch, CURLOPT_URL, $url);
    // Executing cURL session
     $results = curl_exec($ch);
    // Closing cURL session
     curl_close($ch);
    // Return the results return $results;
    }
    // Method to get XPath object
     public function getXPathObj($item) {
    // Instantiating a new DomDocument object
     $xmlPageDom = new DomDocument();
    // Loading the HTML from downloaded page
     @$xmlPageDom->loadHTML($item);
    // Instantiating new XPath DOM object $xmlPageXPath = new DOMXPath($xmlPageDom); return $xmlPageXPath; //get xpath }
    }
}
?>
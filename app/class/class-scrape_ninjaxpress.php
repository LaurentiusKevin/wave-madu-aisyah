<?php
use JonnyW\PhantomJs\Client;

$client = Client::getInstance();
$client->getEngine()->setPath('plugin/php-phantomjs/vendor/php-phantomjs/vendor');
?>
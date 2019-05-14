<?php
// $url = str_replace('/','',$_SERVER['REQUEST_URI']);
// $url = $_SERVER['REQUEST_URI'];
$url = substr($_SERVER['REQUEST_URI'],1);
$data = explode('/',$url);
print_r($data);
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once './pear/Net_URL/URL.php';

$url = new Net_URL;
$url->setOption('encode_query_keys', true);
print_r($url->querystring);
?>

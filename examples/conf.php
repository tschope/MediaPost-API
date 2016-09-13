<?php

include dirname(dirname(__FILE__)).'/vendor/autoload.php';

use MediaPost\API\MapiClient;

$ConsumerKey	= "";
$ConsumerSecret = "";
$Token		    = "";
$TokenSecret	= "";

$mapi = new MapiClient($ConsumerKey, $ConsumerSecret, $Token, $TokenSecret);

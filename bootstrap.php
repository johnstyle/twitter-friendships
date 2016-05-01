<?php

set_time_limit(0);
ini_set('memory_limit', '512M');

use Core\TwitterApi;

include realpath(__DIR__) . '/settings/settings.php';
include realpath(__DIR__) . '/settings/settings.default.php';
include realpath(__DIR__) . '/vendor/autoload.php';

TwitterApi::$searches = include realpath(__DIR__) . '/settings/searches.php';

return new TwitterApi(
    TWITTER_CONSUMER_KEY,
    TWITTER_CONSUMER_SECRET,
    TWITTER_OAUTH_TOKEN,
    TWITTER_OAUTH_TOKEN_SECRET
);

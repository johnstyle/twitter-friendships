<?php

defined('TWITTER_SCREEN_NAME') OR define('TWITTER_SCREEN_NAME', null);
defined('TWITTER_CONSUMER_KEY') OR define('TWITTER_CONSUMER_KEY', null);
defined('TWITTER_CONSUMER_SECRET') OR define('TWITTER_CONSUMER_SECRET', null);
defined('TWITTER_OAUTH_TOKEN') OR define('TWITTER_OAUTH_TOKEN', null);
defined('TWITTER_OAUTH_TOKEN_SECRET') OR define('TWITTER_OAUTH_TOKEN_SECRET', null);

defined('DEFAULT_MIN_FOLLOWERS') OR define('DEFAULT_MIN_FOLLOWERS', 500);
defined('DEFAULT_MIN_FRIENDS') OR define('DEFAULT_MIN_FRIENDS', 250);
defined('DEFAULT_MIN_TWEETS') OR define('DEFAULT_MIN_TWEETS', 500);
defined('DEFAULT_MIN_FRIENDS_RATIO') OR define('DEFAULT_MIN_FRIENDS_RATIO', 0.1);
defined('DEFAULT_MAX_FRIENDS_RATIO') OR define('DEFAULT_MAX_FRIENDS_RATIO', 0.75);
defined('DEFAULT_MIN_ACTIVITY_DAYS') OR define('DEFAULT_MIN_ACTIVITY_DAYS', 30);
defined('DEFAULT_UNFOLLOW_DAYS') OR define('DEFAULT_UNFOLLOW_DAYS', 30);
defined('DEFAULT_REFOLLOW_DAYS') OR define('DEFAULT_REFOLLOW_DAYS', 30 * 6);
defined('DEFAULT_LANGUAGE') OR define('DEFAULT_LANGUAGE', 'fr');
defined('DEFAULT_MAX_FOLLOW_PER_DAY') OR define('DEFAULT_MAX_FOLLOW_PER_DAY', 1);

defined('DIR_ROOT') OR define('DIR_ROOT', realpath(__DIR__) . '/..');
defined('DIR_CACHE') OR define('DIR_CACHE', DIR_ROOT . '/cache');
defined('DIR_DATA') OR define('DIR_DATA', DIR_ROOT . '/data');

defined('DEFAULT_CACHE_TIME') OR define('DEFAULT_CACHE_TIME', 3600*24*7);

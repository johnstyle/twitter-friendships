<?php

namespace Core;

/**
 * Class Console
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Model
 */
class Console
{
    /**
     * @param $title
     */
    public static function title($title)
    {
        echo "\n";
        echo str_repeat('=', 110) . "\n";
        echo $title . "\n";
        echo str_repeat('=', 110) . "\n";
    }

    /**
     * @param $title
     */
    public static function header($title)
    {
        static::title($title);
        echo str_pad('Profil', 50, ' ', STR_PAD_RIGHT) . ' | ' .
            str_pad('Tweets', 9, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad('Fiends', 9, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad('Followers', 9, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad('Ratio', 5, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad('Lang', 4, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad('Last tweet', 19, ' ', STR_PAD_RIGHT) . ' | ' .
            str_pad('Date add', 19, ' ', STR_PAD_RIGHT) . ' | ' .
            str_pad('Search term', 20, ' ', STR_PAD_RIGHT) .
            "\n";
        echo str_repeat('-', 110) . "\n";
    }

    /**
     * @param array $data
     */
    public static function log(array $data)
    {
        echo str_pad('https://twitter.com/' . $data['screen_name'], 50, ' ', STR_PAD_RIGHT) . ' | ' .
            str_pad(number_format($data['statuses_count'], 0, '.', ' '), 9, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad(number_format($data['friends_count'], 0, '.', ' '), 9, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad(number_format($data['followers_count'], 0, '.', ' '), 9, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad(number_format($data['ratio'], 2, '.', ' '), 5, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad($data['lang'], 4, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad($data['last_status'], 19, ' ', STR_PAD_RIGHT) . ' | ' .
            str_pad($data['date'], 19, ' ', STR_PAD_RIGHT) . ' | ' .
            str_pad($data['search_term'], 20, ' ', STR_PAD_RIGHT) .
            "\n";
    }

    /**
     * @param $count
     * @param $total
     */
    public static function total($count, $total = null)
    {
        echo str_repeat('-', 110) . "\n";
        echo 'Total: ' . $count . (null !== $total ? " / " . $total : '') . " users\n";
        echo str_repeat('-', 110) . "\n";
    }
}

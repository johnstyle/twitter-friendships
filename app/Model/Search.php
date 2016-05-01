<?php

namespace Model;

/**
 * Class Search
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Model
 */
class Search extends User
{
    const FILE = '/searches.json';

    protected $search_term = null;

    public static $data;
}

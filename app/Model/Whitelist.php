<?php

namespace Model;

/**
 * Class Whitelist
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Model
 */
class Whitelist extends User
{
    const FILE = '/whitelist.json';

    public static $data;
}

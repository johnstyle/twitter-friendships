<?php

namespace Model;

/**
 * Class Unfollower
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Model
 */
class Unfollower extends User
{
    const FILE = '/unfollowers.json';

    public static $data;
}

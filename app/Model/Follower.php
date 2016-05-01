<?php

namespace Model;

/**
 * Class Follower
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Model
 */
class Follower extends User
{
    const FILE = '/followers.json';

    public static $data;
}

<?php

namespace Model;

use Core\Model;

/**
 * Class User
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Model
 */
class User extends Model
{
    const FILE = '/users.json';

    public static $data;

    protected $name = null;
    protected $screen_name = null;
    protected $profile_image_url_https = null;
    protected $followers_count = null;
    protected $friends_count = null;
    protected $statuses_count = null;
    protected $favourites_count = null;
    protected $lang = null;
    protected $location = null;
    protected $description = null;
    protected $verified = null;
    protected $protected = null;
    protected $created_at = null;
    protected $last_status = null;

    /**
     * @param  array $data
     * @return $this
     */
    public function hydrate(array $data)
    {
        parent::hydrate($data);

        $this->last_status = isset($data['status']['created_at']) ? (new \DateTime($data['status']['created_at']))->format('Y-m-d H:i:s') : null;

        return $this;
    }
}

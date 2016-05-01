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

    /**
     * @return array
     */
    public static function getUserToFollow()
    {
        $users          = [];
        $searches       = Model::getList('searches');
        $followers      = Model::getList('followers');
        $friends        = Model::getList('friends');
        $whitelist      = Model::getList('whitelist');
        $blocks         = Model::getList('blocks');
        $followed       = Model::getList('actions/followed');
        $unfollowed     = Model::getList('actions/unfollowed');
        $usersList      = array_merge($followers, $friends, $whitelist, $blocks, $followed);

        if (!count($followers)
            || !count($friends)
            || !count($searches)) {

            return null;
        }

        $unfollowedDate = (new \DateTime())->modify('-' . DEFAULT_REFOLLOW_DAYS . ' days')->format('Y-m-d');
        $activityDate   = (new \DateTime())->modify('-' . DEFAULT_MIN_ACTIVITY_DAYS . ' days')->format('Y-m-d');

        foreach ($searches as $userId => $user) {

            $user['ratio'] = 0 !== (int) $user['followers_count'] ? (int) $user['friends_count'] / (int) $user['followers_count'] : 0;

            if (array_key_exists($userId, $usersList)
                || (array_key_exists($userId, $unfollowed)
                    && (new \DateTime($unfollowed[$userId]['date']))->format('Y-m-d') > $unfollowedDate)
                || 0 !== (int) $user['protected']
                || '' === (string) $user['profile_image_url_https']
                || DEFAULT_MIN_TWEETS > (int) $user['statuses_count']
                || DEFAULT_MIN_FRIENDS > (int) $user['friends_count']
                || DEFAULT_MIN_FOLLOWERS > (int) $user['followers_count']
                || DEFAULT_MIN_FRIENDS_RATIO > $user['ratio']
                || DEFAULT_MAX_FRIENDS_RATIO < $user['ratio']
                || DEFAULT_LANGUAGE !== (string) $user['lang']
                || (new \DateTime($user['last_status']))->format('Y-m-d') < $activityDate
                || (string) $user['screen_name'] === (string) TWITTER_SCREEN_NAME
            ) {

                continue;
            }

            $users[$userId] = $user;
        }

        $usersByFolowers = [];

        foreach ($users as $id => $user) {

            $usersByFolowers[$id] = $user['followers_count'];
        }

        array_multisort($usersByFolowers, SORT_NUMERIC, SORT_DESC, $users);
        $users = array_slice($users, 0, DEFAULT_MAX_FOLLOW_PER_DAY);

        return [
            'total' => count($searches),
            'count' => count($users),
            'items' => $users,
        ];
    }

    /**
     * @return array
     */
    public static function getUserToUnfollow()
    {
        $users         = [];
        $followers     = Model::getList('followers');
        $friends       = Model::getList('friends');
        $whitelist     = Model::getList('whitelist');
        $followed      = Model::getList('actions/followed');
        $unfollowUsers = array_diff_key($friends, $followers, $whitelist);

        if (!count($followers)
            || !count($friends)
            || !count($unfollowUsers)) {

            return null;
        }

        $followedDate = (new \DateTime())->modify('-' . DEFAULT_UNFOLLOW_DAYS . ' days')->format('Y-m-d');

        foreach ($unfollowUsers as $userId => $user) {

            if (!array_key_exists($userId, $friends)
                || !array_key_exists($userId, $followed)
                || (new \DateTime($followed[$userId]['date']))->format('Y-m-d') > $followedDate) {

                continue;
            }

            $users[$userId] = $user;
        }

        return [
            'total' => count($unfollowUsers),
            'count' => count($users),
            'items' => $users,
        ];
    }
}

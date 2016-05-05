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

            $user['ratio']   = 0 !== (int) $user['followers_count'] ? (int) $user['friends_count'] / (int) $user['followers_count'] : 0;
            $user['context'] = $user['search_term'];

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

        $usersTotal = count($users);
        $usersSort  = [];

        foreach ($users as $id => $user) {

            $usersSort[$id] = $user['followers_count'];
        }

        array_multisort($usersSort, SORT_NUMERIC, SORT_DESC, $users);

        return [
            'searches' => count($searches),
            'total'    => $usersTotal,
            'count'    => count($users),
            'items'    => array_slice($users, 0, DEFAULT_MAX_FOLLOW_PER_DAY),
        ];
    }

    /**
     * @param     $type
     * @param int $limit
     *
     * @return array|null
     */
    public static function getUserToUnfollow($type, $limit = DEFAULT_MAX_FOLLOW_PER_DAY)
    {
        $users         = [];
        $searches      = [];
        $followers     = Model::getList('followers');
        $friends       = Model::getList('friends');
        $whitelist     = Model::getList('whitelist');
        $followed      = Model::getList('actions/followed');

        if (!count($followers)
            || !count($friends)) {

            return null;
        }

        switch ($type) {

            case 'nofollowback':

                $followedDate = (new \DateTime())->modify('-' . DEFAULT_UNFOLLOW_DAYS . ' days')->format('Y-m-d');

                foreach (array_diff_key($friends, $followers, $whitelist) as $userId => $user) {

                    if (!array_key_exists($userId, $friends)
                        || !array_key_exists($userId, $followed)
                        || (new \DateTime($followed[$userId]['date']))->format('Y-m-d') > $followedDate) {

                        continue;
                    }

                    $users[$userId] = $user;
                }
                break;

            case 'inactive':

                $activityDate = (new \DateTime())->modify('-6 month')->format('Y-m-d');

                foreach (array_diff_key($friends, $whitelist) as $userId => $user) {

                    if (!array_key_exists($userId, $friends)) {

                        continue;
                    }

                    $user['ratio']   = 0 !== (int) $user['followers_count'] ? (int) $user['friends_count'] / (int) $user['followers_count'] : 0;
                    $user['context'] = null;

                    if (0 !== (int) $user['protected']) {

                        $user['context'] = 'protected';

                    } elseif ('' === (string) $user['profile_image_url_https']) {

                        $user['context'] = 'profile_image_url_https';

                    } elseif (DEFAULT_LANGUAGE !== (string) $user['lang']) {

                        $user['context'] = 'lang';

                    } elseif ((new \DateTime($user['last_status']))->format('Y-m-d') < $activityDate) {

                        $user['context'] = 'last_status';

                    } elseif (50 > (int) $user['statuses_count']) {

                        $user['context'] = 'statuses_count';

                    } elseif (50 > (int) $user['friends_count']) {

                        $user['context'] = 'friends_count';

                    } elseif (50 > (int) $user['followers_count']) {

                        $user['context'] = 'followers_count';

                    } elseif (3 < (int) $user['ratio']) {

                        $user['context'] = 'max_ratio';
                    }

                    if ($user['context']) {

                        $users[$userId] = $user;
                    }
                }
                break;
        }

        if (!count($users)) {

            return null;
        }

        $usersTotal = count($users);
        $usersSort  = [];

        foreach ($users as $id => $user) {

            $usersSort[$id] = $user['date'];
        }

        array_multisort($usersSort, SORT_STRING, SORT_ASC, $users);

        return [
            'total' => count($searches),
            'count' => $usersTotal,
            'items' => array_slice($users, 0, $limit),
        ];
    }
}

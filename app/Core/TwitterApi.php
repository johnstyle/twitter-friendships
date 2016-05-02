<?php

namespace Core;

/**
 * Class TwitterApi
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Core
 */
class TwitterApi extends \TwitterOAuth
{
    /** @var array $searches */
    public static $searches;

    /**
     * @param string $url
     * @param array  $parameters
     * @param string $model
     * @param  int   $cache
     * @param  int   $sleep
     */
    public function sync($url, array $parameters = array(), $model, $cache = null, $sleep = 10)
    {
        $responseHash = null;
        $cursor = -1;

        echo "\n";
        echo $url . "\n";
        echo str_repeat('-', 110) . "\n";

        do {

            switch($url) {

                case 'users/search':

                    if(-1 === $cursor) {

                        $cursor = 1;
                    }

                    $parameters = array_merge($parameters, array(
                        'page' => $cursor,
                    ));

                    $response = $this->getCached($url, $parameters, DIR_CACHE . '/' . md5($url . json_encode($parameters)) . '.json', $cache, $sleep);

                    if($response) {

                        foreach ($response as &$responseItem) {

                            $responseItem['search_term'] = $parameters['q'];
                        }

                        $response = array(
                            'users' => $response
                        );
                    }
                    break;

                default:

                    $parameters = array_merge($parameters, array(
                        'cursor' => $cursor,
                    ));

                    $response = $this->getCached($url, $parameters, DIR_CACHE . '/' . md5($url . json_encode($parameters)) . '.json', $cache, $sleep);
                    break;
            }

            echo json_encode($parameters) ."\n";

            $responseHashNext = (string) md5(json_encode($response));

            if($response
                && $responseHash !== $responseHashNext) {

                $responseHash = $responseHashNext;

                foreach($response['users'] as $item) {

                    $model::sync($item);
                }

                $model::save();

                if(isset($response['next_cursor'])) {

                    $cursor = (int) $response['next_cursor'];

                } else {

                    $cursor++;
                }

            } else {

                $cursor = 0;
            }

        } while ((int) $cursor !== 0);
    }

    /**
     * @param  string $url
     * @param  array  $parameters
     * @param  string $file
     * @param  int    $cache
     * @param  int    $sleep
     * @return array
     */
    public function getCached($url, array $parameters = array(), $file, $cache = DEFAULT_CACHE_TIME, $sleep = 10)
    {
        if(is_null($cache)
            || !file_exists($file)
            || filemtime($file) < (time() - $cache)) {

            if(!is_null($sleep)) {

                sleep($sleep);
            }

            $response = $this->get($url, $parameters);

            if(!$response
                || isset($response['errors'])) {

                print_r($response);
                unlink($file);
                exit;
            }

            file_put_contents($file, json_encode($response));
        }

        return json_decode(file_get_contents($file), true);
    }

    /**
     * @param  string $url
     * @param  array  $parameters
     * @return array
     */
    public function get($url, $parameters = array())
    {
        $response = parent::get($url, $parameters);

        return json_decode(json_encode($response), true);
    }
}

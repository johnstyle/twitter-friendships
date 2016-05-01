<?php

namespace Core;

/**
 * Class Model
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Core
 */
class Model
{
    protected $id = null;
    protected $date = null;

    /**
     * Model constructor.
     *
     * @param null $id
     */
    public function __construct($id = null)
    {
        if (null !== $id) {

            $this->id = (int) $id;
        }

        if (null === $this->date) {

            $this->date = date('Y-m-d H:i:s');
        }
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {

            return $this->{$name};
        }

        return null;
    }

    /**
     * @param  array $data
     * @return $this
     */
    public function hydrate(array $data)
    {
        foreach ($data as $name => $value) {

            if (!property_exists($this, $name)) {

                continue;
            }

            $this->{$name} = $value;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * @param  array $filters
     * @return array
     */
    public static function load(array $filters = null)
    {
        if (null === static::$data) {

            static::$data = static::getList(static::FILE);
        }

        return static::$data;
    }

    /**
     * @return array
     */
    public static function sync(array $data)
    {
        static::$data['_' . $data['id']] = (new static($data['id']))->hydrate($data)->getData();
    }

    /**
     * @return array
     */
    public static function save()
    {
        if (null !== static::$data) {

            file_put_contents(DIR_DATA . static::FILE, json_encode(static::$data));
        }
    }

    /**
     * @return int
     */
    public static function count()
    {
        return count(static::getList(static::FILE));
    }

    /**
     * @param $file
     *
     * @return array
     */
    public static function getList($file)
    {
        $file = DIR_DATA . '/' . basename(ltrim($file, '/'), '.json') . '.json';

        return file_exists($file)
            ? json_decode(file_get_contents($file), true)
            : [];
    }
}

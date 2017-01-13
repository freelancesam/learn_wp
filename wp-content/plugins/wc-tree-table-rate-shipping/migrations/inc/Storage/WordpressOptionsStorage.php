<?php
namespace Trs\Migration\Storage;


class WordpressOptionsStorage implements IStorage
{
    public function __construct($autoload = null)
    {
        $this->autoload = $autoload;
    }

    public function get($key, $default = null)
    {
        return get_option($key, $default);
    }

    public function set($key, $value)
    {
        update_option($key, $value, $this->autoload);
    }

    private $autoload;
}
<?php
namespace Trs\Migration\Storage;


class StorageBucket implements IStorage
{
    public function __construct(IStorage $wrappee, $keyPrefix)
    {
        $this->wrappee = $wrappee;
        $this->keyPrefix = $keyPrefix;
    }

    public function get($key, $default = null)
    {
        return $this->wrappee->get($this->key($key), $default);
    }

    public function set($key, $value)
    {
        $this->wrappee->set($this->key($key), $value);
    }


    private $wrappee;
    private $keyPrefix;

    private function key($key)
    {
        return "{$this->keyPrefix}{$key}";
    }
}
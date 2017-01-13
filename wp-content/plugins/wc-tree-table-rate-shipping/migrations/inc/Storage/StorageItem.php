<?php
namespace Trs\Migration\Storage;


class StorageItem implements IStorageItem
{
    public function __construct(IStorage $storage, $key)
    {
        $this->storage = $storage;
        $this->key = $key;
    }

    public function get()
    {
        return $this->storage->get($this->key);
    }

    public function set($value)
    {
        return $this->storage->set($this->key, $value);
    }


    private $storage;
    private $key;
}
<?php
namespace Trs\Core\Loader;


interface ILoader
{
    /**
     * @param object $object
     * @return mixed
     */
    public function load($object);
}
<?php
namespace Trs\Migration\Storage;


interface IStorage
{
    function get($key, $default = false);
    function set($key, $value);
}
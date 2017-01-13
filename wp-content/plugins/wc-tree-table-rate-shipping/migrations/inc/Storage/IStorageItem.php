<?php
namespace Trs\Migration\Storage;


interface IStorageItem
{
    function get();
    function set($value);
}
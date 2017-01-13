<?php
namespace Trs\Core\Interfaces;


interface IAttribute
{
    /**
     * @param IPackage $package
     * @return mixed
     */
    function getValue(IPackage $package);
}
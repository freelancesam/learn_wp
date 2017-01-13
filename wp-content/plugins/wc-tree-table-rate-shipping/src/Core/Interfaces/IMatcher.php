<?php
namespace Trs\Core\Interfaces;


interface IMatcher
{
    /**
     * @param IPackage $package
     * @return IPackage|null
     */
    function getMatchingPackage(IPackage $package);

    /**
     * @return bool
     */
    function isCapturingMatcher();
}
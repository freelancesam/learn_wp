<?php
namespace Trs\Core\Interfaces;

use Trs\Core\Model\Customer;
use Trs\Core\Model\Destination;


interface IPackage extends IItemAggregatables
{
    const NONE_VIRTUAL_TERM_ID = '-1';

    /**
     * @return IItem[]
     */
    function getItems();
    
    /**
     * @return bool
     */
    function isEmpty();

    /**
     * @return Destination|null
     */
    function getDestination();

    /**
     * @return Customer|null
     */
    function getCustomer();
    
    /**
     * @param IGrouping $by
     * @return IPackage[]
     */
    function split(IGrouping $by);

    /**
     * @param IPackage[]|IPackage $with
     * @return IPackage
     */
    function merge($with);

    /**
     * @param IPackage[]|IPackage $other
     * @return IPackage
     */
    function exclude($other);
}
<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IAttribute;
use Trs\Core\Interfaces\IPackage;


class CustomerRolesAttribute implements IAttribute
{
    public function getValue(IPackage $package)
    {
        $roles = array();

        if ($customer = $package->getCustomer())
        if ($customerId = $customer->getId())
        if ($wpuser = get_userdata($customerId)) {
            $roles = $wpuser->roles;
        }

        return $roles;
    }
}
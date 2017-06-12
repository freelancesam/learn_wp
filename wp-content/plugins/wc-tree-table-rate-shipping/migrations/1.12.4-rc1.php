<?php
namespace Trs\Migration;

use Trs\Common\Arrays;
use Trs\Migration\Interfaces\IPerRuleMigration;
use Trs\Woocommerce\Model\Shipping\Exceptions\MalformedPersistentId;
use Trs\Woocommerce\Model\Shipping\ShippingMethodPersistentId;


class Migration_1_12_4_rc1 implements IPerRuleMigration
{
    public function migrateRule(array &$rule)
    {
        self::updateShippingMethodReferencesFormat($rule);
    }

    static private function updateShippingMethodReferencesFormat(array &$rule)
    {
        foreach ($rule['operations']['list'] as &$operation) {

            if (@$operation['operation'] === 'add' && isset($operation['calculator'])) {

                $calculator = &$operation['calculator'];

                if (@$calculator['calculator'] === 'shipping_method' &&
                    isset($calculator['ids']) && is_array($ids = &$calculator['ids'])) {

                    $ids = Arrays::map($ids, function($id) {

                        try {
                            ShippingMethodPersistentId::unserialize($id);
                        } catch (MalformedPersistentId $e) {
                            $id = new ShippingMethodPersistentId(true, $id);
                            $id = $id->serialize();
                        }

                        return $id;
                    });

                }
            }
        }
    }
}

return new Migration_1_12_4_rc1();
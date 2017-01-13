<?php
namespace Trs\Migration;

use Trs\Migration\Interfaces\IPerRuleMigration;


class Migration_1_5_0 implements IPerRuleMigration
{
    public function migrateRule(array &$rule)
    {
        $defaultPriceKind = wc_prices_include_tax()
            ? 2 /* with tax */
            : 0 /* base */;

        foreach ($rule['conditions']['list'] as &$condition) {
            if ($condition['condition'] === 'price' && !isset($condition['price_kind'])) {
                $condition['price_kind'] = $defaultPriceKind;
            }
        }

        foreach ($rule['operations']['list'] as &$operation) {

            if (!isset($operation['calculator'])) {
                continue;
            }

            $calculator = &$operation['calculator'];

            if ($calculator['calculator'] === 'percentage' &&
                $calculator['target'] === 'package_price' &&
                !isset($calculator['price_kind'])
            ) {

                $calculator['price_kind'] = $defaultPriceKind;
            }
        }
    }
}

return new Migration_1_5_0();
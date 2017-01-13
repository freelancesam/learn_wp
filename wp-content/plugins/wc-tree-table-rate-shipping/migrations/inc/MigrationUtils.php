<?php
namespace Trs\Migration;


class MigrationUtils
{
    static public function visitRulesRecursively(array &$rule, $callback)
    {
        $callback($rule);

        if (!empty($rule['children'])) {
            foreach ($rule['children'] as &$child) {
                self::visitRulesRecursively($child, $callback);
            }
        }
        
    }
}
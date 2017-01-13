<?php
namespace Trs\Migration;

use Trs\Migration\Interfaces\IConfigMigration;
use Trs\Migration\Interfaces\IMigration;
use Trs\Migration\Interfaces\IPerRuleMigration;


class GeneralMigrationAdapter implements IMigration
{
    /** @var IMigration|IConfigMigration|IPerRuleMigration */
    public function __construct($migration)
    {
        $this->migration = $migration;
    }

    public function migrate(array &$config)
    {
        $migration = $this->migration;
        
        if ($migration instanceof IPerRuleMigration) {
            MigrationUtils::visitRulesRecursively($config, function(&$rule) use($migration) {
                $migration->migrateRule($rule);
            });
        }
        
        if ($migration instanceof IConfigMigration) {
            $migration->migrateConfig($config);
        }
        
        if ($migration instanceof IMigration) {
            $migration->migrate($config);
        }
    }

    private $migration;
}

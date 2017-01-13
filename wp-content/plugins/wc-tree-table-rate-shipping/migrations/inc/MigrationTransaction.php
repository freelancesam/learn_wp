<?php
namespace Trs\Migration;

use Trs\Migration\Backup\ConfigBackup;
use Trs\Migration\Backup\ConfigBackupStorage;
use Trs\Migration\Interfaces\IMigration;
use Trs\Migration\Storage\IStorageItem;


class MigrationTransaction
{
    public function __construct(array $migrations, IStorageItem $config, ConfigBackupStorage $configBackups)
    {
        $this->migrations = $migrations;
        $this->config = $config;
        $this->configBackups = $configBackups;
    }
    
    public function run()
    {
        if (!$this->migrations) {
            return;
        }
        
        $settings = $this->config->get();

        if (($json = @$settings['rule']) !== null && ($rule = json_decode($json, true))) {
            
            foreach ($this->migrations as $migration) {
                $migration->migrate($rule);
            }
            
            $newJson = json_encode($rule);
            
            $this->configBackups->push(new ConfigBackup($json, null, true));

            $settings['rule'] = $newJson;
            $this->config->set($settings);
        }
    }
    
    /** @var IMigration[] */
    private $migrations = array();
    private $config;
    private $configBackups;
}
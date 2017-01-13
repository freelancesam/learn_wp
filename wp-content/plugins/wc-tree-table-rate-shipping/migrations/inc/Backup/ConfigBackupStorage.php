<?php
namespace Trs\Migration\Backup;

use Trs\Common\ClassNameAware;
use Trs\Migration\Storage\IStorage;
use Trs\Migration\Storage\StorageBucket;
use Trs\Migration\Storage\WordpressOptionsStorage;


class ConfigBackupStorage extends ClassNameAware
{
    public static function backup($prefix, $serializedConfig)
    {
        $backups = new self(
            new StorageBucket(
                new WordpressOptionsStorage(),
                $prefix
            ),
            'uniqid'
        );
        
        $backups->push(new ConfigBackup($serializedConfig, new \DateTime(), true));
    }
    
    public function __construct(IStorage $bucket, $uniqidGenerator)
    {
        $this->bucket = $bucket;
        $this->uniqidGenerator = $uniqidGenerator;
    }

    public function push(ConfigBackup $backup)
    {
        $uniqid = $this->uniqidGenerator;
        while ($this->bucket->get($key = $uniqid(), false) !== false);
        $this->bucket->set($key, $backup->toJson());
    }

    private $bucket;
    private $uniqidGenerator;
}
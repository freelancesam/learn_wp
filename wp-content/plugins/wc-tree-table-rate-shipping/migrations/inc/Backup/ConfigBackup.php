<?php
namespace Trs\Migration\Backup;

use DateTime;


class ConfigBackup
{
    static public function fromJson($json)
    {
        $backupData = json_decode($json, true);

        return new self(
            $backupData['config'], new DateTime("@{$backupData['time']}"), true
        );
    }

    public function __construct($config, DateTime $time = null, $configIsSerialized = false)
    {
        if (!isset($time)) {
            $time = new DateTime();
        }

        $this->config = $config;
        $this->time = $time;
        $this->configIsSerialized = $configIsSerialized;
    }

    public function getConfig()
    {
        if ($this->configIsSerialized) {
            $this->config = json_decode($this->config, true);
            $this->configIsSerialized = false;
        }

        return $this->config;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function toJson()
    {
        return json_encode(array(
            'time' => $this->time->getTimestamp(),
            'config' => json_encode($this->config)
        ));
    }

    /** @var DateTime */
    private $time;
    private $config;
    private $configIsSerialized = false;
}
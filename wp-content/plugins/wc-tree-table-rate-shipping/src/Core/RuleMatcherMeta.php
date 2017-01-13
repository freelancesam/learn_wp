<?php
namespace Trs\Core;

use Trs\Core\Interfaces\IGrouping;

/**
 * @property-read bool $capture
 * @property-read IGrouping $grouping
 * @property-read bool $requireAllPackages
 */
class RuleMatcherMeta
{
    public function __construct($capture, IGrouping $grouping, $requireAllPackages = false)
    {
        $this->capture = $capture;
        $this->grouping = $grouping;
        $this->requireAllPackages = $requireAllPackages;
    }

    public function __get($property)
    {
        return $this->{$property};
    }

    private $capture;
    private $grouping;
    private $requireAllPackages;
}
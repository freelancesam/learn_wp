<?php
namespace Trs\Core\Model;

use Trs\Core\Interfaces\IRuleMeta;


class RuleMeta implements IRuleMeta
{
    public function __construct($title = null)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    private $title;
}
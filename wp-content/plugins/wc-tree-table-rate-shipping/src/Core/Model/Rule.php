<?php
namespace Trs\Core\Model;

use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IMatcher;
use Trs\Core\Interfaces\IRule;
use Trs\Core\Interfaces\IRuleMeta;


class Rule implements IRule
{
    public function __construct(IRuleMeta $meta, IMatcher $matcher, ICalculator $calculator)
    {
        $this->meta = $meta;
        $this->matcher = $matcher;
        $this->calculator = $calculator;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function getMatcher()
    {
        return $this->matcher;
    }

    public function getCalculator()
    {
        return $this->calculator;
    }

    private $meta;
    private $matcher;
    private $calculator;
}
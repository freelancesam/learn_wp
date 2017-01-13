<?php
namespace Trs\Core\Interfaces;


interface IAggregator
{
    /**
     * @param IRate[] $rates
     * @return IRate|null
     */
    public function aggregateRates(array $rates);
}
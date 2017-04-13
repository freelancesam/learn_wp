<?php
namespace Trs\Factory\Registries;

use BoxPacking\Packer;
use Trs\Core\Interfaces\IProcessor;
use Trs\Core\PlatformSettings;
use Trs\Core\Processing\Processor;
use Trs\Factory\Interfaces\IRegistry;
use Trs\Factory\Registry;
use Trs\Mapping\Interfaces\ILazyFactory;
use Trs\Mapping\Interfaces\IReader;
use Trs\Mapping\Lazy\FakeLazyFactory;
use Trs\Mapping\Lazy\LazyFactory;
use Trs\Mapping\Reader;

/**
 * @property-read IRegistry $rateAggregators
 * @property-read IRegistry $mappers
 * @property-read IProcessor $processor
 * @property-read ILazyFactory $lazyFactory
 * @property-read IReader $reader
 * @property-read Packer $boxPacker
 */
class GlobalRegistry extends Registry
{
    public function __construct(PlatformSettings $platformSettings, $enableLazyLoading = true)
    {
        $this->platformSettings = $platformSettings;
        $this->enableLazyLoading = $enableLazyLoading;
        parent::__construct();
    }

    public function __get($id)
    {
        if (property_exists($this, $id)) {
            return $this->{$id};
        }

        return $this->get($id);
    }

    protected function init()
    {
        /** @var GlobalRegistry $me */
        $me = $this;

        $this->registerMany(array(
            'rateAggregators' => function() {
                return new RateAggregatorRegistry();
            },
            'mappers' => function() use($me) {
                return new MapperRegistry(
                    $me->rateAggregators,
                    $me->processor,
                    $me->lazyFactory,
                    $me->boxPacker,
                    $me->platformSettings
                );
            },
            'processor' => function() use($me) {
                return new Processor();
            },
            'lazyFactory' => function() use($me) {
                return $me->enableLazyLoading ? new LazyFactory() : new FakeLazyFactory();
            },
            'reader' => function() use($me) {
                return new Reader($me->mappers);
            },
            'boxPacker' => function() use($me) {
                return new Packer($me->platformSettings->dimensionPrecision);
            }
        ));
    }

    private $enableLazyLoading;
    private $platformSettings;
}
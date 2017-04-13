<?php
namespace Trs\Factory\Registries;

use BoxPacking\Packer;
use Trs\Core\Interfaces\IProcessor;
use Trs\Core\PlatformSettings;
use Trs\Factory\FactoryTools;
use Trs\Factory\Interfaces\IRegistry;
use Trs\Factory\Registry;
use Trs\Mapping\Interfaces\ILazyFactory;
use Trs\Mapping\Interfaces\ILazyFactoryAware;
use Trs\Mapping\Mappers\AbstractMapper;
use Trs\Mapping\Mappers\AggregatorMapper;
use Trs\Mapping\Mappers\ChildrenCalculatorMapper;
use Trs\Mapping\Mappers\PackageConditionMapper;


class MapperRegistry extends Registry
{
    public function __construct(IRegistry $rateAggregators,
                                IProcessor $processor,
                                ILazyFactory $lazyFactory,
                                Packer $boxPacker,
                                PlatformSettings $platformSettings)
    {
        parent::__construct();
        $this->rateAggregators = $rateAggregators;
        $this->processor = $processor;
        $this->lazyFactory = $lazyFactory;
        $this->boxPacker = $boxPacker;
        $this->platformSettings = $platformSettings;
    }

    public function get($id)
    {
        $mapper = parent::get($id);

        if (!$mapper) {
            if ($class = self::getObjectClass($id)) {
                switch ($class) {
                    case AggregatorMapper::className():
                        $mapper = new $class($this->rateAggregators);
                        break;

                    case ChildrenCalculatorMapper::className():
                        $mapper = new $class($this->processor);
                        break;

                    case PackageConditionMapper::className():
                        $mapper = new $class($this->boxPacker, $this->platformSettings);
                        break;

                    default:
                        $mapper = new $class();
                }

                if ($mapper instanceof ILazyFactoryAware) {
                    $mapper->setLazyFactory($this->lazyFactory);
                }

                $this->set($id, $this->asis($mapper));
            }
        }

        return $mapper;
    }

    protected function registered($id)
    {
        $registered = parent::registered($id);

        if (!$registered) {
            $registered = self::getObjectClass($id) !== null;
        }

        return $registered;
    }

    private static function getObjectClass($id)
    {
        return FactoryTools::resolveObjectIdToClass($id, 'Mapper', AbstractMapper::className());
    }

    private $rateAggregators;
    private $processor;
    private $lazyFactory;
    private $boxPacker;
    private $platformSettings;
}
<?php
namespace Trs\Mapping\Mappers;

use BoxPacking\Packer;
use Exception;
use InvalidArgumentException;
use Trs\Common\Interfaces\IComparator;
use Trs\Common\NumberComparator;
use Trs\Common\Range;
use Trs\Core\Conditions\Common\Compare\BetweenCondition;
use Trs\Core\Conditions\Common\Compare\EqualCondition;
use Trs\Core\Conditions\Common\Compare\GreaterCondition;
use Trs\Core\Conditions\Common\Compare\GreaterOrEqualCondition;
use Trs\Core\Conditions\Common\Compare\LessCondition;
use Trs\Core\Conditions\Common\Compare\LessOrEqualCondition;
use Trs\Core\Conditions\Common\Compare\NotEqualCondition;
use Trs\Core\Conditions\Common\Enum\DisjointCondition;
use Trs\Core\Conditions\Common\Enum\EqualEnumCondition;
use Trs\Core\Conditions\Common\Enum\IntersectCondition;
use Trs\Core\Conditions\Common\Enum\SubsetCondition;
use Trs\Core\Conditions\Common\Enum\SupersetCondition;
use Trs\Core\Conditions\Common\Logic\NotCondition;
use Trs\Core\Conditions\Common\Stub\TrueCondition;
use Trs\Core\Conditions\DestinationCondition;
use Trs\Core\Conditions\ItemsPackableCondition;
use Trs\Core\Conditions\Package\AbstractPackageCondition;
use Trs\Core\Conditions\Package\PackageAttributeCondition;
use Trs\Core\Conditions\Package\TermsCondition;
use Trs\Core\Interfaces\IAttribute;
use Trs\Core\PlatformSettings;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;


class PackageConditionMapper extends AbstractMapper
{
    public function __construct(Packer $boxPacker, PlatformSettings $platformSettings)
    {
        $this->boxPacker = $boxPacker;
        $this->platformSettings = $platformSettings;
    }

    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        $type = (string)$data['condition'];

        if ($type === 'true') {
            return new TrueCondition();
        }

        
        $operator = $data['operator'];

        $condition = null;
        $attribute = $type;
        switch ($type) {

            case 'terms':

                $termsByTaxonomy = array();
                foreach ($data['value'] as $termWithTaxonomy) {
                    list($taxonomy, $term) = explode(':', $termWithTaxonomy, 2);
                    $termsByTaxonomy[$taxonomy][] = $term;
                }

                @list($searchMode, $allowOthers) = explode('&', $operator, 2);
                
                static $searchModeMap = array(
                    'any' => TermsCondition::SEARCH_ANY,
                    'all' => TermsCondition::SEARCH_ALL,
                    'no' =>  TermsCondition::SEARCH_NO,
                );
                
                $searchMode = @$searchModeMap[$searchMode];
                if (!isset($searchMode)) {
                    throw new Exception("Invalid terms search mode '{$operator}'");
                }
                
                if ($allowOthers !== null && $allowOthers !== 'only') {
                    throw new Exception("Invalid terms search operator '{$operator}'");
                } else {
                    $allowOthers = ($allowOthers === null);
                }

                $subcondition = null;
                if (!empty($data['subcondition']['condition'])) {
                    $subcondition = $this->read($data['subcondition'], $reader, $context);
                }
                
                $condition = new TermsCondition($termsByTaxonomy, $searchMode, $allowOthers, $subcondition);
                
                break;
            
            case 'destination':

                $condition = new DestinationCondition($data['value']);

                if ($operator == 'disjoint') {
                    $condition = new NotCondition($condition);
                }

                break;

            case 'package':

                $condition = new ItemsPackableCondition($this->boxPacker, $data['box']);

                if ($operator == 'larger') {
                    $condition = new NotCondition($condition);
                }

                $attribute = 'item_dimensions';

                break;
            
            case 'customer':

                $condition = static::createEnumCondition($operator, $data['value']);
                $attribute = "{$type}_{$data['attribute']}";
                
                break;

            case 'weight':
            case 'volume':
            case 'price':
            case 'count':

                $compareWith =
                    $operator == 'btw'
                        ? new Range(
                            ($min = (string)@$data['min']) === '' ? null : $min,
                            ($max = (string)@$data['max']) === '' ? null : $max
                        )
                        : $data['value'];

                $precision = null;
                switch ($type) {
                    case 'weight': $precision = $this->platformSettings->weightPrecision; break;
                    case 'volume': $precision = pow($this->platformSettings->dimensionPrecision, 3); break;
                    case 'price': $precision = $this->platformSettings->pricePrecision; break;
                    case 'count': $precision = 1; break;
                }

                $comparator = new NumberComparator($precision);

                $condition = static::getNumberCondition($operator, $compareWith, $comparator);

                if ($type == 'price') {
                    $attribute = array(
                        'attribute' => $attribute,
                        'price_kind' => @$data['price_kind'],
                    );
                }

                break;
        }

        if (!($condition instanceof AbstractPackageCondition)) {
            
            if (!isset($condition, $attribute)) {
                throw new Exception("Could not instantiate condition " . json_encode($data));
            }

            if (!($attribute instanceof IAttribute)) {
                $attribute = $reader->read('attribute', $attribute, $context);
            }

            $condition = new PackageAttributeCondition($condition, $attribute);
        }

        return $condition;
    }


    private $boxPacker;
    private $platformSettings;

    static private function createEnumCondition($operator, $value)
    {
        $innerConditionClass = static::getEnumConditionClass($operator);
        $condition = new $innerConditionClass($value);
        return $condition;
    }

    static private function getNumberCondition($operator, $compareWith, IComparator $comparator)
    {
        switch ((string)$operator) {
            case 'btw' : return new BetweenCondition($compareWith, $comparator);
            case 'eq'  : return new EqualCondition($compareWith, $comparator);
            case 'ne'  : return new NotEqualCondition($compareWith, $comparator);
            case 'gt'  : return new GreaterCondition($compareWith, $comparator);
            case 'gte' : return new GreaterOrEqualCondition($compareWith, $comparator);
            case 'lt'  : return new LessCondition($compareWith, $comparator);
            case 'lte' : return new LessOrEqualCondition($compareWith, $comparator);
            default:
                throw new InvalidArgumentException("Unknown number condition operator '{$operator}'");
        }
    }

    static private function getEnumConditionClass($operator)
    {
        $conditions = array(
            'intersect' => IntersectCondition::className(),
            'disjoint' => DisjointCondition::className(),
            'superset' => SupersetCondition::className(),
            'subset' => SubsetCondition::className(),
            'equal' => EqualEnumCondition::className(),
        );

        if (!$condition = $conditions[$operator]) {
            throw new Exception("Uknown condition operator '{$operator}'");
        }

        return $condition;
    }
}
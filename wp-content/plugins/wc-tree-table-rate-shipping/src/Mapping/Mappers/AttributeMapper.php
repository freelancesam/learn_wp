<?php
namespace Trs\Mapping\Mappers;

use Exception;
use Trs\Core\Attributes\AbstractAttribute;
use Trs\Core\Attributes\PriceAttribute;
use Trs\Core\Attributes\TermsAttribute;
use Trs\Factory\FactoryTools;
use Trs\Mapping\Interfaces\IMapper;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;


class AttributeMapper extends AbstractMapper implements IMapper
{
    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        if (!is_array($data)) {
            $data = array('attribute' => $data);
        }

        $attribute = null;
        switch ($attributeName = $data['attribute']) {
            
            case 'classes':
            case 'tags':
            case 'categories':
                
                static $terms = array(
                    'classes' => TermsAttribute::TERM_SHIPPING_CLASS,
                    'tags' => TermsAttribute::TERM_TAG,
                    'categories' => TermsAttribute::TERM_CATEGORY,
                );
                
                $attribute = new TermsAttribute($terms[$attributeName]);
                
                break;

            case 'price':
                $attribute = new PriceAttribute((int)@$data['price_kind']);
                break;
            
            case 'product':
            case 'product_variation':
            case 'item':
            case 'weight':
            case 'volume':
            case 'count':
            case 'destination':
            case 'item_dimensions':
            case 'customer_roles':
                $class = FactoryTools::resolveObjectIdToClass($attributeName, 'Attribute', AbstractAttribute::className());
                $attribute = new $class();
                break;
        }

        if (!isset($attribute)) {
            throw new Exception("Unknown attribute '{$attributeName}'");
        }

        return $attribute;
    }
}
<?php
namespace Trs\Woocommerce\Converters;

use Trs\Core\Attributes\ProductVariationAttribute;
use Trs\Core\Grouping\AttributeGrouping;
use Trs\Core\Interfaces\IItem;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Address;
use Trs\Core\Model\Customer;
use Trs\Core\Model\Destination;
use Trs\Core\Model\Dimensions;
use Trs\Core\Model\Package;
use Trs\Core\Model\Price;
use Trs\Woocommerce\Model\Item;
use WC_Cart;
use WC_Product;
use WC_Product_Variation;


class PackageConverter
{
    public static function fromCoreToWoocommerce(IPackage $package)
    {
        $wcpkg = array();
        $wcpkg['contents'] = self::makeWcItems($package);
        $wcpkg['contents_cost'] = self::calcContentsCostField($wcpkg['contents']);
        $wcpkg['applied_coupons'] = array(); // is not supported yet
        $wcpkg['user']['ID'] = self::getCustomerId($package);
        $wcpkg['destination'] = self::getDestination($package);
        return $wcpkg;
    }

    public static function fromWoocommerceToCore(array $package)
    {
        $items = array();
        foreach ((array)@$package['contents'] as $itemData) {

            $quantity = $itemData['quantity'];
            if ($quantity == 0) {
                continue;
            }

            $price = new Price(
                $itemData['line_subtotal'] / $quantity,
                $itemData['line_subtotal_tax'] / $quantity,
                ($itemData['line_subtotal'] - $itemData['line_total']) / $quantity,
                ($itemData['line_subtotal_tax'] - $itemData['line_tax']) / $quantity
            );

            $variationAttributes = array();
            foreach ((@$itemData['variation'] ?: array()) as $attr => $value) {
                if (substr_compare($attr, 'attribute_', 0, 10) == 0) {
                    $variationAttributes[substr($attr, 10)] = $value;
                }
            }

            /** @var WC_Product $product */
            $product = $itemData['data'];
            
            while ($quantity--) {
                $item = new Item();
                $item->setPrice($price);
                $item->setDimensions(new Dimensions((float)$product->length, (float)$product->width, (float)$product->height));
                $item->setProductId((string)$product->id);
                $item->setWeight((float)$product->get_weight());
                $item->setOriginalProductObject($product);
                $item->setProductVariationId($product->variation_id ? (string)$product->variation_id : null);
                $item->setVariationAttributes($variationAttributes);
                $items[] = $item;
            }
        }

        $destination = null;
        if (($dest = @$package['destination']) && @$dest['country']) {

            $destination = new Destination(
                $dest['country'],
                @$dest['state'],
                @$dest['postcode'],
                @$dest['city'],
                new Address(@$dest['address'], @$dest['address_2'])
            );
        }

        $customer = null;
        if (isset($package['user']['ID'])) {
            $customer = new Customer($package['user']['ID']);
        }

        return new Package($items, $destination, $customer);
    }

    private static function makeWcItems(IPackage $package)
    {
        $wcItems = array();

        $lineGrouping = new AttributeGrouping(new ProductVariationAttribute());
        $lines = $package->split($lineGrouping);

        foreach ($lines as $line) {

            $items = $line->getItems();
            if (!$items) {
                continue;
            }

            /** @var IItem $item */
            $item = reset($items);

            $product = null; {

                if ($item instanceof Item) {
                    /** @var Item $item */
                    $product = $item->getOriginalProductObject();
                }
                
                if (!isset($product)) {
                    
                    $productPostId = $item->getProductVariationId();
                    if (!isset($productPostId)) {
                        $productPostId = $item->getProductId();
                    }

                    $product = wc_get_product($productPostId);
                }
            }

            $wcItem = array(); {

                $wcItem['data'] = $product;
                $wcItem['quantity'] = count($items);

                $wcItem['product_id'] = $product->id;
                $wcItem['variation_id'] = $product->variation_id;
                $wcItem['variation'] = ($product instanceof WC_Product_Variation) ? $product->get_variation_attributes() : null;

                $wcItem['line_total'] = $line->getPrice(Price::WITH_DISCOUNT);
                $wcItem['line_tax'] = $line->getPrice(Price::WITH_DISCOUNT | Price::WITH_TAX) - $wcItem['line_total'];
                $wcItem['line_subtotal'] = $line->getPrice(Price::BASE);
                $wcItem['line_subtotal_tax'] = $line->getPrice(Price::WITH_TAX) - $wcItem['line_subtotal'];
            }

            // We don't want to have a cart instance dependency just to generate line id. generate_cart_id() method
            // is a static method both conceptually and actually, i.e. it does not (should not) depend on actual
            // cart instance. So we'd rather call it statically.
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            $wcItemId = @WC_Cart::generate_cart_id($wcItem['product_id'], $wcItem['variation_id'], $wcItem['variation']);

            $wcItems[$wcItemId] = $wcItem;
        }

        return $wcItems;
    }

    private static function calcContentsCostField($wcItems)
    {
        $value = 0;

        foreach ($wcItems as $item) {

            /** @var WC_Product $product */
            $product = $item['data'];

            if ($product->needs_shipping() && isset($item['line_total'])) {
                $value += $item['line_total'];
            }
        }

        return $value;
    }

    private static function getCustomerId(IPackage $package)
    {
        if ($customer = $package->getCustomer()) {
            return $customer->getId();
        }

        return null;
    }

    private static function getDestination(IPackage $package)
    {
        if ($destination = $package->getDestination()) {

            $address = $destination->getAddress();

            return array_map('strval', array(
                'country' => $destination->getCountry(),
                'state' => $destination->getState(),
                'postcode' => $destination->getPostalCode(),
                'city' => $destination->getCity(),
                'address' => $address ? $address->getLine1() : null,
                'address_2' => $address ? $address->getLine2() : null,
            ));
        }

        return null;
    }
}
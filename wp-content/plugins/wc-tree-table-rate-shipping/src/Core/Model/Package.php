<?php
namespace Trs\Core\Model;

use Trs\Common\Arrays;
use Trs\Core\Interfaces\IGrouping;
use Trs\Core\Interfaces\IItem;
use Trs\Core\Interfaces\IPackage;


class Package implements IPackage
{
    public function __construct(array $items = array(), Destination $destination = null, Customer $customer = null)
    {
        $this->items = $items;
        $this->destination = $destination;
        $this->customer = $customer;
    }

    public static function fromOther($other, Destination $destination = null, Customer $customer = null)
    {
        $package = new self(array(), $destination, $customer);
        $package = $package->merge($other);
        return $package;
    }
    
    public static function create(array $items = array(), Destination $destination = null, Customer $customer = null)
    {
        return new static($items, $destination, $customer);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getPrice($flags = Price::BASE)
    {
        $sum = 0;
        foreach ($this->items as $item) {
            $sum += $item->getPrice($flags);
        }

        return $sum;
    }

    public function getWeight()
    {
        $weight = 0;
        foreach ($this->getItems() as $item) {
            $weight += $item->getWeight();
        }

        return $weight;
    }

    public function getTerms($taxonomy)
    {
        $terms = Arrays::map($this->getItems(), function (IItem $item) use ($taxonomy) {
            
            $terms = $item->getTerms($taxonomy);
            
            if (!$terms) {
                $terms[] = self::NONE_VIRTUAL_TERM_ID;
            }
            
            $terms = Arrays::map($terms, 'strval');
            
            return $terms;
        });

        $terms = $terms ? call_user_func_array('array_merge', $terms) : $terms;

        $terms = array_values(array_unique($terms, SORT_STRING));

        return $terms;
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function split(IGrouping $by)
    {
        $buckets = array();

        foreach ($this->getItems() as $item) {
            foreach (array_unique($by->getPackageIds($item)) as $bucket) {
                $buckets[$bucket][] = $item;
            }
        }

        $packages = array();
        foreach ($buckets as $id => $items) {
            $packages[] = new Package($items, $this->getDestination(), $this->getCustomer());
        }

        if (!$packages) {
            $packages[] = $this;
        }

        return $packages;
    }

    public function merge($with)
    {
        if (!is_array($with)) {
            $with = array($with);
        }

        if (!$with) {
            return $this;
        }

        $otherItems = call_user_func_array(
            'array_merge',
            Arrays::map($with, function(IPackage $pkg) {
                return $pkg->getItems();
            })
        );

        $theseItems = $this->getItems();

        $mergedItems = array();
        foreach (array_merge($theseItems, $otherItems) as $item) {
            $mergedItems[spl_object_hash($item)] = $item;
        }

        $package = $this;
        if (count($mergedItems) > count($theseItems)) {
            $package = new Package(array_values($mergedItems), $this->getDestination(), $this->getCustomer());
        }

        return $package;
    }

    public function exclude($other)
    {
        if (!is_array($other)) {
            $other = array($other);
        }

        $theseItems = $this->getItems();

        $restItems = array(); {

            foreach ($theseItems as $item) {
                $restItems[spl_object_hash($item)] = $item;
            }

            /** @var IPackage $pkg */
            foreach ($other as $pkg) {
                foreach ($pkg->getItems() as $item) {
                    unset($restItems[spl_object_hash($item)]);
                }
            }
        }

        $package = $this;
        if (count($restItems) < count($theseItems)) {
            $package = new Package($restItems, $this->getDestination(), $this->getCustomer());
        }

        return $package;
    }

    /** @var IItem[] */
    private $items;
    private $destination;
    private $customer;
}
<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IAttribute;
use Trs\Core\Interfaces\IPackage;


class TermsAttribute implements IAttribute
{
    const TERM_TAG = 'tag';
    const TERM_SHIPPING_CLASS = 'shipping_class';
    const TERM_CATEGORY = 'category';

    public function __construct($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function getValue(IPackage $package)
    {
        return $package->getTerms($this->taxonomy);
    }

    private $taxonomy;
}
<?php
namespace Trs\Mapping\Lazy\Wrappers;

use Trs\Core\Interfaces\IMatcher;
use Trs\Core\Interfaces\IPackage;


class LazyMatcher extends AbstractLazyWrapper implements IMatcher
{
    public function getMatchingPackage(IPackage $package)
    {
        return $this->load()->getMatchingPackage($package);
    }

    public function isCapturingMatcher()
    {
        return $this->load()->isCapturingMatcher();
    }

    /**
     * @return IMatcher
     */
    protected function load()
    {
        return parent::load();
    }
}
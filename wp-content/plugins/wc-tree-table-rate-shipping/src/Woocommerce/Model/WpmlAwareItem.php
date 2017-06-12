<?php
namespace Trs\Woocommerce\Model;

use Deferred\Deferred;


class WpmlAwareItem extends Item
{
    public function getTerms($taxonomy)
    {
        global $sitepress;

        if (isset($sitepress)) {

            $lang = $sitepress->get_current_language();
            $restoreLanguage = new Deferred(function() use($sitepress, $lang) {
                $sitepress->switch_lang($lang);
            });

            $sitepress->switch_lang($sitepress->get_default_language());
        }

        return parent::getTerms($taxonomy);
    }

}
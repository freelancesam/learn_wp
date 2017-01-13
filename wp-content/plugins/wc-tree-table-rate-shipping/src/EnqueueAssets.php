<?php
namespace Trs;


class EnqueueAssets
{
    public function __construct(PluginMeta $meta)
    {
        $this->meta = $meta;
    }

    public function __invoke()
    {
        $this->deregisterHandles();
        $this->includeStyles();
        $this->includeScripts();
        $this->protectFromForeignSelect2Versions();
    }

    
    private $meta;

    private function deregisterHandles()
    {
        $handles = array(
            'select2',
            'ign_voucher_select2_js',
            'ign_voucher_select2_css'
        );

        foreach ($handles as $handle) {
            wp_dequeue_style($handle);
            wp_dequeue_script($handle);
            wp_deregister_style($handle);
            wp_deregister_script($handle);
        }
    }

    private function includeStyles()
    {
        wp_enqueue_style(
            'trs-admin-css',
            $this->meta->getAssetUrl('trs/css/admin.css')
        );

        wp_enqueue_style(
            'trs-select2-css',
            $this->meta->getAssetUrl('select2/select2.css')
        );
    }

    private function includeScripts()
    {
        wp_register_script(
            'trs-jquery-nestedsortable-js',
            $this->meta->getAssetUrl('jquery/jquery.nestedsortable.js'),
            array('jquery-ui-sortable')
        );

        wp_register_script(
            'trs-jquery-scrollTo-js',
            $this->meta->getAssetUrl('jquery/jquery.scrollTo.min.js'),
            array('jquery')
        );

        wp_register_script(
            'select2',
            $this->meta->getAssetUrl('select2/select2.js'),
            array('jquery')
        );

        wp_register_script(
            'trs-ractive',
            $this->meta->getAssetUrl('ractive/ractive.js')
        );

        wp_register_script(
            'trs-ractive-decorators-autosize',
            $this->meta->getAssetUrl('ractive/ractive-decorators-autosize.js')
        );

        wp_register_script(
            'trs-ractive-decorators-combine',
            $this->meta->getAssetUrl('ractive/ractive-decorators-combine.js')
        );

        wp_register_script(
            'trs-ractive-decorators-destination-list',
            $this->meta->getAssetUrl('ractive/ractive-decorators-destination-list.js')
        );

        wp_register_script(
            'trs-ractive-decorators-select2',
            $this->meta->getAssetUrl('ractive/ractive-decorators-select2.js')
        );

        wp_register_script(
            'trs-ractive-decorators-sortable',
            $this->meta->getAssetUrl('ractive/ractive-decorators-sortable.js')
        );

        wp_register_script(
            'trs-ractive-transitions-slide',
            $this->meta->getAssetUrl('ractive/ractive-transitions-slide.js')
        );

        wp_register_script(
            'trs-ractive-mixins',
            $this->meta->getAssetUrl('trs/js/ractive-mixins.js')
        );

        wp_enqueue_script(
            'trs-admin-js',
            $this->meta->getAssetUrl('trs/js/admin.js'),
            array(
                'jquery', 'jquery-color', 'jquery-ui-sortable', 'jquery-form', 'underscore',
                'trs-jquery-nestedsortable-js', 'trs-jquery-scrollTo-js',
                'trs-ractive', 'trs-ractive-transitions-slide',
                'trs-ractive-decorators-select2', 'trs-ractive-decorators-sortable', 'trs-ractive-mixins',
                'trs-ractive-decorators-combine', 'trs-ractive-decorators-destination-list',
                'trs-ractive-decorators-autosize'
            )
        );
    }

    private function protectFromForeignSelect2Versions()
    {
        $assetUrl = $this->meta->getAssetUrl();

        add_action('wp_print_scripts', function () use ($assetUrl) {

            global $wp_scripts;

            /** @var \_WP_Dependency $dep */
            foreach ($wp_scripts->registered as $dep) {
                if (($src = (string)@$dep->src) !== '') {
                    if (substr_compare($src, $assetUrl, 0, strlen($assetUrl)) !== 0 &&
                        preg_match('#(/|\\\\)select2(\.min)?\.(js|css)#i', $src)
                    ) {
                        $wp_scripts->remove($dep->handle);
                    }
                }
            }

            $wp_scripts->done = array();
        });
    }
}
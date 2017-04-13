<?php
namespace Trs\Services;

use PluginUpdateChecker_3_0;
use Trs\PluginMeta;
use Trs\Services\Interfaces\IService;
use WP_Error;


class UpdateService implements IService
{
    public function __construct(PluginMeta $pluginMeta)
    {
        $this->pluginMeta = $pluginMeta;
    }

    public function install()
    {
        $meta = $this->pluginMeta;

        require($meta->getLibsPath('yahnis-elsts/plugin-update-checker/plugin-update-checker.php'));

        $apiUpdatesEndpoint = $meta->getApiUpdatesEndpoint();
        $entryFile = $meta->getEntryFile();

        $updateChecker = new PluginUpdateChecker_3_0($apiUpdatesEndpoint, $entryFile);

        $updateChecker->addQueryArgFilter(function($queryArgs) use($meta) {
            $queryArgs['license'] = $meta->getLicense();
            return $queryArgs;
        });

        add_filter('upgrader_pre_download', function($response, $downloadUrl) use($apiUpdatesEndpoint, $entryFile) {

            if (strpos($downloadUrl, $apiUpdatesEndpoint) !== false) {

                if ($response === false) {
                    $downloadUrl .= (strpos($downloadUrl, '?') === false ? '?' : '&') . 'check=1';
                    $checkResponse = wp_safe_remote_get($downloadUrl);
                    if (is_array($checkResponse) && @$checkResponse['body'] && $checkResponse['body'] !== 'OK') {
                        $response = new WP_Error('download_failed', '', $checkResponse['body']);
                    }
                }

                if ($response === false) {
                    if (file_exists(dirname($entryFile).'/.git') || file_exists(dirname($entryFile).'/.idea')) {
                        $response = new WP_Error('download_failed', '', 'Development plugin copy protected from erasing during update.');
                    }
                }
            }

            return $response;

        }, 10, 3);
    }

    private $pluginMeta;
}
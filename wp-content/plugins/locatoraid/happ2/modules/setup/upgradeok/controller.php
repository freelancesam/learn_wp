<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_UpgradeOk_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$view = $this->make('/setup/upgradeok/view');
		$view = $this->make('/layout/view/body')
			->set_content($view)
			;
		return $this->make('/http/view/response')
			->set_view($view)
			;
	}
}
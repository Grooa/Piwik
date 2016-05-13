<?php

namespace Plugin\Piwik;

class AdminController
{

	public function index()
	{
		ipAddCss('assets/admin.css');
		ipAddJs('assets/admin.js');

		$url = ipGetOption('Piwik.url');
		$siteId = ipGetOption('Piwik.siteId');
		$authToken = ipGetOption('Piwik.authToken');

		if (empty($url) || empty($siteId)) {
			return 'Please set your Piwik URL, site ID and authentication token in the plugin settings';
		}

		// Query parameters
		$query = http_build_query(
			array(
				'module' => 'Widgetize',
				'action' => 'iframe',
				'moduleToWidgetize' => 'Dashboard',
				'actionToWidgetize' => 'index',
				'idSite' => $siteId,
				'period' => 'month',
				'date' => 'today',
				'token_auth' => $authToken
			)
		);

		return "<iframe src=\"$url/index.php?$query\" class=\"ipPluginPiwik\"></iframe>";
	}
}

<?php

namespace Plugin\Piwik;

class Event
{

	public static function ipBeforeController()
	{

		// Don't track managers
		if (ipIsManagementState()) {
			return;
		}

		// Check required options
		$url = ipGetOption('Piwik.url');
		$siteId = ipGetOption('Piwik.siteId');

		if (empty($url) || empty($siteId)) {
			return;
		}

		// Add all event functions
		$functionCalls = array_map(
			function ($name) {
				return array($name);
			},
			ipGetOption('Piwik.trackedEvents')
		);

		// Heart beat timer
		$heartBeatInterval = intval(ipGetOption('Piwik.heartBeatInterval'));
		if (gettype($heartBeatInterval) == 'integer' && $heartBeatInterval > 0) {
			$functionCalls[] = array(
				'enableHeartBeatTimer',
				$heartBeatInterval
			);
		}

		// Track user
		$userTrackingField = ipGetOption('Piwik.userTrackingField');
		if (!empty($userTrackingField)) {
			$userData = ipUser()->data();

			if (isset($userData[$userTrackingField])) {
				$functionCalls[] = array(
					'setUserId',
					ipUser()->data()[$userTrackingField]
				);
			}
		}

		// Set tracker url and site id
		array_push(
			$functionCalls,
			array('setTrackerUrl', $url . '/piwik.php'),
			array('setSiteId', $siteId)
		);

		// Add script to page
		ipAddJsVariable('_paq', $functionCalls);
		ipAddJs(
				$url . '/piwik.js',
				array(' defer' => '', ' async' => ''),
				10
			);

	}

}

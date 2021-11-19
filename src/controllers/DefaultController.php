<?php
/**
 * Guide module for Craft CMS 3.x
 *
 * A Craft CMS module to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace modules\guide\controllers;

use Craft;
use craft\elements\Entry;
use craft\web\Controller;
use modules\guide\Guide;

class DefaultController extends Controller
{
	protected $allowAnonymous = [];

	/**
	 * Handle a request going to our module's index action URL,
	 * e.g.: actions/guide/default
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		// Determine if the guide options are valid
		$this->_validateGuide();

		// Can the user view the guide?
		$this->requirePermission('canViewGuide');

		// Display the template with the custom variables
		return $this->renderTemplate('guide/index', [
			"pluginName" => Guide::$instance->getSettings()->name,
			"guideSection" => Guide::$instance->getSettings()->section,
			"entry" => Entry::findOne([
				'section' => Guide::$instance->getSettings()->section,
				'id' => Craft::$app->request->getSegment(2)
			])
		]);
	}

	/**
	 * Throws an error if an invalid configuration was supplied for the Guide
	 *
	 * @return void
	 */
	private function _validateGuide()
	{
		$section = Craft::$app->sections->getSectionByHandle(Guide::$instance->getSettings()->section);

		if (!$section) {
			throw new \Exception("This section does not exist. You must supply the Guide with a valid section handle in config/guide.php.");
		}

		$sectionSettings = Craft::$app->getSections()->getSectionSiteSettings($section->id);

		if (!$sectionSettings || count($sectionSettings) !== 1) {
			throw new \Exception("This section does not have any valid settings. You must supply the Guide with a valid section handle in config/guide.php.");
		}

		if ($sectionSettings[0]->hasUrls) {
			throw new \Exception("The section you use to power the Guide cannot have public URLs. Please ensure your section does not have public URLs or use a different section handle in config/guide.php");
		}

		return true;
	}
}

<?php
/**
 * Guide module for Craft CMS 3.x
 *
 * A Craft CMS module to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace modules\guidemodule\controllers;

use modules\guidemodule\Config;

use Craft;
use craft\elements\Entry;
use craft\web\Controller;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    TrendyMinds
 * @package   GuideModule
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our module's index action URL,
     * e.g.: actions/guide-module/default
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
        return $this->renderTemplate('guide-module/index', [
            "pluginName" => Config::getName(),
            "entry" => Entry::findOne([
                'section' => 'userManual',
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
        $section = Craft::$app->sections->getSectionByHandle(Config::getSection());

        if (!$section) {
            throw new \Exception("This section does not exist. You must supply the Guide with a valid section handle in config/guidemodule.php.");
        }

        $sectionSettings = Craft::$app->getSections()->getSectionSiteSettings($section->id);

        if (!$sectionSettings || count($sectionSettings) !== 1) {
            throw new \Exception("This section does not have any valid settings. You must supply the Guide with a valid section handle in config/guidemodule.php.");
        }

        if ($sectionSettings[0]->hasUrls) {
            throw new \Exception("The section you use to power the Guide cannot have public URLs. Please ensure your section does not have public URLs or use a different section handle in config/guidemodule.php");
        }

        return true;
    }
}

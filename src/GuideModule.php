<?php
/**
 * Guide module for Craft CMS 3.x
 *
 * A Craft CMS module to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace modules\guidemodule;

use modules\guidemodule\services\GuideModuleService as GuideModuleServiceService;
use modules\guidemodule\Config;

use Craft;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\web\View;
use craft\web\UrlManager;
use craft\web\twig\variables\Cp;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;
use yii\base\Event;
use yii\base\Module;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    TrendyMinds
 * @package   GuideModule
 * @since     1.0.0
 *
 * @property  GuideModuleServiceService $guideModuleService
 */
class GuideModule extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this module class so that it can be accessed via
     * GuideModule::$instance
     *
     * @var GuideModule
     */
    public static $instance;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/guidemodule', $this->getBasePath());
        $this->controllerNamespace = 'modules\guidemodule\controllers';

        // Base template directory
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function (RegisterTemplateRootsEvent $e) {
            if (is_dir($baseDir = $this->getBasePath().DIRECTORY_SEPARATOR.'templates')) {
                $e->roots[$this->id] = $baseDir;
            }
        });

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * Set our $instance static property to this class so that it can be accessed via
     * GuideModule::$instance
     *
     * Called after the module class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['guide'] = 'guide-module/default/index';
                $event->rules['guide/<page:\d+>'] = 'guide-module/default/index';
            }
        );

        // Register permissions on who can see the guide
        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function(RegisterUserPermissionsEvent $event) {
                $event->permissions['Guide'] = [
                    'canViewGuide' => [
                        'label' => 'View Guide',
                    ],
                ];
            }
        );

        // Setup control panel navigation links to the Guide
        if (Craft::$app->user->checkPermission('canViewGuide')) {
            Event::on(
                Cp::class,
                Cp::EVENT_REGISTER_CP_NAV_ITEMS,
                function(RegisterCpNavItemsEvent $event) {
                    $event->navItems[] = [
                        'url' => 'guide',
                        'label' => Config::getName(),
                        'icon' => '@modules/guidemodule/icon-mask.svg'
                    ];
                }
            );
        }
    }
}

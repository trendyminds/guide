<?php
/**
 * Guide module for Craft CMS 3.x
 *
 * A Craft CMS module to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace modules\guide;

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

class Guide extends Module
{
	public static $instance;

	public function __construct($id, $parent = null, array $config = [])
	{
		Craft::setAlias('@modules/guide', $this->getBasePath());
		$this->controllerNamespace = 'modules\guide\controllers';

		// Base template directory
		Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function (RegisterTemplateRootsEvent $e) {
			if (is_dir($baseDir = $this->getBasePath() . DIRECTORY_SEPARATOR . 'templates')) {
				$e->roots[$this->id] = $baseDir;
			}
		});

		// Set this as the global instance of this module class
		static::setInstance($this);

		parent::__construct($id, $parent, $config);
	}

	public function init()
	{
		parent::init();
		self::$instance = $this;

		// Register our CP routes
		Event::on(
			UrlManager::class,
			UrlManager::EVENT_REGISTER_CP_URL_RULES,
			function (RegisterUrlRulesEvent $event) {
				$event->rules['guide'] = 'guide/default/index';
				$event->rules['guide/<page:\d+>'] = 'guide/default/index';
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
						'label' => $this->getSettings()->name,
						'icon' => '@modules/guide/icon-mask.svg'
					];
				}
			);
		}
	}

	/**
	 * The settings of what the public name and section for powering the Guide should be.
	 * These can be modified based on your project needs
	 *
	 * @return object
	 */
	public function getSettings(): object
	{
		return (object) [
			// The public facing name of the plugin in the Craft control panel
			'name' => 'Guide',

			// The handle of the section that drives the Guide. This section must not have public URLs
			'section' => 'guide',
		];
	}
}

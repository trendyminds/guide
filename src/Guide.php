<?php

/**
 * Guide plugin for Craft CMS 3.x
 *
 * A Craft CMS plugin to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace trendyminds\guide;

use trendyminds\guide\variables\GuideVariable;
use trendyminds\guide\twigextensions\GuideTwigExtension;
use trendyminds\guide\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\UrlHelper;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;

use yii\base\Event;

/**
 * Class Guide
 *
 * @author    TrendyMinds
 * @package   Guide
 * @since     1.0.0
 *
 */
class Guide extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Guide
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->name = $this->getName();

        // Register twig extensions
        $this->_addTwigExtensions();

        // Register CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            [$this, 'registerCpUrlRules']
        );

        // Register variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('guide', GuideVariable::class);
            }
        );

        // Plugin Install event
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            [$this, 'afterInstallPlugin']
        );
    }

    /**
     * Returns the user-facing name of the plugin, which can override the name
     * in composer.json
     *
     * @return string
     */
    public function getName()
    {
        $pluginName = Craft::t('guide', 'Guide');
        $pluginNameOverride = $this->getSettings()->pluginNameOverride;

        return ($pluginNameOverride)
            ? $pluginNameOverride
            : $pluginName;
    }

    public function registerCpUrlRules(RegisterUrlRulesEvent $event)
    {
        $rules = [
            'guide/<guidePath:([a-zéñåA-Z0-9\-\_\/]+)?>' => ['template' => 'guide/index'],
        ];

        $event->rules = array_merge($event->rules, $rules);
    }

    public function afterInstallPlugin(PluginEvent $event)
    {
        $isCpRequest = Craft::$app->getRequest()->isCpRequest;

        if ($event->plugin === $this && $isCpRequest) {
            Craft::$app->controller->redirect(UrlHelper::cpUrl('settings/plugins/guide/'))->send();
        }
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        $options = [[
            'label' => '',
            'value' => '',
        ]];

        foreach (Craft::$app->sections->getAllSections() as $section) {
            $siteSettings = Craft::$app->sections->getSectionSiteSettings($section['id']);
            $hasUrls = true;

            foreach ($siteSettings as $siteSetting) {
                if (!$siteSetting->hasUrls) {
                    $options[] = [
                        'label' => $section['name'],
                        'value' => $section['id'],
                    ];
                }
            }
        }

        // Get override settings from config file.
        $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->handle));

        return Craft::$app->view->renderTemplate(
            'guide/settings',
            [
                'settings' => $this->getSettings(),
                'overrides' => array_keys($overrides),
                'options' => $options,
                'siteTemplatesPath' => Craft::$app->getPath()->getSiteTemplatesPath(),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getSettings()
    {
        $settings = parent::getSettings();
        $config = Craft::$app->config->getConfigFromFile('guide');

        foreach ($settings as $settingName => $settingValue) {
            $settingValueOverride = null;
            foreach ($config as $configName => $configValue) {
                if ($configName === $settingName) {
                    $settingValueOverride = $configValue;
                }
            }
            $settings->$settingName = $settingValueOverride ?? $settingValue;
        }

        // Allow handles from config
        if (!is_numeric($settings->section)) {
            $section = Craft::$app->getSections()->getSectionByHandle('homepage');
            if ($section) {
                $settings->section = $section->id;
            }
        }

        return $settings;
    }

    // Private Methods
    // =========================================================================

    private function _addTwigExtensions()
    {
        Craft::$app->view->twig->addExtension(new GuideTwigExtension);
    }
}

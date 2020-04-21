<?php
/**
 * Guide module for Craft CMS 3.x
 *
 * A Craft CMS module to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace modules\guidemodule\assetbundles\guidemodule;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * GuideModuleAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    TrendyMinds
 * @package   GuideModule
 * @since     1.0.0
 */
class GuideModuleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@modules/guidemodule/assetbundles/guidemodule";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/GuideModule.css',
        ];

        parent::init();
    }
}

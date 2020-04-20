<?php

/**
 * Guide plugin for Craft CMS 3.x
 *
 * A Craft CMS plugin to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace trendymidns\guide\assetbundles\guide;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    TrendyMinds
 * @package   Guide
 * @since     1.0.0
 */
class GuideAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@trendyminds/guide/assetbundles/guide";

        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/Guide.css',
        ];

        parent::init();
    }
}

<?php

/**
 * Guide plugin for Craft CMS 3.x
 *
 * A Craft CMS plugin to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace trendyminds\guide\variables;

use trendyminds\guide\Guide;

use Craft;

/**
 * @author    TrendyMinds
 * @package   Guide
 * @since     1.0.0
 */
class GuideVariable
{
    // Public Methods
    // =========================================================================

    public function getName()
    {
        $name = Guide::$plugin->getName();

        return $name;
    }

    public function getSettings()
    {
        $settings = Guide::$plugin->getSettings();

        return $settings;
    }
}

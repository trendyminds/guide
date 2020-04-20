<?php

/**
 * Guide plugin for Craft CMS 3.x
 *
 * A Craft CMS plugin to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace trendyminds\guide\models;

use trendyminds\guide\Guide;

use Craft;
use craft\base\Model;

/**
 * @author    TrendyMinds
 * @package   Guide
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $pluginNameOverride;

    /**
     * @var integer
     */
    public $section;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pluginNameOverride'], 'string'],
            ['section', 'number'],
        ];
    }
}

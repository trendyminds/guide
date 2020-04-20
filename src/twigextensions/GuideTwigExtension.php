<?php

/**
 * Guide plugin for Craft CMS 3.x
 *
 * A Craft CMS plugin to display a help guide in the control panel
 *
 * @link      https://trendyminds.com
 * @copyright Copyright (c) 2020 TrendyMinds
 */

namespace trendyminds\guide\twigextensions;

use trendyminds\guide\Guide;

use Craft;
use craft\elements\Entry;
use craft\helpers\UrlHelper;
use craft\web\View;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

/**
 * @author    TrendyMinds
 * @package   Guide
 * @since     1.0.0
 */
class GuideTwigExtension extends Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Guide Twig Extension';
    }

    /**
     * @inheritdoc
     */
    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction('getHelpDocument', [$this, 'getHelpDocument']),
        ];
    }

    /**
     * Render an entry in the given section using the nominated template
     *
     * @return string
     */
    public function getHelpDocument()
    {
        $settings = Guide::$plugin->getSettings();
        $query = Entry::find();

        $segments = Craft::$app->request->segments;
        $segment = end($segments);
        $sectionId = $settings->section;

        if (count($segments) === 1 && $segment === 'guide') {
            $id = null;
        } else {
            $id = $segment;
        }

        $criteria = [
            'sectionId' => $sectionId,
            'id' => $id,
        ];

        Craft::configure($query, $criteria);
        $entry = $query->one();

        // If the app has not been set up at all or there are no entires,
        // redirect to the settings page
        if (!$sectionId || !$entry) {
            Craft::$app->controller->redirect(UrlHelper::cpUrl('settings/plugins/guide/'))->send();
        } else {
            $output = Craft::$app->view->renderTemplate('guide/_body.twig', [
                'entry' => $entry,
            ]);

            // Ensure template mode is set back to control panel
            Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_CP);

            return $output;
        }
    }
}

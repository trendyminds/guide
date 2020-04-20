# Guide for Craft CMS

A Craft CMS plugin to display a help guide in the control panel

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require trendyminds/guide

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for usermanual.

4. Select the section the plugin should use as the **Guide** page in the CP.
    * (Optional) - Replace the plugin's name to something your user's will understand.
    * (Optional) - Use more than the default `body` fieldhandle by setting up custom template overrides.

5. Click the **Guide** link in the CP nav.

## Configuration

* All settings may be optionally configured using a [config file](http://buildwithcraft.com/docs/plugins/plugin-settings#config-file). The values, contained in [`config.php`](https://github.com/trendyminds/guide/blob/master/src/config.php), are described below:

<a id="config-settings-pluginNameOverride"></a>
### pluginNameOverride
Intuitive, human-readable plugin name for the end user.

<a id="config-settings-section"></a>
### section
Entries in this section must have associated urls.

## Some notes
* The plugin currently only pulls in the `body` field from each entry in the selected section, unless you're using a template override.
* While the **Guide** section works best with `Structures`, you can certainly get away with using a one-off `Single`.
* If you're running _Craft Client_ or _Craft Pro_ make sure your content editors don't have permission to edit whatever section you've selected to use as your **Guide**
* Only sections with entry URLs may be used as your **Guide** section.

## Thanks
This is a fork of [Craft User Manual](https://github.com/hillholliday/Craft-User-Manual) by Hill Holliday. The primary changes here are requiring your "Guide" content type to have no front-end accessible URLs.

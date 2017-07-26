=== CSSIgniter Shortcodes ===
Contributors: anastis, silencerius, tsiger
Plugin Name: CSSIgniter Shortcodes
Plugin URI: http://www.cssigniter.com/ignite/ci-shortcodes/
Author URI: http://www.cssigniter.com/
Author: The CSSigniter Team
Tags: shortcode, shortcodes, button, box, tooltip, separator, blockquote, list, map, google maps, icons
Requires at least: 4.3
Tested up to: 4.5.3
Stable tag: 2.2.3

This plugin defines and allows you to use a lot of useful shortcodes. Need a button? Sure. A message box? You know we have it.

== Description ==

This shortcodes plugin, has been created to complement and be used mainly with CSSIgniter's premium and free themes. But of course, anyone can use it with any theme. 
A lot of useful shortcodes are defined. See the plugin's homepage for a complete guide. 

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the Settings page you will see the "CSSIgniter Shortcodes" settings screen of the plugin.

== Documentation ==

http://www.cssigniter.com/docs/shortcodes/

== Frequently Asked Questions ==

= What shortcodes are supported? =

Quite a few! Check http://www.cssigniter.com/docs/shortcodes/ for complete usage instructions.

== Screenshots ==

1. Some default-styled shortcodes.

== Changelog ==

= 2.3 =
* A Google Maps API key is now required (by Google) to display maps, and this got reflected in the inline documentation. Instructions can be found here: http://www.cssigniter.com/docs/article/generate-a-google-maps-api-key/
* Google Maps API is now disabled by default.
* Improved options initialization. Introduced filter 'ci_shortcodes_default_settings' to modify the default plugin options.
* Updated language files.

= 2.2.3 =
* Updated font-awesome to v.4.6.3
* Fixed issue with the tabs shortcode, where border_color and border_width attributes wouldn't work properly.

= 2.2.2 =
* Updated font-awesome to v.4.6.1
* Fixed issue where Google Maps API would load a retired version of the API.
* Deleted unneeded jquery file.

= 2.2.1 =
* Google Maps API is now loaded protocol-less for compatibility with https installations. Also removed deprecated sensor parameter.

= 2.2 =
* Updated font-awesome to v.4.5.0
* Prepared the plugin for language packs compatibility.
* Deleted po/mo files.
* Added POT file.

= 2.1 =
* Added filters for each shortcode's markup.
* Fixed an issue where the quote's cheatsheet wouldn't display.
* Renamed register_ci_shortcode_settings() to ci_shortcodes_register_settings() and changed all references.

= 2.0 =
* Plugin rewritten from scratch. 
* Everything is now responsive.
* CSS themes are no longer available.
* Added shortcodes for accordions, tabs, headings, sliders.
* Shortcodes now support build-in color schemes, font-awesome icons, and much, much more.

= 1.2.2 =
* Added a "Settings" link in the plugins listing page.
* If exists, the Upgrade Notice is now shown in the plugins listing page.

= 1.2.1 =
* Fixed an issue where the columns shortcodes wouldn't get styled appropriately, when compatibility mode was enabled.

= 1.2 =
* Added internationalization support.
* Stylesheets are now loaded on the top of the page.

= 1.1 = 
* Added a missing clearfix class "group" to all stylesheets.

= 1.0 = 
* Initial Release

== Upgrade Notice ==

= 2.3 =
Maps now require an API key to work. Please read the changelog.

= 2.1 =
If you're updating from v1.x, please read the changelog before you continue.

= 2.0 =
This is a complete plugin rewrite. While backward compatibility is maintained, in terms of usage, output and styling has significantly changed. Therefore you are urged to keep a full backup before upgrading, and review all your shortcodes after the upgrade.

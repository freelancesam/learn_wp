=== WooCommerce Tree Table Rate Shipping ===
Contributors: dangoodman
Tags: woocommerce, shipping, woocommerce shipping, woocommerce shipping plugin, weight-based shipping, volumetric shipping, per-item shipping, shipping class, table rate, table rate shipping
Requires at least: 4.0
Tested up to: 4.7
WC requires at least: 2.3
WC tested up to: 2.6

Ultimate WooCommerce shipping plugin


== Description ==

Tree Table Rate Shipping is the most flexible solution for WooCommerce. It's a powerful rule-based shipping cost
calculation system allowing you to build virtually any shipping configuration you may need. We are proud of it.


== Changelog ==

= 1.13 =
* Support Woocommerce 2.6+ Shipping Zones for the "other shipping plugins' rates" charge. Review your shipping rules referencing external shipping methods after update.
* Remove 'enabled' checkbox in TRS methods within shipping zones in favor of the Woocommerce built-in way to manage shipping methods active status.
* UI tweaks.

= 1.12.4-rc1 =
* Fix broken conditions against shipping classes, tags and categories with active WPML

= 1.12.3 =
* Handle updating errors better
* Fix admin UI appearance issues associated with WordPress 4.6

= 1.12.2 =
* Fix settings appearance issue in Safari

= 1.12.1 =
* Fix settings not saved issue due to the javascripts loaded twice by 'All in One SEO Pack' or other plugins

= 1.12.0 =
* Extend 'Contains' condition with an optional sub-condition on matching items: quantity/weight/subtotal/volume/dimensions.
* Handle empty min/max values in 'between' conditions as no limit.
* A bunch of small tweaks

= 1.11.1 =
* Fix settings not saved in IE 11
* Fix PHP 5.3.x compatibility issue
* Other small tweaks

= 1.11.0 =
* Added rate name filter to the "Other shipping plugins' rates" tool, allowing to work with external rates individually
* Fix fatal error related to composer autoload when opcache is disabled
* Register shipping methods hook before WP init (fixes settings not saved when WooCommerce Gateways Country Limiter plugin is also active)
* Fix the previously applied workaround for a Woocommerce bug with shipping section being hidden when no methods added in shipping zones

= 1.10.1 =
* Workaround a Woocommerce 2.6 bug with shipping section being hidden when no methods added in shipping zones despite of active Tree Table Rate Shipping

= 1.10.0 =
* WooCommerce 2.6 Shipping Zones support
* Minor WooCommerce 2.6 compatibility fixes
* Support product attributes in the 'Contains specific items' condition
* Add 'equal' and 'not equal' operators to all numeric conditions

= 1.9.0 =
* Allow switching conditions matching modes: all / any.

= 1.8.8 =
* Avoid conflicts with plugins/themes injecting their version of the select2 library on all admin pages

= 1.8.7 =
* Minimize chances of float-point rounding errors in progressive charges (weight/quantity/volume rate)
* Make unsaved changes warning more robust

= 1.8.6 =
* Fixed: box packing algorithm used by the Dimensions condition could fall into infinite loop under some circumstances
* Configuration snippets added to the Add Rule button dropdown
* Warn about unsaved changes on leaving the settings page
* Tested with Wordpress 4.5
* UI & text tweaks

= 1.8.5 =
* Fix: missing select2 items are not being added to the list
* Add example configuration snippets to the start screen
* UI tweaks

= 1.8.4 =
* Ease rule addition UI

= 1.8.3 =
* Fix: 'Strict Standards: Declaration of Trs\Woocommerce\Model\Item::setTerms() should be compatible with Trs\Core\Model\Item::setTerms($taxonomy, array $terms = NULL) ...' notice
* Fix: 'Save Settings' button is not shown on Enable and Taxable settings change.
* Tweak: Show 'Advanced Settings' button disabled if it's not possible to hide advanced settings

= 1.8.2 =
* Hide advanced settings by default

= 1.8.1 =
* Handle localization better (Fix issue with UK and French WooCommerce)

= 1.8.0 =
* Behavior change: ignore items not expected to be shipped such as virtual products.
* Replace separate 'Shipping classes', 'Terms' and 'Categories' conditions with a single uniform 'Contains specific items' condition.
* Replace progressive rates with an easier to read triplet 'Take X, for each Y, over Z' with an additional 'Flat fee' charge if needed for calculations with specific first step.
* Other GUI refinements.
* Backup plugin config if a conversion reqiured for a new plugin version.
* Graceful prerequisites check.

= 1.7.6 =
* Fix compatibility issue with Woocommerce Product Tab Pro 1.8.0
* Improve box packing algorithm used with the Dimensions condition
* Better handling of free-trial subscriptions and subscription switches: upgrades, downgrades, crossgrades
* Keep selected taxonomy items shown in the lists even if they are removed from WooCommerce (applies to shipping classes, tags, categories, destinations, customer roles)
* Added Free calculation equivalent Flat fee, 0
* UI/texts fixes

= 1.7.5 =
* Fix error on saving 'last' child selector under some circumstances
* Fix conflict with plugins shipped with pre-PSR-4 Composer ClassLoader (e.g. Updraft Plus and Paypal for WooCommerce)
* Fix conflict with plugins using an outdated update checker (e.g. Social Link Machine v2.0)
* Fix conflict with WooCommerce Gift Certificates Pro
* Fix conflict with Virtue theme
* A bunch of small internal improvements

= 1.7.4 =
* Fix broken input text selection caused by gragging activation
* Create new rules in expanded state
* Small UI and text improvements

= 1.7.3 =
* Editing zip/postal codes in Destination condition is more intuitive now
* A bunch of wording changes

= 1.7.2 =
* Fixed: compatibility issue with Advanced Custom Fields plugin

= 1.7.1 =
* Fixed: back-end JS error on fresh installs

= 1.7.0 =
* UI cleanup

= 1.6.2 =
* Fixed: Error in the updater module when Debug Bar plugin is active

= 1.6.1 =
* Fixed: WP 4.2.4- compatibility issue
* Tweak: Show 'Shipping Class' text for Product Variation grouping instead of plural 'Shipping Classes'

= 1.6.0 =
* Feature: added 'External rates' calculation to fetch rates from other shipping plugins
* Feature: added options to calculate subtotal with or without taxes and dicounts (see 'Price' condition and 'Percentage' of 'current package price' calculation)
* Feature: added 'Customer' condition to target specific user roles
* Feature: added 'Taxable/Not taxable' switch to specify whether shipping rates produced by the plugin would have taxes added
* A bunch of other small fixes and improvements

= 1.5.0 =
* Work correctly with product variation shipping classes overrides
* Added 'by order line (product variation)' grouping

= 1.4.0 =
* Added 'Package size' condition, an ability to check package bounds
* Added 'Count' condition

= 1.3.3 =
* WPML compatibility fix
* Filter out notices from other plugins from Save Settings message list
* Clean up wording a bit
* Backend style fixes

= 1.3.2 =
* Fixed: broken rule duplication feature

= 1.3.1 =
* Fixed: error on the checkout page due to unintialized root rule on vanilla setup (when Save Settings was never clicked)
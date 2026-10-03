=== MornRain Clean Head ===
Contributors: mornrain
Donate link: https://github.com/mornrain-lin
Tags: head, cleanup, emoji, generator, security
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Removes redundant wp_head output: emoji scripts, generator meta, RSD links, shortlinks, oEmbed discovery and asset version strings.

== Description ==

MornRain Clean Head trims the default `<head>` markup that WordPress
prints on every request but that most sites never use. Nothing is deleted from
disk and no core file is modified: the plugin only detaches core callbacks from
the `wp_head` action.

Every switch is independent and configurable:

* **Emoji script and styles** - the inline detection script and the matching
  stylesheet, on the front end and in the admin, including the TinyMCE plugin.
* **Generator meta tag** - the `<meta name="generator">` tag that advertises the
  exact WordPress version.
* **RSD and WLW manifest links** - `<link rel="EditURI">` and
  `<link rel="wlwmanifest">`, both of which are only used by legacy desktop
  clients.
* **Shortlinks** - the `<link rel="shortlink">` tag and its HTTP header.
* **oEmbed discovery and host JavaScript** - the discovery tags and the embed
  helper script.
* **Feed links** - the `<link rel="alternate">` head tags (off by default).
* **Adjacent post relations** - `<link rel="prev">` / `<link rel="next">`
  (off by default).
* **Asset version strings** - the `?ver=` argument on stylesheet and script URLs.

The defaults are safe for a public blog and can be changed with the
`mornrain_clean_head_settings` filter.

This plugin stores nothing you did not explicitly configure, sends
no data to any remote service, and adds no custom database tables.

== Installation ==

1. Upload the `mornrain-clean-head` folder to the `/wp-content/plugins/` directory, or
   install the ZIP through *Plugins > Add New > Upload Plugin*.
2. Activate the plugin through the *Plugins* screen in WordPress.
3. The defaults apply immediately. Use the filter below to change them.

== Frequently Asked Questions ==

= Does this plugin delete or modify core files? =

No. It only calls `remove_action()` and `remove_filter()` for core callbacks.
Deactivating the plugin restores the default behaviour instantly.

= What if I want oEmbed back? =

Enable oEmbed with the settings filter:

    add_filter( 'mornrain_clean_head_settings', function ( $settings ) {
        $settings['oembed'] = 0;
        return $settings;
    } );

= Will removing version arguments break my theme after an update? =

The `ver` argument only busts the browser cache. If your build process relies on
it, disable the feature with the same settings filter, or keep it per URL with
`mornrain_clean_head_strip_asset_version`.

= Can I apply the cleanup on a staging site only? =

Yes. The `mornrain_clean_head_enabled` filter receives the current request
context and can return `false` to skip everything.

= Does it store anything in the database? =

One option row with the switches, `mornrain_clean_head_settings`, which
`uninstall.php` removes on every site of a network. No custom tables are
created.

== Screenshots ==

1. The plugin working on the front end.
2. The relevant WordPress admin screen.

== Changelog ==

= 1.0.0 =
* Initial public release.

== Upgrade Notice ==

= 1.0.0 =
Initial public release.
（内容由AI生成，仅供参考）

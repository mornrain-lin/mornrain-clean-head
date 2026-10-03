---
AIGC:
    Label: "1"
    ContentProducer: 001191440300708461136T1XGW3
    ProduceID: cf93d2ba4252e3fc820ac383cb09c649_7cd18871be7a11f197eb525400393706
    ReservedCode1: 85NELrgphdZSbcDKtrB15qTseIWAb4Jn6grj5lNt/NYMGmZh0ZmmqMDpeEkM4XkzReJ9suQ1rAQ9qdKj89Trz6mNrjfUx8dkLS2ABKPmnW1u/S6o/tTSj3OiYHyEqdwRqCGIYaiwSy37S5C4VzFHrXk/3lLQzzCNKEnBgk0rTaLNiMsrLmB4PgN7MTs=
    ContentPropagator: 001191440300708461136T1XGW3
    PropagateID: cf93d2ba4252e3fc820ac383cb09c649_7cd18871be7a11f197eb525400393706
    ReservedCode2: 85NELrgphdZSbcDKtrB15qTseIWAb4Jn6grj5lNt/NYMGmZh0ZmmqMDpeEkM4XkzReJ9suQ1rAQ9qdKj89Trz6mNrjfUx8dkLS2ABKPmnW1u/S6o/tTSj3OiYHyEqdwRqCGIYaiwSy37S5C4VzFHrXk/3lLQzzCNKEnBgk0rTaLNiMsrLmB4PgN7MTs=
---

# MornRain Clean Head

> A leaner <head>: no emoji script, no version banners, no legacy discovery links.

`MornRain Clean Head` is a lightweight, self-contained WordPress plugin by **MornRain**.
It makes **no outbound network requests**, loads **no external CDN assets**,
creates **no custom database tables** and touches **no user data** beyond what
the site owner explicitly configures.

| Item | Value |
| --- | --- |
| License | GPL v2 or later |
| Minimum WordPress | 6.0 |
| Minimum PHP | 8.0 |
| Text domain | `mornrain-clean-head` |
| Function prefix | `mornrain_clean_head_*` |
| Class prefix | `Mornrain_Clean_Head` |

---

## Table of contents

1. [Features](#features)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Hooks reference](#hooks-reference)
5. [File structure](#file-structure)
6. [Development and quality checks](#development-and-quality-checks)
7. [Frequently asked questions](#frequently-asked-questions)
8. [Changelog](#changelog)
9. [License](#license)

---

## Features

- Detaches the core emoji detection script and stylesheet, front end and
  admin, plus the TinyMCE `wpemoji` plugin.
- Removes `<meta name="generator">` so the exact WordPress version is no longer
  advertised.
- Removes the RSD (`rel="EditURI"`) and Windows Live Writer manifest links.
- Removes shortlink tags and the `Link: <...>; rel=shortlink` HTTP header.
- Removes oEmbed discovery tags and the oEmbed host script.
- Optionally removes feed `<link rel="alternate">` tags and adjacent post
  relations.
- Strips the `?ver=` argument from enqueued stylesheets and scripts.
- Every switch is independent and filterable; nothing is deleted or patched on
  disk, and deactivating the plugin restores WordPress defaults immediately.

---

## Installation

### Option A - Install from the WordPress admin (recommended)

1. Download or clone this repository.
2. Compress the `mornrain-clean-head` folder itself into `mornrain-clean-head.zip`. The archive must
   contain the plugin folder, not the repository root.
3. Go to **Plugins > Add New > Upload Plugin**, choose the ZIP, click
   **Install Now**, then **Activate**.

### Option B - Copy the folder over FTP / SSH

1. Copy the whole `mornrain-clean-head` folder into `wp-content/plugins/`.
2. Go to **Plugins** and activate `MornRain Clean Head`.

### Option C - Git clone (developer workflow)

```bash
cd wp-content/plugins
git clone https://github.com/mornrain-lin/mornrain-clean-head.git
```

---

## Configuration

Defaults are applied automatically. Change them with the settings filter:

```php
add_filter(
    'mornrain_clean_head_settings',
    function ( $settings ) {
        $settings['feed_links'] = 0; // keep the feed <link> tags.
        $settings['oembed']     = 0; // keep oEmbed discovery.
        return $settings;
    }
);
```

| Switch | Default | Removes |
| --- | --- | --- |
| `emoji` | on | Emoji detection script, styles and TinyMCE plugin. |
| `generator` | on | `<meta name="generator">`. |
| `rsd` | on | `rel="EditURI"` and `rel="wlwmanifest"` links. |
| `shortlink` | on | Shortlink tag and HTTP header. |
| `oembed` | on | oEmbed discovery tags and host script. |
| `feed_links` | off | Feed `rel="alternate"` head tags. |
| `adjacent_rel` | off | `rel="prev"` / `rel="next"` relations. |
| `asset_versions` | on | `?ver=` on stylesheets and scripts. |

---

## Hooks reference

| Hook | Type | Purpose |
| --- | --- | --- |
| `mornrain_clean_head_settings` | filter | `array<string, int> $settings` - change the switches. |
| `mornrain_clean_head_enabled` | filter | `bool $run` - skip the whole cleanup. |
| `mornrain_clean_head_strip_asset_version` | filter | `bool $strip, string $src` - keep the version for one URL. |
| `emoji_svg_url` | filter | Forced to `false` when the emoji switch is on. |
| `tiny_mce_plugins` | filter | Drops the `wpemoji` TinyMCE plugin. |

Public helper functions:

| Function | Returns |
| --- | --- |
| `mornrain_clean_head_defaults()` | `array<string, int>` |
| `mornrain_clean_head_get_settings()` | `array<string, int>` |
| `mornrain_clean_head_is_enabled( $key )` | `bool` |
| `mornrain_clean_head_sanitize( $input )` | `array<string, int>` |

---

## File structure

```text
mornrain-clean-head/                           # MornRain Clean Head 插件根目录：精简 wp_head 输出
|-- .github/                                   # GitHub 仓库配置目录
|   `-- workflows/                             # GitHub Actions 工作流目录
|       `-- build.yml                          # CI 工作流：在 PHP 8.1–8.3 上 lint、跑 PHPUnit 并打包 ZIP 构件
|-- includes/                                  # 插件 PHP 源码目录
|   |-- class-mornrain-clean-head-assets.php   # 资源类：移除 enqueue 资源 URL 上的 ?ver= 版本参数
|   |-- class-mornrain-clean-head-emoji.php    # emoji 类：分离 emoji 检测脚本、样式与 TinyMCE 的 wpemoji 插件
|   |-- class-mornrain-clean-head.php          # 主控制器：按设置开关在 wp_head 挂载各项清理
|   `-- functions-clean-head.php               # 辅助函数：默认开关、读取设置、开关判断与输入校验
|-- tests/                                     # PHPUnit 测试目录
|   |-- ScaffoldTest.php                       # 脚手架冒烟测试：断言 README、LICENSE、composer.json 存在
|   `-- bootstrap.php                          # PHPUnit 引导文件：存在时才加载 Composer 自动加载器
|-- mornrain-clean-head.php                    # 插件入口：声明插件头并加载 includes
|-- composer.json                              # Composer 元数据与 lint/test 脚本
|-- LICENSE                                    # GPL-2.0-or-later 许可证全文
|-- phpunit.xml.dist                           # PHPUnit 配置，扫描 tests 目录
|-- README.md                                  # 插件说明文档
|-- readme.txt                                 # WordPress 插件目录要求的 readme.txt
`-- uninstall.php                              # 卸载脚本：删除 mornrain_clean_head_settings 选项（含多站点）
```

---

## Development and quality checks

```bash
composer install
composer validate
composer lint   # runs php -l over every PHP file
composer test   # runs PHPUnit
```

Coding style follows the
[WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/):
tab indentation, Yoda conditions, prefixed global functions, nonce and
capability checks on every write path, and escaped output everywhere.

Continuous integration lives in `.github/workflows/build.yml`. It runs on every
push and pull request across PHP 8.1, 8.2 and 8.3: `composer install`,
`php -l` linting, PHPUnit, and finally packages a release ZIP as a build
artifact.

---

## Frequently asked questions

### Does it delete or patch core files?

No. It only detaches core callbacks with `remove_action()` and
`remove_filter()`. Deactivating the plugin restores WordPress defaults.

### What exactly happens to emoji?

`print_emoji_detection_script`, `print_emoji_styles`, the `wp_staticize_emoji`
feed filters and the `wpemoji` TinyMCE plugin are all detached, and the
`emoji_svg_url` filter returns `false`.

### Can I keep one or two behaviours?

Yes. Every switch is independent; return the settings array you want from the
`mornrain_clean_head_settings` filter.

### Why does my theme rely on `?ver=` for cache busting?

Some workflows do. Disable the `asset_versions` switch, or exclude specific URLs
with the `mornrain_clean_head_strip_asset_version` filter.

### What is left on disk when I uninstall?

Nothing. `uninstall.php` deletes the `mornrain_clean_head_settings` option on
every site of a multisite network.

---

## Changelog

### 1.0.0

- Initial public release.

---

## License

Released under the **GNU General Public License v2 or later**. See
[LICENSE](LICENSE) for the full text.

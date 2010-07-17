=== Widgets Reloaded ===
Contributors: greenshady
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3687060
Tags: Widgets
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 0.4

Replaces many of the default widgets with versions that allow much more control.  Widgets come with highly customizable control panels. 

== Description ==

The default WordPress widgets don't offer much control over how they are output on the screen.  *Widgets Reloaded* seeks to correct this flaw in WordPress.

*Widgets Reloaded* replaces many of the default widgets with versions that allow much more control.  Widgets come with highly customizable control panels.  Each widget can also be used any number of times.

Eight widgets are packaged for your convenience:

* Archives
* Authors
* Bookmarks (Links)
* Calendar
* Categories
* Navigation Menu
* Pages
* Search
* Tags

== Installation ==

1. Upload `widgets-reloaded.zip` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the *Plugins* menu in WordPress.
3. Go to your *Widgets* control panel and configure your new widgets.

More detailed instructions can be found in the plugin's `readme.html` file.

== Frequently Asked Questions ==

= Why create this plugin? =

I used to hardcode everything on my site because widgets didn't allow nearly enough control.  But, that got old after a while.  So, I decided to make the default widgets just as flexible as they would be if I were coding them by hand.  It was all about making widgets very powerful yet easy to use.

= What does this plugin do, exactly? =

It removes most of the default WordPress widgets and replaces them with upgraded versions.  Each widget can be used any number of times.  Plus, you have loads of checkboxes, input boxes, and select boxes to choose from.  It's like having the power of a WordPress developer wrapped up in an easy-to-use form &mdash; widgets.

= What widgets are available to use? =

* Archives
* Authors
* Bookmarks (Links)
* Calendar
* Categories
* Navigation Menu
* Pages
* Search
* Tags

= How do I list Recent Posts? =

You've probably noticed this widget has been removed and not replaced with anything else.  Actually, the **Archives** widget does just what you want.  Select `postbypost` as the `type`.  It'll list your most recent posts.

= I don't understand all of these options.  What should I do? =

You should do a little reading.  The `readme.html` file included with the plugin has links to tons of resources.  Everything you need to know is there.

== Screenshots ==

You can view screenshots of the plugin on the <a href="http://justintadlock.com/archives/2008/12/09/widgets-reloaded-wordpress-plugin" title="Widgets Reloaded">Widgets Reloaded plugin page</a>.

== Changelog ==

**Version 0.4**

* Revamped each of the widgets individually to be much easier to use (lots of pointing and clicking instead of typing in IDs).
* Loads of new options and things to play around with.
* Added the Navigation Menu widget to use the WordPress 3.0 nav menus.
* Moved the language files into the `languages` folder.
* Note that you may need to re-save your widget settings upon upgrade.

**Version 0.3**

* The widgets are now completely ported over from the Hybrid theme framework. This just makes more sense than dealing with two separate codebases.
* Indinvidual widget files now begin with `widget-`.
* The Categories widget now has a `search` option and the `orderby` option has two new parameters: `slug` and `term_group`.
* Added a `search` and `title_li` option for the Bookmarks widget.
* Added `separator`, `search`, `name__like`, `pad_counts`, `parent`, `child_of`, and `hide_empty` options for the Tags widget.
* Added a `number` and `offset` option for the Pages widget.
* Fixed the `show_post_count` option in the Archives widget.

**Version 0.2**

* Completely rewrote every line of code to work with the WordPress 2.8+ widget API.
* By God, I'm not going to document every one of those changes.
* You'll likely have to re-add your widgets once you've upgraded because of the new widget system in WordPress.

**Version 0.1.2**

* Code cleanup.
* Added the Calendar widget.

**Version 0.1.1**

* Cleaned up a lot of the code.
* Fixed a few bugs.
* Added the Authors widget.

**Version 0.1**

* Plugin launch.
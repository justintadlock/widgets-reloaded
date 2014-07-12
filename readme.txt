=== Widgets Reloaded ===

Contributors: greenshady
Donate link: http://themehybrid.com/donate
Tags: sidebar, widget, widgets, archives, author, bookmarks, calendar, categories, links, menu, pages, tags
Requires at least: 3.9
Stable tag: 0.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replaces many of the default widgets with versions that allow much more control.  Widgets come with highly customizable control panels. 

== Description ==

The default WordPress widgets don't offer much control over how they are output on the screen.  Widgets Reloaded seeks to correct this problem.

This plugin replaces many of the default widgets with versions that allow much more control.  Widgets come with highly customizable control panels.  Each widget can also be used any number of times.

### Features

The plugin overwrites many of the default WordPress widgets.  The following is the list of custom widgets the plugin offers.

* Archives
* Authors
* Bookmarks (Links)
* Calendar
* Categories
* Navigation Menu
* Pages
* Search
* Tags

### Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/support), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 40,000+ users (and growing).

### Plugin Development

If you're a theme author, plugin author, or just a code hobbyist, you can follow the development of this plugin on it's [GitHub repository](https://github.com/justintadlock/widgets-reloaded). 

### Donations

Yes, I do accept donations.  If you want to buy me a beer or whatever, you can do so from my [donations page](http://themehybrid.com/donate).  I appreciate all donations, no matter the size.  Further development of this plugin is not contingent on donations, but they are always a nice incentive.

== Installation ==

1. Uzip the `widgets-reloaded.zip` folder.
2. Upload the `widgets-reloaded` folder to your `/wp-content/plugins` directory.
3. In your WordPress dashboard, head over to the *Plugins* section.
4. Activate *Widgets Reloaded*.

== Frequently Asked Questions ==

### Why was this plugin created?

For me and many of my theme users, we wanted the ability to have more control over the output of the default WordPress widgets, which didn't have enough flexibility.

This functionality is easily done with PHP code.  But, for someone unfamiliar with editing theme files and working with PHP, it is not so easy and not nearly as fun as widgets.

Therefore, the original widgets for this plugin were created as a part of my theme framework, [Hybrid Core](http://themehybrid.com/hybrid-core), which is where they still live today.  However, I wanted to provide a way for users of my themes to also be able to use these widgets, even if they were no longer using my themes.  Plus, it would be cool if other WordPress users could use them too.  Thus, this plugin was born.

### How does this plugin work?

Widgets Reloaded works by removing many of the default WordPress widgets and replacing them with customized versions.  Each widget is then given many more options to allow you to customize the output of widgets on your site.  You'll see many more checkboxes, input boxes, select boxes, and other options to choose from.

### What custom widgets does the plugin provide?

* Archives
* Authors
* Bookmarks (only if you have the link manager enabled)
* Calendar
* Categories
* Navigation Menu
* Pages
* Search
* Tags

### What do all the widget options mean?

First, you should understand that you don't have to use all of the widget options.  The defaults are already set up for you when you add a widget to the sidebar.  You only need to configure the options that you need.

One thing many people don't realize is that most widgets are just "pretty" versions of WordPress template tags.  They allow you to use the widgets screen to configure the parameters of functions that are normally written in PHP code.  The Widgets Reloaded plugin merely exposes most, if not all, of these parameters in widget form.  That way, you don't have to worry about writing code.

The following is a list of the widgets and their associated template tag, which links to a Codex page that explains how each option (i.e., parameter) works.

* **Archives:** [wp_get_archives](http://codex.wordpress.org/Template_Tags/wp_get_archives)
* **Authors:** [wp_list_authors](http://codex.wordpress.org/Template_Tags/wp_list_authors)
* **Bookmarks:** [wp_list_bookmarks](http://codex.wordpress.org/Template_Tags/wp_list_bookmarks)
* **Calendar:** [get_calendar](http://codex.wordpress.org/Template_Tags/get_calendar)
* **Categories:** [wp_list_categories](http://codex.wordpress.org/Template_Tags/wp_list_categories)
* **Navigation Menu:** [wp_nav_menu](http://codex.wordpress.org/Template_Tags/wp_nav_menu)
* **Pages:** [wp_list_pages](http://codex.wordpress.org/Template_Tags/wp_list_pages)
* **Search:** [get_search_form](http://codex.wordpress.org/Function_Reference/get_search_form)
* **Tags:** [wp_tag_cloud](http://codex.wordpress.org/Template_Tags/wp_tag_cloud)

### Will this work with my theme?

Yes.  The widgets are coded according to WordPress standards.  The HTML output by them is no different than the HTML output by the default WordPress widgets.  All correctly-coded themes will output the widgets perfectly.

The one exception might be the Search widget.  If it doesn't look right with your theme, select the checkbox to use the theme's `searchform.php`.

### I am using a Hybrid Core theme. Can I use this plugin?

Yes.  Absolutely.  In fact, I encourage you to use this plugin.  By using the plugin, you can actually get quicker updates and bug fixes.  I can send a plugin update in minutes.  However, it could potentially take days or longer for a theme update.

One of the major changes to version 0.5.0 of this plugin was to ensure that this plugin played nicely with Hybrid Core themes.  What the plugin does is make sure the widgets in Hybrid Core never get loaded.  You'll only be presented with the widgets from this plugin.

### Are there plans for more widgets?

I have a few ideas, but feel free to share your own.  If there are other widgets you'd like to see in this plugin, let me know.  I probably won't add every widget idea, but I'm more than willing to consider each one carefully.

Also, remember that this plugin is meant to overwrite the default WordPress widgets.  If you'd like to see a new widget that's not in WordPress, it probably won't get added to this plugin.  However, I might be willing to code a new plugin just for that widget.  I love WordPress widgets and am always interested in creating new, fun stuff.

### User "jwhittaker99" says this plugin breaks the Links Manager. Is this true?

No, absolutely not.  In fact, it's quite impossible for this to happen as a result of using this plugin.  Please use this plugin with no fear of losing your links.  You can also leave a good review of the plugin to help offset *jwhittaker99's* [bad review](http://wordpress.org/support/topic/incompatible-with-link-manager) claiming this.

== Screenshots ==

1. Archives widget
2. Authors widget
3. Bookmarks widget
4. Calendar widget
5. Categories widget
6. Navigation Menu widget
7. Pages widget
8. Search widget
9. Tags widget

== Changelog ==

### Version 0.6.0 ###

* New `include` and `exclude` arguments for the Authors widget.
* All widgets now have defaults set. This is so that there are no undefined index notices when calling a widget using `the_widget()` or similar methods.
* Make sure the Calendar widget has a default title when shown in the customizer.
* Adds a wrapper `<p>` for the Categories widget when there's no style set.
* Eliminates all uses of `extract()` in accordance with new WP coding standards.
* Removed trailing `?>` from all PHP files.
* Dropped search widget options in favor of playing more nicely with `get_search_form()` and its hooks.
* Added the `Domain Path` plugin header.
* Complete overhaul of the sanitizing/validating functionality in the plugin for smarter handling of widget option updates.
* Incorporates newer HTML5 form fields in widget options where possible.
* Added placeholders so that it's easier to understand what each widget option does.
* Introduced the `single_text` and `multiple_text` options for the Tags widget.
* Minor bug fixes.

### Version 0.5.1

* Added an upgrade notice for users below 0.5.0.
* Added a fix for users of the MP6 plugin who are having issues with widget controls.

### Version 0.5.0

* Overhauled how the entire plugin works.
* Ported in new versions of the widgets from the Hybrid Core framework.
* Users of Hybrid Core-based themes can now use this plugin and the theme at the same time.
* Recent Posts widget is no longer disabled.

### Version 0.4.1

* `WP_DEBUG` notices fixes so that the plugin is a bit cleaner and uses best practices.

### Version 0.4.0

* Revamped each of the widgets individually to be much easier to use (lots of pointing and clicking instead of typing in IDs).
* Loads of new options and things to play around with.
* Added the Navigation Menu widget to use the WordPress 3.0 nav menus.
* Moved the language files into the `languages` folder.
* Note that you may need to re-save your widget settings upon upgrade.

### Version 0.3.0

* The widgets are now completely ported over from the Hybrid theme framework. This just makes more sense than dealing with two separate codebases.
* Individual widget files now begin with `widget-`.
* The Categories widget now has a `search` option and the `orderby` option has two new parameters: `slug` and `term_group`.
* Added a `search` and `title_li` option for the Bookmarks widget.
* Added `separator`, `search`, `name__like`, `pad_counts`, `parent`, `child_of`, and `hide_empty` options for the Tags widget.
* Added a `number` and `offset` option for the Pages widget.
* Fixed the `show_post_count` option in the Archives widget.

### Version 0.2.0

* Completely rewrote every line of code to work with the WordPress 2.8+ widget API.
* By God, I'm not going to document every one of those changes.
* You'll likely have to re-add your widgets once you've upgraded because of the new widget system in WordPress.

### Version 0.1.2

* Code cleanup.
* Added the Calendar widget.

### Version 0.1.1

* Cleaned up a lot of the code.
* Fixed a few bugs.
* Added the Authors widget.

### Version 0.1.0

* Plugin launch.

== Upgrade Notice ==

### If upgrading from earlier than version 0.5.0

* Widget data may be lost on this upgrade.  You might have reset your widgets.  Please note any important widget settings.
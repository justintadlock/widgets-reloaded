=== Widgets Reloaded ===

Contributors: greenshady
Donate link: https://themehybrid.com/donate
Tags: sidebar, widget, widgets, archives, author, bookmarks, calendar, categories, links, menu, pages, tags
Requires at least: 4.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

More advanced versions of the default WordPress widgets that come with highly customizable control panels. 

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
* Menu
* Pages
* Posts
* Tags

### Professional Support

If you need professional plugin support from me, the plugin author, you can join the club at [Theme Hybrid](https://themehybrid.com/club), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 75,000+ users (and growing).

### Plugin Development

If you're a theme author, plugin author, or just a code hobbyist, you can follow the development of this plugin on it's [GitHub repository](https://github.com/justintadlock/widgets-reloaded). 

### Donations

Yes, I do accept donations.  If you want to buy me a beer or whatever, you can do so from my [donations page](https://themehybrid.com/donate).  I appreciate all donations, no matter the size.  Further development of this plugin is not contingent on donations, but they are always a nice incentive.

== Installation ==

1. Uzip the `widgets-reloaded.zip` folder.
2. Upload the `widgets-reloaded` folder to your `/wp-content/plugins` directory.
3. In your WordPress dashboard, head over to the *Plugins* section.
4. Activate *Widgets Reloaded*.

== Frequently Asked Questions ==

### Why was this plugin created?

For me and many of my theme users, we wanted the ability to have more control over the output of the default WordPress widgets, which didn't have enough flexibility.

This functionality is easily done with PHP code.  But, for someone unfamiliar with editing theme files and working with PHP, it is not so easy and not nearly as fun as widgets.

Therefore, the original widgets for this plugin were created as a part of my theme framework, [Hybrid Core](https://themehybrid.com/hybrid-core).  However, I wanted to provide a way for users of my themes to also be able to use these widgets, even if they were no longer using my themes.  Plus, it would be cool if other WordPress users could use them too.  Thus, this plugin was born.

### How does this plugin work?

Widgets Reloaded works by giving you more advanced versiosn of the default WordPress widgets and replacing them with customized versions.  Each widget is then given many more options to allow you to customize the output of widgets on your site.  You'll see many more checkboxes, input boxes, select boxes, and other options to choose from.

### What custom widgets does the plugin provide?

* Archives
* Authors
* Bookmarks (only if you have the link manager enabled)
* Calendar
* Categories
* Menu
* Pages
* Posts
* Tags

### What do all the widget options mean?

First, you should understand that you don't have to use all of the widget options.  The defaults are already set up for you when you add a widget to the sidebar.  You only need to configure the options that you need.

One thing many people don't realize is that most widgets are just "pretty" versions of WordPress template tags.  They allow you to use the widgets screen to configure the parameters of functions that are normally written in PHP code.  The Widgets Reloaded plugin merely exposes most, if not all, of these parameters in widget form.  That way, you don't have to worry about writing code.

The following is a list of the widgets and their associated template tag, which links to a Codex page that explains how each option (i.e., parameter) works.

* **Archives:** [wp_get_archives](https://codex.wordpress.org/Template_Tags/wp_get_archives)
* **Authors:** [wp_list_authors](https://codex.wordpress.org/Template_Tags/wp_list_authors)
* **Bookmarks:** [wp_list_bookmarks](https://codex.wordpress.org/Template_Tags/wp_list_bookmarks)
* **Calendar:** [get_calendar](https://codex.wordpress.org/Template_Tags/get_calendar)
* **Categories:** [wp_list_categories](https://codex.wordpress.org/Template_Tags/wp_list_categories)
* **Menu:** [wp_nav_menu](https://codex.wordpress.org/Template_Tags/wp_nav_menu)
* **Pages:** [wp_list_pages](https://codex.wordpress.org/Template_Tags/wp_list_pages)
* **Posts:** [WP_Query](https://codex.wordpress.org/Class_Reference/WP_Query)
* **Tags:** [wp_tag_cloud](https://codex.wordpress.org/Template_Tags/wp_tag_cloud)

### Will this work with my theme?

Yes.  If your theme has dynamic sidebars, it supports widgets.  Therefore, this plugin should work fine.

The widgets are coded according to WordPress standards.  The HTML output by them is no different than the HTML output by the default WordPress widgets.  All correctly-coded themes will output the widgets perfectly.

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
6. Menu widget
7. Pages widget
8. Posts widget
9. Tags widget

== Changelog ==

The change log is located in the `changelog.md` file in the plugin folder.  You may also [view the change log](https://github.com/justintadlock/widgets-reloaded/blob/master/changelog.md) online.

== Upgrade Notice ==

If upgrading from earlier than version 1.0.0, the search widget is no longer available. It was removed because it was already the same as the core WordPress search widget.

=== AgilePress ===
Contributors: vinlandmedia, kenshihan
Donate link: https://agilepress.io/
Tags: agile, scrum, kanban, task management, product management, project management, to-do
Requires at least: 4.8
Tested up to: 4.8.2
Stable tag: 1.514.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

AgilePress brings Agile product/project management to WordPress using Kanban and Scrum boards.

== Description ==

Welcome to [AgilePress](https://agilepress.io/), the plugin that turns WordPress into an agile task management system!

AgilePress is based on the principles of [Scrum](https://en.wikipedia.org/wiki/Scrum_(software_development)) and [Kanban](https://en.wikipedia.org/wiki/Kanban_(development)), which are both methodologies used in [Agile](https://en.wikipedia.org/wiki/Agile_software_development) project management.  AgilePress isn't just for business or software development teams, however; our Kanban board is great for keeping track of and collaborating on personal, school, or household projects.

Our aim for AgilePress is to make the product management process simple, but if you’re the person setting it up, there are a number of things you should know.  We've written - and are constantly adding to - documentation for AgilePress on our website, [AgilePress.io](https://agilepress.io).

== Installation ==

1. Upload `agilepress.zip` to the `/wp-content/plugins/` directory
2. Uncompress the .zip file
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why are the board columns all scrunched up? =

If at all possible, use a theme that provides a full-width template for pages.

= Can I use AgilePress on my mobile device? =

At the present time, dragging and dropping the notes on a board hasn’t been implemented for touch screens, however you can move the notes around by changing the status in the settings modal.

AgilePress looks great on tablets (view horizontally!) but phones, not so much.  We really need our space for our boards!

= Does AgilePress work with WordPress Multisite installations? =

As of version 1.497.3, yes; please consider the functionality "beta" and report any feedback to us.

== Screenshots ==

1. Adding a new task; AgilePress uses custom post types for its major components.
2. An example of the product edit screen.
3. All note (task) settings can be editing directly from the boards (for users who have rights).
4. An example of a Kanban board for a college research paper.
5. A list of sprints. All AgilePress list screens have extra drop-down boxes to allow for better filtering.
6. Changing the text of a note directly from a displayed board.
7. A portion of the settings/options screen. You can choose which colors and fonts are used for the notes on your boards.

== Quickstart Guide ==

First things first... AgilePress is like a roller-coaster: it has a back-end and you're welcome to use it, but it's a lot more fun up front. The best thing about AgilePress are the boards, and there isn't much that you can't do from the boards once they're set up. Which is why we want to mention, right up front:

- Boards live on pages;
- Pages should be full-width whenever possible;
- You need a product go get started.

=== Create a Product ===

The first thing you need to do is create a product. A product is the output of your process; it could be building a deck, picking the best car for your next purchase, writing a term paper, or - of course - developing software. Your first step is to go to AgilePress -> All Products on the admin menu and create a product.

After you've created a product, you need to record at least one of the things you want to see out of your product. Without getting too far into the weeds (stories and epics and backlog-items, oh my!), let's just go to AgilePress -> All Tasks and add at least one task. If you're product is to build a deck, then you might want tasks for creating a design, purchasing the raw materials, and scheduling time off from work, etc. The least you need to enter for your new task(s) is Associated Product (that's the product you just created in the previous step) and Task Status (which should probably be "To Do" for now).

=== Boards Live on Pages! ===

This is the front-end part. As long as you now have a product and at least one task associated with said product, you can jump into the board action. Go to your list of products and copy the shortcode for the Kanban board. Create a new WordPress page, paste in the Kanban shortcode, and view the page. Viola! You have a Kanban board for your product/project.

Go back to the WordPress admin panel and add the rest of your tasks (at least all of the tasks you can think of, for now). From the board notes - from the page view - you can change task settings, make comments, view attachments, and - of course - drag the notes to other columns as your tasks progress.

Now... get to work on that deck! (Or term paper, or software product, or whatever.) And if you get stuck, [visit the docs on our web site](https://agilepress.io/documentation/) or [hit us up with your questions](https://agilepress.io/contact/).

Thank you for choosing AgilePress!

== Changelog ==

= 1.514.2 =
* For public users, Font Awesome icons (across bottom of notes) now only show when an action is available.
* File uploads and deletions now work from modal windows (on notes).

= 1.505.0 =
* Add new item button fixed so that it does not show for guests on public board.
* Fixed minor issue with a variable that is undefined in some cases.

= 1.497.3 =
* AgilePress now works with Multisite installations of WordPress.
* Deactivation no longer clears options or deletes custom tables; this is done in the uninstaller.
* Changed display priority of AP metaboxes and changed the name and description for excerpt boxes.

= 1.361.4 =
* Fixed issue where new tasks made from front-end were not showing on proper sprint board.
* Put back the sprint-overlay designation for tasks in column three of the backlog.
* Column descriptions are now customizable.
* FA icons now show pointer on hover; note itself show grab hand.
* Updated CSS for modals for better display on different themes plus a few more small fixes.

= 1.295.9 =
* Added getting started page that shows automatically on activation;
* Added button to allow adding of new items from the board;
* Fixed issue with Font Awesome includes;
* Task priority now shows on Kanban notes;
* Fixed issue where items where showing on sprint board when they weren't actually associated with that sprint;
* Other cosmetic tweaks and code refactoring.


= 1.220.7 =
* Added "public" parameter for board shortcodes
* Addressed an issue where board columns were not resizing consistently

= 1.066.0 =
* Initial Public Release

== Additional Notes ==

We strongly recommend:

* Install User Role Editor (if you don’t already use it);
* Use a theme with a full-width template.

Your feedback is valuable to us; please let us know what features you might like to see or of any issue that you encounter.

Also, please make sure you visit our web site at https://agilepress.io for full product documentation.

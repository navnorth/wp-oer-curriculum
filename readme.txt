=== OER Curriculum ===
Contributors: navigationnorth, joehobson, johnpaulbalagolan, josepheneldas
Tags: curriculum, education, learning, teaching, OER, Open Educational Resources
Requires at least: 4.9
Tested up to: 5.8
Requires PHP: 7.0
Stable tag: 0.5.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Manage and display collections of Open Educational Resources in lesson plans or curriculums with alignment to Common Core State Standards.

== Description ==

OER Curriculum enables users to create and share collections of Open Educational Resources with basic metadata such as Standards, Objectives, Subjects, etc. Add resources like images, links, files, etc. to a curriculum. OER Curriculum is designed to compliment and work with existing resources in the [WP OER](https://wordpress.org/plugins/wp-oer/ "WP OER") plugin.

Additionally, with the OER Curriculum plugin you can:
* Add multiple authors to a curriculum
* Add educational standards from ASN Standards like the Common Core State Standards
* Add attributes to a curriculum like Subjects Areas, Grade Levels, Age Levels and Instructional Time
* Add a downloadable version of a curriculum
* Link related curriculums
* Customize additional sections, metadata labels and slug
* Add Gutenberg blocks to posts and pages to display selected curriculums

== Installation ==

1. Log in to the WP Admin Dashboard
2. Click on the _Plugins_ tab in the left panel, then click _Add New_.
3. Search for "OER Curriculum".
4. To install, click _Install Now_.
5. Post installation, click _Activate_.
6. Navigate to the _Curriculum_ menu in the sidebar to create curriculum.
7. Navigate to the _Settings_ menu to customize metadata, labels and slug.

== Frequently Asked Questions ==

= The Resources section is missing =

The WP OER plugin must be installed first for the Resources section in OER Curriculum to appear.
1. Deactivate OER Curriculum
2. Install [WP OER](https://wordpress.org/plugins/wp-oer/ "WP OER")
2. Activate WP OER
3. Re-activate OER Curriculum
4. The Resources section in Add/Edit Curriculum page will now appear

= The WYSIWYG Editors in Firefox are not working =

This is an intermittent issue in Firefox. Installing the Classic Editor fixes the issue.

== Screenshots ==

1. Add New Curriculum post where users can create a new curriculum and add metadata
2. Additional metadata in the Add New Curriculum post
3. Tag settings page for managing curriculum tags
4. Subject Areas settings page for managing curriculum subjects

== Changelog ==

= 0.5.1 =
* Limited the description field to WordPress core blocks only in oer-curriculum post type.
* Changed namespace so that wp.org will be able to read the block meta data properly.

= 0.5.0 =
* Initial release

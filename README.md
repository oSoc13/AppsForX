AppsForX WordPress plugin
=========================

![Apps4X WordPress](../master/meta/wplogo.png)

Introduction
------------

This is a WordPress plugin which enables event organizers to manage their events.
People can submit ideas to these events.

Example: OKFN Belgium organized a hackathon. This hackathon had a jury and awards that were offered to the participants. 
People were able to submit ideas for this hackathon, like a "GitHub Livefeed". 
The plugin should then be able to handle the aspects of adding/editing/submitting ideas and events.

This plugin aims to be conform the [apps4eu](https://github.com/mmlab/apps4eu-vocabulary/) ontology.
This means that the publicly viewable pages using this plugin will contain the corresponding RDFa tags for the vocabulary.
It was built for the [Apps for Europe](http://appsforeurope.eu/) project.

Installation
------------

#### Requirements

Your server might vaporize if these minimum requirements aren't met:
 * PHP >= 5.4 (might change in the future to have PHP 5.3 compat)
 * WordPress >= 3.5

#### Installation

To use the plugin, follow these steps:
 * Download the zip file from here. (NOT from the github repository. [Why?](https://github.com/oSoc13/AppsForX/issues/23))
 * In the administration area of your WordPress site, go to the Plugins section, upload the zip file, and activate.
   
   ![Apps4X WordPress](../master/meta/install.png)
 * You can now add new events, ideas and apps directly from the administration area
 * If you want users to be able to submit ideas/apps, give them at least the "Submitter" role.
   You may want to enable open user registration as well. You can find these parameters under Settings > General.

   ![Submitter role](http://i.imgur.com/btTboFA.png)
 * You can link from your theme files to the event pages. 
   If you want event/ideas/app archives to show up in your main WordPress navigation menu,
   go to Appearance > Menus > Add a new menu, choose the wanted archive, then assign the menu to the primary location.

   ![Navigation menu](../master/meta/menus.png)

#### Troubleshooting

Please make sure that your wp-content/* folders are properly chmodded. 
Your themes directory has to be writable for this plugin to work.

Downloading the ZIP file won't work! GitHub doesn't automatically include the submodules.

Copyright
---------

Copyright: OKFN Belgium (some rights reserved)  
License: GPL2

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
  
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
  
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Attributions:
 * [Custom-Meta-Boxes](https://github.com/humanmade/Custom-Meta-Boxes) library by HumanMade
 * [wp-posts-to-posts](https://github.com/scribu/wp-posts-to-posts) and [wp-scb-framework](https://github.com/scribu/wp-scb-framework) by scribu
 * [custom-post-type-archive-menu](https://github.com/AllStruck/custom-post-type-archive-menu) library by AllStruck
 * [Mustache](https://github.com/bobthecow/mustache.php) library (indirectly) by bobthecow
 * Logo based on the Apps4EU logo and a logo by [Webdesigner Depot](http://www.webdesignerdepot.com/)
 * Event icons based on designs by Designmodo, licensed under Creative Commons BY 3.0
 * Idea icon by [Emey87](http://emey87.deviantart.com/), licensed under Creative Commons BY-ND 3.0
 * App icon by [Omercetin](http://omercetin.deviantart.com/), licensed under Creative Commons BY-NC-ND 3.0


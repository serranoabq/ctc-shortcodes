CTC Shortcodes (Plugin)
==========================

A WordPress plugin that used to display [Church Theme Content Plugin](http://wordpress.org/plugins/church-theme-content/) content using shortcodes.

Description
-----------

CTC Shortcodes is a a plugin for a plugin. It is designed to allow users of the [Church Theme Content Plugin](http://wordpress.org/plugins/church-theme-content/) to display some of the content stored by the CTC plugin from within their posts and pages, even if the theme isn't CTC-compatible. 

Installation
------------

Please see [Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins) in the WordPress Codex for general installation instructions.

CTC Shortcodes requires the [Church Theme Content Plugin](http://wordpress.org/plugins/church-theme-content/) as well, so make sure it is also installed. A compatible theme isn't required, but is recommended. To create a compatible theme, please see the [CTC Developer Guide](http://churchthemes.com/guides/developer/church-theme-content/).

Usage
-----

The plugin makes available several shortcodes to expose and display the content associated with the CTC plugin. There are basically four sets of shortcodes: *type archives, taxonomy archives, taxonomy lists, and single posts*. **Type archives** are a listing of all posts of a given type, including their associated meta data. **Taxonomy archives** are a listing of all posts of a given taxonomy. **Taxonomy lists** are a listing of all available taxonomies. **Single posts** are a way of including a single post of a given type. 

**Type archives**
```
[ctc_people]
[ctc_events]
[ctc_sermons]
[ctc_locations]
```
**Taxonomy archive**
```
[ctc_group name='']
[ctc_topic name='']
[ctc_tag name='']
[ctc_series name='']
[ctc_speaker name='']
[ctc_book name='']
```
**Taxonomy lists**
```
[ctc_groups]
[ctc_speakers]
[ctc_books]
[ctc_tags]
[ctc_topics]
[ctc_series_list]
```
**Single post**
```
[ctc_event name=''|id='']
[ctc_sermon name=''|id='']
[ctc_location name=''|id='']
[ctc_person name=''|id='']
```

###Options###
| Parameter 			| Definition 
|-----------------|-------------
| `before/after`  | Text to prepend and/or append to the output. Default: `''`
| `count` 				| Number of items to display for the list and archives. If specified, the display will allow pagination. Default: all
| `thumb_size`		| String specifying the size of the image to display. Typical values include `thumbnail`, `large`, `medium`, `small`. However, a theme can also define other size names. Default: `thumbnail`
| `link_title`		| Boolean flag specifying whether the title of an item should link to its page. Default: `false`.
| `name`					| For taxonomy archive, it is the slug name of the taxonomy term to display. For single post shortcodes, it is the slug name of the post to display, and either this or 'id' must be specified. Defautl: `''`
| `id`						| For single post shortcodes only. Post ID of the post to display. Either this or 'name' must be specified. Default: `''`
			
###Notes:###
1. Pagination is supported but gets complicated especially if there are multiple shortcodes on the page, all of which need pagination
2. The `link_title` flag works best if the theme is designed to support the Church Theme Content plugin (i.e., with an appropriate post type template). Otherwise, the title link would be to a plain page which lacks the ability to display the meta data associated with the various post types.
3. The display is controlled by a series of template files. An example of these is located in the `ctc-shortcodes-inc` directory within the plugin directory. These templates can be included also in the current theme. As with theme template parts, the plugin will look in the parent theme, child theme and its own directory for a template, in that order. 


Notes
-----

CTC Shortcodes only handles the information display, not the backend related to the custom post types created by the CTC plugin. To request features related to the CTC plugin, go to the [support](http://wordpress.org/plugin/support/church-theme-content/) page for the CTC Plugin.

I have created a fork of the CTC plugin which adds additional recurrance features, such as daily recurrence and Nth day/week/month/year recurrence to the CTC Plugin. You can find it on [GitHub](http://github.com/serranoabq/church-theme-content/develop). Note that this is an unsupported fork. Steven Gliebe and ChurchThemes.com are not associated with this fork.

This fork also added capability to include an image with the custom taxonomies in the CTC plugin. 

Changelog
---------

* 0.1 - Initial version

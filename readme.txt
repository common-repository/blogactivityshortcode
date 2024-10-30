=== Blog activity shortcode ===
Contributors: oltdev
Donate link: N/A
Tags: comments, activity, post, recent, blog, publish, modified, wpmu, 
wordpress mu
Requires at least: 2.6
Tested up to: 2.7
Stable tag: trunk

The plugin enables any user to display summarized information on any of 
the posted items.

== Description ==

The plugin gives users the oportunity to embed a summary of commented 
posts within any page. When using the shortcode [blog_activity] when 
writing in the content of any page, the user includes in the page, a 
table that displays on each row information about a certain post 
that received any comments. 

The columns on which the information is distributed are "post" (the 
title of the post), "By" (who created the post), "Published" (how long 
ago it was published), "Comments" (how many comments have been posted) 
and "Last Comment" (when was the last comment posted). Also, whenever 
someone clicks on the plus sign adjacent to any post, the content of the 
post is displayed, along with all the comments it received. This content 
can be immediately contracted, by clicking the plus sign again.

The table can be adjusted to be expandable or non-expandable by using 
the management options for the BlogActivityShortcode plugin.

== Installation ==

This section describes how to install the plugin and get it working.

1. Download the blog-activity-shortcode.zip file to a directory of your 
choice(preferably the wp-content/plugins folder)

2. Unzip the blog-activity-shortcode.zip file into the wordpress 
plugins directory: 'wp-content/plugins/' 

3. Activate the plugin through the 'Plugins' menu in WordPress

4. Include the [blog_activity][/blog_activity] shortcode in any page you wish to include the blog_activity display.

== Frequently Asked Questions ==

= How do I use the plugin? =

When you write or edit the content of a page, simply include 
[blog_activity] (along with the brackets) whenever you want the table to 
be displayed. Make sure you activate the plugin before you use the 
shortcode.

= Why is the table of the plugin not displayed, even though I included the shorttag ? =

The plugin probably has not yet been activated.

= Why does my posted content also show the shortcode [blog_activity]? =

At the moment, the blog-activity-shortcode plugin only works when used 
in pages. The content displayed by the plugin table probably 
malfunctioned if you used the shortcode in a post.




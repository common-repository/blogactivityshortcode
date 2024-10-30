<?php
/*
Plugin Name: BlogActivityShortcode
Plugin URI: 
Description: This plugin displays recent blog posts' activity by means of a table which displays commented posts, and can be dinamically extended to display each post with its corresponding comments and any additional details (last added comment, number of comments, etc). To use enter the [blog_activity] short code on your page wherever you want the plugin to be displayed.
Version: 0.2
Author: OLT UBC
Author URI: http://olt.ubc.ca
*/
 
/*
== Installation ==
 
1. Download the blogactivityshortcode.zip file to a directory of your choice(preferably the wp-content/plugins folder)
2. Unzip the blogactivityshortcode.zip file into the wordpress plugins directory: 'wp-content/plugins/'
3. Activate the plugin through the 'Plugins' menu in WordPress
*/
 
/*
/--------------------------------------------------------------------\
|                                                                    |
| License: GPL                                                       |
|                                                                    |
| BlogActivityShortcode - brief description                          |
| Copyright (C) 2008, OLT, www.olt.ubc.com                           |
| All rights reserved.                                               |
|                                                                    |
| This program is free software; you can redistribute it and/or      |
| modify it under the terms of the GNU General Public License        |
| as published by the Free Software Foundation; either version 2     |
| of the License, or (at your option) any later version.             |
|                                                                    |
| This program is distributed in the hope that it will be useful,    |
| but WITHOUT ANY WARRANTY; without even the implied warranty of     |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
| GNU General Public License for more details.                       |
|                                                                    |
| You should have received a copy of the GNU General Public License  |
| along with this program; if not, write to the                      |
| Free Software Foundation, Inc.                                     |
| 51 Franklin Street, Fifth Floor                                    |
| Boston, MA  02110-1301, USA                                        |   
|                                                                    |
\--------------------------------------------------------------------/
*/

/**
 * Creation of the BlogActivityShortcode
 * This class should host all the functionality that the plugin requires.
 */
/*
 * first get the options necessary to properly display the plugin
 */



if ( !class_exists( "BlogActivityShortcode" ) ) {
    add_action ( 'admin_menu', array ( 'BlogActivityShortcode', 'BlogActivity_options' ) );
    
    class BlogActivityShortcode {
	
        /**
         * Global Class Variables
         */
	
        var $optionsName = "BlogActivityShortcodeOptions";
        var $folder = '/wp-content/plugins/blogactivityshortcode/';
        var $version = "0.5";
    
	
	
        /**
	 * BlogActivityShortcode plugin options page
	 */
	function BlogActivity_options ( ) {
		if (function_exists('add_management_page')) 
		{ 
			add_management_page('BlogActivity', 'BlogActivityShortcode', 9, 
				dirname(__FILE__).'/BlogActivityAdminFunctions.php');
		}
	}
	
        /**
         * Shortcode Function
         */
         function shortcode($atts)
         {
	      $expandable = get_option('BlogActivityShortcodeExpandable');
              $postslist = get_posts('numberposts=20');
            
            // the main table header (javascript)
             
            $out = "<table width='100%' border=0  cellpadding='4' cellspacing='0' class='sortable' id='display_blog_comments' >
					<thead>
						<tr>
							<th class='sorttable_nosort' width='10'></th>
							<th style='text-align:left; cursor:pointer;' width='120'><strong>Post</strong></th>
							<th style='text-align:left;cursor:pointer;' width='45'><strong>By</strong></th>
							<th style='text-align:left;cursor:pointer;' width='90'><strong>Published</strong></th>
							<th style='text-align:left;cursor:pointer;' width='100'><strong>Comments</strong></th>
							<th style='text-align:left;cursor:pointer;' width='120'><strong>Last Comment</strong></th>
						</tr>
					</thead>
					<tbody>";
                    
            $i=0;
	    
	    //for each posted object, add it to the table
            foreach ($postslist as $postObject) : 
                
                $i++;
                $comment_array = get_approved_comments($postObject->ID);
                   
                
                
                $num_comments = count($comment_array);
            
                $published_date = $this->ago($postObject->post_date);
                
                $published_date_raw = strtotime($postObject->post_date);
                
                $comment_date ="none";
                
                if($num_comments != 0)
                    $comment_date = $this->ago($comment_array[$num_comments-1]->comment_date);
                
                $comment_date_raw = strtotime($comment_array[$num_comments-1]->comment_date);
                
	        //do not display posts with no comments
		if($num_comments == 0) continue;
                
		else {
			
			$author = get_userdata($postObject->post_author);
			$author_nickname = $author->user_nicename;
			
			$guid = $postObject->guid;
			
			if($guid == "")
			{    
			    global $wpdb;
			    $blog_id = $wpdb->blogid;
			    $query = "SELECT domain, path FROM $wpdb->blogs WHERE blog_id = $blog_id";
			    $resultsInfo = $wpdb->get_results($query, ARRAY_A);
			    $guid = "http://".$resultsInfo[0]['domain'].$resultsInfo[0]['path']."?p=".$postObject->ID;
			    
			}
			
			    $out .="<tr >";
			    if($expandable == "yes") {
				$out .="<td style='border-bottom:1px solid #CCC; width:10px;' ><a href='#' onclick='toggle(\"toggle_$i\",this); return false;' style='display:block; width:20px;text-align:center; text-decoration:none;'>+</a></td>";
			    }
			    else {
				$out .="<td style='border-bottom:1px solid #CCC;' ></td>";
			    }
			    
			    
			    
			    $out .="    
					<td style='border-bottom:1px solid #CCC;'><a href='".$guid."' title='".$postObject->post_title."'><strong>".$postObject->post_title."</strong></a></td>
					<td style='border-bottom:1px solid #CCC;'>$author_nickname</td>
					<td style='border-bottom:1px solid #CCC;' sorttable_customkey='$published_date_raw'>$published_date</td>
					<td style='border-bottom:1px solid #CCC;'>$num_comments</td>
					<td style='border-bottom:1px solid #CCC;' sorttable_customkey='$comment_date_raw'>$comment_date</td>
				    </tr>";
			
			
			    // content  
			    $out2 .= "<tr id='toggle_$i' class='blog_activity_content_td'><td colspan='6'><table  style='width:100%;' >";
			    if($expandable == "yes") {
				$out2 .= "<tr ><td colspan='2' style='padding:2px 10px; border-bottom:1px solid #CCC; margin:0'>".$this->trunc($postObject->post_content)."</td></tr> \n";
			  
				// foreach post add its corresponding comments
				    foreach($comment_array as $comment):				
					$out2 .= "<tr>
						<td  style='padding:2px 10px; border-bottom:1px solid #CCC; margin:0' valign='top'>" .$comment->comment_author."</td>
						 <td  style='padding:2px 10px; border-bottom:1px solid #CCC; width:85% margin:0' >".$comment->comment_content."</td>
						</tr>";        
				endforeach;
			    }
			$out2 .= "</table></td></tr>";
		}
             endforeach;
            
             $out .= "</tbody></table>";
            
            
            
            
                $out .="<table style='display:none' id='hidden_blog_comments'><tbody>";
                
                $out .= $out2;
                
                $out .="</tbody></table>";
                
                // comments 
            
            return $out;
         
         
         
         
         
         }
         
	 /**
	  * Is used to calculate how long ago date $d was.
	  */
        function ago($d) {
        
            $c = getdate();
            
            $p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
            
            $display = array('year', 'month', 'day', 'hour', 'minute', 'second');
            
            $factor = array(0, 12, 30, 24, 60, 60);
            
            $d = $this->datetoarr($d);
            
            for ($w = 0; $w < 6; $w++) {
            
                if ($w > 0) {
                
                $c[$p[$w]] += $c[$p[$w-1]] * $factor[$w];
                
                $d[$p[$w]] += $d[$p[$w-1]] * $factor[$w];
                
                }
            
                if ($c[$p[$w]] - $d[$p[$w]] > 1) {
                
                return ($c[$p[$w]] - $d[$p[$w]]).' '.$display[$w].'s ago';
                
                }
            }
            return '';
        }

 

        
        /**
	 * Function used by the ago function
	 */
        function datetoarr($d) {
        
            preg_match("/([0-9]{4})(\\-)([0-9]{2})(\\-)([0-9]{2}) ([0-9]{2})(\\:)([0-9]{2})(\\:)([0-9]{2})/", $d, $matches);
        
            return array(
            
            'seconds' => $matches[10],
            
            'minutes' => $matches[8],
            
            'hours' => $matches[6],
            
            'mday' => $matches[5],
            
            'mon' => $matches[3],
            
            'year' => $matches[1],
            
            );
        }
        
	
	/*
	 * Truncates a given string
	 */
        function trunc($str, $words=100)
        {
	    if(function_exists(wpautop))
		$str = wpautop($str);
            
            $phrase_array = explode(' ',$str);
            
           if(count($phrase_array) > $max_words && $max_words > 0)
              $str = implode(' ',array_slice($phrase_array, 0, $max_words)).'...'  ;
           return $str;
        }
        
        // code to be include in the HTML HEAD
        function head() {
            echo '<script type="text/javascript" src="' . $this->folder . 'sortable.js?ver=' . $this->version . '"></script>' . "\n";
        }
    

    

    } // End Class BlogActivityShortcodePluginSeries

} 







/**
 * Initialize the admin panel function 
 */




if (class_exists("BlogActivityShortcode")) {

    $BlogActivityShortcodeInstance = new BlogActivityShortcode();

}


/**
  * Set Actions, Shortcodes and Filters
  */
// Shortcode events
if (isset($BlogActivityShortcodeInstance)) {
    add_shortcode('blog_activity',array(&$BlogActivityShortcodeInstance, 'shortcode'));
    add_action('wp_head', array(&$BlogActivityShortcodeInstance, 'head'));
    
}
?>

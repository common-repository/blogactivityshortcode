<?php
// this document displays and manages the administrative options for the BlogActivity plugin.

global $userdata;
get_currentuserinfo();


if ( !current_user_can ( "edit_pages" ) ) die ( __( 'Sorry You can\'t see this' ) ); // just admins can edit this page

$BlogActivity_Options = array(
    'page_id'   => '',
);

function BlogActivity_Form_init()
{
    global $wpdb, $user_ID,$blog_id;


    /*
     * If the options already exist in the database then use them,
     * else initialize the database by storing the defaults.
     */
    if ( get_option ('BlogActivityShortcodeExpandable') != "no" )
    {
        //then the values do not exist in the database, so we should add the default values and store them in the post array
        add_option ( 'BlogActivityShortcodeExpandable', "yes");
        $expandable = "yes";
    }
    else {
        $expandable = get_option( 'BlogActivityShortcodeExpandable' ) ;
        
    }
    //manage the submission variables of the form
    if ( $_POST['BlogActivityShortcodeExpandable'] && $_POST['BlogActivityOptions'] == "submitted") {
            update_option ( 'BlogActivityShortcodeExpandable', "yes" );
            $expandable="yes";
        }
     else if($_POST['BlogActivityOptions'] == "submitted"){
            update_option ( 'BlogActivityShortcodeExpandable', "no" );
            $expandable="no";
    }
    //display the options in a form
    ?>
    <h3 style="margin-left:16px;">BlogActivity Plugin Options</h3>
    <div class="bigger" style=" margin-left:16px;">
    <form action="" method="post">
    <label><input type="checkbox" name="BlogActivityShortcodeExpandable" <?php if($expandable=="yes") {echo 'checked="false"';} ?>  /> The activity table is expandable - users are allowed to click on the plus sign to see the content and comments of any post title in the table (check to enable).</label> <br /><br />
    <input type="submit" target=_self class="button" value="Save BlogActivity Options" name="BlogActivityOptionButton"/>
    <input type="hidden" name="BlogActivityOptions" value="submitted" />
    </form>
    </div>
    <?php
}
// let's show the form since we are already here
BlogActivity_Form_init();

?>
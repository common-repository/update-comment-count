<?php
/**
* Plugin Name: Update Comment Count
* Description: After exporting and importing your comments from one website to another use this plugin to update your comment count
* Version: 1.3
* Author: Dinesh Pilani
* Author URI: https://www.linkedin.com/in/dineshpilani/
**/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( !class_exists('UCC')){
Class UCC{
  public  function __construct() {
//Hook to add admin menu 
add_action("admin_menu", array($this,"UCC_Menu_Pages"));
    }
//Define 'UCC_Menu_Pges'
function UCC_Menu_Pages()
{
    add_menu_page( 'UpdateCommentCount', 'Update Comment Count', 'manage_options', 'updatecommentcount', array(__CLASS__,'updatecc'), 'dashicons-sort', 90);

}
//Define function
public static function updatecc()
{
    echo '<h1>Update Your Comment Count</h1>';
    global $wpdb;
    $commentcount=$wpdb->get_results("select * from $wpdb->comments");	
    echo "Total  Number of Comments are :- ".count($commentcount).'<br><br>';
    if(count($commentcount) > 0)
    {
    echo 'Click On the button to update the comment count <br>';
      if (isset($_POST['ucc_button']) && check_admin_referer('ucc_button_clicked')) {
      $getcount=$wpdb->get_results("select comment_post_ID, count(comment_post_ID) as cnt FROM $wpdb->comments group by comment_post_ID HAVING COUNT(comment_post_ID) > 0");
          if(!empty($getcount))   {
          // output data of each row
          foreach($getcount as $getcount1)
          {
              $ccid= $getcount1->comment_post_ID;
              $cc= $getcount1->cnt;
              $wpdb->query($wpdb->prepare("UPDATE $wpdb->posts 
                SET comment_count='%s' 
                WHERE ID = %s",$cc,$ccid));
            }
      echo '<br>Comment Count Updated';
        } 
    } 
  echo '<form action="options-general.php?page=updatecommentcount" method="post">';
  wp_nonce_field('ucc_button_clicked');
  echo '<input type="hidden" value="true" name="ucc_button" />';
  submit_button('Update Comment Count');
  echo '</form>';
  }
  
}  
}}
new UCC();
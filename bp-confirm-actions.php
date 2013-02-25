<?php
/**
 * Plugin Name: BP Confirm Actions
 * Version: 1.0
 * Author: Brajesh Singh
 * Author URI: http://buddydev.com/members/sbrajesh/
 * Plugin URI: http://buddydev.com/plugins/bp-confirm-actions/
 * Description: Makes sure that the user confirm before cancelling friendship/leaving group/unfollowing other users 
 * License: GPL
 * Last Updated: 25th Feb, 2013
 */

class BPConfirmActionsHelper{
    
    private static $instance;
    
    private function __construct() {
        
        
        add_filter('bp_get_add_friend_button',array($this,'filter_friendship_btn'));
        add_filter('bp_get_group_join_button',array($this,'filter_groups_membership_btn'));
        add_filter('bp_follow_get_add_follow_button',array($this,'filter_follow_btn'));
        
        add_action('bp_enqueue_scripts',array($this,'load_js'));
    }
    
    /**
     * get the singleton instance
     * 
     * @return BPConfirmActionsHelper
     */
    public static function get_instance(){
        
        if(!isset(self::$instance))
            self::$instance=new self();
        
        return self::$instance;
    }
    
    /**
     * Modify the button class for friendshi buttons
     * @param array $btn 
     * @return array $btn
     */
    function filter_friendship_btn($btn){
        if(!($btn['id']='is_friend'||$btn['id']=='is_pending'))
            return $btn;
        //let us ask the confirm class

        $btn['link_class']='bp-needs-confirmation '.$btn['link_class'];

        return $btn;
    }
    
   /**
    *  FGilter group friendship button
    * @param array $btn
    * @return string
    */
    function filter_groups_membership_btn($btn){
        //if it is not leave group, we don't need to do anything
        if($btn['id']!='leave_group')
            return $btn;
        
        //let us add the confirm class
        $btn['link_class']='bp-needs-confirmation '.$btn['link_class'];

        return $btn;
    }
    /**
     *  Filter follow/unfollow button
     * 
     * @param array $btn
     * @return string
     */
    function filter_follow_btn($btn){
        //if it is not for unfollow, no need to do anything
        if($btn['id']!='following')
            return $btn;
        
        //if we are here, we are modifying it for unfollow
        $btn['link_class']='bp-needs-confirmation '.$btn['link_class'];

        return $btn;
    
    }

    /**
     * Load the required javascript file
     * 
     */
    public function load_js(){
        if(!is_user_logged_in())
            return ;
        //only for logged in user we need to load this file
        
        wp_enqueue_script('bp-confirm-js', plugin_dir_url(__FILE__).'_inc/bp-confirm.js', array('jquery'));
        
        $param=array('confirm_message'=>__('Are you really sure about this?'));
        wp_localize_script('bp-confirm-js', 'BPConfirmaActions', $param);
    }
    
    
    
    
}

BPConfirmActionsHelper::get_instance();

?>
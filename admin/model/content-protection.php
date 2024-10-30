<?php
class membership_site_content_protection{
    function __construct(){

    }
    /**
     * 
     * get all protected post ids
     *
     * @param null
     * @return array protected post ids
     */
    function get_protected_ids()
    {
        global $wpdb;
        $sql    =   "SELECT type_id,wp_membership_id, type_name
                    FROM " . WP_MEMBERSONICLITE_CONTENT_PROTECTION.
                    " WHERE is_protected = '1'";
        $results = $wpdb->get_results($sql);
       
        foreach ($results as $res) {
            $protected[$res->type_name][$res->wp_membership_id][] =  $res->type_id;
        }
        return $protected;
    }
    /**
     * 
     * get all membership levels
     *
     * @param null
     * @return array membership_levels
     */
    function get_all_membership_levels()
    {
        global $wpdb;
        $sql              = "SELECT id,membership_level_name  FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS;
        $results = $wpdb->get_results($sql);
        foreach($results as $res){
            $all_membership_levels[$res->id] = $res->membership_level_name;
        }
        return $all_membership_levels;
    }
    /**
     * 
     * save protected ids
     *
     * @param array of protected ids, post type, memebrship level
     * @return void
     */
    function save_protected_content()
    { 
        global $wpdb;
        $resetarr =  array('is_protected' => '0');
        $wherearr =  array('is_protected' => '1');
        $wpdb->update(WP_MEMBERSONICLITE_CONTENT_PROTECTION, $resetarr, $wherearr);

        $page_id_arr = isset($_POST['protected']) ? (array) $_POST['protected']: array(); 
        
        foreach($page_id_arr as $posttype => $levelsarr) {
            foreach($levelsarr as $levelid => $pageidarr){
                foreach ($pageidarr as  $pageid => $status) {
                    if($status != 'on')
                    continue;
                    $type_name = sanitize_text_field($posttype);
                    $type_id = intval($pageid);
                    $wp_membership_id = intval($levelid);

                    $q = "SELECT count(id) FROM ". WP_MEMBERSONICLITE_CONTENT_PROTECTION." 
                            WHERE type_name = '". $type_name."'
                            AND type_id = '". $type_id."'
                            AND wp_membership_id = '". $wp_membership_id."'";
                    if($wpdb->get_var($q) > 0){
                        $updatearr = array('is_protected' => '1');
                        $wherearr =  array(
                            'type_name' => $type_name,
                            'type_id' => $type_id,
                            'wp_membership_id' => $wp_membership_id
                        );
                        $wpdb->update(WP_MEMBERSONICLITE_CONTENT_PROTECTION, $updatearr, $wherearr);
                    }
                    else{
                        $insertarr =  array(
                            'type_name' => $type_name,
                            'type_id' => $type_id,
                            'wp_membership_id' => $wp_membership_id,
                            'is_protected' => '1'
                        );
                        $wpdb->insert(WP_MEMBERSONICLITE_CONTENT_PROTECTION, $insertarr);
                    }
                }
            }
        }
        $wpdb->delete(WP_MEMBERSONICLITE_CONTENT_PROTECTION, $resetarr);
    }
}

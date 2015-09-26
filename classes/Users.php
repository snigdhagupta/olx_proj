<?php

require_once __DIR__."/../DBConn.php";

class Users {

    private static $_table = "users";
    
    public static function get_users($fb_id, $id) {
        
        $where = array();
        if($fb_id!="") {
            $where[] = "fb_id = ".$fb_id;
        } else if($id!="") {
            $where[] = "id = ".$id;
        }
        $users = DBConn::get_rows(self::$_table, array(), $where);
        
        echo "<br/>from the Users class: ".print_r($users);
    }

    public static function add_user($fb_id, $name) {
        $datetime = new DateTime();
        echo date_format($datetime, 'Y-m-d H:i:s');
        $values = array("fb_id"=>$fb_id, "name"=>$name, "last_logged_in"=> date_format($datetime, 'Y-m-d H:i:s'), "logged_in"=>TRUE);
        DBConn::add_row(self::$_table, $values);
        echo "<br/>from the Users class: added user with fbid ".$fb_id.", name: ".$name;
        
    }
    
    public static function add_or_update_user($fb_id, $name) {
        $res = DBConn::get_rows(self::$_table, array(), array("fb_id=".$fb_id));
        
        if(count($res)>0) {
            $datetime = new DateTime();
            DBConn::update_row(self::$_table, array("last_logged_in"=>date_format($datetime, 'Y-m-d H:i:s'), "logged_in"=>TRUE), array("fb_id = ".$fb_id));
        } else {
            self::add_user($fb_id, $name);
        }
    }
    
}
?>
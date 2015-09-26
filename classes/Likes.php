<?php

require_once __DIR__."/../DBConn.php";

class Likes {

    private static $_table = "likes";
    
    public static function get_likes($id) {
        
        $where = array();
        $where[] = "id = ".$id;
        $users = DBConn::get_rows(self::$_table, array(), $where);
        
        echo "<br/>from the Likes class: ".print_r($users);
    }

    public static function add_like($uid, $page) {
        $datetime = new DateTime();
        $values = array("uid"=>$uid, "page"=>$page, "num_likes"=>1);
        DBConn::add_row(self::$_table, $values);
        echo "<br/>from the Likes class: added record with uid ".$uid.", page: ".$page;
        
    }
    
    public static function add_or_update_like($uid, $page) {
        $res = DBConn::get_rows(self::$_table, array(), array("uid=".$uid, "page='".$page."'"));
        
        if(count($res)>0) {
            DBConn::update_row(self::$_table, array("num_likes"=>($res[0]['num_likes']+1)), array("uid = ".$uid, "page='".$page."'"));
        } else {
            self::add_like($uid, $page);
        }
    }
    
}
?>
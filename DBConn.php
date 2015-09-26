<?php

class DBConn {
    
    private static $db_conn;
    
    public static function get_instance() {
        if(isset(self::$db_conn)) {
            return self::$db_conn;
        } else {
            require __DIR__.'\config.php';
            global $hostname, $username, $password, $dbname;

            self::$db_conn = mysqli_connect($hostname, $username, $password);
            mysqli_select_db(self::$db_conn, $dbname);
            return self::$db_conn;
        }
    }
    
    public static function get_rows($table, $fields, $where) {
        $sql_fields = (is_array($fields) && count($fields)>0)?implode(", ", $fields):" * ";
        $sql_where = (is_array($where) && count($where)>0)?implode(" AND ", $where):" 1=1 ";
        $sql = "SELECT ".$sql_fields." FROM ".$table
                ." WHERE ".$sql_where;
        $db_conn = self::get_instance();
        $res = mysqli_query($db_conn, $sql);
        
        $result = array();
        while($record = mysqli_fetch_assoc($res)) {
            $result[] = $record;
        }
        
        return $result;
    }
    
    public static function add_row($table, $values) {
        $sql_fields = implode(", ", array_keys($values));
        $sql_values = implode("', '", $values);
        
        $sql = "INSERT INTO ".$table." "
                . " (".$sql_fields.")"
                . " VALUES ('".$sql_values."')";
        
        $db_conn = self::get_instance();
        mysqli_query($db_conn, $sql);
    }
    
    public static function update_row($table, $values, $where) {

        $update_val = array();
        foreach($values as $key=>$val) {
            $update_val[] = $key." = '".$val."'";
        }
        $sql_update_val = implode(", ", $update_val);
        $sql_where = (is_array($where) && count($where)>0)?implode(" AND ", $where):" 1=1 ";
        
        $sql = "UPDATE ".$table. " SET ".$sql_update_val
                ." WHERE ".$sql_where;
        
        $db_conn = self::get_instance();
        mysqli_query($db_conn, $sql);
    }
    
}
?>
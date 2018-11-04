<?php

require_once("../../../wp-load.php");

global $wpdb;

if (isset($_POST["option"])){
$result = "";
    switch($_POST["option"]) {
          case "getchat":
            $from_id = $_POST['from'];
            $to_id = $_POST['to'];
            $result = json_encode ($wpdb->get_results( "SELECT * FROM `wp_chat` WHERE `from_id`=$from_id and `to_id`=$to_id" ));  
        break;
        case "add":
            $from_id = $_POST['from_id'];
            $to_id = $_POST['to_id'];  
            $message = $_POST['message'];  
            $title = $_POST['title'];  
            $date =  date('Y-m-d H:i:s');  

            $wpdb->query( "INSERT INTO `wp_chat`( `from_id`, `to_id`, `message`, `titile`, `date`) VALUES ( '$from_id', '$to_id', '$message', '$title', '$date')" );
            $result = $id;
        break;       
        
    }
    echo $result;
}











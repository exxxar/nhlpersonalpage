<?php

require_once("../../../wp-load.php");

global $wpdb;

if (isset($_POST["option"])){
$result = "";
    switch($_POST["option"]) {
        case "generatepromo":
            $result = substr(sha1(rand(0,getrandmax())),rand(0,24),16);
        break;
        case "getlistall":
            $result = json_encode ($wpdb->get_results( "SELECT * FROM `wp_promo`" ));  
        break;
        case "getlistactive":
            $result = json_encode ($wpdb->get_results( "SELECT * FROM `wp_promo` WHERE `is_active`='1' " ));  
        break;
        case "get":
            $promo_id = $_POST['id'];
            $result = json_encode ($wpdb->get_results( "SELECT * FROM `wp_promo` WHERE `id`='$promo_id' " ));  
        break;
        case "add":
            $promo_id = $_POST['id'];
            $promocode= $_POST['promocode'];
            $date_start= $_POST['date_start'];  
            $date_exp= $_POST['date_end'];  
            $action_text = $_POST['action_text'];  
            $count = $_POST['count'];  
            $is_active =  $_POST['is_active'];  
            $value =  $_POST['value'];  

            if (trim($promo_id)=="")
                $result = $wpdb->query( "INSERT INTO `wp_promo`( `promocode`, `date_start`, `date_exp`, `action_text`, `is_active`,`count`,`value`) VALUES ( '$promocode', '$date_start', '$date_exp', '$action_text', '$is_active','$count','$value')" );
            else
                $result = $wpdb->query( "UPDATE `wp_promo` SET `is_active`='$is_active',`promocode`='$promocode',`date_start`='$date_start',`date_exp`='$date_exp',`action_text`='$action_text',`count`='$count',`is_active`='$is_active',`value`='$value'   WHERE `id`='$promo_id'");
       
            $result = "$promo_id  $promocode    $date_start   $date_exp    $action_text      $count      $is_active";
        break;   
        case "remove":
            $id = $_POST['id'];
            $is_active = $_POST['is_active'];
            $result = $wpdb->query( "UPDATE `wp_promo` SET `is_active`='$is_active' WHERE `id`=$id");
        break;    


        case "usersonpromo":
            $promo_id = $_POST['promo_id'];
            $tmp = $wpdb->get_results("SELECT * FROM `wp_user_has_promo` WHERE `promo_id`='$promo_id'");
            $jsonArr = [];

            foreach($tmp as $u){
                $userInfo = $wpdb->get_results("SELECT `user_login`,`user_nicename`,`user_email` FROM `wp_users` WHERE `ID`='$u->user_id'");
                array_push($jsonArr,$userInfo);
            }

            $result =  json_encode( $jsonArr); 
        break;
        case "accept":
            $promocode = $_POST['promocode'];
            $cur_user_id = get_current_user_id();

            $tmp = $wpdb->get_results("SELECT * FROM `wp_promo` WHERE `promocode`='$promocode' and `count`>=1");

            if (count($tmp)==0) {
                $result = "Таких промокодов нет!";
                break;
            }

            $promo_id = $tmp[0]->id;
            $count = $tmp[0]->count;

            $tmp = $wpdb->get_results("SELECT * FROM `wp_user_has_promo` WHERE `user_id`='$cur_user_id' and  `promo_id`='$promo_id'");

            if (count($tmp)!=0) {
                $result = "Такой промокод уже использован вами!";
                break;
            }

            $count--;
            $wpdb->query( "UPDATE `wp_promo` SET `count`='$count' WHERE `id`=$promo_id" );

            $result = $wpdb->query( "INSERT INTO `wp_user_has_promo`( `user_id`, `promo_id`) VALUES ( '$cur_user_id', '$promo_id')" );
        
    break;    
        
    }
    echo $result;
}











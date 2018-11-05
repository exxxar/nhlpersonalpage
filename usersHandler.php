<?php

require_once("../../../wp-load.php");

global $wpdb;

if (isset($_POST["option"])){
$result = "";
    switch($_POST["option"]) {

        case "getallusers":            
        $result = json_encode ($wpdb->get_results("SELECT `user_login`,`user_nicename`,`user_email`,`ID`  FROM `wp_users`"));  
        break;
        case "getuser":
            $id = $_POST['id'];
            $result = json_encode ($wpdb->get_results( "SELECT * FROM `wp_user_info` WHERE `id`=$id" ));    
             
        break;
        case "insert":
            $userId = $_POST['userId'];
            $name = $_POST['name'];
            $hut_command = $_POST['hut_command'];
            $email = $_POST['email'];
            $vk = $_POST['vk'];
            $fb = $_POST['fb'];
            $telegramm = $_POST['telegramm'];
            $viber = $_POST['viber'];
            $skype = $_POST['skype'];
            $whatsapp = $_POST['whatsapp'];
            $payment_type = $_POST['payment_type'];
            $peyment_card = $_POST['peyment_card'];
            $exp = $_POST['exp'];
            $img = $_POST['img'];
            $referal = $_POST['referal'];

            $wpdb->query( "INSERT INTO `wp_user_info`( `userId`, `name`, `hut_command`, `email`, `vk`, `fb`, `telegramm`,`viber`,`skype`, `whatsapp`, `payment_type`, `payment_card`, `exp`, `img`, `referal`) VALUES ( '$userId', '$name', '$hut_command', '$email', '$vk', '$fb', '$telegramm','$viber','$skype', '$whatsapp', '$payment_type', '$payment_card', '$exp', '$img', '$referal')" );
            $result = $id;
        break;       
        case "update":
            $id = $_POST['id'];
            $userId = $_POST['userId'];
            $name = $_POST['name'];
            $hut_command = $_POST['hut_command'];
            $email = $_POST['email'];
            $vk = $_POST['vk'];
            $fb = $_POST['fb'];
            $telegramm = $_POST['telegramm'];
            $whatsapp = $_POST['whatsapp'];
            $payment_type = $_POST['payment_type'];
            $peyment_card = $_POST['peyment_card'];
            $exp = $_POST['exp'];
            $img = $_POST['img'];
            $referal = $_POST['referal'];
            $result = $id;
            $wpdb->query( "UPDATE `wp_user_info` SET `userId`='$userId',`name`='$name',`hut_command`='$hut_command',`email`='$email',`vk`='$vk',`fb`='$fb',`telegramm`='$telegramm',`whatsapp`='$whatsapp',`payment_type`='$payment_type',`payment_card`='$payment_card',`exp`='$exp',`img`='$img',`referal`='$referal' WHERE `id`=$id");
        break;
    }
    echo $result;
}











<?php

require_once("../../../wp-load.php");

global $wpdb;

if (isset($_POST["option"])){
$result = "";
    switch($_POST["option"]) {
        case "getmessages":

            if (isset($_POST['id']))
                $id = $_POST['id'];  

            
            $user_id = get_current_user_id();
            if (!isset($_POST['id']))
                $result = json_encode($wpdb->get_results("SELECT *, `wp_users`.`user_nicename` FROM `wp_chat` INNER JOIN `wp_users` ON `wp_chat`.`to_id`=`wp_users`.`ID` WHERE `wp_chat`.`from_id`='$user_id'"));
            else
                $result = json_encode($wpdb->get_results("SELECT *, `wp_users`.`user_nicename` FROM `wp_chat` INNER JOIN `wp_users` ON `wp_chat`.`to_id`=`wp_users`.`ID` WHERE `wp_chat`.`from_id`='$user_id' and `wp_chat`.`to_id`='$id'"));
        break;
        case "getchatusers":
        $result = json_encode ($wpdb->get_results("SELECT `user_login`,`user_nicename`,`user_email`,`ID`  FROM `wp_users`"));
        break;
          case "getchat":
            $from_id = $_POST['from'];
            $to_id = $_POST['to'];
            $result = json_encode ($wpdb->get_results( "SELECT * FROM `wp_chat` WHERE `from_id`=$from_id and `to_id`=$to_id" ));  
        break;
        case "send":
            $from_id = get_current_user_id();
            $to_id = $_POST['to_id'];  
            $message = $_POST['message'];  
            $title = $_POST['title'];  
            $date =  date('Y-m-d H:i:s');  

            
            if ($to_id>0) {
               

                 $wpdb->query( "INSERT INTO `wp_chat`( `from_id`, `to_id`, `message`, `title`, `date`) VALUES ( '$from_id', '$to_id', '$message', '$title', '$date')" );

                 $tmp = $wpdb->get_results("SELECT `user_login`,`user_nicename`,`user_email`,`ID`  FROM `wp_users` WHERE `ID`=$from_id");


                 $htmlText =  file_get_contents ( plugin_dir_url( __FILE__ )."message.html");//"<h1>$subj</h1><p>$text</p>";
        
                 $htmlText = str_replace ("{{subj}}",$title,$htmlText);
                 $htmlText = str_replace ("{{message}}",$message,$htmlText);

                 $headers[] = 'Content-type: text/html; charset=utf-8'; // в виде массива
                 wp_mail($email ,$title,  $htmlText, $headers);
            }
            else {
                $tmp = $wpdb->get_results("SELECT `user_login`,`user_nicename`,`user_email`,`ID`  FROM `wp_users`");

                foreach($tmp as $u) {
                    $to_id = $u->ID;
                    $email = $u->user_email;
                    $wpdb->query( "INSERT INTO `wp_chat`( `from_id`, `to_id`, `message`, `title`, `date`) VALUES ( '$from_id', '$to_id', '$message', '$title', '$date')" );  
                    
                    
                    $htmlText =  file_get_contents ( plugin_dir_url( __FILE__ )."message.html");//"<h1>$subj</h1><p>$text</p>";
        
                    $htmlText = str_replace ("{{subj}}",$title,$htmlText);
                    $htmlText = str_replace ("{{message}}",$message,$htmlText);

                    $headers[] = 'Content-type: text/html; charset=utf-8'; // в виде массива
                    wp_mail($email ,$title,  $htmlText, $headers);
                    

                }
            }
            $result = "$from_id   $to_id      $message    $title     $date";
        break;       
        
    }
    echo $result;
}











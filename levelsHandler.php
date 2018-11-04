<?php

require_once("../../../wp-load.php");

global $wpdb;

if (isset($_POST["option"])){
$result = "";
    switch($_POST["option"]) {
        case "insert":
            $level = $_POST['level'];
            $title = $_POST['title'];
            $exp = $_POST['exp'];
            $disc = $_POST['disc'];
            $wpdb->query( "INSERT INTO `wp_levels`( `level`, `title`, `exp`, `disc`) VALUES ('$level','$title','$exp','$disc')" );
            $result = $id;
        break;
        case "remove":
            $id = $_POST['id'];
            $wpdb->delete( 'wp_levels', array( 'id' => $id ) );    
            $result = $id;            
        break;
        case "update":

            $id = $_POST['id'];
            $level = $_POST['level'];
            $title = $_POST['title'];
            $exp = $_POST['exp'];
            $disc = $_POST['disc'];
           $result = $id;
            $wpdb->query( "UPDATE `wp_levels` SET `level`='$level',`title`='$title',`exp`='$exp',`disc`='$disc' WHERE `id`=$id");
        break;
    }
    echo $result;
}











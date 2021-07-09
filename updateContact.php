#!/usr/local/bin/php
<?php
    session_start();
    $username = $_SESSION['user'];
    $contact_username =$_POST['p_username'];
    $contact_name = $_POST['p_name'];
    $contact_email = $_POST['p_email'];
    
    try{
        $users = new SQLite3('users.db');
    } catch(Exception $ex){
        echo $ex->getMessage();
        die;
    }
    
    $update_query = "UPDATE ". $username . "_contacts SET name='$contact_name', email='$contact_email' WHERE username='$contact_username';"; //sets contact
    $update_run = $users->query($update_query);
    
    $users->close();
?>
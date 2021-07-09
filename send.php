#!/usr/local/bin/php
<?php
    session_start();
    $sender = $_SESSION['user'];
    $message =$_POST['p_message'];
    $calls_json = $_POST['p_calls'];
    $calls = json_decode($calls_json, false);
    $date = $_POST['p_datetime'];
    $raw = $_POST['p_raw'];
    
    try{
        $users = new SQLite3('users.db');
    } catch(Exception $ex){
        echo $ex->getMessage();
        die;
    }
    
    //for good measure
    $sender = trim($sender);
    
    $contact_not_found = false;
    $error_list = "";
    
    foreach($calls as $call){//for each call given
        
        $style = $call[0];//the 0th element in the call is the style
        $recipient = $call[1];//the 1st element in the call is the recipient
        
        //clean 'em up
        $style = trim($style);
        $recipient = trim($recipient);
        
        //Check to see if name is in contact book
        $contact_query = "SELECT username FROM ". $sender . "_contacts WHERE name='$recipient' OR email='$recipient';";
        $contact_run = $users->query($contact_query);
        $contact = $contact_run->fetchArray(SQLITE3_ASSOC);
        
        
        if($contact !== false){//if fetchArray did not fail
            $recipient = $contact['username'];//recipient to the username of the contact in the contact book; i.e., replace contact name with real username
            $recipient = trim($recipient); //clean it up
        }
        
        //Check to see if contact is in db
        $user_query = "SELECT username FROM creds WHERE username='$recipient' OR email='$recipient';";
        $user_run = $users->query($user_query);
        $true_contact = $user_run->fetchArray(SQLITE3_ASSOC);
    
        
       if($true_contact === false){
            $error_list .= $recipient . ", ";//add name to error list as a recipient not found
            $contact_not_found = true; //flag
        } else{
            $recipient = $true_contact['username'];
            $recipient = trim($recipient);
            
            
            //if user is in db, add message to messages
            $message_query = "INSERT INTO messages (sender, recip, msg, style, date, raw_time) VALUES ('$sender', '$recipient', '$message', '$style', '$date', '$raw');";
            $users->query($message_query);
        }
    
    }
    
    if($contact_not_found){//if there was a contact that was not found
        echo $error_list;//return the error list
    }else{
        echo "success";
    }
    
    $users->close();
?>
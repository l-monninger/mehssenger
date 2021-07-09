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
    
    $similar_contacts = [];
    
    //check if username represents an actual user
    $check_query = "SELECT username FROM creds WHERE username='$contact_username';";
    $check_run = $users->query($check_query);
    if($check_run->fetchArray() === false){//if user not found
        //check for similar usernames and echo them; my approximation of a search engine
        $first_two = substr($contact_username, 0, 2);
        $first_three = substr($contact_username, 0, 3);
        $first_four = substr($contact_username, 0, 4);
        $similar_query = "SELECT username FROM creds WHERE (username LIKE '%$contact_username%' OR username LIKE '%$first_two%' OR username LIKE '%$first_three%' OR username LIKE '%$first_four%' OR INSTR('$contact_username', username) )COLLATE NOCASE;";//Note, I tried everything I could find to see check if $contact_username contained a real username. It appears the php SQLite API must have flaw. I was able to run the command succesfully in the terminal, but when I tried to instantiate it here, it would never work. I tried every string manipulation I could think of, but no dice. I even tried hard coding values in and that didn't work
        $similar_run = $users->query($similar_query);
        //get five most similar contacts
        $counter = 0;
        while($row = $similar_run->fetchArray()){//while similar contacts to be had
            $similar_contacts[] = $row['username'];
            ++$counter;
            if($counter >= 5){//if we've gotten 5 suggestions; we'll limit this here
                break;
            }
        }
        
        echo json_encode($similar_contacts);
        die;
    }
    
    //check if username already has an associated contact in this users contact book
    $already_query = "SELECT username FROM " . $username . "_contacts WHERE username='$contact_username';";
    $already_run = $users->query($already_query);
    if($already_run->fetchArray() !== false){//if user found
        echo "already";
        die;
    }
    
    
    
    //add contact to user's contact book
    $insert_query = "INSERT INTO ". $username . "_contacts (username, name, email) VALUES ('$contact_username', '$contact_name', '$contact_email');";
    $insert_run = $users->query($insert_query);
    
    echo "success";
    
    $users->close();
?>
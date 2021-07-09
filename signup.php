#!/usr/local/bin/php
<?php

    function signup($username, $password, $password_confirm, $email, &$password_match, &$user_taken, &$email_taken){
        
        if($password == $password_confirm){
            $password_match = true;
        } else{
            $password_match = false;
        }
        
        try{
            $users = new SQLite3('users.db');
        } catch(Exception $ex){
            echo $ex->getMessage();
        }
        
        $user_query = "SELECT * FROM creds WHERE (username='$username' OR email='$username');"; //must prevent from using an email that matches username so that email can also be used as a unique indeifier for messaging
        $user_run = $users->query($user_query);
        
        if($user_run->fetchArray() === false){
            $user_taken = false;
        } else{
            $user_taken = true;
        }
    
    
        $email_query = "SELECT email FROM creds WHERE (email='$email' OR username='$email');";//same as above
        $email_run = $users->query($email_query);
        
         if(($email_run->fetchArray()) === false){
            $email_taken = false;
        } else{
            $email_taken = true;
        }
        
        if(!$password_match || $user_taken || $email_taken){
            $users->close();
            return;
            exit;
        }
        
        $password = hash('md2', $password);
    
        $add_creds = "INSERT INTO creds (username, password, email) VALUES ('$username', '$password', '$email');";
        $creds = $users->query($add_creds);
        $add_contacts = "CREATE TABLE IF NOT EXISTS ". $username .  "_contacts(name TEXT, username TEXT, email TEXT);";
        $sent = $users->query($add_contacts);
        $users->close();
        
        session_start();
        
        header('Location: login.php');
    }

    $username = $_POST['user'];
    $password = $_POST['pass'];
    $password_confirm = $_POST['pass_confirm'];
    $email = $_POST['email'];
    $password_match = true;
    $user_taken = false;
    $email_taken = false;
    
     if(isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['pass_confirm']) && isset($_POST['email'])){
        signup($username, $password, $password_confirm, $email, $password_match, $user_taken, $email_taken);
    }
?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <meta charset="UTF-8">
        <meta property="og:title" content="Meh Sign-up" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="Mehssenger Sign-up Page" />
        <meta property="og:url" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/signup.php/" />
         <meta property="og:image" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/birds.jpg" />
        <title>Sign-up</title>
        <link rel="stylesheet" href="signup_style.css">
    </head>
    <body>
        <main>
            <div id="registration_backing">
                
            </div>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <h1><span id="meh">Meh</span>ssenger</h1>
                <div>
                    <input type="text" id="user" name="user" placeholder="Username (> 3 char, no quotes)" pattern="[^'\x22]{4,}" >
                </div>
                <div>
                    <input type="password" id="pass" name="pass" placeholder="Password (> 5 char, no quotes)" pattern="[^'\x22]{6,}">
                </div>
                <div>
                    <input type="password" id="pass_confirm" name="pass_confirm" placeholder="Confirm Password">
                </div>
                <div>
                    <input type="text" id="email" name="email" placeholder="Email" pattern=".+@.+">
                </div>
                <div>
                    <input type="submit" value="Sign-up"> <a href="login.php" id="login">Login</a>
                </div>
                <p id="error">
                            *Legitimate email not required.
                <?php
                    if(!$password_match){
                ?>
                            The passwords do not match. <br>
                <?php
                    }
                    if($user_taken){
                ?>
                            That user name is taken. <br>
                <?php
                    }
                    if($email_taken){
                ?>
                            That email is taken.
                <?php
                    }
                ?>
            </p>
            </form>
        </main>
       <footer>
            <small>This page is &copy; 2018 by Liam Monninger. Background image by <a href=https://wallpaperstudio10.com/wallpaper-animals_bird-68427.html>SASA</a> from Wallpaper Studio.</small>
        </footer>
    </body>
</html>
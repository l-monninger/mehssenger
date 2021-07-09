#!/usr/local/bin/php
<?php
    session_start();
    $_SESSION['lin'] = false; //lin for logged in
    
    /**
     Validates user
     
     @param $username {string} is the submitted username
     @param $password {string} is the submitted password
     @param $error {boolean} is an error flag
     */
    function validate($username, $password, &$error){
        
        try{
            $users = new SQLite3('users.db');
        } catch(Exception $ex){
            echo $ex->getMessage();
            die;
        }
        
        //check password
        $pass_query = "SELECT password FROM creds WHERE username = '$username';";
        $pass_run = $users->query($pass_query);
        $true_pass = $pass_run->fetchArray();
        $true_pass = trim($true_pass['password']);
        
        $password = hash('md2', $password);
        
        if($password === $true_pass){//if submitted password matches true password
            $_SESSION['lin'] = true;//log user in
            $_SESSION['user'] = $username;//set session user to username
            $users->close();
            header('Location: terminal.php');//redirect to terminal
            exit;//for good measure
        } else{
            $users->close();
            $error = true;
        }
    }
    
    //initialize variables
    $error = false;
    $username = $_POST['user'];
    $password = $_POST['pass'];
    
    if(isset($_POST['user']) && isset($_POST['pass'])){//if username and password are set
        validate($username, $password, $error);
    }
?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <meta charset="UTF-8">
        <meta property="og:title" content="Meh Login" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="Mehssenger Login Page" />
        <meta property="og:url" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/login.php/" />
         <meta property="og:image" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/birds.jpg" />
        <title>Login</title>
        <link rel="stylesheet" href="login_style.css">
    </head>
    <body>
        <main>
            <div id="login_backing">
            </div>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <h1><span id="meh">Meh</span>ssenger</h1>
                    <div>
                        <input type="text" name="user" placeholder="Username">
                    </div>
                    <div>
                        <input type="password" name="pass" placeholder="Password">
                    </div>
                    <input type="submit" value="Login">
                    <a href="signup.php" id="register">Register</a>
                <?php
                        if($error){//if there was an error
                ?>
                            <p id="error_message">
                                Credentials not recognized.
                            </p>
                <?php
                    //didn't want to be too specific with error flag to avoid credential guessing
                        }
                ?>
                </form>
        </main>
        <footer>
            <small>This page is &copy; 2018 by Liam Monninger. Background image by <a href=https://wallpaperstudio10.com/wallpaper-animals_bird-68427.html>SASA</a> from Wallpaper Studio.</small>
        </footer>
    </body>
</html>
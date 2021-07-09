#!/usr/local/bin/php
<?php
    
    session_start();

    if(!isset($_SESSION['lin']) || !$_SESSION['lin']){//if user not loggedin
        header('Location: login.php');//send them to login
    }
    
    $username = $_SESSION['user'];//get user
    
    try{
            $users = new SQLite3('users.db');
    } catch(Exception $ex){
            echo $ex->getMessage();
            //actually don't want the page to die on this one
    }

    //get user email
    $get_email_query = "SELECT email FROM creds WHERE username='$username';";
    $get_email_run = $users->query($get_email_query);
    $user_email_row = $get_email_run->fetchArray();
    $user_email = $user_email_row['email'];
    
    $users->close();
?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <meta charset="UTF-8">
        <meta property="og:title" content="Meh Account" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="Mehssenger Account Page" />
        <meta property="og:url" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/account.php/" />
        <meta property="og:image" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/birds.jpg" />
        <link rel="stylesheet" href="account_style.css">
        <script src="account_methods.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" defer></script>
        <title>Meh Account</title>
    </head>
    <body>
        <nav id="menu_bar">
            <a href="terminal.php"><input type="button" class="tab_b" id="terminal_b" value="Terminal"></a><a href="inbox.php"><input type="button" class="tab_b" id="inbox_b" value="Inbox"></a><a href="account.php"><input type="button" class="tab_b" id="account_b" value="Account"></a><a href="about.php"><input type="button" class="tab_b" id="about_b" value="About"></a>
        </nav>
        <main>
            <h1 class="contact_book_label">Your Info/Logout</h1>
            <!--The backend is dependent on the struture of this element. -->
            <form>
                <fieldset>
                    <input type="text" value="Username: <?php echo $username; ?>" class="username fixed" readonly><input type="text"  value="Name: You" class="name fixed" readonly><input type="text" value="Email: <?php echo $user_email; ?>" class="email fixed" readonly><input type="button" class="cb_button" value="Logout" onclick="logout();">
                </fieldset>
            </form>
            <h1 class="contact_book_label">Add Contact</h1>
            <!--The backend is dependent on the struture of this element. -->
            <form>
                <fieldset>
                    <input type="text" list="similar_contacts" placeholder="Contact Username" class="username" id="new_contact_user"><datalist id="similar_contacts"><!-- empty to start --></datalist><input type="text"  placeholder="Contact Name" class="name" id="new_contact_name"><input type="text" placeholder="Contact Email" class="email" id="new_contact_email"><input type="button" id="new_contact" class="cb_button" value="Add Contact" onclick="newContact();">
                </fieldset>
            </form>
            <h1 class="contact_book_label">Your Contacts</h1>
            <div id="contact_book">
                <?php
                     try{
                            $users = new SQLite3('users.db');
                    } catch(Exception $ex){
                            echo $ex->getMessage();
                    }
                    
                    //lode up all of the contacts sorted by given name
                    $contact_book_query = "SELECT * FROM " . $username . "_contacts ORDER BY name ASC;";
                    $contact_book_run = $users->query($contact_book_query);
                    while($contact = $contact_book_run->fetchArray()){//while still contacts to get
                        $name = $contact['name'];
                        $username = $contact['username'];
                        $email = $contact['email'];
                ?>
                        <!--The backend is dependent on the struture of this element. -->
                        <form>
                            <fieldset>
                                <input type="text" value="<?php echo $username; ?>" class="username fixed" readonly><input type="text" id="<?php echo $username_name; ?>" value="<?php echo $name; ?>" class="name"><input type="text" id="<?php echo $email; ?>" value="<?php echo $email; ?>" class="email"><input type="button" class="cb_button" value="Update" onclick="updateContact(this.previousSibling.previousSibling.previousSibling.value, this.previousSibling.previousSibling.value, this.previousSibling.value);">
                            </fieldset>
                        </form>
                  
                <?php
                    }
                
                ?>
            </div>
        </main>
        <footer>
            <small>This page is &copy; 2018 by Liam Monninger.</small>
        </footer>
    </body>
</html>
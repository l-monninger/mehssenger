#!/usr/local/bin/php
<?php
    
    session_start();
    
    if(!isset($_SESSION['lin']) || !$_SESSION['lin']){//if user has not logged in
        header('Location: login.php');
    }
?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <meta charset="UTF-8">
        <meta property="og:title" content="Meh Terminal" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="Mehssenger Terminal Page" />
        <meta property="og:url" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/terminal.php/" />
         <meta property="og:image" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/birds.jpg" />
        <link rel="stylesheet" href="terminal_style.css">
        <script src="terminal_methods.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" defer></script>
        <title>Meh Terminal</title>
    </head>
    <body>
        <nav id="menu_bar">
            <a href="terminal.php"><input type="button" class="tab_b" id="terminal_b" value="Terminal"></a><a href="inbox.php"><input type="button" class="tab_b" id="inbox_b" value="Inbox"></a><a href="account.php"><input type="button" class="tab_b" id="account_b" value="Account"></a><a href="about.php"><input type="button" class="tab_b" id="about_b" value="About"></a>
        </nav>
        <form id="terminal_all">
            <fieldset id="large_text_field">
                <div id="terminal_wrapper">
                    <p id="processed">Ready! Press 'ctrl' + 'enter' to submit command.<br>Syntax: message styles @ contacts; message styles @ contacts; ... : Message <br>
                    Example: msg, love @ Mom, rdoug123; intro @ pop@gmail.com: Hello guys!</p>
                    <textarea id="terminal" rows="25" cols="75"></textarea>
                </div>
            </fieldset>
        </form>
        <p id="cmd_reminders">Press 'ctrl' + 'enter' to submit command.</p>
    </body>
    <footer>
            <small>This page is &copy; 2018 by Liam Monninger.</small>
    </footer>
</html>
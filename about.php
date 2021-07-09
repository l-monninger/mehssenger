#!/usr/local/bin/php
<?php
    
    session_start();
    
    if(!isset($_SESSION['lin']) || !$_SESSION['lin']){//if not loggedin
        header('Location: login.php');//redirect to login page
    }
?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <meta charset="UTF-8">
        <meta property="og:title" content="Meh About" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="Mehssenger About Page" />
        <meta property="og:url" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/about.php/" />
         <meta property="og:image" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/birds.jpg" />
        <link rel="stylesheet" href="about_style.css">
        <script src="about_methods.js" defer></script>
        <title>Meh About</title>
    </head>
    <body>
        <nav id="menu_bar">
            <a href="terminal.php"><input type="button" class="tab_b" id="terminal_b" value="Terminal"></a><a href="inbox.php"><input type="button" class="tab_b" id="inbox_b" value="Inbox"></a><a href="account.php"><input type="button" class="tab_b" id="account_b" value="Account"></a><a href="about.php"><input type="button" class="tab_b" id="about_b" value="About"></a>
        </nav>
        <main>
            <article>
                <section>
                    <h1>Welcome to Meh<i>ssenger!</i></h1>
                    <p>
                        Welcome to <span class="meh">Meh</span><i>ssenger</i>, the alright messaging service! There are a lot of little details designed to make <span class="meh">Meh</span><i>ssenger</i> both a user-friendly and flexible messaging service, so let's dive right in.
                    </p>
                    <h2>A Note on User Privacy</h2>
                    <p>
                       <span class="meh">Meh</span><i>ssenger</i> does not encrypt user data. As such, the user should be mindful of the information kept on the site. It is recommended that legitimate emails and frequently used passwords not be used and messages concerning private information not be sent. <br> <br> <br>
                    </p>
                </section>
                <section>
                    <h1>Getting Started</h1>
                    <p>
                       As you're here, you will have already set up an account and will be wondering how best to use it. At present, <span class="meh">Meh</span><i>ssenger</i> features three pages beyond this one, each with its own functionality. These pages are "Terminal," "Inbox," and "Account." You'll note that you were originally redirected to "Terminal" after logging in. This is because the "Terminal" is <span class="meh">Meh</span><i>ssenger</i>'s most unique feature, and is intended to become your favorite tool for messaging on this site. We'll cover the terminal shortly, but we'll begin with basic messages and the more intuitive pages -- "Inbox" and "Account" -- first. <br> <br>
                    <h2><span class="meh">Meh</span><i>ssenger</i>'s Messages</h2>
                    <h3>Message Basics</h3>
                    <p>
                        Messages are differentiable by their content (i.e., the text of the message itself), the sender, the recipient, the message style and the message datetime. There is no enforced limit on character count and messages can be formatted with tabs and non-breaking spaces. A message can only be viewed in the "Inbox."
                    </p>
                    <h3>Message Types</h3>
                    <p>
                        <img class="message_type" src="msg.PNG" alt="msg Message Type"> <br>
                        <img class="message_type" src="frml.PNG" alt="frml Message Type"> <br>
                        <img class="message_type" src="love.PNG" alt="love Message Type"> <br>
                        <img class="message_type" src="intro.PNG" alt="intro Message Type"> <br>
                        <img class="message_type" src="memo.PNG" alt="memo Message Type"> <br>
                        There are five message types in <span class="meh">Meh</span><i>ssenger</i>: msg, frml, love, intro, memo. As shown above, each is rendered slightly differently when displayed in inbox. Each is intended to carry slightly different semantic meaning. That meaning, however, is at the disgression of the users. <br> <br>
                    </p>
                    <h2>Inbox</h2>
                    <p>
                        The "Inbox" functions more or less how you would expect any messaging inbox to function. It displays all of your messaging threads and allows for the creation of new threads and responses. Messages, both sent and received, will be displayed in real time.
                    </p>
                    <h3>Overview</h3>
                    <p>
                        <img src="inbox.PNG" alt="Inbox Overview"> <br>
                        All threads are loaded onto the inbox page as block groups consisting of a region which displays all of the messages between you and one other user, a text area to respond inline and a submission button which allows the user to select a message type and submit. All thread content areas can, themselves be scrolled. The Inbox Page as a whole can be scrolled to navigate between threads, though you may find the thread navigational tool at the left of the window more useful. To use said tool, simply hover over the series of thin bands at the left of the window and the thread navigational element will appear. You may then click on a contact name, and your view will be redirected to the location of the thread for that contact.
                    </p>
                    <h3>New Threads</h3>
                    <p>
                        <img src="new_thread.png" alt="New Thread"> <br>
                        The "Inbox" also allows you to create new threads. To do so, simply hover over the "New Thread" button in the bottom right of the view. A form will open up which allows you to input the name of the contact you would like to make a new thread with. <b>If the contact name entered is not matched with a user in your contact book, as will be discussed shortly, or any user in the <span class="meh">Meh</span><i>ssenger</i> datatbase, an error message will display and you will be prompted to try again.</b> If the thread which you are attempting to create already exists, the message typed will simply be added to the thread. <br> <br>
                    </p>
                    <h2>Account</h2>
                    <p>
                        <img src="account.PNG" alt="Account"> <br>
                        The "Account" page provides several useful features for managing your account. There, you can <b>logout</b>, view your personal info, add contacts to your contact book, and edit contacts on the fly.
                    </p>
                    <h3>Adding Contacts</h3>
                    <p>
                        The contact book allows you to specify an alternative name and email for a given <span class="meh">Meh</span><i>ssenger</i> user. Contacts added must have a <span class="meh">Meh</span><i>ssenger</i> account, otherwise, the add will fail. If the add fails, up to five similar users may be suggested in the "Contact Username" text edit. If the attempt contact already exists in your contact book, you will be notified.
                    </p>
                    <h3>Using Contact Names and Emails</h3>
                    <p>
                        Once you've added a contact, the associated username, name and contact are substitutable anywhere you might need to specify a contact. I.e., instead of writing out your desired contact's official username, you may choose to use the name you have given them or their email. Prior to adding to your contact book, however, contacts must be specified by their official username. <br> <br>
                    </p>
                    <h2>Terminal</h2>
                    <p>
                        The "Terminal" provides a command-line-like interface for sending messages. Using the proper syntax, you may send messages directly from the terminal.
                    </p>
                    <h3>Syntax</h3>
                    <p>
                        The terminal syntax is as follows... <br><br>
                    </p>
                    <pre>   message types @ recipients; message types @ recipients... : message body <br></pre>
                    <p>
                        An example "Terminal" call would read... <br><br>
                    </p>
                    <pre>   msg, love @ Kayla, douglas123; memo @ dad@gmail.com: Hello! <br></pre>
                    <p>
                        If called and all contacts were found, the above would send msg and love messages to Kayla and douglas123 and a memo to Dad all of which read "Hello!"
                    </p>
                    <h3>Errors</h3>
                    <p>
                        <img src="terminal_error.PNG" alt="Error"> <br>
                        The "Terminal" will notify you of erroneous calls to certain contacts. If a contact is not found either in your contact book or in the <span class="meh">Meh</span><i>ssenger</i> database, then an error message will pop up in the processed commands section at the top of the "Terminal." Attempting to send messages of unsupported types will not flag an error; such messages will simply be sent as msg's. The "Terminal" will not allow you to submit a command which does not follow the proper syntax. <br> <br> <br>
                    </p>
                </section>
                <section>
                    <h1>Tips, Tricks and Bugs</h1>
                    <h2>Getting Nifty with <span class="meh">Meh</span><i>ssenger</i></h2>
                    <p>
                        Here are a few tips and tricks which will make your time in <span class="meh">Meh</span><i>ssenger</i> more enjoyable.
                        <ol>
                            <li>All large text areas (the "Terminal", each response thread and the "New Thread" submissions) can be can be submitted by pressing 'ctrl' + 'enter.'</li>
                            <li>All large text areas preserve tabs, return carriages and non-breaking spaces.</li>
                            <li>White text generally indicates readonly text, while grey text indicates editable text</li>
                        </ol>
                    </p>
                    <h2>Bugs</h2>
                    <p>
                        <ol>
                            <li>Macintosh users may experience delays when submitting in the inbox using 'ctrl' + 'enter.'</li>
                            <li>Low internet speeds can severely increase message send time.</li>
                            <li>The first messages sent may not display date information. Eventually, message dates do display, however.</li>
                            <li>Little mobile optimization has been ensured. Features such as the terminal may be unsupported for mobile users.</li>
                        </ol>
                    </p>
                </section>
            </article>
        </main>
        <footer>
            <small>This page is &copy; 2018 by Liam Monninger.</small>
        </footer>
    </body>
</html>
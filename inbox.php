#!/usr/local/bin/php
<?php
    //this section will be used to prepare threads
    session_start();
    
    if(!isset($_SESSION['lin']) || !$_SESSION['lin']){//if user not logged in
        header('Location: login.php');
    }
    
    $username = $_SESSION['user'];//get username
    
    try{
        $users = new SQLite3('users.db');
    } catch(Exception $ex){
        echo $ex->getMessage();
        //actually don't want the page to die on this one
    }
    
    //let's get some threads
    $threads = [];
    
    if(isset($_SESSION['user'])){//if user isset; this is where it's important to check; don't want weird behavior in selection query to mess up whole page
        $threads_query = "SELECT * FROM messages WHERE (sender='$username' OR recip='$username') ORDER BY raw_time DESC;";//select messages where user is sender or recipient
        $threads_run = $users->query($threads_query);
        while($row = $threads_run->fetchArray(SQLITE3_ASSOC)){//while able to get another message
            $thread_sender = $row['sender'];
            $thread_recip = $row['recip'];
            if(!in_array($thread_sender, $threads, true) && $thread_sender !== $username){//if the sender isn't already a thread
                $threads[] = $thread_sender;
            }
            if(!in_array($thread_recip, $threads, true) && $thread_recip !== $username){//if the recipient isn't already a thread
                $threads[] = $thread_recip;
            }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <meta charset="UTF-8">
        <meta property="og:title" content="Meh Inbox" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="Mehssenger Inbox Page" />
        <meta property="og:url" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/inbox.php/" />
         <meta property="og:image" content="http://www.pic.ucla.edu/~lmonninger/Final_Project/birds.jpg" />
        <title>Meh Inbox</title>
        <link rel="stylesheet" href="inbox_style.css">
        <script src="inbox_methods.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" defer></script>
    </head>
    <body>
        <nav id="menu_bar">
            <a href="terminal.php"><input type="button" class="tab_b" id="terminal_b" value="Terminal"></a><a href="inbox.php"><input type="button" class="tab_b" id="inbox_b" value="Inbox"></a><a href="account.php"><input type="button" class="tab_b" id="account_b" value="Account"></a><a href="about.php"><input type="button" class="tab_b" id="about_b" value="About"></a>
        </nav>
        <nav id="threads_nav">
            <h1 id="threads_nav_label">Threads</h1>
            <p>
            <?php
                if(sizeof($threads > 0)){//if threads were found (this goes nicely with the isset check above)
                    foreach($threads as $thread){//fpreach thread in threads
                        $contact = '';
                        
                        //check for contact
                        $contact_query = "SELECT name FROM ". $username . "_contacts WHERE username='$thread';";
                        $contact_run = $users->query($contact_query);
                        $contact_row = $contact_run->fetchArray(SQLITE3_ASSOC);
                        
                        if($contact_row !== false){//if contact was found
                            $contact = $contact_row['name'];
                        } else{
                            $contact = $thread;//otherwise contact remains thread
                        }
                        //add thread or contact to nav element
            ?>
                <a href="#<?php echo $thread; ?>_head"><input type="button" value="<?php echo $contact ?>"></a> <br>
            <?php
                    }
                }
            ?>
            </p>
        </nav>
        <main>
            <form id="nm">
                <h2 id="nm_label">New Thread</h2>
                <fieldset id="nm_fieldset">
                    <input type="text" id="nm_recipient" name="nm_contact" placeholder="Contact"/>
                    <textarea id="nm_message" rows="2" cols="120" placeholder="Message..."></textarea>
                    <div id="nm_select_submit" class="nm_element">
                        <select id="nm_style" onchange="changeNMBG(this.value);"><option value="msg">msg</option><option value="frml">frml</option><option value="love">love</option><option value="intro">intro</option><option value="memo">memo</option></select><input type="button" value="Make Thread" onclick="newMessage();">
                    </div>
                </fieldset>
            </form>
            <?php
                if(sizeof($threads) > 0){
                    foreach($threads as $thread){//if threads were found (this goes nicely with the isset check above)
                        
                        $contact = '';
                        
                        //check for contact in contact book
                        $contact_query = "SELECT name FROM ". $username . "_contacts WHERE username='$thread';";
                        $contact_run = $users->query($contact_query);
                        $contact_row = $contact_run->fetchArray(SQLITE3_ASSOC);
                        
                        if($contact_row !== false){//if contact found
                            $contact = $contact_row['name'];
                        } else{
                            $contact = $thread;//otherwise contact remains thread
                        }
            ?>
                        <h2 class="thread_head" id="<?php echo $thread?>_head">Between You and <?php echo $contact; ?> </h2>
                        <div class="thread_wrapper" id="<?php echo $thread; ?>_wrapper" >
                            <div class="thread_content" id="<?php echo $thread; ?>_content">
            <?php
                            //select all messages that belong to this thread
                            $thread_query = "SELECT * FROM messages WHERE ((sender='$username' AND recip='$thread') OR (sender='$thread' AND recip='$username'));";
                            $thread_run = $users->query($thread_query);
                            while($row = $thread_run->fetchArray(SQLITE3_ASSOC)){//while messages to be added
                                $sender = $row['sender'];
                                $style = $row['style'];
                                $message = $row['msg'];
                                $datetime = $row['date'];
                                if($style === "msg" && $sender === $username){//if style is msg and was sent by user
            ?>
                                    <div class="sent msg">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b>You: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "frml" && $sender === $username){//if style is frml and was sent by user
            ?>
                                    <div class="sent frml">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b>You: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "love" && $sender === $username){//if style is love and was sent by user
            ?>
                                    <div class="sent love">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b>You: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "intro" && $sender === $username){//if style is intro and was sent by user
            ?>
                                    <div class="sent intro">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b>You: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "memo" && $sender === $username){//if style is memo and was sent by user
            ?>
                                    <div class="sent memo">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b>You: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "msg" && $sender === $thread){//if style is msg and was sent by other
            ?>
                                    <div class="rec msg">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b><?php echo $contact ?>: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "frml" && $sender === $thread){//if style is frml and was sent by other
            ?>
                                    <div class="rec frml">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b><?php echo $contact ?>: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "love" && $sender === $thread){//if style is love and was sent by other
            ?>
                                    <div class="rec love">
                                        <p>
                                        <i class="datetime"><?php echo $date; ?> <br> </i>
                                        <b><?php echo $contact ?>: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "intro" && $sender === $thread){//if style is intro and was sent by other
            ?>
                                    <div class="rec intro">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b><?php echo $contact ?>: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($style === "memo" && $sender === $thread){//if style is memo and was sent by other
            ?>
                                    <div class="rec memo">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b><?php echo $contact ?>: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($sender === $username){//if misc and was sent by user
            ?>
                                    <div class="sent msg">
                                        <p>
                                        <i class="datetime"><?php echo $date; ?> <br> </i>
                                        <b><?php echo $sender ?>: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }elseif($sender === $thread){//if misc and was sent by other
            ?>
                                    <div class="rec msg">
                                        <p>
                                        <i class="datetime"><?php echo $datetime; ?> <br> </i>
                                        <b><?php echo $contact ?>: <br> </b>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }else{//no clue what happened but here's your message anyways
            ?>
                                    <div class="misc">
                                        <p>
                                        <i>The following has for some reason been attributed to this thread:</i>
                                        <?php echo $message ?>
                                        </p>
                                    </div>
            <?php
                                }
                            }
            ?>
                        <!--The backend is dependent on the struture of this element. -->
                        </div>
                                <form>
                                    <fieldset id="<?php echo $thread; ?>" class="respond_fields"><textarea class="response_message" id="<?php echo $thread; ?>_textarea" rows="4" cols="50" placeholder="New message..."></textarea><div class="select_submit"><select><option value="msg">msg</option><option value="frml">frml</option><option value="love">love</option><option value="intro">intro</option><option value="memo">memo</option></select><input type="button" value="Send" onclick="respond(this.parentNode.parentNode.id, this.parentNode.parentNode.firstChild.value, this.previousSibling.value);"></div></fieldset>
                                </form>
                    </div>
            <?php
                    }
                } else{//no threads
            ?>
                   <p id="no_messages">
                       You don't have any messages.
                   </p>
            <?php
                }
            ?>
        </main>
        <footer>
            <small>This page is &copy; 2018 by Liam Monninger.</small>
        </footer>
    </body>
</html>
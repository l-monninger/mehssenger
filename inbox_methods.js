const SCROLL_NUMBER = 1000000; //this will be used to set div scroll position, its hacky, but it works better for what I'm trying to do than .scrollHeight; should be safe in all browsers too

/**
 Sets window onload such that response threads will be scrolled to the bottom and textareas will preserve tabs and be submitted on 'ctrl' + 'enter'

*/
window.onload = function(){
    $(".thread_content").scrollTop(SCROLL_NUMBER); //scroll to bottom of each content thread
    
    let map = {}; //used to check for multi-key event
    
    $("textarea").unbind();
    $(".response_message").unbind(); //to make sure no duplicate event listeners are added
    
    /**
     Sets keydown to function which preserves tabs
     
     @param e {object} is the event
    */
    $("textarea").keydown(function(e){
        e = e || event; //most robust  way of adding this kind of event listener; Note: found this on stack exchange
        if(e.keyCode===9 || e.which===9){//if tab
            e.preventDefault();
            let s = this.selectionStart;
            this.selectionEnd = s+1;
            this.value = this.value.substring(0, this.selectionStart) + "\t" + this.value.substring(this.selectionEnd); //place tab in space of next character
        }
    })
    
    /**
     Adds 'ctrl' + 'enter' event listener to "New Thread" form (part 1: keydown)
     
     @param e {object} is the event
    */
    $("#nm_message").keydown(function(e){
        e = e || event; //Ibid.
        map[e.keyCode] = e.type === 'keydown'; //set the map value to boolean representing whether keydown is triggered
        if(map[13] && map[17]){//if keycode 13 (enter) and keycode 17 (ctrl)
            newMessage();
        }
    })
    
    /**
     Adds 'ctrl' + 'enter' event listener to "New Thread" form (part 2: keyup)
     
     @param e {object} is the event
    */
    $("#nm_message").keyup(function(e){
        e = e || event;
        map[e.keyCode] = e.type === 'keydown'; //set the map value to boolean representing whether keydown is triggered; i.e., will set values which equaled true after keydown to false
    })
    
    /**
     Adds 'ctrl' + 'enter' event listener to all response areas form (part 1: keydown)
     
     @param e {object} is the event
    */
    $(".response_message").keydown(function(e){
        e = e || event;//Ibid.
        map[e.keyCode] = e.type === 'keydown';
        if(map[13] && map[17]){//if keycode 13 (enter) and keycode 17 (ctrl)
            console.log("triggered");
            let id = $(this).attr('id');//we're going to switch from a jquery element to a standard js get element for consistency and ease of implementation
            let element = document.getElementById(id);
            element = element.nextSibling.firstChild.nextSibling; //we now have the submit button and can call to the respond function as in the html body; this is predicated on the html structure within the form remaining as is
            respond(element.parentNode.parentNode.id, element.parentNode.parentNode.firstChild.value, element.previousSibling.value);
        }
    })
    
    /**
     Adds 'ctrl' + 'enter' event listener to all respons areas form (part 2: keyup)
     
     @param e {object} is the event
    */
    $(".response_message").keyup(function(e){
        e = e || event;//Ibid.
        map[e.keyCode] = e.type === 'keydown';//set the map value to boolean representing whether keydown is triggered; i.e., will set values which equaled true after keydown to false
    })
    
}

/**
 Updates the content div: reloads div as whole in order to reset reponse textarea value; resets scroll position to bottom of thread and reinstatiates event listeners
 
 @param id {string} is the the contact name which serves as a unique indetifier for all contact threads
 */
function updateDiv(id){
    let full_id = "#" + id + "_wrapper"; //full_id is the id for the thread wrapper
    let content_id = "#" + id + "_content"; //content_id is the wrapper for the thread content
    
    /**
     Sets load function of thread wrapper to reset scroll position and reinstatiate event listeners
     */
    $(full_id).load(window.location.href + " " + full_id +">*", function(){
        
        $(content_id).scrollTop(SCROLL_NUMBER);
        
        let map = {};
        
        $(full_id + " .response_message").unbind(); //to make sure no duplicate event listeners are added
        
         $(full_id + " .response_message").keydown(function(e){
            e = e || event;
            if(e.keyCode===9 || e.which===9){//if tab
                e.preventDefault();
                var s = this.selectionStart;
                this.selectionEnd = s+1;
                this.value = this.value.substring(0, this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
            }
        })
    
        /**
         Adds 'ctrl' + 'enter' event listener to response area form (part 1: keydown)
         
         @param e {object} is the event
        */
        $(full_id + " .response_message").keydown(function(e){//if keycode 13 (enter) and keycode 17 (ctrl)
            e = e || event;
            map[e.keyCode] = e.type === 'keydown';
            if(map[13] && map[17]){
                console.log("triggered");
                let id = $(this).attr('id');//we're going to switch from a jquery element to a standard js get element for consistency and ease of implementation
                let element = document.getElementById(id);
                element = element.nextSibling.firstChild.nextSibling; //
                respond(element.parentNode.parentNode.id, element.parentNode.parentNode.firstChild.value, element.previousSibling.value);
            }
        })
        
        /**
        Adds 'ctrl' + 'enter' event listener to response area form (part 2: keyup)
        
        @param e {object} is the event
        */
        $(full_id + " .response_message").keyup(function(e){
            e = e || event;
            map[e.keyCode] = e.type === 'keydown';
        })
    
    });
    
}

/**
 Creates a new message thread; name newMessage maintained from earlier iteration of project {onclick}
 */
function newMessage(){
    
    let recipient = $("#nm_recipient").val();
    let message = $("#nm_message").val();
    let style = $("#nm_style").val();
    
    //clean everything up
    message = message.replace(/\n/g, "<br>");
    message = message.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
    message = message.replace(/"/g, "&#34;");
    message = message.replace(/'/g, "&#39;");
    recipient = recipient.trim();
    style = style.trim();
    
    let calls = [];
    
    calls.push([style, recipient]);
    
    calls = JSON.stringify(calls);
    
    let now = new Date();
    let unix_string = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate() + " " + now.getHours() + ":" + ("0" + now.getMinutes()).slice(-2) + ":" + ("0" + now.getSeconds()).slice(-2); //unix date
    let raw = Math.round(now.getTime() / 1000); //1000ms to 1s
    
    if(message !== ''){// if message not empty
        $.ajax({
            url: "send.php",
            method: "POST",
            data: { p_message : message, p_calls : calls, p_datetime : unix_string, p_raw : raw },
            success: function(data){
                if(data !== "success"){//if php script did not return "success"
                    $("#nm_recipient").val("Entered contact does not exist as user. Try another contact.");
                }else{
                    location.reload();
                }
            }
        });
    }
}

/**
 Responds to in thread {onclick}
 
 @param recipient {string} is the message recipient
 @param message {string} is the message itself
 @param style {string 'msg', 'frml', 'love', 'intro' or 'memo'} is the style of the message
 */
function respond(recipient, message, style){
    
    //clean it all up
    message = message.replace(/\n/g, "<br>");
    message = message.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
    message = message.replace(/"/g, "&#34;");
    message = message.replace(/'/g, "&#39;");
    recipient = recipient.trim();
    style = style.trim();
    
    let calls = [];
    
    calls.push([style, recipient]);
    
    calls = JSON.stringify(calls);
    
    let now = new Date();
    let unix_string = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate() + " " + now.getHours() + ":" + ("0" + now.getMinutes()).slice(-2) + ":" + ("0" + now.getSeconds()).slice(-2); //get unix time
    let raw = Math.round(now.getTime() / 1000); //1000ms to 1s

    if(message !== ''){
        $.ajax({
            url: "send.php",
            method: "POST",
            data: { p_message : message, p_calls : calls, p_datetime : unix_string, p_raw : raw },
            success: function(data){
                updateDiv(recipient);
            }
        });
    }
}

/**
 Set interval function: checks threads for new content every 2.5s
 */
setInterval(function(){
    $(".thread_content").each(function(){
        let scroll = $(this).scrollTop();
        $(this).load(window.location.href + " #" + $(this).attr('id') +">*");
        $(this).scrollTop(scroll);
    })
}, 2500)

/**
 Changes background color of "New Thread" to match selected style
 */
function changeNMBG(val){
    
    let nm = document.getElementById("nm");
    if(val === "msg"){//if msg
        nm.style.backgroundImage = "linear-gradient(#4169b3, #0059b3)";
    } else if(val === "frml"){//if frml
        nm.style.background = "black";
    } else if(val === "love"){//if love
        nm.style.backgroundImage = "url('hearts.png')";
        nm.style.backgroundSize = "30vh"
    } else if(val === "intro"){//if intro
        nm.style.background = "#5BC236";
    } else if(val === "memo"){//if memo
        nm.style.backgroundImage = "linear-gradient(#eed8bf, #d3b17d, #d8ad7f)";
    }
    
}
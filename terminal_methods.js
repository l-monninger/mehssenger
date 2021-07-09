let map = {}; //to be used for multi-key events

/**
 Sets onkeydown and onnkeyup to function which sends messages when 'ctrl' + 'enter' are selected; will not send messages onkeyup, because this will set map values to false
 @param e {object} is the event
 */
onkeydown = onkeyup = function(e){
    e = e || event;//most robust; found on stack exchange
    map[e.keyCode] = e.type === 'keydown';
    if(map[13] && map[17]){//if keycode 13 (enter) and keycode 17 (ctrl)
        let command_text = document.getElementById("terminal").value;
        let first_match = command_text.split(/:((?:.|\n)*)/); //deals with issue of colons in message body
        
        let calls = first_match[0];//everything before first colon is "calls"
        let message = first_match[1];//everything after first colon is "message"
        
        //clean it all up
        message = message.replace(/\n/g, "<br>");
        message = message.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
        message = message.replace(/"/g, "&#34;");
        message = message.replace(/'/g, "&#39;");
        
        let callstack = calls.split(';');//split "calls" into individual calls in "callstack"
    
        let call_list = [];
        
        for(let call of callstack){//for each call in callstack
            call = call.trim();//clean it up
            let halves = call.split(/@((?:.|\n)*)/);//split it at the first '@'
            let styles = halves[0].split(',');//split the stuff before the '@' at ','
            let recipients = halves[1].split(',');//split stuff after the '@' at ','
            for(let style of styles){//for each style in styles
                style = style.trim();//clean it up
                for(let recipient of recipients){
                    recipient = recipient.trim();
                    call_list.push([style, recipient]);//at style-recipient pair to call_list
                }
            }
        }
            
        call_list = JSON.stringify(call_list);
        
        let now = new Date();
        let unix_string = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate() + " " + now.getHours() + ":" + ("0" + now.getMinutes()).slice(-2) + ":" + ("0" + now.getSeconds()).slice(-2);//get unix datetime
        let raw = Math.round(now.getTime() / 1000);//1000ms to 1s
        
        $.ajax({
            url: "send.php",
            method: "POST",
            data: { p_message : message, p_calls : call_list, p_datetime : unix_string, p_raw : raw },
            success: function(data){
                let display_text = command_text;
                display_text = display_text.replace(/\n/g, "<br>");
                display_text = display_text.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                document.getElementById("processed").innerHTML += "<br>" + display_text;//update processed text area
                if(data !== "success"){//if the php script did not return "success"
                    document.getElementById("processed").innerHTML += "<br>" + "Error: contact(s) " + data.substring(0, data.length - 2) + " not found. Messages were sent to all other contacts.";//error message
                }
                document.getElementById("terminal").value = "";//wipe out terminal value
            }
        });
        
    }
}

let terminal = document.getElementById("terminal");

/**
    Sets onkeydown function for terminal so as to preserve tabs
    
    @param e {object} is the event
 */
terminal.onkeydown = function(e){
    e = e || event;//Ibid.
    if(e.keyCode==9 || e.which==9){//if tab
        e.preventDefault();
        var s = this.selectionStart;
        this.selectionEnd = s+1;
        this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);//insert tab in space of next character
    }
}

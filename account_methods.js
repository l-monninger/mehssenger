/**
 Updates contact in contact contact book {onclick}
 
 @param username {string} is the contact's username
 @param name {string} is the contact's given name
 @param email {string} is the contact's given email
*/
function updateContact(username, name, email){
    
    username = username.trim();
    name = name.trim();
    email = email.trim();

    if(name !== '' && email !== ''){//if name and email are not empty
        $.ajax({
            url: "updateContact.php",
            method: "POST",
            data: { p_username : username, p_name : name, p_email : email },
            success: function(data){
                $("#contact_book").load(window.location.href + " #contact_book>*");//reload contact book
            }
        });
    }
  
}

/**
 Creates new contact in contact contact book {onclick}
 
 @param username {string} is the contact's username
 @param name {string} is the contact's given name
 @param email {string} is the contact's given email
*/
function newContact(){
    
    let username = $("#new_contact_user").val();
    let name = $("#new_contact_name").val();
    let email = $("#new_contact_email").val();
    
    username.trim();
    name.trim();
    email.trim();
    
    console.log(username);
    console.log(name);
    console.log(email);

    if(name !== '' && email !== ''){//if name and email are not empty
        $.ajax({
            url: "newContact.php",
            method: "POST",
            data: { p_username : username, p_name : name, p_email : email },
            success: function(data){
                if(data === "success"){//if php script returns success
                    $("#new_contact").css("background-image", "linear-gradient(#115993, #0059b3)");//reset button styling
                    $("#new_contact").val("Add Contact");
                    $("#similar_contacts").empty;
                    $("#contact_book").load(window.location.href + " #contact_book>*"); //reload contact book
                }else if(data === "already"){//if php script returns already (user is already in contact book)
                    $("#new_contact").css("background", "orange");
                    $("#new_contact").val("Contact Already Exists");
                    $("#similar_contacts").empty();
                }else{//if the php script has not failed, it must have returned a json with similar names for
                    $("#new_contact").css("background", "red");
                    $("#new_contact").val("User not found. Try again.");
                    $("#similar_contacts").empty();
                    $("#new_contact_user").val('');
                    $("#new_contact_user").attr('placeholder', username);
                    let similar_contacts = JSON.parse(data);
                    for(let contact of similar_contacts){//for every element in similar contacts
                        $("#similar_contacts").append("<option value=" + contact + ">");
                    }
                }
            }
        });
    }
  
}

/**
 Logs user out {onclick}
 */
function logout(){
    $.ajax({
        url: "logout.php",
        method: "POST",
        success: function(){
            window.location.replace("login.php");//redirects user to login
        }
    });
}
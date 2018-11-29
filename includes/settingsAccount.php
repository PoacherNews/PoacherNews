<style>
input {
    padding-left: 2px;
}

#changeEmail, #changePassword {
    display: grid;
    grid-template-columns: 150px 300px;
    grid-template-rows: auto auto auto;
    grid-row-gap: 5px;
}
#changePassword {
    grid-template-columns: 200px 300px;
}
#changeEmail > label, #changePassword > label {
    grid-column-start: 1;
}

#deleteAccount input[type=checkbox] {
    margin: 0;
    vertical-align: middle;
}
#deleteAccountButton {
    display: block;
    margin-top: 25px;
    width: 150px;
    height: 35px;
    background-color: #FF8383;
    border-color: #D44F33;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    cursor: pointer;
}
#deleteAccountButton:hover {
    background-color: red;
    color: white;
}
    
/* Added by Bruce Head */    
#enable2fa {
    display: block;
    margin-top: 25px;
    width: 250px;
    height: 35px;
    background-color: #6EDC6B;
    border-color: #267C23;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    cursor: pointer;
}
#enable2fa:hover {
    background-color: green;
    color: white;
}
    
#disable2fa {
    display: block;
    margin-top: 25px;
    width: 250px;
    height: 35px;
    background-color: #FF8383;
    border-color: #D44F33;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    cursor: pointer;
}
#disable2fa:hover {
    background-color: red;
    color: white;    
}        
/* Added by Bruce Tail*/

</style>
<form id="account">
    <input type="hidden" name="action" value="updateAccount"/>
    <div class="sectionHeader">
        <h2>Change Email</h2>
    </div>
    <div id="changeEmail">
        <label for="currentEmail">Current Email</label>
        <input name="currentEmail" type="text" value="<?php print($_SESSION['email']); ?>" disabled/>
        <label for="newEmail">New Email</label>
        <input type="text" name="newEmail" placeholder="New Email" autocomplete="off" />
        <label for="confirmEmail">Confirm Email</label>
        <input type="text" name="confirmEmail" placeholder="Confirm Email" autocomplete="off" />
    </div>

    <div class="sectionHeader">
        <h2>Change Password</h2>
    </div>
    <div id="changePassword">
        <label for="currentPassword">Current Password</label>
        <input type="password" name="currentPassword" autocomplete="off" />
        <label for="newPassword">New Password</label>
        <input type="password" name="newPassword" autocomplete="off" >
        <style>
            .passwordInfo {
                grid-column: 1 / span 2;
                font-style: italic;
                color: grey;
            }
        </style>
        <span class="smalltext passwordInfo">Must be at least six characters, with at least one uppercase and one lowercase letter, and with at least one number.</span>
        <label for="confirmPassword">Confirm New Password</label>
        <input type="password" name="confirmPassword" autocomplete="off" />
    </div>
    
    <div id="saveMessage" class="settingsMessage error"></div>
    <input class="settingsSubmit" type="submit" value="Save changes"/>
</form>

<!-- Added by Bruce Head -->    
<br>
<div class="sectionHeader">
    <h2>Two-Factor Authentication</h2>
        <span class="subheader">Enables two-factor authentication with the use of a two-factor authentication app</span>
</div>
    <?php 
        if($_SESSION['2fa'] == 0) { ?>
            <button id="enable2fa">Enable Two-Factor Authentication</button>
    <?php } ?>
    <?php 
        if($_SESSION['2fa'] == 1) { ?>
            <button id="disable2fa">Disable Two-Factor Authentication</button>
     <?php } ?>
<!-- Added by Bruce Tail -->

<br>
<form id="deleteAccount">
    <input type="hidden" name="action" value="deleteAccount"/>
    <div class="sectionHeader">
        <h2>Delete Account</h2>
        <span class="subheader">Will remove your profile and bookmarks from the site permanently. Comments and articles you've written will no longer be tied to your account.</span>
    </div>
    <div id="deleteAccount">
        <input id="deleteAccountButton" type="submit" value="DELETE ACCOUNT"/>
        <input type="checkbox" name="deleteConfirm"/>
        <label for="deleteConfirm">Confirm account deletion (this <span style="font-weight:bold">cannot</span> be undone!)</label>
    </div>
    <div id="deleteError" class="settingsMessage"></div>
</form>
<script>
    $("#account").submit(function(event) {
        $("#saveMessage").hide();
        $("#saveMessage").removeClass("error");
        event.preventDefault();
        $.post("util/settingsHandler.php", $(this).serialize(), function(data) {
            /* NOTE - TODO: The current password here is sent over plaintext in a POST. This should instead be verified with a challenge type auth.
               See https://stackoverflow.com/questions/9934189/securely-send-a-plain-text-password
            */
            if(data == "Success") {
                $("#saveMessage").addClass("success");
                $("#saveMessage").text("Setings successfully saved.");
                // Reset password fields after saving
                $("[name=currentPassword]").val('');
                $("[name=newPassword]").val('');
                // Reset/update email fields after saving
                $("[name=currentEmail]").val($("[name=newEmail]").val());
                $("[name=newEmail]").val('');
                $("[name=confirmEmail]").val('');
            } else {
                $("#saveMessage").addClass("error");
                $("#saveMessage").text(data);
            }
            $("#saveMessage").fadeIn();
            $("#saveMessage").delay(5000).fadeOut();
        });
    });
    
/* Added by Bruce head */
    // Redirect to enable/disable 2FA
    $('#enable2fa, #disable2fa').click(function() {
        window.location.href = 'includes/settings2FA.php';
    });
/* Added by Bruce tail */
            
    $("#deleteAccount").submit(function(event) {
        $("#deleteError").hide();
        $("#deleteError").text('');
        $("#deleteError").removeClass("error");
        event.preventDefault();
        $.post("util/settingsHandler.php", $(this).serialize(), function(data) {
            if(data != "Success") {
                $("#deleteError").addClass("error");
                $("#deleteError").text(data);
                $("#deleteError").fadeIn();
            } else {
                $("#deleteError").addClass("success");
                $("#deleteError").text("Account successfully deleted. You will be redirected momentarily..");
                $("#deleteError").fadeIn();
                setTimeout(function() { location.reload(); }, 2000); // Refresh after 2 seconds
            }
        });
    });
</script>
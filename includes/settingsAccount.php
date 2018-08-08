<style>
input {
    padding-left: 2px;
}

#changeEmail, #changePassword {
    display: grid;
    grid-template-columns: 150px 300px;
    grid-template-rows: 20px 20px 20px;
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

</style>
<form id="account">
    <input type="hidden" name="action" value="updateAccount"/>
    <div class="sectionHeader">
        <h2>Change Email</h2>
    </div>
    <div id="changeEmail">
        <label for="currentEmail">Current Email</label>
        <input name="currentEmail" type="text" placeholder="Current Email"/>
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
        <input type="password" name="newPassword" autocomplete="off" />
        <label for="confirmPassword">Confirm New Password</label>
        <input type="password" name="confirmPassword" autocomplete="off" />
    </div>

    <div class="settingsMessage"></div>
    <input class="settingsSubmit" type="submit" value="Save changes"/>
</form>
<br>
<form id="deleteAccount" value="deleteAccount">
    <div class="sectionHeader">
        <h2>Delete Account</h2>
        <span class="subheader">Will remove your profile and bookmarks from the site permanently. Comments and articles you've written will no longer be tied to your account.</span>
    </div>
    <div id="deleteAccount">
        <input id="deleteAccountButton" type="button" value="DELETE ACCOUNT"/>
        <input type="checkbox" name="deleteConfirm"/>
        <label for="deleteConfirm">Confirm account deletion (this <span style="font-weight:bold">cannot</span> be undone!)</label>
    </div>
</form>
<script>
    $("#account").submit(function(event) {
        $(".settingsMessage").hide();
        $(".settingsMessage").removeClass("error");
        event.preventDefault();
        $.post("util/settingsHandler.php", $(this).serialize(), function(data) {
            /* NOTE - TODO: The current password here is sent over plaintext in a POST. This should instead be verified with a challenge type auth.
               See https://stackoverflow.com/questions/9934189/securely-send-a-plain-text-password
            */
            if(data == "Success") {
                $(".settingsMessage").addClass("success");
                $(".settingsMessage").text("Setings successfully saved.");
                $("[name=currentPassword]").val('');
                $("[name=newPassword]").val('');
                $("[name=confirmPassword]").val('');
            } else {
                $(".settingsMessage").addClass("error");
                $(".settingsMessage").text(data);
            }
            $(".settingsMessage").fadeIn();
        });
    })
</script>
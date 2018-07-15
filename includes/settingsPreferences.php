<style>
#preferences {
	display: grid;
}

#timezoneInputs {
    display: grid;
    grid-template-rows: 20px auto;
    grid-row-gap: 5px;
    max-width: 250px;
}
#timezoneInputs > label {
    grid-row-start: 1;
    font-weight: 600;
}
</style>
<form id="preferences">
    <input type="hidden" name="action" value="updatePreferences"/>
    <div class="sectionHeader">
        <h2>Language</h2>
        <span class="subheader">Your preferred language, displayed on your profile page.</span>
    </div>
    <span class="settingsNotice">This feature coming soon!</span>

    <div class="sectionHeader">
        <h2>Date Format</h2>
        <span class="subheader">Specifies how dates are displayed throughout the website. Uses <a href="https://secure.php.net/manual/en/datetime.formats.date.php">PHP date formatting</a> to specify format.</span>
    </div>
    <span class="settingsNotice">This feature coming soon!</span>

    <div class="sectionHeader">
        <h2>Time Zone</h2>
        <span class="subheader">Specifies times throughout the site to be displayed in your local timezone.</span>
    </div>
    <div id="timezoneInputs">
        <label for="timezone">Time Zone</label>
        <select name="timezone">
            <option value="HAST" <?php print($user['TimeZone'] == "HAST" ? "selected" : ""); ?>>Hawaii-Aleutian Time Zone (UTC−10:00)</option>
            <option value="AKST" <?php print($user['TimeZone'] == "AKST" ? "selected" : ""); ?>>Alaska Time Zone (UTC−09:00)</option>
            <option value="PST" <?php print($user['TimeZone'] == "PST" ? "selected" : ""); ?>>Pacific Time Zone (UTC−08:00)</option>
            <option value="MST" <?php print($user['TimeZone'] == "MST" ? "selected" : ""); ?>>Mountain Time Zone (UTC−07:00)</option>
            <option value="CST" <?php print($user['TimeZone'] == "CST" ? "selected" : ""); ?>>Central Time Zone (UTC−06:00)</option>
            <option value="EST" <?php print($user['TimeZone'] == "EST" ? "selected" : ""); ?>>Eastern Time Zone (UTC−05:00)</option>
        </select> 
    </div>

    <div class="sectionHeader">
        <h2>Theme</h2>
        <span class="subheader">Changes the display theme of the website.</span>
    </div>
    <span class="settingsNotice">This feature coming soon!</span>

    <div class="settingsMessage"></div>
    <input class="settingsSubmit" type="submit" value="Save changes"/>
</form>
<script>
    $("#preferences").submit(function(event) {
        $(".settingsMessage").hide();
        event.preventDefault();
        $.post("util/settingsHandler.php", $(this).serialize(), function(data) {
            console.log($(this).serialize()); //DEBUG
            if(data == "Success") {
                $(".settingsMessage").addClass("success");
                $(".settingsMessage").text("Setings successfully saved.");
            } else {
                $(".settingsMessage").addClass("error");
                $(".settingsMessage").text(data);
            }
            $(".settingsMessage").fadeIn();
        });
    })
</script>
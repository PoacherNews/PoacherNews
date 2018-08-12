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
        <h2>Date Format</h2>
        <span class="subheader">Specifies how dates are displayed throughout the website.</span>
    </div>
    <div id="dateformatInputs">
        <label for="dateformat">Date Format</label>
        <select name="dateformat">
            <option value="F j,Y" <?php print($user['DateFormat'] == "F j,Y" ? "selected" : ""); ?>><?php print date("F j, Y"); ?></option>
            <option value="j F Y" <?php print($user['DateFormat'] == "j F Y" ? "selected" : ""); ?>><?php print date("j F Y"); ?></option>
            <option value="m/d/y" <?php print($user['DateFormat'] == "m/d/y" ? "selected" : ""); ?>><?php print date("m/d/y"); ?></option>
            <option value="d/m/y" <?php print($user['DateFormat'] == "d/m/y" ? "selected" : ""); ?>><?php print date("d/m/y"); ?></option>
            <option value="l jS \of F Y" <?php print($user['DateFormat'] == "l jS \of F Y" ? "selected" : ""); ?>><?php print date("l jS \of F Y"); ?></option>
        </select> 
    </div>

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
        $(".settingsMessage").removeClass("error");
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

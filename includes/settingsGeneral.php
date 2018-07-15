<?php $bioMaxLength = 200; ?>

<style>
#general {
	display: grid;
}

textarea[name=bio] {
	max-width: 500px;
	font-family: inherit;
	margin-top: 5px;
}
.bioTextCounter {
	font-size: 13px;
}

#displayNameInputs, #locationInputs {
    display: grid;
    grid-template-rows: 20px auto;
    grid-template-columns: 1fr 1fr;
    grid-column-gap: 10px;
    grid-row-gap: 5px;
    max-width: 500px;
}
#displayNameInputs > label, #locationInputs > label {
    grid-row-start: 1;
    font-weight: 600;
}
</style>
<form id="general">
	<div class="sectionHeader">
		<h2>Bio</h2>
		<span class="subheader">A short description of yourself, displayed on your profile page.</span>
	</div>
	<textarea id="bio" name="bio" rows="5" cols="5" maxlength="<?php echo $bioMaxLength; ?>" placeholder="Enter a bio here"><?php if(!is_null($user['Bio'])) { print($user['Bio']); } ?></textarea>
	<div class="bioTextCounter">0/<?php echo $bioMaxLength; ?> characters</div>

	<div class="sectionHeader">
		<h2>Display Name</h2>
		<span class="subheader">Will be displayed as your first and last name on your profile, comments, and authored articles.</span>
    </div>
    <div id="displayNameInputs">
        <label for="firstName">First name</label>
        <input name="firstName" type="text" placeholder="First name" value="<?php if(!is_null($user['FirstName'])) { print($user['FirstName']); } ?>"/>
        <label for="lastName">Last name</label>
        <input name="lastName" type="text" placeholder="Last name" value="<?php if(!is_null($user['LastName'])) { print($user['LastName']); } ?>"/>
    </div>

	<div class="sectionHeader">
		<h2>Location</h2>
		<span class="subheader">Will be displayed on your profile page.</span>
	</div>
    <span class="settingsNotice">This feature coming soon!</span>
    <!-- Disabled until location columns are added to user database. 7/14 -CS
	<div id="locationInputs">
        <label for="city">City</label>
        <input name="city" type="text" placeholder="City"/>
        <label for="state">State</label>
        <select name="state">
            <?php include('includes/stateOptions.html'); ?>
        </select>
    </div>
    -->
    <input class="settingsSubmit" type="submit" value="Save changes"/>
</form>
<script>
	function trackBioCharCount() {
		$max = <?php echo $bioMaxLength; ?>;
		$len = $("#bio").val().length;
		$(".bioTextCounter").text($len+"/"+$max+" characters");
	}
	$("#bio").keyup(trackBioCharCount);
	$(document).ready(function() {
		trackBioCharCount();  // If the bio is filled by page load, load the char length into the counter
	});

    // $("#general").submit(function(event) {
    //     event.preventDefault();
    //     console.log($(this).serialize());
    //     // $.post("settings2.php", $("#general").serialize());
    //     $(this).submit();
    // })
</script>
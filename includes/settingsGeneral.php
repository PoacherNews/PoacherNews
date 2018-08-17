<?php $bioMaxLength = 200; ?>

<style>
#general {
	display: grid;
}
	
#profilePic {
	max-height: 250px;
	max-width: 250px;
}	

textarea[name=bio] {
	max-width: 500px;
	font-family: inherit;
	margin-top: 5px;
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
<!-- ADDED BY BRUCE -->
    <div class="sectionHeader">
		<h2>Profile Picture</h2>
		<span class="subheader">Image representation of yourself, displayed on your profile page.</span>
	</div>
    <div class="avatar">
    <?php
	// Echo profile picture if set
        if($user['ProfilePicture'] != 'defaultAvatar.png')
        {
		// profilePicture file path
        	echo "<img id='profilePic' src='../res/img/profilePictures/".$user['UserID']."/".$user['ProfilePicture']."'>";
        }
	// Echo default profile picture
        else 
        {
		// defaultAvatar.png file path
            	echo "<img id='profilePic'
			src='../res/img/profilePictures/".$user['ProfilePicture']."'>";
            	echo "<br>";
        }
    ?>
    <?php
        if($user['UserID'] == $_SESSION['userid'])
            {
    ?>
                <form id="upload" method="post" enctype="multipart/form-data">
                    Select image to upload:
                    <input id="imgInp" onchange="readURL(this);" type="file" name="profilePicture" class="profilePicture">
                    <input class="settingsSubmit" type="submit" value="Save Picture" name="submit">
                </form>
        <?php
            }
        ?>
    </div>
<!-- -->

<form id="general">
    <input type="hidden" name="action" value="updateGeneral"/>
	<div class="sectionHeader">
		<h2>Bio</h2>
		<span class="subheader">A short description of yourself, displayed on your profile page.</span>
	</div>
	<textarea id="bio" name="bio" rows="5" cols="5" maxlength="<?php echo $bioMaxLength; ?>" placeholder="Enter a bio here"><?php if(!is_null($user['Bio'])) { print($user['Bio']); } ?></textarea>
	<div class="smalltext" id="bioTextCounter">0/<?php echo $bioMaxLength; ?> characters</div>

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
		<span class="subheader">Will be displayed as your location on your profile page.</span>
	</div>
	<div id="locationInputs">
        <label for="city">City</label>
        <input name="city" type="text" placeholder="City" value="<?php if(!is_null($user['City'])) { print($user['City']); } ?>"/>
        <label for="state">State</label>
        <select name="state">
            <?php include('includes/stateOptions.php'); ?>
        </select>
    </div>
   
    <div class="settingsMessage"></div>
    <input class="settingsSubmit" type="submit" value="Save changes"/>
</form>
<script>
	function trackBioCharCount() {
		$max = <?php echo $bioMaxLength; ?>;
		$len = $("#bio").val().length;
		$("#bioTextCounter").text($len+"/"+$max+" characters");
	}
	$("#bio").keyup(trackBioCharCount);
	$(document).ready(function() {
		trackBioCharCount();  // If the bio is filled by page load, load the char length into the counter
	});

    $("#general").submit(function(event) {
        $(".settingsMessage").hide();
        $(".settingsMessage").removeClass("error");
        event.preventDefault();
        $.post("util/settingsHandler.php", $(this).serialize(), function(data) {
            if(data == "Success") {
                $(".settingsMessage").addClass("success");
                $(".settingsMessage").text("Setings successfully saved.");
            } else {
                $(".settingsMessage").addClass("error");
                $(".settingsMessage").text(data);
            }
            $(".settingsMessage").fadeIn();
            $(".settingsMessage").delay(5000).fadeOut();
        });
    })
    
/* ADDED BY BRUCE */
                 $("form#upload").submit(function(event){   
                      var formData = new FormData($(this)[0]);
                      $.ajax({
                        url: 'util/uploadProfilePicture.php',
                        type: 'POST',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (returndata) {
                            //$("#comment-list-box").append(returndata);

                        $("form#upload")[0].reset();
                        //$("#loaderIcon").hide();   
                        },
                        error:function (){}                 
                      });
                    });   
    
    function readURL(input) { // Reads in a picture and displays it
        var imgInp = document.getElementById('imgInp').value;
        if(!(imgInp.length == 0)) { // Display image
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profilePic').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            }
        } else { // Remove image
            $('#profilePic').attr('src', '');
        }
    }
    $("#imgInp").change(function(){readURL(this);});
/* */
</script>

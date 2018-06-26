<?php
// TODO:
// Error check for blank bio
// Change profile picture
    include 'util/userUtils.php';

    $username = $_GET['Username'];

    // connection to database
    include 'util/db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
        
    $sql = "SELECT * FROM User WHERE Username = '".$username."'";
    $result = $db->query($sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $profilepicture = $row['ProfilePicture'];
    $bio = $row['Bio'];
    $userid = $row['UserID'];
    $usertype = $row['Usertype']; 

    // Redirect to index if blank Username or profile does not exist
    if($username == NULL || $result->num_rows != 1)
    {
        //echo "Error. User: $username does not exist";
        echo '<meta http-equiv="refresh" content="0; url=index.php">';
        exit;
    }
?>

<style>
.add_button, .edit_button2{
	width: 70px;
}

.save_button, .cancel_button, .edit_button {
	display: none;
	width: 70px;
}

#text_id {
	width: 200px;
	height: 70px;
}

</style>

<div class="user">
    <div class="picture">
<?php
    if($profilepicture != null)
    {
        echo "<img src='../res/img/profilePictures/".$username."/".$profilepicture."'>";
    }
    else 
    {
        echo "(Profile Picture)";
        if(strtolower($username) == strtolower($_SESSION['username']))
        {
?>
            <form action="../util/uploadProfilePicture.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="profilePicture" class="profilePicture">
                <input type="submit" value="Upload Image" name="submit">
            </form>
<?php
        }
    }
?>
    </div>
            
    <div class="info">
        <?php 
            echo "<h3>$username</h3>";
            if($bio != null)
            {
        ?>
                <div class="bioContainer">
                    <?php echo $bio; ?>
                </div>
                <form action="../util/handleBio.php" method="POST">
                    <textarea name="text" id="text_id" class="form-control" style="resize:vertical;display:none"><?php echo $bio ?></textarea>
                    <button type="button" name="save" class="save_button">Save</button>
                </form>
                
        <button type="button" class="edit_button2">Edit</button>
        <button type="button" class="cancel_button">Cancel</button>
<script>
    	$(".edit_button2").click(function(){$(".bioContainer").toggle(), $("#text_id").toggle(), $(".edit_button2").hide(), $(".save_button").show(), $(".cancel_button").show()})
        $(".cancel_button").click(function(){$(".bioContainer").toggle(), $("#text_id").toggle(), $(".edit_button2").show(), $(".save_button").hide(), $(".cancel_button").hide()})
        
        $(document).ready(function() {

    $(".save_button").click(function()
    {
        $(".bioContainer").toggle(),
        $("#text_id").toggle(),
        $(".edit_button2").show(), 
        $(".save_button").hide(), 
        $(".cancel_button").hide()
	        
        var bio = $('textarea#text_id').val();
        // Test JQuery var
        //$(".bioContainer").text(bio);

        $.ajax({
            type: 'POST',
            url: '../util/handleBio.php',
            async: false,
            data: {text : bio},
            success: function (data) 
            {
                $(".bioContainer").html(bio);
            },
            error: function () 
            {
                $(".bioContainer").html('Error loading information from server.');
            }
        });  
                
    });
});


</script>
        
        <?php
            }
            else
            {
            	if(strtolower($username) == strtolower($_SESSION['username']))
        		{
        ?>
                <div class="bioContainer">
                </div>
                <form action="../util/handleBio.php" method="POST">
<textarea name="text" id="text_id" class="form-control" style="resize:vertical;display:none"></textarea>
<button type="button" name="save" class="save_button">Save</button>
</form>

<button type="button" class="add_button">Add Bio</button>
<button type="button" class="cancel_button">Cancel</button>
<button type="button" class="edit_button">Edit</button>

<script>
	$(".add_button").click(function(){$("#text_id").toggle(), $(".add_button").hide(), $(".save_button").show(), $(".cancel_button").show()})
	$(".cancel_button").click(function(){$("#text_id").toggle(), $(".add_button").show(), $(".save_button").hide(), $(".cancel_button").hide()})
    $(".edit_button").click(function(){$(".bioContainer").toggle(), $("#text_id").toggle(), $(".edit_button").hide(), $(".save_button").show(), $(".cancel_button").show()})


$(document).ready(function() {

    $(".save_button").click(function()
    {
        $("#text_id").toggle(),
        $(".edit_button").show(), 
        $(".add_button").hide(), 
        $(".save_button").hide(), 
        $(".cancel_button").hide()
	        
        var bio = $('textarea#text_id').val();
        // Test JQuery var
        //$(".bioContainer").text(bio);

        $.ajax({
            type: 'POST',
            url: '../util/handleBio.php',
            async: false,
            data: {text : bio},
            success: function (data) 
            {
                $(".bioContainer").html(bio);
            },
            error: function () 
            {
                $(".bioContainer").html('Error loading information from server.');
            }
        });  
                
    });
});


</script>

        <?php
        		}
            }
        ?>
    </div>
</div>

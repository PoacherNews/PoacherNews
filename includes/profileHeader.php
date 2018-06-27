<?php
// TODO:
// Error check for blank bio
// On cancel set textarea to biocontainer text
// 
// Change / Remove profile picture
    include 'util/db.php';
    include 'util/userUtils.php';

    // connection to database
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    $userData = getUserById($_GET['uid'], $db);
    
    // Disabled and replaced by Colin, 6/26
    // $sql = "SELECT * FROM User WHERE Username = '".$username."'";
    // $result = $db->query($sql);
    // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    // $profilepicture = $row['ProfilePicture'];
    // $bio = $row['Bio'];
    // $userid = $row['UserID'];
    // $usertype = $row['Usertype']; 

    $username = $userData['Username'];
    $profilepicture = $userData['ProfilePicture'];
    $bio = $userData['Bio'];
    $userid = $userData['UserID'];
    $usertype = $userData['Usertype'];


    // Redirect to index if blank Username or profile does not exist
    if($username === NULL)
    {
        // echo "Error. UserID: {$_GET['uid']} does not exist";
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

.bioContainer {
    display: none;
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
            if($userid == $_SESSION['userid'])
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
            <div class="bioContainer2">
                <?php echo $bio; ?>
            </div>
                
            <form action="../util/handleBio.php" method="POST">
                <textarea name="text" id="text_id" style="resize:vertical;display:none"><?php echo $bio ?></textarea>
                <button type="button" name="save" class="save_button">Save</button>
            </form>
                
            <button type="button" class="edit_button2">Edit</button>
            <button type="button" class="cancel_button">Cancel</button>
            
            <script>
                $(".edit_button2").click(function(){$(".bioContainer2").toggle(), $("#text_id").toggle(), $(".edit_button2").hide(), $(".save_button").show(), $(".cancel_button").show()})
                $(".cancel_button").click(function(){$(".bioContainer2").toggle(), $("#text_id").toggle(), $(".edit_button2").show(), $(".save_button").hide(), $(".cancel_button").hide()})
        
                $(document).ready(function()
                {
                    $(".save_button").click(function()
                    {
                        $(".bioContainer2").toggle(),
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
                                $(".bioContainer2").html(bio);
                            },
                            error: function () 
                            {
                                $(".bioContainer2").html('Error loading information from server.');
                            }
                        });  
                    });
                });
            </script>
        
        <?php
        }
        else
        {
            if($userid == $_SESSION['userid'])
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

                    $(document).ready(function()
                    {
                        $(".save_button").click(function()
                        {
                            $(".bioContainer").toggle(),
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

<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
    <body>
    
        <?php 
            include 'includes/header.php';
            include 'includes/nav.php';
            //include 'includes/footer.html';
        ?>
        
        <div id="titleDiv">
            <h1 id="titleText">Create a New User</h1>
        </div>
       
        <div id = "bodyDiv">
            <div id="imgContainer">
                    <img id="mascotImage" src="res/img/logo.png">
            </div>

            <form id="newUserForm" action = "login.php">

                <div>
                    <label for="username"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="uname" required>
                </div>

                <div>
                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="psw" required>
                </div>

                <div>
                    <button type="submit">Create</button>
                    <label>
                      <input type="checkbox" checked="checked" name="remember"> Remember me
                    </label>
                </div>

                <div>
                    <button type="button" class="cancelbtn">Cancel</button>
                </div>
            </form>
        </div>
    </body>
</html>

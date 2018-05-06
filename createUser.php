<!DOCTYPE html>
<html>
    <head>
	   <?php include 'includes/globalHead.html' ?>
    </head>
    <body>
    
        <?php 
        	include 'util/loginCheck.php';
// Check to see if the user has already logged in
if(empty($_SESSION['loggedin'])) {
    $loggedIn = false;
} else { // The user is already logged in, so send them back to the index
    echo "You are already logged in";
    echo '<meta http-equiv="refresh" content="0; url=/index.php">';
    exit;
}
        	
            include 'includes/header.php';
            include 'includes/nav.php';
            //include 'includes/footer.html';
        ?>
        
    <!-- Added by Bruce -->
        <form action="/util/handleCreateUser.php" method="POST">
    <!-- -->

        <div id="titleDiv">
            <h1 id="titleText">Create a New User</h1>
        </div>
       
        <div id = "bodyDiv">
            <div id="imgContainer">
                    <img id="mascotImage" src="/res/img/logo.png">
            </div>

          <!--  <form id="newUserForm" action = "login.php"> -->

    <!-- Added by Bruce -->
		        <input type="hidden" name="action" value="make_new">

                <div>
                    <label for="firstName"><b>First Name</b></label>
                    <input type="text" placeholder="First Name" name="firstname" required>
                </div>
                
                <div>
                    <label for="lastName"><b>Last Name</b></label>
                    <input type="text" placeholder="Last Name" name="lastname" required>
                </div>

                <div>
                    <label for="email"><b>Email</b></label>
                    <input type="text" placeholder="Email" name="email" required>
                </div>

		        <div>
                    <label for="confirmEmail"><b>Confirm Email</b></label>
                    <input type="text" placeholder="Confirm Email" name="email_confirm" required>
                </div>            
    <!-- -->
                
                <div>
                    <label for="username"><b>Username</b></label>
                    <input type="text" placeholder="Username" name="username" required>
                </div>

                <div>
                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="*****" name="password" required>
                </div>
                
	<!-- Added by Bruce -->
                <div>
                    <label for="confirmPassword"><b>Confirm Password</b></label>
                    <input type="password" placeholder="*****" name="password_confirm" required>
                </div>

	            <!-- Echo createUser error message -->
	            <?php
	            if (isset($error)) {
                    echo "<p>$error</p>\n";
                }
                ?>
	<!-- -->
                
                <div>
                    <input type="submit" name="submit" value="Create">
                </div>

            <!--
                <div>
                    <button type="button" class="cancelbtn">Cancel</button>
                </div>
            -->

           <!-- </form> -->
        </div>
        </form>
    </body>
</html>

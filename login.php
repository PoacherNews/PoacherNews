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
    <form action="login.php" method="post">
        <div class="loginWrap">
            <div class="loginForm">
                <h1 class="loginH1">Login</h1>
                <label class="loginLbl" for="username">Username: </label>
                <input type="text" id="loginUsername" name="username" placeholder="Username">
                <label class="loginLbl" for="password">Password: </label>
                <input type="password" id="loginPassword" name="password" placeholder="Password">
            </div>
            <img class="loginImg" src="res/img/logo.png">
        </div>
        <div class="loginButtons">
            <input class="loginInput" type="submit" value="Submit">
            <a href="createUser.php">Create an account</a>
        </div>
        <div class="loginRelative"></div>
    </form>
    
</body>
</html>

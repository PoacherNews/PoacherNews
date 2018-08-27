<?php include 'util/loginCheck.php'; ?>
<!DOCTYPE html>
<html>
<head>
   <?php include 'includes/globalHead.html' ?>
   <style>
        .accountContainer {
            max-width: 400px;
            border: solid 2px #83A8F0;
            border-radius: 20px 0px;
            padding: 10px;
            margin: 8% auto 8% auto;
        }

        .accountContainer h1 {
            border-bottom: 1px solid #83A8F0;
        }

        .formFields {
            margin: 0px 25px 0px 25px;
            display: grid;
            grid-template-columns: 1.75fr 2fr;
            grid-column-gap: 20px;
            grid-row-gap: 5px;
        }

        #tosField {
            grid-column: 1 / span 2;
            font-style: italic;
        }

        #createSubmitButton {
            grid-column: 1 / span 2;
            background: #83A8F0;
            color: white;
            border-radius: 100px;
            cursor: pointer;
            font-size: 14px;
            min-width: 250px;
            line-height: 15px;
            padding: 6px 16px;
            position: relative;
            text-align: center;
            white-space: nowrap;
            margin: 10px;
        }
        #createSubmitButton:hover {
            border: 1px solid #1864F7;
            opacity: 0.75;
        }

        .passwordInfo {
            grid-column: 1 / span 2;
            color: grey;
            font-style: italic;
        }

        .errorMessage {
            grid-column: 1 / span 2;
            margin: 5px 0px 5px 0px;
            padding: 10px;
            border-radius: 2px;
            font-weight: bold;
            box-shadow: 0 0 0 1px #e0b4b4 inset;
            background-color: #fff6f6;
            color: #9f3a38;
        }
</style>
</head>
<body>
    <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) { // Disallow access if already logged in.
            echo '<meta http-equiv="refresh" content="0; url=/index.php">';
            exit;
        }

        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
 
    <div class="pageContent">
        <form class="accountContainer" action="/util/handleCreateUser.php" method="POST">
            <input type="hidden" name="action" value="make_new">
            <h1>Create an Account</h1>
            <div class="formFields">
                <label for="firstName">First Name</label>
                <input type="text" placeholder="First Name" name="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>" required/>
                <label for="lastName">Last Name</label>
                <input type="text" placeholder="Last Name" name="lastname" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>" required/>
                <label for="email">Email</label>
                <input type="text" placeholder="Email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" required/>
                <label for="confirmEmail">Confirm Email</label>
                <input type="text" placeholder="Confirm Email" name="email_confirm" value="<?php echo isset($_POST['email_confirm']) ? $_POST['email_confirm'] : '' ?>" required/>
                <label for="username">Username</label>
                <input type="text" placeholder="Username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" required/>
                <label for="password">Password</label>
                <input type="password" placeholder="*****" name="password" required>
                <span class="smalltext passwordInfo">Must be at least six characters, with at least one uppercase and one lowercase letter, and with at least one number.</span>
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" placeholder="*****" name="password_confirm" required>
                <div id="tosField">
                    <label for="agree">I agree to the <a href="/terms.php">Terms of Service</a></label>
                    <input type="checkbox" name="checkbox">
                </div>

                <!-- createUser error message -->
                <?php
                if (isset($error)) {
                    echo "<p class='errorMessage'>$error</p>\n";
                }
                ?>

                <input id="createSubmitButton" type="submit" name="submit" value="Sign Up">
            </div>
        </form>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>
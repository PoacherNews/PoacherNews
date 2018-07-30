<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
<head>
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/help.css">
</head>
<body>
    <?php            
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <h1 id="helpHeader">Frequently Asked Questions</h1>
        <h2 class="helpSectionHeader">Basics</h2>
        <ul> 
            <li><h3 class="questionHeader">Q: What is <span class="text-emphasis">The Poacher?<span></h2></li>
            <li><span class="text-emphasis">The Poacher</span> is a satirical online news publication made by students, for students. It allows aspiring and accomplished authors alike a home to post articles and provides a space for a community to read, comment, and engage with one another.</li>
            <li><h3 class="questionHeader">Q: What is 'satrical' news?</h3></li>
            <li><h3 class="questionHeader">Q: How can I become a user?</h3></li>
            <li>Anyone can sign up for an account free of charge <a href="createUser.php">here</a>.
        </ul>
        <h2 class="helpSectionHeader">Account Information</h2>
        <ul>
            <li><h3 class="questionHeader">Q: Where can I signup/login/logout?</h3></li>
            <li>The account creation page is located <a href="createUser.php">here</a>. You can log in or log out by using the drop down menu in the upper right of the header, or by accessing the login page <a href="login.php">here</a>.
            <li><h3 class="questionHeader">Q: What capabilities do I have as a user?</h3></li>
            <li>Anyone can read articles published on <span class="text-emphasis">The Poacher</span>, but those with registered accounts can rate articles, bookmark articles for later reading, and leave comments.</li>
            <li><h3 class="questionHeader">Q: What if I  want to write for <span class="text-emphasis">The Poacher</span>?</h3></li>
            <li>To apply for writer status, please contact us via the information on our <a href="feedback.php">feedback page</a>.</li>
        </ul>
        <h2 class="helpSectionHeader">Terms of Use</h2>
        <ul>
            <li><h3 class="questionHeader">Q: What are the Terms of Use?</h3></li>
            <li>Our Terms of Use can be found <a href="terms.php">here</a>.
            <li><h3 class="questionHeader">Q: Do I have to sign the Terms of Use?</h3></li>
            <li>All new accounts must explicitly agree to the Terms of Use during the account creation process.</li>
        </ul>
        <h2 class="helpSectionHeader">Contact</h2>
        <ul>
            <li><h3 class="questionHeader">Q: Who can I contact for Feedback/Questions/Concerns?</h3></li>
            <li>If you feel the need to contact the staff at <span class="text-emphasis">The Poacher</span> for any reason, you can find the contact information <a href="feedback.php">here</a>.</li>
        </ul>
        <h2 class="helpSectionHeader">Social Media</h2>
        <ul>
            <li><h3 class="questionHeader">Q: What social media platforms is <span class="text-emphasis">The Poacher</span> on?</h3></li>
        </ul>
        <h2 class="helpSectionHeader">Advertising</h2>
        <ul>
            <li><h3 class="questionHeader">Q: Can I advertise for <span class="text-emphasis">The Poacher</span>?</h3></li>
            <li>Yes! For advertisement inquries, please visit our <a href="advertising.php">advertising page</a>.</li>
        </ul>
    </div>
    <?php include('includes/footer.html'); ?>
    </body>
</html>

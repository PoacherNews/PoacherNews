<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
<head>
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/advertising.css">
</head>
<body>
    <?php 
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <h1 id="advertisingHeader">Advertising</h1>
        <h2 class="advertisingSectionHeader">Who Can I Contact for Advertising?</h2>
            <p>Please contact us by the address listed on our <a href="feedback.php">feedback page</a>. Make sure you include "Advertising" in the subject line.</p>
        <h2 class="advertisingSectionHeader">Be sure to include: </h2>
            <ul>
                <li>Your company name</li>
                <li>What type of business your company performs</li>
                <li>How you would like us to advertise your product or business</li>
                <li>Any additional contact information we would need for correspondence</li>
            </ul>
        <p>For any additional information, you may contact us with general questions at the address listed on our <a href="feedback.php">feedback page</a>.</p>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>

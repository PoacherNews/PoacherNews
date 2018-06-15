<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
    <body>
        <?php 
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <div class="adDivLeft"></div> 
        <div class="adDivRight"></div>
        <p id="adTitleId"><b>Advertising</b></p>
        <br>
        <h1 class="adP">Who Can I Contact for Advertising?</h1>
        <h2 class="adP">Questions asked by us: </h2>
            <ul>
                <li>What's your company name?</li>
                <li>What does your business entail?</li>
                <li>How you would like us to advertise your product?
                </li>
            </ul>
        <p>For more information, please contact:</p>
        <h2>poachernews@gmail.com</h2>
        <hr class="adHr">
        <div id="adDivAds">Placeholder Advertisement</div>
        <hr class="adHr">
        <?php include('includes/footer.html'); ?>
    </body>
</html>

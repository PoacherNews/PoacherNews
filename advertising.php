<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
	<style>
		.adDivLeft {
			border-left: 1px solid grey;
			height: auto;
			height: 100vh;
			float: left;
			margin-left: 20%;
			margin-right: 30px;
		}

		.adDivRight {
			border-right: 1px solid grey;
			height: 100vh;
			float: right;
			margin-right: 20%;
			margin-left: 30px;
		}

		#adTitleId {
			text-align: center;
			font-size: 40px;
			text-decoration: underline;
		}

		.adP {
			font-size: 20px;
		}
		.adHr {
			width: 100%;
			margin-bottom: 0%;
			border: 1px dashed grey;
		}

		#adDivAds {
			background-color: antiquewhite;
			text-align: center;
			font-size: 30px;
			height: 20vh;
		}
	</style>
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

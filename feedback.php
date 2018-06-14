<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
	<style>
		.fbDivLeft {
			border-left: 1px solid grey;
			height: auto;
			height: 100vh;
			float: left;
			margin-left: 20%;
			margin-right: 30px;
		}

		.fbDivRight {
			border-right: 1px solid grey;
			height: 100vh;
			float: right;
			margin-right: 20%;
			margin-left: 30px;
		}

		#fbTitleId {
			text-align: center;
			font-size: 40px;
			text-decoration: underline;
		}

		.fbDead1 {
			padding-left: 20px;
			font-family: serif;
		}

		.fbHead2 {
			padding-left: 20px;
			font-family: serif;
		}

		.fbHead3 {
			padding-left: 20px;
			font-family: serif;
		}
		.fbVl {
			border-left: 2px solid #83A8F0;
			height: 15px;
			display: inline;
			margin: 20px;
		}
		.fbP {
			font-size: 20px;
		}
		.fbHr {
			width: 100%;
			margin-bottom: 0%;
			border: 1px dashed grey;
		}

		#fbDivAds {
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
        <div class="fbDivLeft"></div> 
        <div class="fbDivRight"></div>
        <p id="fbTitleId"><b>Feedback</b></p>
        <br>
        <p class="fbP">Our staff here at <i>The Poacher</i> really appreciates what our users think about our content. Let us know how our website is doing! </p>
        <p class="fbP">For more information, please contact:</p>
        <h2 class="fbHead2">poachernews@gmail.com</h2>
        <hr class="fbHr">
        <div id="fbDivAds">Placeholder Advertisement</div>
        <hr class="fbHr">
        <?php include('includes/footer.html'); ?>
    </body>
</html>

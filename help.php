<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
	<style>
		.helpDivLeft {
			border-left: 1px solid grey;
			height: auto;
			height: 100vh;
			float: left;
			margin-left: 20%;
			margin-right: 30px;
		}

		.helpDivRight {
			border-right: 1px solid grey;
			height: 100vh;
			float: right;
			margin-right: 20%;
			margin-left: 30px;
		}

		#helpTitleId {
			text-align: center;
			font-size: 40px;
			text-decoration: underline;
		}

		.helpHead1 {
			padding-left: 20px;
			font-family: serif;
		}

		.helpHead2 {
			padding-left: 20px;
			font-family: serif;
		}

		.helpVl {
			border-left: 2px solid #83A8F0;
			height: 15px;
			display: inline;
			margin: 20px;
		}

		.helpHr {
			width: 100%;
			margin-bottom: 0%;
			border: 1px dashed grey;
		}

		#helpDivAds {
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
        <div class="helpDivLeft"></div> 
        <div class="helpDivRight"></div>
        <p id="helpTitleId"><b>Frequently Asked Questions</b></p>
        <br>
        <h1 class="helpHead1">Basics:</h1>
        <ul> 
            <li><h2 class="helpHead2">Q: What is the Poacher?</h2></li>
            <li><h2 class="helpHead2">Q: What is 'satrical' news?</h2></li>
            <li><h2 class="helpHead2">Q: How can I become a user?</h2></li>
        </ul>
        <h1 class="helpHead1">Account Information:</h1>
        <ul>
            <li><h2 class="helpHead2">Q: Where can I signup/login/logout?</h2></li>
            <li><h2 class="helpHead2">Q: What capabilities do I have as a user?</h2></li>
        </ul>
        <h1 class="helpHead1">Terms of Use:</h1>
        <ul>
            <li><h2 class="helpHead2">Q: What are the Terms of Use?</h2></li>
            <li><h2 class="helpHead2">Q: Do I have to sign the Terms of Use?</h2></li>
        </ul>
        <h1 class="helpHead1">Contact:</h1>
        <ul>
            <li><h2 class="helpHead2">Q: Who can I contact for Feedback/Questions/Concerns?</h2></li>
        </ul>
        <h1 class="helpHead1">Social Media:</h1>
        <ul>
            <li><h2 class="helpHead2">Q: What social media platforms is <i>The Poacher</i> on ?</h2></li>
        </ul>
        <h1 class="helpHead1">Advertising:</h1>
        <ul>
            <li><h2 class="helpHead2">Q: Can I advertise for <i>The Poacher</i>?</h2></li>
        </ul>
        <hr class="helpHr">
        <div id="helpDivAds">Placeholder Advertisement</div>
        <hr class="helpHr">
        <?php include('includes/footer.html'); ?>
    </body>
</html>

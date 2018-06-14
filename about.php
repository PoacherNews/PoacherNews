<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
	<style>
		.abtDivLeft {
			border-left: 1px solid grey;
			height: auto;
			height: 100vh;
			float: left;
			margin-left: 20%;
			margin-right: 30px;
		}

		.abtDivRight {
			border-right: 1px solid grey;
			height: 100vh;
			float: right;
			margin-right: 20%;
			margin-left: 30px;
		}

		.abtDivh {
			margin-right: 50%;
			margin-left: 23%;
			border: 3px solid black;
			border-radius: 10%;
			text-decoration: underline;
		}

		.adbDivp {
			border: 3px solid black;
			margin-left: 23%;
		}


		#abtTitleId {
			text-align: center;
			font-size: 40px;
			text-decoration: underline;
		}

		.abtHead1 {
			padding-left: 20px;
			font-family: serif;
		}

		.abtVl {
			border-left: 2px solid #83A8F0;
			height: 15px;
			display: inline;
			margin: 20px;
		}

		p.abtPar {
			padding-left: 20px;
			font-family: serif;
			font-size: 26px;
		}

		.abtHr {
			width: 100%;
			margin-bottom: 0%;
			border: 1px dashed grey;
		}

		#abtDivAds {
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
        <div class="abtDivLeft"></div> 
        <div class="abtDivRight"></div>
        <p id="abtTitleId"><b>About <i>The Poacher</i></b></p>
        
        <p class="abtPar"><i>The Poacher</i> is the newest, worldly acclaimed satirical website created by five lonely students at the University of Missouri. Our publication entails a plethora of newly discovered ideas that gives users (as well as writers) a voice in topics such as sports, politics, entertainment, and local news. Our goal assures a humble journey of bringing a community of people together to laugh, read, share, and comment with another.
        </p>
        <h1 class="abtHead1"><b>Content:</b></h1>
        <p class="abtPar">
           Otherwise known as <i>Poacher News</i>, has a standard of authenticity. Our content is and will not be duplicated, redistributed nor shall we promote plagiarism of one’s work or ideas. 
        </p>

        <h1 class="abtHead1"><b>Platform:</b></h1>
        <p class="abtPar"> We think of our brand as tool to for representing respected as well as aspiring writers to provide users with gut-busting articles. For more information on a chance to write for The Poacher, please click on our Help page.
        </p>
            
        <h1 class="abtHead1"><b>Comments/Feedback:</b></h1>
        <p class="abtPar"> We take matters very seriously when a user voices their dislike or concerns that they might have. For any reason to contact <i>The Poacher</i> regarding said topics, please visit our Feedback page.</p>

        <p class="abtPar"><i>The Poacher</i> is available online (only) and is free of charge for all users.
        </p>
        <hr class="abtHr">
        <div id="abtDivAds">Placeholder Advertisement</div>
        <hr class="abtHr">
        <?php include('includes/footer.html'); ?>
    </body>
</html>

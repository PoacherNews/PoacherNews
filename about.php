<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/about.css">
</head>
<body>
    <?php 
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <h1 id="aboutHeader">About <span class="text-emphasis">The Poacher</span></h1>
        <h2 class="aboutSectionHeader">Who We Are</h2>        
        <p>
            <span class="text-emphasis">The Poacher</span> is the newest, worldly acclaimed satirical website created by five lonely students at the University of Missouri. Our publication entails a plethora of newly discovered ideas that gives users (as well as writers) a voice in topics such as sports, politics, entertainment, and local news. Our goal assures a humble journey of bringing a community of people together to laugh, read, share, and comment with another.
        </p>
        <h2 class="aboutSectionHeader">Our Content</h2>
        <p>
           The Poacher, otherwise known as <span class="text-emphasis">Poacher News</span>, has a standard of authenticity. Our content is and will not be duplicated or redistributed, nor shall we promote or allow plagiarism of anyone’s work or ideas. 
        </p>

        <h2 class="aboutSectionHeader">Our Platform</h2>
        <p>
            We think of our brand as tool to for representing respected as well as aspiring writers to provide users with gut-busting articles. For more information on a chance to write for The Poacher, please click on our <a href="help.php">Help page</a>.
        </p>
            
        <h2 class="aboutSectionHeader">Comments/Feedback</h2>
        <p>
            We take feedback very seriously, so when a user voices their dislike or concerns that they might have, we're here to listen. For any reason to contact <span class="text-emphasis">The Poacher</span> regarding said topics, please visit our <a href="feedback.php">Feedback page</a>.
        </p>

        <p><span class="text-emphasis">The Poacher</span> is available online (only) and is provided free of charge for all users.</p>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>

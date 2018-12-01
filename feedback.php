<?php
    include 'util/loginCheck.php';
    include 'util/emailForm.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/feedback.css">
</head>
<body>
    <?php 
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div class="mainContent">
        <h1 id="feedbackHeader">Feedback</h1>
        <p>
            The staff here at <span class="text-emphasis">The Poacher</span> really appreciate what our users think about our content. Let us know how our website is doing!
        </p>
        <p>
            For feedback or more information, please contact:<a id="feedbackContactLink" href="mailto:poachernews@gmail.com">poachernews@gmail.com</a>
        </p>
        <form name="contactform" class="formTable" method="post" action="util/emailForm.php">
            <label for="first_name">First Name *</label>
            <input class="feedbackInput" type="text" name="first_name" maxlength="50" size="30">
            <label for="last_name">Last Name *</label>
            <input class="feedbackInput" type="text" name="last_name" maxlength="50" size="30">
            <label for="email">Email Address *</label>
            <input class="feedbackInput" type="text" name="email" maxlength="80" size="30">
            <label for="comments">Comments *</label>
            <textarea class="feedbackInput" name="comments" maxlength="2000" cols="50" rows="6" id="comments"></textarea>
            <input class="feedbackSubmit" type="submit" value="Submit">  
        </form>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>

<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
<head>
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/feedback.css">
</head>
<body>
    <?php 
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <h1 id="feedbackHeader">Feedback</h1>
        <p>
            The staff here at <span class="text-emphasis">The Poacher</span> really appreciate what our users think about our content. Let us know how our website is doing!
        </p>
        <p>
            For feedback or more information, please contact:<a id="feedbackContactLink" href="mailto:poachernews@gmail.com">poachernews@gmail.com</a>
        </p>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>

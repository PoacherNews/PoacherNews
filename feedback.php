<?php
    include 'util/loginCheck.php';
?>



<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/feedback.css">
</head>
<style>
    @media all and (min-width: 600px){
            .mainContent {
                margin: 15px 10% 15px 10%;
                padding: 15px;
                border-left: 1px solid #83A8F0;
                border-right: 1px solid #83A8F0;
            }
        }

        @media all and (max-width: 599px){
            .mainContent {
                margin: 5px 10% 5px 10%;
                padding: 5px;
                border-left: 1px solid #83A8F0;
                border-right: 1px solid #83A8F0;
            }
        }
    .errorMessage {
                margin: 5px 0px 5px 0px;
                padding: 10px;
                border-radius: 2px;
                font-weight: bold;
                box-shadow: 0 0 0 1px #e0b4b4 inset;
                background-color: #fff6f6;
                color: #9f3a38;
            }
            #feedbackHeader {
                text-align: center;
                font-size: 2.5rem;
            }

            #feedbackContactLink {
                display: block;
                font-size: 1.3rem;
                font-weight: bold;
                padding-top: 5px;
            }

            .feedbackInput, select {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .feedbackSubmit {
                width: 100%;
                background-color: #83A8F0;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .feedbackSubmit:hover {
                background-color: #BDD4E7;
            }

            #comments {
                height: 30%;
                width: 100%;
            }
</style>
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
            For feedback or more information, please contact:<a id="feedbackContactLink" href="mailto:poachernews@gmail.com">poachernews@gmail.com or fill out the form below!</a>
        </p>
        <?php
            if (isset($error)) {
                echo "<p class='errorMessage'>$error</p>";
            }
        ?>
        <form name="contactform" class="formTable" method="post" action="/util/emailForm.php">
            <label for="first_name">First Name *</label >
            <input class="feedbackInput" type="text" name="first_name" maxlength="50" size="30" >
            <label for="last_name">Last Name *</label>
            <input class="feedbackInput" type="text" name="last_name" maxlength="50" size="30" >
            <label for="email">Email Address *</label>
            <input class="feedbackInput" type="text" name="email" maxlength="80" size="30" >
            <label for="comments">Comments *</label>
            <textarea class="feedbackInput" name="comments" maxlength="2000" cols="50" rows="6" id="comments" ></textarea>
            <input class="feedbackSubmit" type="submit" value="Submit">  
        </form>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>

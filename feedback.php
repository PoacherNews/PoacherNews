<?php
    include 'util/loginCheck.php';
    include 'util/emailForm.php';
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
        <form name="contactform" method="post" action="util/emailForm.php">
            <table width="450px">
            <tr>
            <td valign="top">
            <label for="first_name">First Name *</label>
            </td>
            <td valign="top">
            <input  type="text" name="first_name" maxlength="50" size="30">
            </td>
            </tr>
            <tr>
            <td valign="top">
            <label for="last_name">Last Name *</label>
            </td>
            <td valign="top">
            <input  type="text" name="last_name" maxlength="50" size="30">
            </td>
            </tr>
            <tr>
            <td valign="top">
            <label for="email">Email Address *</label>
            </td>
            <td valign="top">
            <input  type="text" name="email" maxlength="80" size="30">
            </td>
            </tr>
            <tr>
            <td valign="top">
            <label for="comments">Comments *</label>
            </td>
            <td valign="top">
            <textarea  name="comments" maxlength="1000" cols="25" rows="6"></textarea>
            </td>
            </tr>
            <tr>
            <td colspan="2" style="text-align:center">
            <input type="submit" value="Submit">  
            </td>
            </tr>
            </table>
        </form>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>

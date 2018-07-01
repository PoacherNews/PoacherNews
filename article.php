<?php 
    ob_start();
    session_start(); 
    include('util/db.php');
    include('util/articleUtils.php');
    include('util/userUtils.php');
    function redirectHome() {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/articlepage.css">
    </head>
    <body>
        <?php 
            include('includes/header.php');
            include('includes/nav.php');
            
            date_default_timezone_set('America/Chicago');
            $articleData = getArticleByID($_GET['articleid'], $db);
            if(!$articleData) {
                redirectHome();
            }
            if(!isset($_SESSION) || empty($_SESSION)) {
                $isAuthor = FALSE;
                $isAdmin = FALSE;
            } else {
                $isAuthor = ($articleData['UserID'] == $_SESSION['userid'] ? TRUE : FALSE);
                $isAdmin = ($_SESSION['usertype'] == "A" ? TRUE : FALSE);
            }
            $isDraft = ($articleData['IsDraft'] == 1 && $articleData['IsSubmitted'] == 0 ? TRUE : FALSE);
            $isPending = ($articleData['IsDraft'] == 1 && $articleData['IsSubmitted'] == 1 ? TRUE : FALSE);

            if($isDraft || $isPending) {
                if(!($isAuthor || $isAdmin)) { // Allow admins and the article's author to view drafts and their own pending articles
                    redirectHome();
                }
            } else { // Article is published, so increase view count
                increaseViewCount($articleData['ArticleID'], $db);
            }
            
            if($isDraft) { print "<div id=\"infoBanner\">DRAFT</div>"; }
            if($isPending) { print "<div id=\"infoBanner\">PENDING APPROVAL</div>"; }
        ?>

        
        <div class="articleColumns">
            <section id="articleColumn">
                <span class="articleDetails">
                    <span style="display: none" id="aid"><?php print $articleData['ArticleID'] ?></span> <!-- Hidden field to track article ID -->
                    <h1><?php print $articleData['Headline'] ?></h1>
                    <p>
                        <?php
                            $authorData = getAuthorByID($articleData['UserID'], $db);
                            print "Written by <a href=\"/profile.php?uid={$articleData['UserID']}\">{$authorData['FirstName']} {$authorData['LastName']}</a>";
                        ?>
                    </p>
                    <p id="pubDate">Published on <?php print date("l, F j Y g:i a", strtotime($articleData['PublishDate']))?></p>
                    <p id="pubDetails">
                        <?php print "<a href=\"section.php?Category={$articleData['Category']}\">{$articleData['Category']}</a>" ?>
                        &mdash;
                        <?php print "{$articleData['Views']} ".($articleData['Views'] === 1 ? "View" : "Views") ?>,
                        <?php
                            $numFaves = getNumFavorites($articleData['ArticleID'], $db);
                            print "{$numFaves} ".($numFaves === 1 ? "Favorite" : "Favorites");
                        ?>
                        <span class="rightIcons"">
                            <span id="rating">
                            <?php
                                function displayStars($val) {
                                    if($val < 0 || $val > 5) {
                                        return;
                                    }
                                    for($i=1; $i <= floor($val); $i++) {
                                        print '<i starNum="'.$i.'" id="fullStar" title="Click to rate '.$i.' star'.($i > 1 ? "s" : "").'" class="fas fa-star"></i>';
                                    }
                                    if(!(floor($val) == $val)) {
                                        if($val - floor($val) >= 0.5) {
                                            print '<i starNum="'.ceil($val).'" id="halfStar"  title="Click to rate '.ceil($val).' stars" class="fas fa-star-half-alt"></i>';
                                        } else { 
                                            print '<i starNum="'.$i.'" id="emptyStar"  title="Click to rate '.$i.' stars" class="far fa-star"></i>';
                                        }
                                    }
                                    for($i=ceil($val)+1; $i<=5; $i++) {
                                        print '<i starNum="'.$i.'" id="emptyStar"  title="Click to rate '.$i.' stars" class="far fa-star"></i>';
                                    }
                                }
                                $articleRating = getRatingByID($_GET['articleid'], $db);
                                if(isset($_SESSION['loggedin']) && !is_null(getArticleUserRating($_SESSION['userid'], $_GET['articleid'], $db))) {
                                    displayStars(getArticleUserRating($_SESSION['userid'], $_GET['articleid'], $db));
                                } else {
                                    displayStars($articleRating);
                                }
                                print number_format((float)$articleRating, 2, '.', '').'/5';                       
                            ?>
                            <script>
                                function restoreStar($elem) {
                                    if($elem.is("#halfStar")) {
                                        $elem.attr("class", "fas fa-star-half-alt");
                                    } else if($elem.is("#emptyStar")) {
                                        $elem.attr("class", "far fa-star");
                                    } else {
                                        $elem.attr("class", "fas fa-star");
                                    }
                                    $elem.css("color", "inherit");
                                }
                                $("#rating .fa-star,.fa-star-half-alt").hover(function() { 
                                    $(this).css("color", "gold");
                                    $(this).attr("class", "fas fa-star");
                                    $(this).prevAll().css("color", "gold");
                                    $(this).prevAll().attr("class", "fas fa-star");
                                }, function() {
                                    restoreStar($(this));
                                    $(this).prevAll().each(function() {
                                        restoreStar($(this));
                                    });
                                });
                                $("#rating .fa-star,.fa-star-half-alt").click(function() {
                                    $.get('util/articleHandler.php', {
                                        'request' : 'rate',
                                        'aid' : $("#aid").text(),
                                        'score' : $(this).attr('starNum')
                                    }).done(function(data) {
                                        location.reload();
                                    });
                                });
                            </script>
                            </span>
                            <?php
                                if(!isset($_SESSION['loggedin'])) {
                                    // TODO: Display this error only after they try to rate or favorite while not logged in
                                    print "<a href=\"login.php\">Log in</a> to favorite and rate articles";
                                } else {
                                    if(isFavorite($_SESSION['userid'], $articleData['ArticleID'], $db)) { // If it's favorited
                                        print '<i id="unfavorite" title="Click to Unfavorite" class="fas fa-heart"></i>';
                                    } else { // If it hasn't been favorited
                                        print '<i id="favorite" title="Click to Favorite" class="far fa-heart"></i>';
                                    }
                                }
                            ?>
                        </span>
                        <script>
                            $("#favorite").click(function() {
                                $.get('util/articleHandler.php', {
                                    'request' : "favorite",
                                    'aid' : $("#aid").text(),
                                }).done(function(data) {
                                    location.reload();
                                });
                            });
                            $("#unfavorite").click(function() {
                                $.get('util/articleHandler.php', {
                                    'request' : "unfavorite",
                                    'aid' : $("#aid").text(),
                                }).done(function(data) {
                                    location.reload();
                                });
                            });
                         </script>
                    </p>
                </span>
                <div class="articleImage">
                    <img src="<?php print $articleData['Image'] ?>"/>
                </div>
                <div class="articleBody">
                    <p><?php print nl2br($articleData['Body']); ?></p>
                </div>
            </section>
            <section id="articleAdColumn">
                <div class="ad"><p>AD</p></div>
                <div class="ad"><p>AD</p></div>
                <div class="ad"><p>AD</p></div>
            </section>
        </div>
        <div id="commentSection">
            <h1>Comments</h1>
            <hr/>
            <?php
                function drawComment($comment, $indent, $db) {
                    $indentMultiplier = 60; // Amount to indent each nested reply set in pixels.
                    $user = getUserById($comment['UserID'], $db);
                    return '<div class="comment" id="comment-'.$comment['CommentID'].'" style="margin-left: '.($indent * $indentMultiplier).'px">
                                <img src="/res/img/'.$user['ProfilePicture'].'"/>
                                <div class="commentText">
                                    <div class="commentTitle">
                                        <span class="commentAuthor">'.$user['FirstName'].' '.$user['LastName'].' <a class="username" href="/profile.php?uid='.$user['UserID'].'">('.$user['Username'].')</a></span> <span class="commentDate">'.date("l, F j Y g:i a", strtotime($comment['CommentDate'])).'</span>
                                    </div>
                                    <p class="commentBody">'.nl2br($comment['CommentText']).'</p>
                                    <div class="commentLinks">
                                        <a class="commentReplyLink" id="'.$comment['CommentID'].'">Reply</a> &dash; <a href="#comment-'.$comment['CommentID'].'">Link</a>
                                    </div>
                                </div>
                            </div>';
                }
                function getReplies($cid, $indentLevel, $db) {
                    $replies = getCommentReplies($_GET['articleid'], $cid, $db);
                    if(!empty($replies)) {
                        // post replies call this function iteratively
                        foreach($replies as $i=>$reply) {
                            print(drawComment($reply, $indentLevel+1, $db));
                            getReplies($reply['CommentID'], $indentLevel+1, $db);
                        }
                    }
                }
                $articleComments = getArticleRootComments($articleData['ArticleID'], $db);
                if(!$articleComments) {
                    print '<div id="noCommentsMessage">No comments yet.</div>';
                } else {
                foreach($articleComments as $comment) {
                    print(drawComment($comment, 0, $db));
                    getReplies($comment['CommentID'], 0, $db);
                }
                }
                if(!($isDraft || $isPending)) { // Only display a comment form if the article is published
                    print '<h2 id="commentFormHeader">Leave a comment</h2>
                        <form id="commentForm">
                            <textarea id="commentBody" placeholder="Enter a comment" required></textarea>
                            <input type="hidden" id="commentReplyToId"/>
                            <input type="button" id="commentClearButton" value="Clear"/>
                            <input type="button" id="commentResetButton" value="Reset"/>
                            <input type="submit" id="commentSubmitButton" value="Submit"/>
                            <div id="errorMessage">An error occured. Please try again later.</div>
                        </form>';
                }
            ?>
            <script>
                $("#commentClearButton").click(function() {
                    // Clears any text in the comment textarea field.
                    $("#commentBody").val('');
                });
                $("#commentResetButton").click(function() {
                    // Clears text in the comment textarea and also resets the form if it's set to be a reply
                    $("#commentBody").val('');
                    $("#commentFormHeader").text("Leave a comment");
                    $("#commentReplyToId").val('');
                });
                $(".commentReplyLink").click(function() {
                    $("#commentReplyToId").val($(this).attr('id'));
                    $("#commentFormHeader").text("Reply to "+$("#comment-"+$(this).attr('id')).find(".commentAuthor").text()); // Inform user that they're replying to a user
                    $('html, body').animate({ // Scroll the page to the reply form
                                scrollTop: ($('#commentFormHeader').offset().top - 100) // -100 to account for nav bar space
                    }, 500);
                });
                $("#commentForm").submit(function(event) {
                    $.post("util/articleHandler.php", {
                        aid : $("#aid").text(),
                        content : $("#commentBody").val(),
                        replyTo : $("#commentReplyToId").val()
                    }).done(function(data) {
                        if(data) { // Success. Refresh page to display new comment.
                            location.reload();
                        } else { // An error occured, display an error message.
                            $("#errorMessage").show();
                        }
                    });
                    return false; // Prevent default page reload.
                });
            </script>
        </div>
        <div id="further-reading">
            <h1>Further reading</h1>
            <hr/>
        </div>
        <div class="articleColumns">
            <?php
                $categoryArticles = array_chunk(getRelatedArticles($articleData['Category'], $articleData['ArticleID'], $db), 2);
                foreach ($categoryArticles as $key => $categorySet) {
                    print '<section class="threeColumnSection '.($key == 1 ? 'center' : '').'">';
                    foreach($categorySet as $key => $article) {
                    print   "<article>
                                <div class=\"articleBody\">
                                    <h1><a href=\"article.php?articleid={$article['ArticleId']}\">{$article['Headline']}</a></h1>
                                    <p>".substr($article['Body'], 0, 100)."...</p>
                                    <a class=\"continue-reading\" href=\"article.php?articleid={$article['ArticleId']}\">Continue Reading</a>
                                </div>
                            </article>";
                    }
                    print '</section>';
                }
            ?>
        </div>
        <?php include('includes/footer.html'); ?>
    </body>
</html>

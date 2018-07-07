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
<<<<<<< HEAD
                            $numFaves = getNumFavorites($articleData['ArticleID'], $db);
                            print "{$numFaves} ".($numFaves === 1 ? "Favorite" : "Favorites");
                            
                             if($isDraft) { // Create link back to editorpage with the text in correct format
                                 print "<form action=\"editorpage.php?articleid=                          {$articleData['ArticleID']}\" method=\"post\">
                                            <input type=\"submit\" id=\"edit-draft\" name=\"draft\" value=\"Edit\">
                                            <input type=\"hidden\" name=\"title\" value=\"{$articleData['Headline']}\">
                                            <input type=\"hidden\" name=\"body\" value=\"{$articleData['Body']}\">
                                        </form>";
                             }
                            
=======
                            $articleRating = getRatingByID($_GET['articleid'], $db);
                            print "Rated ".number_format((float)$articleRating, 2, '.', '')."/5 stars";
>>>>>>> ad14ab36acae92125154170c3054043884cc5755
                        ?>
                        <span class="rightIcons"">
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

                                if(!isset($_SESSION['loggedin'])) {
                                    print "<a href=\"login.php\">Log in</a> to favorite and rate articles";
                                } else if($isDraft || $isPending) {
                                    print "<span>Rating and favorites disabled until article is published.</span>";
                                } else {
                                    print '<span id="rating">';
                                       displayStars(getArticleUserRating($_SESSION['userid'], $_GET['articleid'], $db));
                                    print '</span>';
                                    if(isFavorite($_SESSION['userid'], $articleData['ArticleID'], $db)) { // If it's favorited
                                        print ' <i id="unfavorite" title="Click to Unfavorite" class="fas fa-heart"></i>';
                                    } else { // If it hasn't been favorited
                                        print ' <i id="favorite" title="Click to Favorite" class="far fa-heart"></i>';
                                    }
                                }
                            ?>
                        </span>
                        <script>
                            // Rating functionality
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

                            // Favorite icon functionality
                            $("#unfavorite").hover(function() {
                                $(this).css("color", "red");
                            }, function() {
                                $(this).css("color", "inherit");
                            });
                            $("#favorite").hover(function() {
                                $(this).css("color", "red");
                                $(this).attr("class", "fas fa-heart");
                            }, function() {
                                $(this).css("color", "inherit");
                                $(this).attr("class", "far fa-heart")
                            })
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
                if(!($isDraft || $isPending)) { // Only display a comment form if the article is published
                    if(!isset($_SESSION['loggedin'])) {
                        print '<div id="commentsMessage">Please log in to comment.</div>';
                    } else {
                        print '<form id="commentForm">
                                <textarea id="commentBody" placeholder="Add a comment..." required></textarea>
                                <input type="button" id="commentClearButton" value="Clear"/>
                                <input type="submit" id="commentSubmitButton" value="Submit"/>
                                <div class="errorMessage">An error occured. Please try again later.</div>
                            </form>';
                    }
                }
                function drawComment($comment, $indent, $db) {
                    $indentMultiplier = 60; // Amount to indent each nested reply set in pixels.
                    $user = getUserById($comment['UserID'], $db);
                    $replyFormHtml = '<form class="replyForm">
                                        <textarea name="content" class="replyBody" placeholder="Add a reply..." required></textarea>
                                        <input type="hidden" name="replyTo" value="'.$comment['CommentID'].'"/>
                                        <input type="hidden" name="aid" value="'.$_GET['articleid'].'"/>
                                        <input type="button" class="replyCancel" value="Cancel"/>
                                        <input type="submit" id="commentSubmitButton" value="Submit"/>
                                        <div class="errorMessage">An error occured. Please try again later.</div>
                                    </form>';
                    $commentHtml = '<div class="comment" id="comment-'.$comment['CommentID'].' cid="'.$comment['CommentID'].'" style="margin-left: '.($indent * $indentMultiplier).'px">
                                <img src="/res/img/'.$user['ProfilePicture'].'"/>
                                <div class="commentText">
                                    <div class="commentTitle">
                                        <span class="commentAuthor">'.$user['FirstName'].' '.$user['LastName'].' <a class="username" href="/profile.php?uid='.$user['UserID'].'">('.$user['Username'].')</a></span> <span class="commentDate">'.date("l, F j Y g:i a", strtotime($comment['CommentDate'])).'</span>
                                    </div>
                                    <p class="commentBody">'.nl2br($comment['CommentText']).'</p>
                                    <div class="commentLinks">
                                        <a class="commentReplyLink" id="'.$comment['CommentID'].'">Reply</a> &dash; <a href="#comment-'.$comment['CommentID'].'">Link</a>
                                    </div>
                                    '.(isset($_SESSION['loggedin']) ? $replyFormHtml : '').'
                                </div>
                            </div>';
                    return $commentHtml;
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
                if($isDraft || $isPending) {
                    print '<div id="commentsMessage">Comments disabled until article is published.</div>';
                } else {
                    $articleComments = getArticleRootComments($articleData['ArticleID'], $db);
                    if(!$articleComments) {
                        print '<div id="commentsMessage">No comments yet.</div>';
                    } else {
                        foreach($articleComments as $comment) {
                            print(drawComment($comment, 0, $db));
                            getReplies($comment['CommentID'], 0, $db);
                        }
                    }
                }
            ?>
            <script>
                $("#commentClearButton").click(function() {
                    // Clears any text in the comment textarea field.
                    $("#commentBody").val('');
                });
                $(".replyCancel").click(function() {
                    $(this).closest('.replyForm').slideUp();
                });
                $(".commentReplyLink").click(function() {
                    $(this).parent().siblings('.replyForm').slideToggle();
                });
                $(".replyForm").submit(function(event) {
                    $.post("util/articleHandler.php", $(this).serialize())
                        .done(function(data) {
                            location.reload();
                        }).fail(function(data) {
                            $(".replyForm .errorMessage").show();
                        });
                    
                    return false; // Prevent default page reload.
                });
                $("#commentForm").submit(function(event) {
                    $.post("util/articleHandler.php", {
                        aid : $("#aid").text(),
                        content : $("#commentBody").val(),
                        replyTo : null
                    }).done(function(data) {
                        location.reload();
                    }).fail(function(data) {
                        $("#commentForm .errorMessage").show();
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

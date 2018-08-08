<?php
    include 'util/loginCheck.php';
    include 'util/db.php';
    include 'util/userUtils.php';
    include 'util/articleUtils.php';

    if(empty($_GET['uid']) || !userExists($_GET['uid'], $db)) {
        header('Location: index.php');
    }
    $userDetails = getUserById($_GET['uid'], $db);
    if(getUserTimezone($_SESSION['userid'], $db) != NULL) {
        date_default_timezone_set(getUserTimezone($_SESSION['userid'], $db));
    } else {
        date_default_timezone_set('US/CENTRAL');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" href="res/css/profile.css">
</head>

<body>
    <?php
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <div id="profileContainer">
            <div id="left-sidebar">
                <img class="avatar" src="/res/img/<?php print($userDetails['ProfilePicture']); ?>">
                <span class="userRealName"><?php print($userDetails['FirstName'].' '.$userDetails['LastName']); ?></span>
                <span class="userName">(<?php print($userDetails['Username']); ?>)</span>
            </div>
            <div id="right-sidebar">
                <span class="userType">
                    <?php
                        switch($userDetails['Usertype']) {
                            case 'U':
                                print "User";
                                break;
                            case 'W':
                                print "Writer";
                                break;
                            case 'A':
                                print "Admin";
                                break;
                        }
                    ?>
                </span>
                <?php
                    if($_SESSION['userid'] == $_GET['uid']) { // If the user logged in is viewing their own profile
                        print('<a class="editText" href="settings.php">Edit Profile</a>');
                    }
                ?>
                <div class="userStats">
                    <span class="statsVal"><?php print(getNumUserBookmarks($_GET['uid'], $db)); ?></span>
                    <span class="statsVal"><?php print(getNumUserComments($_GET['uid'], $db)); ?></span>
                    <span class="statsVal"><?php print(getNumUserArticlesWritten($_GET['uid'], $db)); ?></span>
                    <span class="statsVal"><?php print(getNumUserRatings($_GET['uid'], $db)); ?></span>
                    <span class="statsText">Bookmarked Articles</span>
                    <span class="statsText">Comments</span>
                    <span class="statsText">Articles Written</span>
                    <span class="statsText">Ratings</span>
                </div>
                <div class="profileNav">
                    <div class="navItem active" datatab="about">About</div>
                    <div class="navItem" datatab="bookmarks">Bookmarks</div>
                    <div class="navItem" datatab="comments">Comments</div>
                    <div class="navItem" datatab="history">Articles</div>
                    <div class="navBody">
                        <div id="about" class="tabcontent active">
                            <div class="aboutSectionHeader">Bio</div>
                                <p><?php
                                    print(is_null($userDetails['Bio']) ? "No bio set yet." : $userDetails['Bio']);
                                ?></p>
                            <div class="aboutSectionHeader">Location</div> <!-- 7/10 Location Support not yet implemented in site -->
                                 <p><?php
                                     print(is_null($userDetails['Location']) ? "No location set yet." : $userDetails['Location']);
                                 ?></p>
                            <div class="aboutSectionHeader">Time Zone</div>
                                <p><?php
                                    switch($userDetails['TimeZone']) {
                                        case "HAST":
                                            print "Hawaii-Aleutian Time (UTC−10:00)";
                                            break;
                                        case "AKST":
                                            print "Alaska Time (UTC−09:00)";
                                            break;
                                        case "PST":
                                            print "Pacific Time (UTC−08:00)";
                                            break;
                                        case "MST":
                                            print "Mountain Time (UTC−07:00)";
                                            break;
                                        case "CST":
                                            print "Central Time (UTC−06:00)";
                                            break;
                                        case "EST":
                                            print "Eastern Time (UTC−05:00)";
                                            break;
                                        case NULL:
                                            print "No timezone set yet.";
                                            break;
                                    }
                                ?></p>
                        </div>
                        <div id="bookmarks" class="tabcontent">
                            <ul>
                            <?php
                                $bookmarks = getUserBookmarks($_GET['uid'], NULL, NULL, $db);
                                if(empty($bookmarks)) {
                                    print "<span class='tabNotice'>No bookmarks yet.</span>";
                                } else {
                                    foreach($bookmarks as $bookmark) {
                                        $author = getUserById($bookmark['UserID'], $db);
                                        print "<li>
                                                <div><a href='article.php?articleid={$bookmark['ArticleID']}'>{$bookmark['Headline']}</a></div>
                                                <div><a href='profile.php?uid={$author['UserID']}'>{$author['FirstName']} {$author['LastName']}</a> - ".date("l, F j Y g:i a", strtotime($bookmark['PublishDate']))."</div>
                                              </li>";
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                        <div id="comments" class="tabcontent">
                            <ul>
                                <?php
                                    $comments = getUserComments($_GET['uid'], NULL, $db);
                                    if(empty($comments)) {
                                        print "<span class='tabNotice'>No comments yet.</span>";
                                    } else {
                                        foreach($comments as $comment) {
                                            $article = getArticleByID($comment['ArticleID'], $db);
                                            print "<li>
                                                    <div>\"{$comment['CommentText']}\"</div>
                                                    <div><a href='article.php?articleid={$comment['ArticleID']}'>{$article['Headline']}</a></div>
                                                    <div>Commented on ".date('l, F j Y g:i a', strtotime($comment['CommentDate']))."</div>
                                                  </li>";
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                        <div id="history" class="tabcontent">
                            <?php
                                $userArticles = getArticlesByUserID($_GET['uid'], $db);
                                $publishedArticles = array();
                                $draftArticles = array();
                                $pendingArticles = array();
                                foreach($userArticles as $a) {
                                    if($a['IsDraft'] == 1 && $a['IsSubmitted'] == 0) {
                                        $draftArticles[] = $a;
                                        continue;
                                    }
                                    if($a['IsDraft'] == 1 && $a['IsSubmitted'] == 1) {
                                        $pendingArticles[] = $a;
                                        continue;
                                    }
                                    if($a['IsDraft'] == 0 && $a['IsSubmitted'] == 1) {
                                        $publishedArticles[] = $a;
                                        continue;
                                    }
                                }
                            ?>
                            <div class="historySectionHeader">Published Articles</div>
                            <p>
                                <ul>
                                    <?php
                                        if(empty($publishedArticles)) {
                                            print "No articles published yet.";
                                        }
                                        foreach($publishedArticles as $article) {
                                            print "<li>
                                                <div><a href='article.php?articleid={$article['ArticleID']}'>{$article['Headline']}</a></div>
                                                <div>".date("l, F j Y g:i a", strtotime($article['PublishDate']))."</div>
                                              </li>";
                                        }
                                     ?>
                                </ul>
                            </p>
                            <?php if($_SESSION['userid'] == $_GET['uid']) {
                                print '<div class="historySectionHeader">Drafts</div>';
                                print '<ul>';
                                if(empty($draftArticles)) {
                                            print "No drafts yet.";
                                        }
                                        foreach($draftArticles as $article) {
                                            print "<li>
                                                <div><a href='article.php?articleid={$article['ArticleID']}'>{$article['Headline']}</a></div>
                                                <div>".date("l, F j Y g:i a", strtotime($article['PublishDate']))."</div>
                                              </li>";
                                        }
                                print '</ul>';
                                print '<div class="historySectionHeader">Pending Articles</div>';
                                print '<ul>';
                                if(empty($pendingArticles)) {
                                            print "No pending articles currently.";
                                        }
                                        foreach($pendingArticles as $article) {
                                            print "<li>
                                                <div><a href='article.php?articleid={$article['ArticleID']}'>{$article['Headline']}</a></div>
                                                <div>".date("l, F j Y g:i a", strtotime($article['PublishDate']))."</div>
                                              </li>";
                                        }
                                print '</ul>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(".navItem").click(function() {
                $(".navItem").removeClass("active");
                $(this).addClass("active");
                $(".tabcontent").removeClass("active");
                $("#"+$(this).attr('datatab')).addClass("active");
            })
        </script>
        
        <!--// 7/10 CS
            // Disabling the footer for now until I rebuild the site to use CSS Grid.
            // The footer jumps around as tab size changes and it's very jarring to look at. -->
    </div>
    <?php include 'includes/footer.html'; ?>
</body>
</html>
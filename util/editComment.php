<?php
// TODO:
// Add error for empty field when submitting - also do the same for editArticle.php
// Changing Users/Writers to Admins permissions
// Changing Admins permissions

include 'loginCheck.php';
include 'userUtils.php';

// quit if not an admin or not logged in
if (!$loggedin || !($_SESSION['usertype'] == 'A'))
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be an administrator.";
    echo '<meta http-equiv="refresh" content="1; url=/index.php">';
    exit;
}

include_once ('db.php');

function getCommentData($db)
{
    if (!isset($_GET['CommentID']))
    {
        echo "Error: No user specified.";
        return;
    }
 
   // Connect to the database
//    require_once ('util/db.php');
    // prepare statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT CommentID, ReplyToID, UserID, ArticleID, CommentDate, CommentText FROM Comment WHERE CommentID =?"))
    {
        echo "Error preparing statement: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // bind parameters
    if (!$stmt->bind_param('i', $_GET['CommentID']))
    {
        echo "Error binding parameters: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // execute statement
    if (!$stmt->execute())
    {
        echo "Error executing statement: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // get results from query
    if (!$result = $stmt->get_result())
    {
        echo "Error getting result: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    /*
    if ($result->num_rows != 1)
    {
        echo "Username incorrect.";
        return false;
    }
    */
    $row = $result->fetch_assoc();
    $result->free();
    $stmt->close();
    return $row;
}
// get user data as an array
$data = getCommentData($db);
if (!isset($data) || !$data)
    die("UserID incorrect or database error.");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	   <?php include '../includes/globalHead.html' ?>
        <link rel="stylesheet" href="/res/css/tools.css">
        <title><?php echo $data['Username']; ?> | Edit User</title>
        
        <script>
$(document).ready(function(){
    $("#phide").click(function(){
        $(".ff").hide();
        $(".dd").show();
        $(".aa").hide();
        $(".bb").show();
        $(".ss").show();
        
        $(".yy").hide();
        $(".rr").hide();  
        
        $("#r2hide").hide();
        $("#r2show").hide();
        $("#rhide").show();
        $("#rshow").show(); 
        
    });
    $("#pshow").click(function(){
        $(".ff").show();
        $(".dd").hide();
        $(".aa").show();
        $(".bb").hide();
        $(".yy").show();
        $(".ss").hide();
        $(".hh").hide();
        
        $("#rhide").hide();
        $("#rshow").hide();         
        $("#r2hide").show();
        $("#r2show").show();        

    });
    
        $("#ihide").click(function(){
        $(".aa").hide();
        $(".bb").show();
    });
    $("#ishow").click(function(){
        $(".aa").show();
        $(".bb").hide();
    });   
    
    $("#rhide").click(function(){
        $(".hh").hide();
        $(".ss").show();
        $(".yy").hide();
        
    });
    $("#rshow").click(function(){
        $(".hh").show();
        //$(".ss").hide();
    });    
    
        $("#r2hide").click(function(){
        $(".rr").hide();
    });
    $("#r2show").click(function(){
        $(".rr").show();
        //$(".ss").hide();
    });    
});
        </script>
        
        <style>
        .ff
            {
                display: none;
            }
                        
        .aa
            {
                display: none;
            }    
            
        .hh
            {
                display: none;
            } 
                .rr
            {
                display: none;
            }     
        .yy
            {
                display: none;
                                background-color: antiquewhite;

            }      
                #r2hide, #r2show
            {
                display: none;
            }                 
            .dd {
                background-color: cornsilk;
            }            
            .initial {
                background-color: aliceblue;
            }
            .rep {
                background-color: antiquewhite;
            }
            .ss {
                background-color: bisque;
            }
                        .ren {
                background-color: cornsilk;
            }       
            
        </style>
        
    </head>
    
    <body>
        <?php
	    	include '../includes/header.php';
            include '../includes/nav.php';
        ?>    

        <div class="pageContent">

            <div class="nav">
                <?php
                    $toolsTab = 'commentmanagement';
                    include '../includes/toolsNav.php';
                ?>
            </div>
        
            <h1>Edit Comment &#8216;<?php
                echo "<a href='/article.php?articleid={$data['ArticleID']}#comment-{$data['CommentID']}'>"; 
				echo $data['CommentText'];
				echo "</a>"; ?>&#8217;</h1>

            <table>
                <thead>
                    <tr>
                        <th>CommentID</th>
                        <th>ReplyToID</th>
                        <th>UserID</th>
                        <th>ArticleID</th>
                        <th>CommentDate</th>
                        <th>CommentText</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['CommentID']; ?></td>
                        <td><?php echo $data['ReplyToID']; ?></td>
                        <td><?php echo $data['UserID']; ?></td>
                        <td><?php echo $data['ArticleID']; ?></td>
                        <td><?php echo $data['CommentDate']; ?></td>
                        <td><?php echo $data['CommentText']; ?></td>
                    </tr>
                </tbody>
            </table>
            <h2>Comment Options</h2>
            
<h3>Parent</h3>   
<button id="phide">Collapse</button>
<button id="pshow">Expand</button>
<?php
        date_default_timezone_set('America/Chicago');

        if($data['ReplyToID'] == NULL) {
            $indentLevel = -1;
        }

    	if($data['ReplyToID'] === null) 
        {
                print "Comment is a parent";   
        }
        else 
        {

            $replies = getCommentParents($data['ArticleID'], $data['ReplyToID'], $db);

            foreach ($replies as $key => $r) 
            {
                if($r['CommentText'] != NULL)
                {
                    echo "<div class='dd'>";
                        print(drawComment($r, $indentLevel, $db));
                    echo "</div>";

                    echo "<div class='ff'>";
                        getParents($data['ReplyToID'], $indentLevel, $db);
                    echo "</div>";
                }
            }

        }
        
?>              
            
<h3>Initialized</h3>            
<?php 
          
            echo "<div class='initial'>";
            // $indentLevel not being set to $counter??
            echo "<div class='bb'>";
            print(drawComment($data, $indentLevel+1, $db));
            echo "</div>";
            
            echo "<div class='aa'>";
            print(drawComment($data, $counter+1, $db));
            echo "</div>";
            echo "</div>";

            /*
        echo $data['CommentText'];
        */
?>
            
<h3>Replies</h3>
<button id="rhide">Collapse</button>
<button id="rshow">Expand</button>   
            
<button id="r2hide">Collapse</button>
<button id="r2show">Expand</button>    
<?php 
            // Check for multiple replies case CommentID 19
            // Initial multiples aren't printed
            $replies = getCommentReplies($data['ArticleID'], $data['CommentID'], $db);

            foreach ($replies as $key => $s) 
            {
                if($s['ReplyToID'] != NULL)
                {
                    echo "<div class='ss'>";
                        print(drawComment($s, $store+2, $db));
                    echo "</div>";
                    
                    echo "<div class='yy'>";
                        print(drawComment($s, $f+2, $db));
                    echo "</div>";
                    
                    echo "<div class='hh'>";
                        getReplies($s['CommentID'], $store+2, $db);
                    echo "</div>";
                    
                    echo "<div class='rr'>";
                        getReplies($s['CommentID'], $f+2, $db);
                    echo "</div>";
                    

                }
            }
?>   

<?php
                function drawComment($comment, $indent, $db) {
                    $indentMultiplier = 60; // Amount to indent each nested reply set in pixels.
                    $user = getUserById($comment['UserID'], $db);

                    $displayName = $user['FirstName'].' '.$user['LastName']; // First and last name by default
                    $userLink = '<a class="username" href="/profile.php?uid='.$user['UserID'].'">('.$user['Username'].')</a>';
                    if(is_null($comment['UserID'])) { // Determine if the account or the comment was deleted.
                        $commentBody = '<span class="deletedComment">Account deleted.</span>';
                    } else if(is_null($comment['CommentText'])) {
                        $commentBody = '<span class="deletedComment">Comment deleted.</span>';
                    } else {
                        $commentBody = nl2br($comment['CommentText']);
                    }
                    $commentHtml =
                        '<div class="comment" id="comment-'.$comment['CommentID'].'" cid="'.$comment['CommentID'].'" style="margin-left: '.($indent * $indentMultiplier).'px">'.
                            (is_null($comment['UserID']) ? '<span style="padding: 7px"></span>' : '<img src="/res/img/'.$user['ProfilePicture'].'"/>')
                            .'<div class="commentText">
                                    <div class="commentTitle">
                                        <span class="commentAuthor">'.(is_null($comment['UserID']) ? 'Deleted Account' : $displayName).' '.(is_null($comment['UserID']) ? '' : $userLink).'</span> <span class="commentDate">'.date("l, F j Y g:i a", strtotime($comment['CommentDate'])).'</span>
                                    </div>
                                    <p class="commentBody">'.$commentBody.'</p>
                                    
                                    <!--
                                    <div class="commentEditInputs">
                                        <textarea>'.$commentBody.'</textarea>
                                        <input type="button" class="editCancel" value="Cancel"/>
                                        <input type="button" class="editSave" value="Save"/>
                                    </div>
                                    -->
                                    
                                    <div class="commentLinks">
                                        <a class="commentReplyLink" id="'.$comment['CommentID'].'">Reply</a>
                                        &dash;
                                        <a href="#comment-'.$comment['CommentID'].'">Link</a>
                                        '.($_SESSION['userid'] == $comment['UserID'] && !is_null($comment['CommentText']) ? '&dash; <a class="commentEditLink">Edit</a> &dash; <a class="commentDeleteLink">Delete</a>' : '').'
                                        '.($comment['Edited'] == TRUE ? '(edited)' : '').'
                                    </div>
                                    '.(isset($_SESSION['loggedin']) ? $replyFormHtml : '').'
                                </div>
                            </div>';
                    return $commentHtml;
                }
                function getReplies($cid, $indentLevel, $db) {
                    global $data;
                    $replies = getCommentReplies($data['ArticleID'], $cid, $db);
                    
                    if(!empty($replies)) {
                        // post replies call this function iteratively
                        foreach($replies as $i=>$reply) {
                            print(drawComment($reply, $indentLevel+1, $db));
                            getReplies($reply['CommentID'], $indentLevel+1, $db);
                        }
                        global $store;
                        if($reply['ReplyToID'] == $data['CommentID'])
                        {
                            $store = $counter;
                        }
                            global $counter;
                            $indentLevel = $counter;
                            $counter++;
                    }
                }
            

                function getParents($cid, $indentLevel, $db) {
                    global $data;
                    $replies = getCommentParents($data['ArticleID'], $cid, $db);

                    global $counter;
                    global $f;
                    $counter = 0;
                    if(!empty($replies)) {
                        //print $indentLevel;
                        // post replies call this function iteratively
                        foreach($replies as $i=>$reply) {
                            (drawComment($reply, $indentLevel, $db));
                                if($reply['ReplyToID'] != NULL) {
                                    getParents($reply['ReplyToID'], $indentLevel, $db);
                                    $counter++;
                                }
                                $indentLevel = $counter;
                            $f =$counter;
                        }
                    }    
                    if($reply['CommentID'] == $data['ReplyToID'])
                    {
                            echo "<div class='ren'>";
                    }
                    print(drawComment($reply, $indentLevel, $db));
                    if($reply['CommentID'] == $data['ReplyToID'])
                    {
                            echo "</div>";
                    }
                }
?>
        </div>
        
        <?php include '../includes/footer.html'; ?>
    </body>
</html>

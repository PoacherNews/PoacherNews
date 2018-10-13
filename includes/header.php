<header id="mainHeader">
    <div class="headerContainer">
        <div id="left">
            <div class="srcIcn">
                <a>Search</a>
                <i class="fa fa-search"></i>
            </div>
        </div>
        <div id="center">
            <a href="/index.php"><h1>The Poacher</h1></a>
        </div>
        <div id="right">
            <?php
                if(!empty($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                    print "<span class=\"username\"><a href='/profile.php?uid={$_SESSION['userid']}'>{$_SESSION['username']}</a> <i class=\"fa fa-caret-down\"></i>
                            <div class='uname-content-wrapper'>
                                <div class=\"username-content\">
                                    <p>Logged in as <b>{$_SESSION['username']}</b></p>
                                    <hr>
                                    <a href=\"/profile.php?uid={$_SESSION['userid']}\">Your Profile</a>";
                                    if($_SESSION['usertype'] == 'W' || $_SESSION['usertype'] == 'A') {
                                        print "<a href=\"/tools.php\">Tools</a>";
                                    }
                    print "         <a href=\"/help.php\">Help</a>
                                    <a href=\"/settings.php\">Settings</a>
                                    <a href=\"/logout.php\">Log Out</a>
                                </div>
                            </div>
                        </span>";
                } else {
                    print "<span class=\"lgnLink\"><a style='color: inherit; text-decoration: none' href=\"/login.php\">Have an account? <b>Log In<i class=\"fa fa-caret-down\"></i></b></a> 
                                <div class=\"lgnDrpdwn-content\">
                                    <p>Log in here!</p>
                                    <form action=\"/util/handleLogin.php\" method=\"POST\">
                                        <input type=\"text\" id=\"username\" name=\"username\"
                                        placeholder=\"Username\"><br>
                                        <input type=\"password\" id=\"password\" name=\"password\"
                                        placeholder=\"Password\"><br>
                                        <input type=\"submit\" name=\"submit\" value=\"Log In\">
                                    </form>
                                    <hr>
                                    <p>Need an account?</p>
                                    <button>
                                        <a href=\"/createUser.php\">Sign Up</a>
                                    </button>
                                </div>
                            </span>";
                }
            ?>
        </div>
    </div>
</header>
<script>
    $(document).ready(function() {
       $(".srcIcn").click(function() {
           $("#searchbar").slideToggle("fast");
           $("input:text").show(function() { //clears text if user withdrawls from searching
               $(this).val('');
           });
           if($("#drpdwnNav").is(":visible")) {
                $('#drpdwnNav').slideToggle("fast");
                $('#navbar').slideToggle("fast");
            }
       });
    });
</script>
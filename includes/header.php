<header class="header">
    <div class="hdrflxr">
        <div id="hdrlft">
            <div class="hdrlftFlxr">
                <div id="drpdwn">
                    <div id="drpdwnIcon"></div>
                    <div id="drpdwnIcon"></div>
                    <div id="drpdwnIcon"></div>
                </div>
            </div>
            <div class="hdrlftFlxr">
                <!-- 
                <a><i class="fa fa-search"></i></a>
                <input type="text" id="srcip" placeholder="What are you looking for?" name="searchBar">
                -->
                
            </div>
        </div>
        <div id="hdrmid">
            <div class="hdrctr">
                <a href="/index.php"><img class="hdrLogo" src="/res/img/PoacherLogo.png"></a>
                <span class="hdrTitle"><a class="hdrA" href="/index.php">The Poacher</a></span>	
            </div>
        </div>
        <div id="hdrrt">
            <?php
                if($_SESSION['loggedin']) {
                    print "<span class=\"username\">{$_SESSION['username']}
                                <div class=\"username-content\">
                                    <p>Logged in as <b>{$_SESSION['username']}</b></p>
                                    <hr>
                                    <a href=\"/profile.php\">Your Profile</a>
                                    <a href=\"/help.php\">Help</a>
                                    <a href=\"/settings.php\">Settings</a>
                                    <a href=\"/logout.php\">Log Out</a>
                                </div>
                        </span>";
                } else {
                    print "<span class=\"lgnLink\">Need an account?<b> Log In</b>
                                <div class=\"lgnDrpdwn-content\">
                                    <p>Login in here!</p>
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
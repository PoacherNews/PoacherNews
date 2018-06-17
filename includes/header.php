<header class="header">
    <div class="hdrflxr">
        <div id="hdrlft">
			<div class="hdrlftFlxr">
				<div id="drpdwn">
					<div id="drpdwnIcon"></div>
					<div id="drpdwnIcon"></div>
					<div id="drpdwnIcon"></div>
				</div>
				<div class="srcIcn">
					<a>Search</a>
					<i class="fa fa-search"></i>
				</div>
			</div>
        </div>
       <!-- <div id="hdrmid"> -->
            <div class="hdrctr">
                <a href="/index.php"><img class="hdrLogo" src="/res/img/PoacherLogo.png"></a>
                <span class="hdrTitle"><a class="hdrA" href="/index.php">The Poacher</a></span>	
            </div>
       <!-- </div> -->
        <!-- <div id="hdrrt"> -->
            <?php
                if($_SESSION['loggedin']) {
                    print "<span class=\"username\">{$_SESSION['username']}
                                <div class=\"username-content\">
                                    <p>Logged in as <b>{$_SESSION['username']}</b></p>
                                    <hr>
                                    <a href=\"/profile.php?Username=".$_SESSION['username']."\">Your Profile</a>
                                    <a href=\"/help.php\">Help</a>
                                    <a href=\"/settings.php\">Settings</a>
                                    <a href=\"/logout.php\">Log Out</a>
                                </div>
                        </span>";
                } else {
                    print "<span onclick=\"clickLgnLink()\" class=\"lgnLink\">Have an account? <b>Log In<i class=\"fa fa-caret-down\"></i></b> 
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
            
       <!-- </div>-->
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
	
	/*
	function clickLgnLink() {
        if($('.lgnDrpdwn-content').is(':hidden')) {
            $('.lgnDrpdwn-content').show();
        } else if($('.lgnDrpdwn-content').is(':visible')) {
            $('.lgnDrpdwn-content').hide();
        }
    }
    */
    
</script>

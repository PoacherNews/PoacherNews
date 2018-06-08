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
                <!--href="/search.php" -->
                <a><i class="fa fa-search"></i></a>
                <input type="text" id="srcip" placeholder="What are you looking for?" name="searchBar">
            </div>
        </div>
        <div id="hdrmid">
            <div class="hdrctr">
                <a href="/index.php"><img class="hdrLogo" src="/res/img/PoacherLogo.png"></a>
                <span class="hdrTitle"><a class="hdrA" href="/index.php">The Poacher</a></span>	
            </div>
        </div>
        <div id="hdrrt">
            <span class="lgnLink">Need an account?<b> Log In</b>
                <div class="lgnDrpdwn-content">
                    <p>Login in here!</p>
                    <form action="/util/handleLogin.php" method="POST">
                        <input type="text" id="username" name="username" placeholder="Username"><br>
                        <input type="password" id="password" name="password" placeholder="Password"><br>
                        <input type="submit" name="submit" value="Submit">
                    </form>
                    <hr>
                    <button>
                        <a href="/createUser.php">Sign Up</a>
                    </button>
                </div>
            </span>
            
            <?php
                if($_SESSION['loggedin']) {
                    print "<a href=\"/logout.php\"><i class=\"fa fa-sign-out\"></i></a>";
                    print "<a class=\"username\" href=\"/userpage.php\">{$_SESSION['username']}</a>"; //TODO: Link to user's userpage 
                }
            ?>
            
        </div>
    </div>
</header>
<script>
    function drpdwnLgn() {
        var x = document.getElementById("lgnDrpdwn");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
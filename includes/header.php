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
            <div class="hdrrtFlxr">
               

                <a href="/login.php"><i class="fa fa-user-circle"></i></a>
                

                <div class="hdrrtFlxc">
                    <a href="/login.php">
                        <div class="hdrrtLgn">Sign In</div>
                    </a>
                </div>
                <div class="hdrrtFlxc">
                    <a href="/createUser.php">
                        <div class="hdrrtSgnUp">Sign Up</div>
                    </a>
                </div>
            </div>
            
        
            <?php
                if($_SESSION['loggedin']) {
                    print "<a href=\"/logout.php\"><i class=\"fa fa-sign-out\"></i></a>";
                    print "<a class=\"username\" href=\"/userpage.php\">{$_SESSION['username']}</a>"; //TODO: Link to user's userpage 
                }
            ?>
            
        </div>
    </div>
</header>
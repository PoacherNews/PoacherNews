<div id="navbar">
			<a href="/section.php?Category=Politics">Politics</a>
			<a href="/section.php?Category=Sports">Sports</a>
			<a href="/section.php?Category=Entertainment">Entertainment</a>
			<a href="/section.php?Category=Video">Video</a>
			<a href="/section.php?Category=Local">Local</a>
			<a href="/section.php?Category=Opinion">Opinion</a>
		</div>
		<!-- Drop Down NAV -->
		<div id="drpdwnNav">
            <div class="drpdwnFlxr">
                <div class="drpdwnFlxc">
                    <!--TODO-->
                    <h2 class="drpdwnCont">Politics</h2>
                    <ul>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                    </ul>
                </div>
                <div class="drpdwnFlxc">
                    <!--TODO-->
                    <h2 class="drpdwnCont">Sports</h2>
                    <ul>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                    </ul>
                </div>
                <div class="drpdwnFlxc">
                    <!--TODO-->
                    <h2 class="drpdwnCont">Entertainment</h2>
                    <ul>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                    </ul>
                </div>
            </div>
			
            <div class="drpdwnFlxr">
                <div class="drpdwnFlxc">
                    <!--TODO-->
                    <h2 class="drpdwnCont">Video</h2>
                    <ul>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                    </ul>
                </div>
                <div class="drpdwnFlxc">
                    <!--TODO-->
                    <h2 class="drpdwnCont">Local</h2>
                    <ul>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                    </ul>
                </div>
                <div class="drpdwnFlxc">
                    <!--TODO-->
                    <h2 class="drpdwnCont">Opinion</h2>
                    <ul>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                        <li><a href="#" class="drpdwnLink">Placeholder</a></li>
                    </ul>
                </div>
            </div>
        </div>
		
        <script>
			
			//DROP DOWN NAV
            
            $(document).ready(function(){
               $("#drpdwn").click(function(){
                   if($('#drpdwnNav').is(':hidden')) {
                       $("#navbar").slideUp("fast");
                       $('#drpdwnNav').slideDown("fast");
                       document.getElementById("hpBody").style.overflow = "hidden";
                   } else if($('#drpdwnNav').is(':visible')) {
                       $("#navbar").slideDown("fast");
                       $('#drpdwnNav').slideUp("fast");
                       document.getElementById("hpBody").style.overflow = "scroll";
                   }
               });
                

            });
			
            
            
			
			//STICKY NAV BAR
			window.onscroll = function() {stickyNav()};
			var navbar = document.getElementById("navbar");
			var sticky = navbar.offsetTop;
			
			
			function stickyNav() {
  				if (window.pageYOffset >= sticky) {
    				navbar.classList.add("sticky");
  				} else {
    				navbar.classList.remove("sticky");
  				}
			}
			
			//SLIDE SEARCHBAR
			/*
			$('.fa-search').on('hover', function(){
    			$('#fade-in').toggleClass('show');
			});
			*/
			$(document).ready(function() {
				$(".fa-search").hover(function () {
					$("#fade-in").toggleClass("show");	
				});
			});
			
			
        </script>
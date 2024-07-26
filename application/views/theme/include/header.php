
	<!-- START LOGO WITH CONTACT -->
	<section class="logo-contact">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="single-top-contact">
						<a href="mailto:<?php echo config_item('email');?>">						
						<i class="fa fa-envelope"></i>
						<h4 ><?php echo config_item('email');?></h4>	
						</a>	
					</div>
				</div><!--- END COL -->	
				<div class="col-lg-1 col-md-4 col-sm-6 col-xs-12"></div>

				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-left:69px;">
					
				</div><!--- END COL -->	
				<div class="col-lg-1 col-md-4 col-sm-6 col-xs-12"></div>

			
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-left:25px;">
					<div class="top_social_profile">
						<ul>
							<li><a target="_blank" href="<?php echo config_item('facebook');?>" class="top_f_facebook"><i class="fa fa-facebook" title="Facebook"></i></a></li>
							<li><a target="_blank" href="<?php echo config_item('tweeter');?>" class="top_f_twitter"><i class="fa fa-twitter" title="Twitter"></i></a></li>
							<li><a target="_blank" href="<?php echo config_item('instagram');?>" class="top_f_instagram"><i class="fa fa-instagram" title="Instagram"></i></a></li>
							<li><a target="_blank" href="<?php echo config_item('linkdin');?>" class="top_f_linkedin"><i class="fa fa-linkedin" title="LinkedIn"></i></a></li>
						</ul>
					</div>
				</div><!--- END COL -->						
			</div><!--- END ROW -->
		</div><!--- END CONTAINER -->
	</section>
	<!-- END LOGO WITH CONTACT -->
	
	<!-- START NAVBAR -->  
	<div id="navigation" class="navbar-light bg-faded site-navigation menu_dropdown">		
		<div class="container">
			<div class="row">
				<div class="col-lg-2 col-md-3 col-sm-4">
					<div class="site-logo">
						<a class="navbar-logo" href="<?php echo base_url()?>Site/index">
						<img src="<?php echo base_url()?>media/theme_assets/img/savi_logo.png" width="65px" alt="">
						
					</a>  
					</div>
				</div><!--- END Col -->					
				<div class="col-lg-10 col-md-9 col-sm-8">
					<div class="header_right">
						<nav id="main-menu" class="ml-auto">
							<ul>
							  <li><a class="" href="<?php echo base_url()?>Site/index">Home</a></li>
							  <li><a class="" href="<?php echo base_url()?>Site/about">About Us</a></li>
							  <!-- <li><a class="" href="<?php echo base_url()?>Site/Plan">Business Plan</a></li> -->
							  <li><a class="" href="<?php echo base_url()?>Site/contact">Contact</a></li>
							  <li><a class="" href="<?php echo base_url()?>Site/register">Register</a></li>
							  <li><a class="" href="<?php echo base_url()?>Site/login">Login</a></li>
							<!-- <li><a href="<?php echo base_url()?>Site/cart" class="top_f_linkedin"><i class="fa fa-shopping-cart" style="font-size:22px;"></i></a></li> -->
                             
							  
                           						  
							</ul>
							
						</nav>
						<div id="mobile_menu"></div> 
					</div>
				</div><!--- END COL -->					
			</div><!--- END ROW -->
		</div><!--- END CONTAINER -->
	</div> 	  
	<!-- END NAVBAR -->	
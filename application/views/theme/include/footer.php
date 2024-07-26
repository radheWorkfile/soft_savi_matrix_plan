	<!-- START FOOTER -->
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-sm-6 col-xs-12" style="margin-top:30px;">
					<div class="footer_logo">
						<a href="<?php echo base_url()?>site"><img src="<?php echo base_url() ?>media/theme_assets/img/savi_logo.png" class="logo_footer" width="65px" alt="" /></a>
						<p class="text_gray">At SAVI, we are a community, a family of passionate individuals driven by a shared vision of empowerment and financial freedom</p>
					</div>
					<div class="social_profile">
						<ul>
							<li><a href="<?php echo config_item('facebook');?>" target="_blank" class="f_facebook"><i class="fa fa-facebook" title="Facebook"></i></a></li>
							<li><a href="<?php echo config_item('tweeter');?>" target="_blank" class="f_twitter"><i class="fa fa-twitter" title="Twitter"></i></a></li>
							<li><a href="<?php echo config_item('instagram')?>" target="_blank" class="f_instagram"><i class="fa fa-instagram" title="Instagram"></i></a></li>
							<li><a href="<?php echo config_item('linkdin')?>" target="_blank" class="f_linkedin"><i class="fa fa-linkedin" title="LinkedIn"></i></a></li>
						</ul>

					</div>
				</div><!--- END COL -->
				<div class="col-lg-4 col-sm-4 col-xs-1">
					<div class="single_footer single_footer_top" style="margin-top:30px;">
						<h4>Quick Links</h4>
						<ul>
							<li><a href="<?php echo base_url() ?>Site/">Home</a></li>
							<li><a href="<?php echo base_url() ?>Site/about">About</a></li>
							<li><a href="<?php echo base_url() ?>Site/contact">Contact </a></li>
						</ul>
					</div>
				</div><!--- END COL -->
				<div class="col-lg-4 col-sm-6 col-xs-12" style="margin-top:30px;">
					<div class="single_footer single_footer_top">
					<h4 class="text-white">Contact Details</h4>
					<div class="d-flex">
						<div class="left">
							<i class="fa fa-home font-size-sett"></i>
						</div>
						<div>
							<span class=" add-kle text_gray"><?php echo config_item('company_address');?></span>
						</div>
					</div>
					
					<div class="d-flex my-3">
						<div class="left">
							<i class="fa fa-envelope font-size-sett"></i>
						</div>
						<div>
							<a href="<?php echo config_item('email');?>" class="text_gray add-kle"><?php echo config_item('email');?></a>
						</div>
					</div>
					</div>
				</div><!--- END COL -->
			</div><!--- END ROW -->
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="footer_copyright">
						<p>&copy; 2023, savi. All rights reserved </p>
					</div>
				</div>
			</div>
		</div><!--- END CONTAINER -->
	</div>
	<!-- END FOOTER -->
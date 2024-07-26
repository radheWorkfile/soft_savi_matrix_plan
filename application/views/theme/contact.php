<!-- START SECTION TOP -->
<section class="section-top set_contact_bgggg">
	<div class="container">
		<div class="col-lg-12 col-sm-12 col-xs-12 text-center">
			<div class="section-top-title">
				<h1>Contact Us</h1>
			</div><!-- //.HERO-TEXT -->
		</div><!--- END COL -->
	</div><!--- END CONTAINER -->
</section>
<!-- END SECTION TOP -->

<!-- ADDRESS -->
<div class="address section-padding">
	<div class="container">
		<div class="row text-center">
			<div class="col-lg-4 col-sm-6 col-xs-12 ">
				<div class="single_address">
					<div class="address_br"><span class="ti-email"></span></div>
					<h4 class="text-white">Email</h4>
					<p><a href="mailto:<?php echo config_item('email'); ?>"
							style="color:rgb(139,117,139,1)!important;"><?php echo config_item('email'); ?></a></p>
				</div>
			</div><!--- END COL -->
			<div class="col-lg-4 col-sm-6 col-xs-12 ">
				<div class="single_address ">
					<div class="address_br"><span class="ti-mobile"></span></div>
					<h4 class="text-white">Phone</h4>
					<p><a href="tel:<?php echo config_item('phone'); ?>"
							style="color:rgb(139,117,139,1)!important;"><?php echo config_item('phone'); ?></a></p>

				</div>
			</div><!--- END COL -->

			<div class="col-lg-4 col-sm-6 col-xs-12 ">
				<div class="single_address">
					<div class="address_br"><span class="ti-location-pin"></span></div>
					<h4 class="text-white">Address</h4>
					<p><?php echo config_item('company_address'); ?></p>
				</div>
			</div><!--- END COL -->
		</div><!--- END ROW -->
	</div><!--- END CONTAINER -->
</div>
<!-- END ADDRESS -->

<!-- START CONTACT -->
<section id="contact" class="contact_us section-padding">
	<div class="container">
		<div class="row contact_us_bg">
			<div class="col-lg-7 col-sm-12 col-xs-12 ">
				<div class="contact">
					<h4>How can I help you?</h4>
					<!-- <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p> -->
					<form class="form" name="enq" method="post" action="<?php echo base_url() ?>Site/formhandler">
						<div class="row">
							<div class="form-group col-md-6">
								<input type="text"
									  oninput="this.value = this.value.toUpperCase().replace(/[^a-zA-Z ]/g, '').replace(/(  *?)/g, ' ')"
									name="name" class="form-control" placeholder="Name" id="name" required="required">
							</div>
							<div class="form-group col-md-6">
								<input type="email" oninput="this.value = this.value.toUpperCase()" name="email"
									id="email" class="form-control" placeholder="Email" required="required">
							</div>
							<div class="form-group col-md-12">
								<input type="text"
									oninput="this.value = this.value.toUpperCase().replace(/[^0-9]/g, '').replace(/(\  *?)\  */g, '$1')"
									maxlength="10" name="mobile" class="form-control" id="number"
									placeholder="Mobile Number *" required="required">
							</div>
							<div class="form-group col-md-12">
								<textarea rows="6" name="message" id="message" class="form-control"
									placeholder="Your Message" required="required"></textarea>
							</div>

							<div class="form-group col-md-12 mt-3">
								<div class="outer-box">
									<div class="inner-box1 d-flex justify-content-around  align-items-center py-2"
										style="border:0.2px solid black ">
										<span class="text-primary">Enter Captcha:-</span>
										<div class="captcha-box-in d-flex align-items-center ">
											<div class="in-colo">
												<span id="first"></span>
												<span id="plus">+</span>
												<span id="second"></span>
											</div>
											<!-- <div class="set-pa "> <a  onclick="refresh();"><i
															class="fa-solid fa-arrows-rotate"></i></a></div> -->
										</div>

									</div>
								</div>
							</div>
							<div class="form-group col-md-12 mt-3">
								<input type="text" id="num" placeholder="Enter Captcha Here" class="set-sum"
									onkeyup="myFunction()" />
							</div>


							<div class="col-md-12 text-center">
								<button type="submit" value="Send message" name="submit" id="submitButton"
									onclick="return Handleform();" class="btn btn-lg contact_btn"
									title="Submit Your Message!">Send Message</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- END COL  -->
			<div class="col-lg-5 col-sm-12 col-xs-12">
				<div>
					<img src="<?php echo base_url() ?>media/theme_assets/img/bg/contact_left.jpg" alt=""
						class="img-shhhh">
				</div>
			</div><!-- END COL  -->
		</div><!-- END ROW -->
	</div><!-- END CONTAINER -->
</section>
<!-- END CONTACT -->
<script>

	var firstNu = document.getElementById("first");
	var num1 = (firstNu.innerHTML = Math.floor(Math.random() * 100));

	var secNum = document.getElementById("second");
	var num2 = (secNum.innerHTML = Math.floor(Math.random() * 10));



	function myFunction() {
		var userInput = document.getElementById("num");
		var inputValu = userInput.value;
		return inputValu;
	}

	var storeRandomValue = parseInt(num1) + parseInt(num2);
	// console.log("added", storeRandomValue);
</script>


<script>
	function Handleform() {
		var name = document.getElementById('name').value;
		var email = document.getElementById('email').value;
		var phone = document.getElementById('number').value;
		var message = document.getElementById('message').value;
		var namePattern = /^[A-Za-z\s\-]+$/;
		var phoneRegex = /^\d{10}$/;
		var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
		var dued = myFunction();
		if (!name) {
			alert("Please Enter Name");
			return false;
		} else if (!namePattern.test(name)) {
			alert("Enter Correct Name");
		} else if (!email) {
			alert("Enter Email Id");
			return false;
		} else if (!emailRegex.test(email)) {
			alert("Enter Valid Email");
			return false;
		} else if (!phone) {
			alert("Please Enter Phone Number");
			return false;
		} else if (!phoneRegex.test(phone)) {
			alert("Enter Correct Number");
			return false;
		} else if (!message) {
			alert("Enter Message");
			return false;
		} else if (!dued) {
			alert("Please Enter Captcha");
			return false;
		} else if (namePattern.test(dued)) {
			alert("Enter Only Number");
			return false;
		}
		else if (dued != storeRandomValue) {
			alert("Enter Correct Captcha");
			return false;
		}

	}
</script>
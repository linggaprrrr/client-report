<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Report Management System | Registration Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com">
        <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png">
		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="/assets/registration/fonts/material-design-iconic-font/css/material-design-iconic-font.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.5.0/css/all.min.css">
		<!-- STYLE CSS -->
		<link rel="stylesheet" href="/assets/registration/css/style.css">
	</head>
	<body>
		<div class="wrapper">
            <form id="wizard">
        		<!-- SECTION 1 -->
                <h2></h2>
                <section>
                    <div class="inner">
						<div class="image-holder" style="background-color: #f0f4ff;">
							<div style="text-align: center">
							     <img src="/assets/images/fba-logo.png" style="width: 70%; margin-top: 20px;" alt="">
							</div>
							<div style="text-align: justify">
							     <p style="font-size: 14px; text-align: justify; margin: 20px; color:#2c3f88 ">The purpose of this document is to make your SMART FBA onboarding process as seamless as possible and to complete the next steps in the setup process. The Below link has all relevant information regarding setting up your new store and how we can help you with the process.</p>
							     <p style="font-size: 14px; text-align: justify; margin: 20px; color:#2c3f88">VERY IMPORTANT: The process is critical to the setup of your business, so please ensure that the document is completed correctly.</p>
							     <p style="font-size: 14px; text-align: justify; margin: 20px; color:#2c3f88">We cannot be held responsible for any delays that may occur due to incorrect documentation and registration.</p>
							     <p style="font-size: 14px; text-align: left; margin: 20px; color:#2c3f88">ONBOARDING: <br><a href="https://docs.google.com/document/d/1LEklwwpcys1NtKja0yxO6E6iuaRmTO6uV4SOYRQGknE/edit" target="_blank">SMART FBA Onboarding Instructions </a> </p>
							</div>
						</div>
						<div class="form-content" >
							<div class="form-header">
								<h3>Registration</h3>
							</div>
							<p>Please fill with your details</p>
							<div class="form-row">
								<div class="form-holder">
									<input type="text" id="name" name="name" placeholder="Full Name" class="form-control" required>
								</div>
								<div class="form-holder">
									<input type="text" id="email" name="email" placeholder="Personal Email" class="form-control" required>
								</div>
							</div>
							<div class="form-row">
								<div class="form-holder">
									<input type="text" id="business_email" name="business_email" placeholder="New Gmail Adress" class="form-control" required>
									<p style="font-size: 10px;text-align: left; margin: 0; color:#2c3f88">Please create a Gmail address specifically for the use with your Amazon Seller Central Account.</p>
								</div>
								<div class="form-holder">
									<input type="text" id="skype_id" name="skype_id" placeholder="Skype ID" class="form-control" required>
									<p style="font-size: 10px;text-align: left; margin: 0; color:#2c3f88">We will create a group to communicate you regular updates and queries regarding your Amazon Business.</p>
								</div>
							</div>
							<div class="form-row">
								<div class="form-holder">
									<input type="text" id="phone_number" name="phone_number" placeholder="Phone Number" class="form-control" required>
								</div>
							    <div class="form-holder">
									<input type="text" id="birth" name="birth"  placeholder="Date of Birth" class="form-control" readonly>
								</div>
							</div>
							<div class="form-row">
								<div class="form-holder w-100">
									<textarea style="height: 60px" id="address" name="address" class="form-control" placeholder="Full Address" required></textarea>
								</div>
							</div>
						</div>
					</div>
                </section>

		

                <!-- SECTION 2 -->
                <h2></h2>
                <section>
                    <div class="inner">
						<div class="image-holder" style="background-color: #f0f4ff;">
							<div style="text-align: center">
							     <img src="/assets/images/fba-logo.png" style="width: 70%; margin-top: 20px;" alt="">
							</div>
							<div style="text-align: justify">
							     <p style="font-size: 18px; text-align: justify; margin: 20px; color:#2c3f88">BUSINESS INFORMATION</p>
							     <p style="font-size: 14px; text-align: justify; margin: 20px; color:#2c3f88">If you do not already have an LLC, then please contact US and we will be able to assist if required.</p>
							</div>
						</div>
						<div class="form-content">
							<div class="form-header">
								<h3>Registration</h3>
							</div>
							<p>About your business</p>
							<div class="business" style="overflow: auto; max-height: 250px;">
							    <div class="form-row">
    								<div class="form-holder">
    									<input type="text" id="business_name" name="business_name" placeholder="Business Name" class="form-control" required>
    								</div>
    								<div class="form-holder">
    									<input type="text" id="business_address" name="business_address" placeholder="Business Address" class="form-control" requiredrequired>
    								</div>
    							</div>
    							<div class="form-row">
    								<div class="form-holder">
    								    <input type="text" id="ein" name="ein" placeholder="Employment Identification Number (EIN)" class="form-control" required>
    								</div>
    								<div class="form-holder">
    									<input type="text" id="state_number" name="state_number" placeholder="State Registration Number" class="form-control" required>
    								</div>
    							</div>
    							<div class="form-row">
    								<div class="form-holder w-100 owner" style="display: flex">
    									<input type="text" id="owner" name="owner[]" autocomplete="off" placeholder="Owner / Shareholder" class="form-control" required>
    									<div>
    									    <a href="#" style="color: #6d7f52; margin-left: 20px;" class="add-owner">
        									    <i class="fas fa-plus-circle"></i>
        									</a>
    									</div>
    								</div>
    							</div>
							</div>
							
						</div>
					</div>
                </section>
                <h2></h2>
                <section>
                    <div class="inner">
						<div class="image-holder" style="background-color: #f0f4ff;">
							<div style="text-align: center">
							     <img src="/assets/images/fba-logo.png" style="width: 70%; margin-top: 20px;" alt="">
							     <p style="font-size: 14px; text-align: justify; margin: 20px; color:#2c3f88">This account will be used to access this website.</p>
							</div>
							<div style="text-align: justify">
							     
							</div>
						</div>
						<div class="form-content">
							<div class="form-header">
								<h3>Registration</h3>
							</div>
							<p>Create your account</p>
							<div class="form-row">
								<div class="form-holder w-100">
									<input type="text" id="username" name="username" placeholder="Username" class="form-control" required>
								</div>
							</div>
							<div class="form-row">
								<div class="form-holder">
									<input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
								</div>
								<div class="form-holder">
									<input type="password" id="confirm_password" name="repassword" placeholder="Confirm Password" class="form-control" required>
								</div>
							</div>
							<div class="form-row">
							    <small id="message"></small>
							</div>
						</div>
					</div>
                </section>
            </form>
		</div>

		<!-- JQUERY -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script src="/assets/registration/js/jquery-3.3.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
		<!-- JQUERY STEP -->
		<script src="/assets/registration/js/jquery.steps.js"></script>
		<script src="/assets/registration/js/main.js"></script>
		<!-- Template created and distributed by Colorlib -->
</body>
<script>
    $( function() {
        $("#birth").datepicker({ 
            autoclose: true, 
            todayHighlight: true
        });
        
        $('a[href="#finish"]').click(function(){
            let name = $('#name').val();
            let email = $('#email').val();
            let address = $('#address').val();
            let business_email = $('#business_email').val();
            let skype_id = $('#skype_id').val();
            let phone_number = $('#phone_number').val();
            let birth = $('#birth').val();
            let business_name = $('#business_name').val();
            let business_address = $('#business_address').val();
            let ein = $('#ein').val();
            let state_number = $('#state_number').val();
            let username = $('#username').val();
            let password = $('#password').val();
            let confirm_password = $('#confirm_password').val();
            
            
            
            if (name == "" || email == "" || address == "" || business_email == "" ||skype_id == "" || phone_number == "" || birth == "" || business_name == "" || business_address == "" || ein == "" || state_number == "" || username == "" || password == "") {
                swal("Waitt!", "Please fill up the form completely", "warning");    
            } else if ($('#password').val() == $('#confirm_password').val()) {
                $.post('/post-register', $('#wizard').serialize(), function(data) {
                    swal({
                      title: "Successfully!",
                      text: "You will be directed to the login page.",
                      icon: "success",
                      button: "Ok!",
                    });
                    setTimeout(() => {  console.log("World!"); }, 5000);
                    window.location = "https://swclient.site/";
                });
            } else
                swal("Waitt!", "Your password dont match", "warning");    
        }); 
        
         $('#password, #confirm_password').on('keyup', function() {
            if ($('#password').val() == $('#confirm_password').val()) {
                $('#message').html('Password is Match').css('color', 'green');
            } else
                $('#message').html('Password not matching!').css('color', 'red');
        });
        
        $('.add-owner').click(function() {
           $('.business').append('<div class="form-row"> <div class="form-holder w-100 owner" style="display: flex"> <input type="text" name="owner[]" autocomplete="off" placeholder="Owner / Shareholder" class="form-control" required> <div> <a href="#" style="color: #6d7f52; margin-left: 20px;" class="add-owner"> </a> </div></div></div>');
        });
        
      } );
</script>
</html>

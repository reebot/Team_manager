<div class="span9">
	<div class="row">
    	<div class="span6"><h2 h2 style="margin-top:0">Register invitation</h2></div>
    </div>
<div class="row">
	<div class="span5" style="max-width:500px">
        		<div class="widget widget-box">
	        		<div class="widget-header"><h3>Register invitation</h3></div>
		        		<div class="widget-content" style="min-height:250px; padding-left:50px"">

	
							<?php echo $this->errors ?>
	
							<form action="<?php echo url("accounts/register") ?>" method="post" id="login_form" class="form">
								
								<input type="hidden" name="tracking_code" value="<?php echo set_value("tracking_code", $this->code) ?>" />
								
								<label for="form-name">First Name*</label>
					<div class="input-prepend">
					<span class="add-on"><i class="icon-user"></i></span>
					<?php echo form_input("first_name", set_value("first_name", Auth::Get("first_name"))) ?>
					</div>
					
					<div class="clear_10"></div>
					
					<label for="form-lastname">Last Name*</label>
					<div class="input-prepend">
					<span class="add-on"><i class="icon-user"></i></span>
					<?php echo form_input("last_name", set_value("last_name")) ?>
					</div>
					
					<div class="clear_10"></div>
		
					<label for="form-mobile">Mobile Number*</label>
					<div class="input-prepend">
					<span class="add-on"><i><strong>M</strong></i></span>
					<?php echo form_input("mobile_number", set_value("mobile_number")) ?>
					</div>
					
					<div class="clear_10"></div>
		
					<label for="form-mobile">E-Mail*</label>
					<div class="input-prepend">
					<span class="add-on"><i class="icon-envelope"></i></span>
					<?php echo form_input("email", set_value("email")) ?>
					</div>
					
					<div class="clear_10"></div>
		
					<label for="form-mobile">Password*</label>
					<div class="input-prepend">
					<span class="add-on"><i class="icon-hand-right"></i></span>
					<?php echo form_password("password", set_value("password")) ?>
					</div>
		
					<div class="clear_10"></div>
					
					<label for="form-mobile">Repeat Password*</label>
					<div class="input-prepend">
					<span class="add-on"><i class="icon-hand-right"></i></span>
					<?php echo form_password("repeat_password", set_value("repeat_password")) ?>
					</div>
		
					<div class="clear_10"></div>
					
					<label for="form-mobile">Are you a manager?<a class="" data-content="Select yes if you want to create games & invite people to play" rel="popover" href="#" data-original-title="Manager or not?"><i class="icon-question-sign" style="margin-top:0px"></i></a></label>
					<div class="input-prepend">
					<span class="add-on"><i class="icon-book"></i></span>
					<?php echo form_dropdown("manager", array( 0 => "No", 1 => "Yes" ), set_value("manager")) ?>
					</div>
		
					<div class="clear"></div>
		
					<button class="btn btn-inverse btn-large" type="submit">Register</button>
	
						</form>
			</div>
	</div>						
</div>
							

			
			<div class="span4">
				  <div class="widget widget-box">
	        		<div class="widget-header"><h3>Already have an account Login now</h3></div>
		        		<div class="widget-content" style="min-height:250px; padding-left:20px"">
							
								<?php echo $this->errors ?>
		
								<form action="<?php echo url("accounts/login") ?>" method="post" id="login_form" class="form">
									
									<input type="hidden" name="tracking_code" value="<?php echo $this->code ?>" />
		
									<p class="input-block">
										<label for="form-mobile">E-Mail*</label>
										<?php echo form_input("email", set_value("email")) ?>
									</p>
									
									<div class="clear_10"></div>
		
									<p class="input-block" style="width:20px">
										<label for="form-mobile">Password*</label>
										<?php echo form_password("password", set_value("password")) ?>
									</p>
									
									<div class="clear_10"></div>
		
									<button class="btn btn-inverse btn-large" type="submit">Login</button>
		
									<!-- <div class='hidden'>
										<label for='spam-check'>Do not fill out this field</label>
										<input name='spam-check' type='text' value='' id="spam-check" />
									</div> -->
								</form>
		        		</div>
        		</div>
	</div>	
	</div>
</div>
							
							
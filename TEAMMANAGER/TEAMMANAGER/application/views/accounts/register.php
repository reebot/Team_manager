<div class="span11">
	<div class="row" style="margin-top:20px">
		<div class="span6">
			<div class="widget widget-box">
				<div class="widget-header"><h3 style="margin-left:48px">Player Registration</h3></div>
				<div class="widget-content" style="padding-left:60px">

				<?php if(!empty($this->errors)){
				    echo '<div class="alert alert-error" style="margin-right:40px">';
				    echo $this->errors;
				    echo '</div>';
				} ?>
		
				<form action="<?php echo current_url() ?>" method="post" id="login_form" class="form">
					
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
				
				<div class="clear"></div>
				</div>
			</div>
			</div>
			
			<div class="span5">
				<div class="well" style="padding:10px 0 30px 50px">
			        <div class="two-fourth">
			            <h3>Why register?</h3>
			            <ul class="checklist">
			              <h4><li>Manage 5, 6, 7 & 11 a side football.</li></h4>
			              <h4><li>Keep a record of payments</li></h4>
			              <h4><li>Search for more games in your area</li></h4>
			              <h4><li>Interact with other players</li></h4>
						  <h4><li>Build your player profile</li></h4>
			            </ul>
			        </div>
				</div>
			</div>
	</div>
</div>
						

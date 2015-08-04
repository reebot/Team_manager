<div class="span9">
	<div class="row" style="margin-top:20px">
		<div class="span5 offset3">
			<div class="widget widget-box">
				<div class="widget-header"><h3 style="margin-left:20px">Login</h3></div>
				<div class="widget-content" style="padding-left:55px">
					
						<?php if(!empty($this->errors)){
						    echo '<div class="alert alert-error" style="margin-right:40px">';
						    echo $this->errors;
						    echo '</div>';
						} ?>

						<form action="<?php echo current_url() ?>" method="post" id="login_form" class="form">

							<input type="hidden" name="tracking_code" value="<?php echo set_value("tracking_code", $this->code) ?>" />
							
							<label for="form-mobile">E-Mail*</label>
							<div class="input-prepend">
							<span class="add-on" ><i class="icon-envelope"></i></span>
							<?php echo form_input("email", set_value("email")) ?>
							</div>
							
							<div class="clear"></div>

							<label for="form-mobile">Password*</label>
							<div class="input-prepend">
							<span class="add-on"><i class="icon-hand-right"></i></span>
							<?php echo form_password("password", set_value("password")) ?>
							</div>
							
							<div class="clear"></div>

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
						
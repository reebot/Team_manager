	<div class="span8">
		<div class="row">
    		<div class="span6"><h2 style="margin-top:0">My profile</h2></div>
    	</div>
		<div class="widget widget-box">
			<div class="widget-header"><h3>My profile</h3></div>
			<div class="widget-content" style="padding-left:40px">
					<?php 
					    if(!empty($this->errors)){
						    echo '<div class="alert alert-error">';
						    echo $this->errors;
						    echo '</div>';
						    } ?>
					<form action="<?php echo current_url() ?>" method="post" id="login_form" enctype="multipart/form-data" class="form">
					
							<label for="form-name">Name*</label>
							<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<?php echo form_input("first_name", set_value("first_name", $this->Account->first_name)) ?>
							</div>
							
					
							<label for="form-lastname">Last Name*</label>
							<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<?php echo form_input("last_name", set_value("last_name", $this->Account->last_name)) ?>
							</div>
					
					
							<label for="form-mobile">Mobile*</label>
							<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<?php echo form_input("mobile_number", set_value("mobile_number", $this->Account->mobile_number)) ?>
							</div>
					
					
							<label for="form-mobile">E-Mail*</label>
							<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<?php echo form_input("email", set_value("email", $this->Account->email)) ?>
							</div>
					
						
							<label for="form-mobile">Avatar</label>
							<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<?php echo form_upload("userfile") ?>
							</div>
						
							<br />
							<button class="btn btn-inverse btn-large" type="submit">Update</button>
					
					</form>
			</div>
			</div>
		</div>

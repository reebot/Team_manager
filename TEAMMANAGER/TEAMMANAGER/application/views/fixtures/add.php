<div class="span9">
		<div class="row">
    	<div class="span6"><h2 style="margin-top:0">New game</h2></div>
    </div>
			
			<form action="<?php echo current_url() ?>" method="post" class="form" enctype="multipart/form-data">

			<!-- Next Game          -->
        		<div class="widget widget-box">
	        		<div class="widget-header"><h3>Register a new game</h3></div>
		        		<div class="widget-content" style="min-height:250px; padding-left:40px"">
							
							    <?php 
								    if(!empty($this->errors)){
									    echo '<div class="alert alert-error">';
									    echo $this->errors;
									    echo '</div>';
									    } ?>

							
                                    <label>Game Type <span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><i class="icon-user"></i></span>
									<?php echo form_dropdown("type", array(
										"5-A-side" => "5-A-side",
										"6-A-side" => "6-A-side",
										"7-A-side" => "7-A-side",
										"11-A-side" => "11-A-side"
									), "", "style='width: 370px'") ?>
                                    </div>
								
                                    <label>Games per block<span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><i class="icon-pencil"></i></span>
									<?php echo form_dropdown("blocks", array(
										5 => 5,
										10 => 10
									), "", "style='width: 370px'") ?>
                                    </div>
								
                                    <label>Location of a Game <span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><i class="icon-globe"></i></span>
									<?php echo form_input("location", set_value("location"), "style='width: 353px'") ?>	
                                    </div>
                                    
								<div class="clear_10"></div>
									
                                    <label>Address <span class="required">*</span></label>
									<div class="input-prepend">
									<span class="add-on"><i class="icon-list"></i></span><?php echo form_textarea("address", set_value("address"), "style='width: 353px; height: 60px'") ?>	
									</div>

									
								<div class="clear_10"></div>
								
                                    <label>Post Code <span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><i class="icon-globe"></i></span><?php echo form_input("postcode", set_value("postcode"), "style='width: 353px'") ?>
                                    </div>	
								
								<div class="clear_10"></div>
									
                                    <label>Directions <span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><i class="icon-globe"></i></span>
                  					<?php echo form_textarea("directions", set_value("directions"), "style='width: 353px; height: 60px'") ?>
                                    </div>
                                    
								<div class="clear_10"></div>
								
                                    <label>Cost of Pitch per Block <span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><strong>&pound;</strong></span>					
									<?php echo form_input("pitch_cost", set_value("pitch_cost"), "style='width: 342px'") ?>
                                    </div>	

								<div class="clear_10"></div>
								
                                    <label id="FeeCalculator" style="color:red; margin-left:10px;"></label>
								
								<div class="clear_10"></div>
									
                                    <label>Block Player Cost per game<span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><strong>&pound;</strong></span>
									<?php echo form_input("player_cost", set_value("player_cost"), "style='width: 342px' placeholder='Charge per game for block (advance) payers?'") ?>
                                    </div>	
								
								<div class="clear_10"></div>
								
                                    <label style="width: 300px">Pay as You Play Fee per Player <span class="required">*</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><strong>&pound;</strong></span>
									<?php echo form_input("payp_cost", set_value("payp_cost"), "style='width: 342px' placeholder='What do you want to charge player per game?'") ?>
                                    </div>	
								
								<div class="clear_10"></div>
								
                                    <label style="width: 300px">Venue picture <span style="font-size: 9px">(Optional)</span></label>
                                    <div class="input-prepend">
									<span class="add-on"><i class="icon-picture"></i></span>
									<?php echo form_upload("userfile") ?>	
                                    </div>
								<div class="clear_10"></div>
								<br />
								
								<button class="btn btn-inverse btn-large" type="submit">Create a Game</button>
		        		</div>
		        	</div>
        		</div>
			</form>
							

</div><!-- end span9 -->
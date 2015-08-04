<div class="span9">
	<div class="row">
    	<div class="span6"><h2 style="margin-top:0">Games I Play</h2></div>
    	<div class="span" style="float:right;"><a href="<?php echo url("fixtures") ?>" class="btn btn-inverse btn-large"><span>Browse for Games</span></a></div>
    </div>
	
		<?php if( ! $this->Fixtures->exists() ): ?>
			You are not playing any games.
		<?php endif ?>
		
		<div class="row">		
		<?php foreach( $this->Fixtures as $Fixture ): ?>
			<div class="span3">
				<div class="widget widget-box">
		        	<div class="widget-header">
		        		<h3 style="margin-left:23px"><a href="<?php echo url("fixtures/view/".$Fixture->fixture_id."/0/".$Fixture->id) ?>"><?php echo ucwords(strtolower($Fixture->fixture_location)) ?></a>
		        		</h3>
		        	</div>
		        	<?php $image = ( $Fixture->image ) ? url("public/uploads/".$Fixture->image, true) : url("public/images/fixture_no_image.jpg", true) ?>
			        	<div class="widget-content" style="min-height:250px; padding:30px">
						    						        
						        <p><a href="<?php echo url("fixtures/view/".$Fixture->fixture_id."/0/".$Fixture->id) ?>"><img src="<?php echo $image ?>" class="img-polaroid" /><span class="small-plus"><!--  --></span></a></p>
						        
								<div class="row-fluid">
								     <a style="width:174px" href="<?php echo url("fixtures/view/".$Fixture->fixture_id."/0/".$Fixture->id) ?>" class="btn btn-inverse">More info</a>
								</div>
								<div class="clear"></div>
						        <b>Time:</b> <?php echo ($Fixture->time) ? date("jS M H:i", $Fixture->time) : "Unknown" ?></br>
						        <b>Postcode:</b> <?php echo $Fixture->fixture_postcode ?></br>
							
							<?php
								$SlotsAvailable = ( ( (int) $Fixture->fixture_type ) * 2) - $Fixture->account_count;
								
								switch ($SlotsAvailable) {
									case '0':
										echo "<span style='color: red'>No slots available.</span>";
										break;
									case '1':
										echo "<span style='color: orange'>1 Slot available.</span>";
										break;
									default:
										echo "<span style='color: green'>".$SlotsAvailable." Slots available.</span>";
										break;
								}
							?>
							<br/>
							
						</div>
					</div>
				</div>
		<?php endforeach ?>
	</div>
</div>
</div>



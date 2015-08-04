<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		
		$("#invite_more").click(function() {
			
			if( $("#invite_elements input").length >= 100 )
			{
				alert("You cannot invite more players to this game.");
			}
			else
			{
				$('#invite_elements').append($('#invite_template').html());
			}
			
		});
		
	});
</script>

<div id="invite_template" style="display: none">
	<label>Player E-Mail</label>
	<input type="text" name="invite[]" />

	<div class="clear_10"></div>
</div>

<!-- start middle -->
<div class="middle">
	<!-- start inner -->
	<div class="inner gradient-down">

		<!-- [] -->
		<div class="full-width">

			<div class="one-half pt20">
				<div class="outer-rounded-box-bold">
					<div class="simple-rounded-box">

						<form action="<?php echo current_url() ?>" method="post" class="form">

							<h4>Invite Players</h4>
							
							<?php echo $this->errors ?>

							<div id="invite_elements">
								
								<?php if( ! $this->Fixture->invite->exists() ): ?>
									
									<label>Player E-Mail</label>
									<input type="text" name="invite[]" />

									<div class="clear_10"></div>
									
								<?php else: ?>
									
									<?php foreach( $this->Fixture->invite as $invite ): ?>
										
										<label>Player E-Mail</label>
										<input type="text" name="invite[]" value="<?php echo $invite->email ?>" disabled='disabled' />

										<div class="clear_10"></div>
										
									<?php endforeach ?>
									
								<?php endif ?>
								
							</div>
							
							<a href="#" id="invite_more" class="btn-small-action"><span>Invite More</span></a>
							
							<div class="clear_10"></div>
							
							<?php echo form_submit("save", "Invite") ?>

						</form>
						
						<div class="clear"></div>

					</div>
					
				</div>
				
			</div>
		
			<div class="clear"></div>

		</div>

	</div>
	<!-- end inner -->
</div>
<!-- end middle -->
<script>
	$('#test').popover(options);
</script>

<div id="invite_template" style="display: none">
	<label>Player E-Mail</label>
	<input type="text" name="invite[]" />
	<div class="clear_10"></div>
</div>

<div class="span9">
	<div class="row">
    	<div class="span6"><h2 style="margin-top:0"><?php echo $this->Fixture->location ?> - <?php echo $this->Fixture->type ?></h2></div>
    </div>
	<div class="row">
		<div class="span3">
			<div class="widget widget-box">
	        	<div class="widget-header"><h3>Game - <?php echo $this->Fixture->location ?></h3></div>
		        	<div class="widget-content">
		        		
		        		<!--  Costs -->
			        	<?php if( $this->Fixture->owner == Auth::Get("id") ): ?>
	        		    <div class="row-fluid">
		        		    <div class="span6"><h4>Pitch cost</h4></div>
		        		    <div class="span5"><h4>£<?php echo number_format($this->Fixture->pitch_cost, 2) ?></h4></div>
		        		    <div class="span1"><a class="" data-content="The actual cost of the pitch for all games in block" rel="popover" href="#" data-original-title="Pitch cost"><i class="icon-question-sign" style="margin-top:10px"></i></a></div>
		        		    
		        		</div>
		        		<div class="row-fluid">
		        		    <div class="span6"><h4>Player Fee</h4></div>
		        		    <div class="span5"><h4>£<?php echo number_format($this->Fixture->player_cost, 2) ?></h4></div>
		        		    <div class="span1"><a class="" data-content="Regular player (Block joined) fee per game" rel="popover" href="#" data-original-title="Regular player fee"><i class="icon-question-sign" style="margin-top:10px"></i></a></div>
		        		</div>
		        		
		        		<?php else: ?>
		        		
						<?php endif ?>
						
						<div class="row-fluid">
		        		    <div class="span6"><h4>Normal Fee</h4></div>
		        		    <div class="span5"><h4>&pound;<?php echo number_format($this->Fixture->payp_cost, 2) ?></h4></div>
		        		    <div class="span1"><a class="" data-content="Non regular player (Pay as you play) fee per game" rel="popover" href="#" data-original-title="Non regular player fee"><i class="icon-question-sign" style="margin-top:10px"></i></a></div>
		        		</div>
		        		
		        		<?php if( $this->Fixture->owner == Auth::Get("id") ): ?>
		        		
		        		<div class="row-fluid">
		        		    <div class="span6"><h4>Payments In</h4></div>
		        		    <div class="span5"><h4>&pound;<?php echo ( $this->TotalPayments ) ? number_format($this->TotalPayments, 2) : 0 ?></h4></div>
		        		    
		        		    <div class="span1"><a class="" data-content="All payments received for this fixture" rel="popover" href="#" data-original-title="Manager payments in"><i class="icon-question-sign" style="margin-top:10px"></i></a></div>
		        		</div>
		        		
		        		<?php endif ?>
							
						<br />
						
						<!-- Address column game details -->
							<b>Where</b><br/>
								<?php echo $this->Fixture->address ?></br>
								<?php echo $this->Fixture->postcode ?>
								<br/><br/>
								<b>Directions</b><br/>
								<?php echo $this->Fixture->directions ?>
								<br/>
							<br/>
		        	</div>
			</div>
		</div>
		
		<div class="span6">
			<div class="widget widget-box">
	        	<div class="widget-header"><h3>Player activity</h3>
	        	
	        		<?php if( $this->Fixture->owner == Auth::Get("id") ): ?>
	        		<a class="" data-content="Click on football below to set a date for the game" rel="popover" href="#" data-original-title="Setting a date for a game"><i class="icon-question-sign" style="float-right;"></i></a>
	        		<?php else: ?>
	        		<a class="" data-content="Click on football below and then the join button to play a game or join the entire block on right" rel="popover" href="#" data-original-title="Setting a date for a game"><i class="icon-question-sign" style="float-right;"></i></a>
	        		<?php endif ?>
	        		
	        		
	        		<a href="#" class="btn btn-primary FixtureBlockJoin" fixture_id="<?php echo $this->Fixture->id ?>" style="float:right; margin: 5px 5px 0 0"><span>Join Block</span></a>
	        	<?php if( $this->Fixture->owner == Auth::Get("id") ): ?>
	        	<a data-toggle="modal" href="#invite" style="float:right; margin: 5px 5px 0 0" class="btn btn-inverse InviteButton">Invite more players</a>
	        	<?php endif ?>
	        	</div>
		        	<div class="widget-content">
		        		
		        		<div id="FixtureBlocks">
							
							<?php if( ! $this->Fixture->game_block->exists() ): ?>
								<h3>There are no games for this fixture.</h3>
							<?php else: ?>
								
								<?php if( $this->BlockButtonVisible ): ?>
											
										
											
								<?php endif ?>
								
								<div class="clear"></div>
								
								<div class="row" style="margin-left:0px">
								
								<?php $i = 0 ?>
								<?php foreach( $this->Fixture->game_block as $Block ): ?>
									<?php $i++ ?>
									
									
									<div class="span FixtureBlock" GameID="<?php echo $Block->id ?>">
										<?php $color = ( $Block->time == 0 || $Block->time > time() ) ? "green" : "grey" ?>
										<div class="FixtureBlockImage">
											<a href="#" block_id="<?php echo $Block->id ?>">
												<img src="<?php echo url("public/images/misc/block_".$i."_".$color.".png", true) ?>" />
											</a>
										</div>
										
										<div class="FixtureBlockDetails">
										<?php if( ! $Block->time ): ?>
											Unknown
										<?php else: ?>
										<center>
										<p style="font-size:12px"><?php echo date("j M", $Block->time) ?> <br/>
											<?php echo date("l", $Block->time) ?></p></center>
										<?php endif ?>
										
									</div>
										
									</div>
										
									
								<?php endforeach ?>

								<div class="clear"></div>

							<?php endif ?>
							
						</div>


						<?php if( $this->Fixture->game_block->exists() ): ?>
							<!-- Game players -->
							
								
								<div id="BlockData" style="padding: 8px">

									<h4>Click on a Game to see more details</h4>

								</div>
								
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Load popup modal to invite more players 	 -->
	<div class="modal hide fade" id="invite" style="padding-bottom:0">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Invite more players</h3>
			</div>
		<div class="modal-body" style="padding:0">
			<div class="divDialogElements" style="padding:20px 20px 0 20px">
				<form id="InvitePlayersForm" action="<?php echo url("fixtures/invite_players/".$this->Fixture->id) ?>" method="post" class="form">
					<h4>Invite Players</h4>
	
						<?php echo $this->errors ?>
	
						<div id="invite_elements">
	
							<label>Player E-Mail</label>
							<input type="text" name="invite[]" /><a href="#" id="invite_more" class="btn btn-success btn-small" style="margin-left:10px;float:left"><span>Invite More</span></a>
	
							<div class="clear_10"></div>
	
						</div>

						<div class="clear_10"></div>
						
						<button class="btn btn-inverse btn-large" type="submit">Send</button>
				</form>
				</div>
			</div>
		</div>
	</div>
	
				
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

	$("#invite_more").click(function(e) {

		e.preventDefault();

		if( $("#invite_elements input").length >= 100 )
		{
			alert("You cannot invite more players to this game.");
		}
		else
		{
			$('#invite_elements').append($('#invite_template').html());
		}

	});
	
	
	$("#FixtureBlocks .FixtureBlock a").click(function(e) {
		
		e.preventDefault(); 
		
		$("#BlockData").html("<h3>Please Wait...</h3>").load( "<?php echo url('game_blocks/getData/') ?>" + $(this).attr("block_id") ).attr("block_id", $(this).attr("block_id"));
		
	});
	
	$(".BlockJoinButton").live("click", function(e) {
		
		e.preventDefault(); 
		
		<?php if( $this->Fixture->player_cost ): ?>
			if( $("#BlockData .BlockPlayer[player=<?php echo Auth::Get('id') ?>]").length > 0 )
			{
				var Confirm = "Are you sure you want to rejoin? Your last refund will be cancelled.";
			}
			else
			{
				var Confirm = "Are you sure you want to join this game? You will be charged \u00A3<?php echo number_format($this->Fixture->payp_cost, 2) ?> credits.";
			}
		
			if( confirm(Confirm) )
			{
		<?php endif ?>
				$("#BlockData").html("<h3>Please Wait...</h3>");
		
				var Block = $(this).attr("block_id");
		
				$.ajax({
					url: "<?php echo url("game_blocks/join/") ?>" + Block,
					dataType: "json",
					success: function(data) {
				
						$("#BlockData").html("<h3>Please Wait...</h3>").load( "<?php echo url('game_blocks/getData/') ?>" + Block ).attr("block_id", Block);
				
						if(data.error != undefined)
						{
							alert(data.error);
						}
						
						if( data.success != undefined )
						{
							$(".FixtureBlockJoin").hide();
						}
				
					}
				});
		<?php if( $this->Fixture->player_cost ): ?>
			}
		<?php endif ?>
		
		return false;
		
	});
	
	$(".BlockLeaveButton").live("click", function(e) {
		
		e.preventDefault(); 
		
		<?php if( $this->Fixture->player_cost ): ?>
			if( confirm("Are you sure you want to leave this game?") )
			{
		<?php else: ?>
			if( confirm("Are you sure you want to leave this game?") )
			{
		<?php endif ?>
				$("#BlockData").html("<h3>Please Wait...</h3>");
		
				var Block = $(this).attr("block_id");
		
				$.ajax({
					url: "<?php echo url("game_blocks/leave/") ?>" + Block,
					dataType: "json",
					success: function(data) {
				
						$("#BlockData").html("<h3>Please Wait...</h3>").load( "<?php echo url('game_blocks/getData/') ?>" + Block ).attr("block_id", Block);
				
						if(data.error != undefined)
						{
							alert(data.error);
						}
				
						if( data.credited != undefined )
						{
							alert("We have credited your account with \u00A3" + data.credited);
						}
				
					}
				});
			}
			
		return false;
		
	});
	
	$("input[name=paid]").click(function() {
		
		$.ajax({
			url: "<?php echo url('manager/Payment/') ?>" + $(this).attr("payment_id") + "/" + ( ( $(this).is(":checked") ) ? 1 : 0 ),
			dataType: "json",
			type: "POST",
			success: function(data) {
				
				if( data.error != undefined )
				{
					alert( data.error );
				}
				
			}
		});
		
	});
	
	$(".FixtureBlockJoin").live("click", function(e) {
		
		e.preventDefault(); 
		
		<?php if( $this->Fixture->player_cost ): ?>
			if( confirm("Are you sure you want to join this block? You will be charged \u00A3<?php echo number_format($this->Fixture->player_cost * $this->Fixture->blocks, 2) ?> credits.") )
			{
		<?php endif ?>
		
				var Fixture = $(this).attr("fixture_id");
		
				$.ajax({
					url: "<?php echo url("game_blocks/join_block/") ?>" + Fixture,
					dataType: "json",
					success: function(data) {

						if(data.error != undefined)
						{
							alert(data.error);
						}
						
						if(data.success != undefined)
						{
							$(".FixtureBlockJoin").parent().html("You have joined this block!");
							
							var CurrentGame = $("#BlockData").attr("block_id");
							
							if( ! CurrentGame )
							{
								$(".FixtureBlock").first().find("a").trigger("click");
							}
							else
							{
								$(".FixtureBlock[GameID=" + CurrentGame + "]").find("a").trigger("click");
							}
						}
				
					}
				});
		<?php if( $this->Fixture->player_cost ): ?>
			}
		<?php endif ?>
		
		return false;
		
	});
	
	<?php if( ! $this->Game ): ?>
		$(".FixtureBlock").first().find("a").trigger("click");
	<?php else: ?>
		$(".FixtureBlock[GameID=<?php echo $this->Game ?>]").find("a").trigger("click");
	<?php endif ?>
	
	$("#InvitePlayersForm").submit(function() {
		
		$.ajax({
			url: $(this).attr("action"),
			type: "POST",
			data: $(this).serialize(),
			success: function(data) {
			
				$("#invite_elements label").not(":first").remove();
				$("#invite_elements input").not(":first").remove();
				$("#invite_elements div").not(":first").remove();
				
				$("#invite_elements input").val("");
				
				$("#InvitedPlayers").html( data );
				
			}
		});
	
		return false;
		
	});

});
</script>

	<script type="text/javascript" charset="utf-8">
	
		$(document).ready(function() {
			
			$(".InviteButton").click(function() {

				$("#invite").dialog({modal: true});
				
				return false;
				
			});
			
		});
		
	</script>
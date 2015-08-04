<script src="<?php echo url("public/js/ui.js", true) ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo url("public/css/ui-lightness/jquery-ui-1.8.17.custom.css", true) ?>" type="text/css" media="screen"/>

<!-- TODO: ADD DRAGGABLE FUNCTIONALITY - WHEN ADDED JQUERY UI, IT BREAKS SOME OF THE CURRENT JAVASCRIPT LIBRARIES -->
<script type="text/javascript" charset="utf-8">
	
	$(document).ready(function() {
		
		// Click Event on a game block
		$("#GameSelector .FixtureBlock a").click(function(e) {
			
			e.preventDefault();
			
			$("#GameSelector").attr("block_id", $(this).attr("block_id"));
			
			// Reset Team Names
			$(".LeftTeamName input[type=text]").val( '' );
			$(".RightTeamName input[type=text]").val( '' );
			
			$.ajax({
				url: "<?php echo url('team_sheet/GetBlock/') ?>" + $(this).attr("block_id"),
				dataType: "json",
				type: "POST",
				success: function(data) {
					
					$("#TeamPlayers").html("");
					
					// Hide Game Selector
					$("#GameSelector").hide();
					$("#TeamSheetOverlay").fadeOut("fast");
					
					// Set Positions
					$("#TeamSheetActions select").val( data.Positions );
					
					// Load Positions
					$("#TeamSheetPitch .Team_1").load("<?php echo url("team_sheet/Positions/1/") ?>" + data.Positions, function() {
						
						$("#TeamSheetPitch .Team_2").load("<?php echo url("team_sheet/Positions/2/") ?>" + data.Positions, function() {

							// Show Teams
							$("#TeamSheet .Team_1").show();
							$("#TeamSheet .Team_2").show();

							// Show Actions
							$("#TeamSheetActions").show();
							
							// Set Team Names
							$(".LeftTeamName input[type=text]").val( data.LeftTeamName );
							$(".RightTeamName input[type=text]").val( data.RightTeamName );
							
							// Show Team Names
							$("#TeamNames").show();

							// Remove All Players
							$("#TeamSheetToolbar .TeamSheetPlayer").remove();
							$("#TeamSheetPitch .TeamSheetPlayer").remove();

							// Remove All Set Positions
							$("#TeamSheetPitch .PositionHasPlayer").removeClass("PositionHasPlayer");
							$("#TeamSheetPitch .Position[player_id]").removeAttr("player_id");
							
							// Toggle Position Names
							if( data.PositionNames == 1 )
							{
								$(".Position .PositionName").show();
							}
							else
							{
								$(".Position .PositionName").hide();
							}

							// All Players
							if( data.Players )
							{
								for( i in data.Players )
								{
									// Set Player ID
									$("#PlayerTemplate .TeamSheetPlayer").attr("player_id", data.Players[ i ].player_id);

									// Set Avatar
									$("#PlayerTemplate img").attr("src", data.Players[ i ].avatar);

									// Set Name
									$("#PlayerTemplate .TeamSheetPlayerName a").html( data.Players[ i ].name );

									// If They have position
									if( data.Players[ i ].team > 0 && data.Players[ i ].position.length > 0 )
									{
										// Get Position
										var Position = $(".Team_" + data.Players[ i ].team).find(".Position[PositionName=" + data.Players[ i ].position + "]");

										// Add player to the content
										$("#TeamPlayers").append( $("#PlayerTemplate").html() );

										// Get the player
										var TeamSheetPlayer = $("#TeamPlayers .TeamSheetPlayer").last();

										// Set his position
										TeamSheetPlayer.position({
											of: Position
										});

										// Indicate position box of having a player
										Position.addClass("PositionHasPlayer").attr("player_id", data.Players[ i ].player_id );
									}
									else
									{
										$("#TeamPlayers").append( $("#PlayerTemplate").html() );
									}

								}
								
								$("#TeamPlayers").append( '<div class="clear_10"></div>' );

							}

						});
						
					});
						
				}
			});
			
		});
		
		$("#TeamSheetActions .ChooseGame").click(function(e) {
			
			e.preventDefault();
			
			// Clear All Players
			$("#TeamPlayers").html("");
			
			// Show Game Selector
			$("#GameSelector").show();
			$("#TeamSheetOverlay").fadeIn("fast");
			
			// Hide Game Sides
			$("#TeamSheet .Team_1").hide();
			$("#TeamSheet .Team_2").hide();
			
			// Hide Team Names
			$("#TeamNames").hide();
			
			// Hide Actions
			$("#TeamSheetActions").hide();
			
			// Remove All Players
			$("#TeamSheetToolbar .TeamSheetPlayer").remove();
			$("#TeamSheetPitch .TeamSheetPlayer").remove();
			
			// Remove All Set Positions
			$("#TeamSheetPitch .PositionHasPlayer").removeClass("PositionHasPlayer");
			$("#TeamSheetPitch .Position[player_id]").removeAttr("player_id");
			
		});
		
	});
	
</script>

<div id="TeamPlayers" style="with: 100%">
	
</div>

<div id="TeamSheet">
	
	<div id="PlayerTemplate">
		
		<div class="TeamSheetPlayer">
			
			<div class="TeamSheetPlayerAvatar">
				<a href="#">
					<img width="32" height="32" src="#" />
				</a>
			</div>
			
			<div class="TeamSheetPlayerName">
				<a href="#">Unknown</a>
			</div>
			
		</div>
		
	</div>

	<div id="TeamSheetToolbar">
		
		<div id="TeamSheetActions" style="display: none; width: 155px">
			
			<a href="#" class="ChooseGame btn-small-action" style="margin-top: 5px"><span>Choose Game</span></a>
			
		</div>
		
	</div>

	<div id="TeamSheetPitch">
		
		<div class="Team_1">
			
			<!-- Positions -->
			<?php // $this->load->view("team_sheet/team_1_positions.php") ?>
			
		</div>
		
		<div class="Team_2">
			
			<!-- Positions -->
			<?php // $this->load->view("team_sheet/team_2_positions.php") ?>
			
		</div>
		
		<div id="GameSelector">
			
			<h3>Please Select a Game first</h3>
			
			<?php if( ! $this->Fixture->game_block->exists() ): ?>
				There are no games for this fixture.
			<?php else: ?>
				
					<?php $i = 0 ?>
					<?php foreach( $this->Fixture->game_block as $Block ): ?>
						<?php $i++ ?>
						<div class="FixtureBlock">
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
									<?php echo date("j M", $Block->time) ?><br/>
									<?php echo date("l", $Block->time) ?>
								<?php endif ?>
							</div>
						</div>
					<?php endforeach ?>

					<div class="clear"></div>

			<?php endif ?>
			
			
		</div>
		
		<div id="TeamSheetOverlay"></div>
		
		<div id="TeamNames" style="display: none">
			
			<div class="LeftTeamName">
				
				<form>
					<label>Team Name</label>
					<input type="text" disabled='disabled' name="name" style="border: 1px solid #ececec; padding: 2px; margin-right: 5px;" value="" />
				</form>
				
			</div>
			
			<div class="RightTeamName">
				
				<form>
					<label>Team Name</label>
					<input type="text" disabled='disabled' name="name" style="border: 1px solid #ececec; padding: 2px; margin-right: 5px;" value="" />
				</form>
				
			</div>
			
		</div>
		
	</div>
	
</div>

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
							$("#TeamPlayers .TeamSheetPlayer").remove();
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

								// Add Draggable Event
							 	$(".TeamSheetPlayer").draggable({ 
									revert: "invalid",
									zIndex: 2000
								});

								$("#TeamPlayers").droppable({
									accept: ".TeamSheetPlayer",
									drop: function( event, ui ) {

										$(".Position[player_id=" + ui.draggable.attr("player_id") + "]").removeAttr("player_id").removeClass("PositionHasPlayer");

										// Reset Player's Position
										$.ajax({
											url: "<?php echo url('team_sheet/ResetPosition') ?>",
											dataType: "json",
											type: "POST",
											data: "block_id=" + $("#GameSelector").attr("block_id") + "&player_id=" + ui.draggable.attr("player_id"),
											success: function(data) {

												if( data.error != undefined )
												{
													alert(data.error);
												}

											}
										});

									}
								});

								// Droppable Positions
								$(".Position").droppable({
									tolerance: "pointer",
									accept: function(o) {

										return ( ! $(this).hasClass("PositionHasPlayer") );

									},
									drop: function( event, ui ) {

										$(".Position[player_id=" + ui.draggable.attr("player_id") + "]").removeAttr("player_id").removeClass("PositionHasPlayer");
										$(this).addClass("PositionHasPlayer");
										$(this).attr("player_id", ui.draggable.attr("player_id"));

										// Center It
										ui.draggable.position({
											of: $(this)
										});

										// Save this data
										$.ajax({
											url: "<?php echo url('team_sheet/SavePosition') ?>",
											dataType: "json",
											type: "POST",
											data: "block_id=" + $("#GameSelector").attr("block_id") + "&player_id=" + ui.draggable.attr("player_id") + "&position=" + $(this).attr("PositionName") + "&team=" + ( ( $(this).parent().hasClass("Team_1") ) ? 1 : 2 ),
											success: function(data) {

												if( data.error != undefined )
												{
													alert(data.error);
												}

											}

										});

									}

								});

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
		
		$(".TogglePositions").click(function(e) {
			
			e.preventDefault();
			
			$(".Position .PositionName").toggle();
			
			// Save this setting
			$.ajax({
				url: "<?php echo url('team_sheet/TogglePositionNames/') ?>" + $("#GameSelector").attr("block_id"),
				dataType: "json",
				success: function(data) {
					
					if( data.error != undefined )
					{
						alert( data.error );
					}
					
				}
			});
			
		});
		
		$(".ResetPositions").click(function(e) {
			
			e.preventDefault();
			
			if( confirm("Are you sure you want to reset all positions?") )
			{
				ResetPositions();
			}
			
		});
		
		$("#TeamSheetActions select").change(function() {
			
			if( confirm("Are you sure you want to change your position layout? All positions will be reset!") )
			{
				var Block = $("#GameSelector").attr("block_id");
				
				$.ajax({
					url: "<?php echo url('team_sheet/SetPositions/') ?>" + Block,
					dataType: 'json',
					type: "POST",
					data: 'positions=' + $(this).val(),
					success: function( data )
					{
						if( data.error != undefined )
						{
							alert( data.error );
						}
						
						if( data.success != undefined )
						{
							// Reset positions
							ResetPositions();
							
							// Reload Team Sheet
							$("#GameSelector .FixtureBlockImage a[block_id=" + Block + "]").trigger("click");
						}
					}
				});
			}
			
		});
		
		$(".LeftTeamName form").submit(function() {
			
			$(this).find("input[type=submit]").val("Wait...");
			
			var Block = $("#GameSelector").attr("block_id");
			
			$.ajax({
				url: "<?php echo url('team_sheet/SaveTeamName/') ?>" + Block + "/1",
				type: "POST",
				data: $(this).serialize(),
				dataType: "json",
				success: function(data) {

					$(".LeftTeamName form input[type=submit]").val("Save");
					
					if( data.error != undefined )
					{
						alert( data.error );
					}

				}
			});
			
			return false;
			
		});
		
		$(".RightTeamName form").submit(function() {
			
			$(this).find("input[type=submit]").val("Wait...");
			
			var Block = $("#GameSelector").attr("block_id");
			
			$.ajax({
				url: "<?php echo url('team_sheet/SaveTeamName/') ?>" + Block + "/2",
				type: "POST",
				data: $(this).serialize(),
				dataType: "json",
				success: function(data) {

					$(".RightTeamName form input[type=submit]").val("Save");
					
					if( data.error != undefined )
					{
						alert( data.error );
					}

				}
			});
			
			return false;
			
		});
		
	});
	
	function ResetPositions() {
		
		var Block = $("#GameSelector").attr("block_id");
		
		$.ajax({
			url: "<?php echo url('team_sheet/ResetPositions/') ?>" + Block,
			type: "POST",
			dataType: "json",
			success: function(data) {
				
				if( data.success != undefined )
				{
					$("#GameSelector .FixtureBlockImage a[block_id=" + Block + "]").trigger("click");
				}
				
				if( data.error != undefined )
				{
					alert( data.error );
				}
				
			}
		});
		
	}
	
</script>

<div id="TeamPlayers" style="width: 100%">
	
</div>

<div class="clear_10"></div>

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
		
		<div id="TeamSheetActions" style="display: none; width: 315px">
			
			<div style="float: left">
				<a href="#" class="ChooseGame btn-small-action" style="margin-top: 5px"><span>Choose Game</span></a>
			</div>
			
			<!-- <a href="#" class="TogglePositions">Toggle Position Names</a> -->
			
			<div style="float: right; padding-top: 10px">
				<label>Positions</label>
				<select name="Positions">
				
					<option value="5-a-side">5-A-side</option>
					<option value="6-a-side">6-A-side</option>
					<option value="7-a-side">7-A-side</option>
					<option value="full">Full</option>
				
				</select>
			</div>
			
			<div class="clear"></div>
			
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
					<input type="text" name="name" style="border: 1px solid #ececec; padding: 2px; margin-right: 5px;" value="" />
					<input type="submit" style="border: 1px solid #ececec; padding: 2px; margin-top: -1px" value="Save" />
				</form>
				
			</div>
			
			<div class="RightTeamName">
				
				<form>
					<label>Team Name</label>
					<input type="text" name="name" style="border: 1px solid #ececec; padding: 2px; margin-right: 5px;" value="" />
					<input type="submit" style="border: 1px solid #ececec; padding: 2px; margin-top: -1px" value="Save" />
				</form>
				
			</div>
			
		</div>
		
	</div>
	
</div>
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
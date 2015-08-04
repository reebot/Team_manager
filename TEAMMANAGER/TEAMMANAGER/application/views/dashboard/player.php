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
	
	<div class="span9">
		<div class="row">
    		<div class="span6"><h2 style="margin-top:0">My profile</h2></div>
    	</div>
				<div class="row">
		    <div class="span4">
		    
			    <!-- Next Game          -->
        		<div class="widget widget-box">
	        		<div class="widget-header"><h3>My activity</h3></div>
		        		<div class="widget-content" style="min-height:250px">
		        			<div class="row-fluid">
			        			<div class="span3">
			        				<img src="<?php echo url("public/images/icons/small_football.jpg", true) ?>" alt="" />
			        			</div>
		        			
			        			<div class="span7">
			        			<h4>Next game</h4>
				        			<?php if( ! $this->NextGame->exists() ): ?>
								
				        			<h4>You don't have any game scheduled.</h4>
								
				        			<?php else: ?>
								
				        			Your next game is on 
				        			<a href="<?php echo url("fixtures/view/".$this->NextGame->game_block_fixture_id) ?>">
					        		<?php echo date("jS \of F o H:i", $this->NextGame->game_block_time) ?> 
					        		</a>.
					        		<?php endif ?>
					        	</div>
					        </div>
					        
					        <div class="clear"></div>
					        <div class="clear"></div>
					        
					        <div class="row-fluid">
			        			<div class="span3">
			        				<a href="#" class="icon">
			        				<img src="<?php echo url("public/images/icons/id.png", true) ?>" width="52" height="52" alt="" />
			        				</a>
			        			</div>
			        			
			        			<div class="span7">
			        				<h4>Connected Players</h4>
                    You are currently connected to <?php echo $this->ConnectedPlayers ?> <?php echo ( $this->ConnectedPlayers > 1 || $this->ConnectedPlayers == 0 ) ? "players" : "player" ?>.</h4>
			        			</div>
					        </div>
					        
		        	</div>
        		</div>
        		
		    </div>

		    <div class="span5">
		    
			    <!-- Confirmed games          -->
        		<div class="widget widget-box">
	        		<div class="widget-header"><h3>Confirmed Games</h3></div>
		        		<div class="widget-content" style="min-height:250px">
		        			<div class="row-fluid">
			        			<div class="span2">
			        				<a href="#" class="icon"><img src="<?php echo url("public/images/icons/recycle.png", true) ?>" width="52" height="52" alt="" /></a>
			        			</div>
		        			
		        			<div class="span8">
		        			<h4>Confirmed games</h4>
			        			You currently have <b><?php echo $this->ConfirmedGames->result_count() ?></b> games confirmed.</br></br>
									<?php if( $this->ConfirmedGames->exists() ): ?>
										
										
										<?php foreach( $this->ConfirmedGames as $Game ): ?>
											
											<b><?php echo date("l", $Game->time) ?></b> - <?php echo date(TIME_FORMAT, $Game->time) ?></br>
											
										<?php endforeach ?>
										
									<?php endif ?>
		        			</div>
		        		</div>
		        	</div>
        		</div>
        		
		    </div>
		    
		</div>
	</div>
	

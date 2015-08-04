
<?php if( isset($this->Account) && $this->Fixture->owner == $this->Account->id && ! $this->Block->time ): ?>
	
	<script type="text/javascript" charset="utf-8">
	
		$(document).ready(function() {
			
			$(".SetDateButton").click(function() {
				
				$("#SetDate").html("");
				$("#SetDate").load( "<?php echo url("game_blocks/setTime/".$this->Block->id) ?>" ).show();
				$("#windowTitleDialog").dialog({modal: true});
				
				return false;
				
			});
			
		});
		
	</script>

	<!-- Load popup modal to set game date 	 -->
	<div class="modal hide fade" id="windowTitleDialog" style="padding-bottom:0">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Set Time & Date</h3>
			</div>
		<div class="modal-body" style="padding:0">
			<div class="divDialogElements" style="padding:20px 20px 0 20px">
				<div id="SetDate"></div>
				</div>
			</div>
		</div>
	</div>
	
	
	
<?php endif ?>
<div class="well well-small">
	<div class="fl">
		<?php if( $this->Block->time): ?>
			<h4 style="margin:0 0 0 10px">Date: <?php echo date("jS \of F o H:i", $this->Block->time) ?></h4>
		<?php else: ?>
			<h4 style="margin:0 0 0 10px">Date: Not set</h4>
		<?php endif ?>
	</div>
</div>

<div class="well well-small">
	<div class="fr" id="BlockGameActionButtons" style="margin-left:10px">
	
		<?php if( $this->Block->time == 0 || $this->Block->time > time() ): ?>
			<?php if( $this->auth->isLogged() ): ?>
				
				<?php if( isset($this->Account) && $this->Fixture->owner == $this->Account->id && ! $this->Block->time ): ?>
					<a data-toggle="modal" href="#windowTitleDialog" class="btn btn-warning SetDateButton">Set Date</a>
				<?php endif ?>
			
				<?php if( array_key_exists( $this->Account->id, $this->Players ) && $this->Players[ $this->Account->id ]['active'] ): ?>
				
					<a href="#" class="btn btn-danger BlockLeaveButton" block_id="<?php echo $this->Block->id ?>" style="margin-right: 0px"><span>Click to Leave</span></a>
				
				<?php else: ?>
				
					<a href="#" class="btn btn-success BlockJoinButton" block_id="<?php echo $this->Block->id ?>" style="margin-right: 0px"><span>Click to Join</span></a>
				
				<?php endif ?>
			
			<?php else: ?>
			
				<a href="<?php echo url("accounts/login") ?>" class="btn-small-action" style="margin-right: 0px"><span>Login To Join</span></a>
			
			<?php endif ?>
		<?php endif ?>
		
	</div>
</div>

<div class="well well-small">
	<h4 style="margin:0 0 0 10px"><?php 
	
		$SlotsAvailable = ( ( (int) $this->Fixture->type * 2 ) - $this->Block->account->where("active", "1")->count() );
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
		
	?></h4>
</div>

<div class="well well-small">
	<?php if( ! $this->Block->account->exists() ): ?>
	
		<h4 style="margin-left:10px">There is no players in this block yet.</h4>
	
	<?php else: ?>
		
		<div class="row-fluid" style="margin:0 0 0 10px">
		<?php foreach( $this->Block->account as $Player ): ?>
			
			<div class="BlockPlayer <?php echo ( ! (int) $Player->join_active ) ? "inactive" : "" ?>" Player="<?php echo $Player->id ?>">
				<div class="BlockPlayerAvatar">
					<a href="#">
						<img src="<?php echo url( ( ! $Player->avatar) ? "public/img/player.png" : "public/uploads/".$Player->avatar, true) ?>"  />
					</a>
				</div>
				<div class="BlockPlayerName">
					<a href="#">
						<?php echo ucwords(strtolower(character_limiter($Player->first_name, 12))) ?>
					</a>
				</div>
			</div>
			
		<?php endforeach ?>
		</div>
		
	<?php endif ?>
</div>


			


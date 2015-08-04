<script src="<?php echo url("public/js/ui.js", true) ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo url("public/css/ui-lightness/jquery-ui-1.8.17.custom.css", true) ?>" type="text/css" media="screen"/>

<script type="text/javascript" charset="utf-8">
	$(function() {
		
		$("#BlockGameActionButtons").hide();
		
		$(".DatePicker").datepicker({
			dateFormat: "d MM yy"
		});
		
		$("#SetDateForm").submit(function() {
			
			$.ajax({
				
				url: $(this).attr("action"),
				dataType: "json",
				type: "POST",
				data: $(this).serialize(),
				success: function(data) {
					
					if(data.error != undefined)
					{
						alert(data.error);
					}
					
					if(data.success != undefined)
					{
						window.location.reload();
						$("#BlockData").html("<h3>Please Wait...</h3>").load( "<?php echo url('game_blocks/getData/'.$this->Block->id) ?>" ).attr("block_id", <?php echo $this->Block->id ?>);
					}
					
				}
				
			});
			
			return false;
			
		});
		
	})
	
</script>

	
	<form action="<?php echo current_url() ?>" id="SetDateForm" method="post" class="form">

					
					<?php echo $this->errors ?>
						<div class="well">					
							<h4>Game date</h4>
							<input type="text" name="date" class="DatePicker" placeholder="Click here to choose a date for the game" value="" />
													
							<h4>Game time</h4>
							<select name="hour" style="margin-right: 10px; float: left">
								<option>00</option>
								<option>01</option>
								<option>02</option>
								<option>03</option>
								<option>04</option>
								<option>05</option>
								<option>06</option>
								<option>07</option>
								<option>08</option>
								<option>09</option>
								<option>10</option>
								<option>11</option>
								<option selected>12</option>
								<option>13</option>
								<option>14</option>
								<option>15</option>
								<option>16</option>
								<option>17</option>
								<option>18</option>
								<option>19</option>
								<option>20</option>
								<option>21</option>
								<option>22</option>
								<option>23</option>
							</select>
							<select name="minute" style="margin-left: 5px; float: left; margin-right: 10px">
								<option>00</option>
								<option>05</option>
								<option>10</option>
								<option>15</option>
								<option>20</option>
								<option>25</option>
								<option selected>30</option>
								<option>35</option>
								<option>40</option>
								<option>45</option>
								<option>50</option>
								<option>55</option>
							</select>
						
						
					
				<div class="clear" style="margin:20px 0 20px 0"></div>
				<div class="modal-footer">
					<a href="#" class="btn" data-dismiss="modal">Cancel</a>
					<button class="btn btn-primary" type="submit">Save</button>
					</div>

	
	
		</form>
	</div>

<?php
	class Payment extends DataMapper
	{
		var $has_one = array("account", "game_block", "fixture");
		
		public function __construct($id = null)
		{
			parent::DataMapper($id);
		}
	}
?>
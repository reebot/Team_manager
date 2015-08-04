<?php
	class Game_Block extends DataMapper
	{
		var $validation = array(
		
			"time" => array(
				"label" => "Date",
				"rules" => array("required", "integer")
			),
			
			"team_1" => array(
				"label" => "Left Team Name",
				"rules" => array("required", "max_length" => 20)
			),
			
			"team_2" => array(
				"label" => "Right Team Name",
				"rules" => array("required", "max_length" => 20)
			)
			
		);
		
		var $has_one = array("fixture");
		
		var $has_many = array("account", "payment");
		
		public function __construct($id = null)
		{
			parent::DataMapper($id);
		}
		
		public function setDate()
		{
			if( ! $this->_validate_date() )
			{
				return false;
			}
			
			return $this->save();
		}
		
		public function _validate_date()
		{
			if( $this->time <= time() )
			{
				return $this->error_message("_validate_date", "Incorrect Date");
			}
			
			$GameBlock = new Game_Block();
			
			if( $GameBlock->where(array("fixture_id" => $this->fixture_id, "time" => $this->time))->count() )
			{
				return $this->error_message("_validate_date", "There is already one game in this block with this date.");
			}
			
			return true;
		}
	}
?>
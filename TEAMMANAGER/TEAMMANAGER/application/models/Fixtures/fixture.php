<?php

	class Fixture extends DataMapper
	{
		var $table = "fixtures";
		
		var $has_many = array("account", "invite", "game_block", "payment");
		
		var $validation = array(
			
			"location" => array(
				"label" => "Location",
				"rules" => array("required", "max_length" => 128)
			),
			
			"postcode" => array(
				"label" => "Postcode",
				"rules" => array("required", "max_length" => 128)
			),
			
			"type" => array(
				"label" => "Type",
				"rules" => array("required", "max_length" => 128)
			),
			
			"player_cost" => array(
				"label" => "Cost for Player per Block",
				"rules" => array("required", "numeric")
			),
			
			"payp_cost" => array(
				"label" => "Cost for Player per Game",
				"rules" => array("required", "numeric")
			),
			
			"blocks" => array(	
				"label" => "Blocks",
				"rules" => array("required", "integer", "valid_match" => array( 5, 10 ))
			)
		
		);
	}
?>
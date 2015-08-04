<?php
	
class Player_Manager extends DataMapper
{
	var $table = "players_managers";
	
	var $has_one = array(
		
		// Advanced Relationship
		"player" => array(
			"class"       => "account",
			"other_field" => "player"
		),
		
		"manager" => array(
			"class"       => "account",
			"other_field" => "manager"
		)
		
	);
	
	var $validation = array(
		
		"player_id" => array(
			"label" => "Player",
			"rules" => array("required", "CheckDoubleRelations")
		)
		
	);
	
	public function __construct($id = null)
	{
		parent::DataMapper($id);
	}
	
	public static function isRelatedTo($player = 0, $manager = 0)
	{
		$PlayerManager = new Player_Manager();
		
		// Query
		return ( bool ) $PlayerManager->where(array("player_id" => $player, "manager_id" => $manager))->count();
	}
	
	public function _CheckDoubleRelations()
	{
		// Check if this player is already related to this player
		if( Player_Manager::isRelatedTo( $this->player_id, $this->manager_id ) )
		{
			$this->error_message("player", "This player is already related to this manager.");
			return false;
		}
		
		return true;
	}
	
}
	
?>
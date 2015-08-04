<?php

class Manager extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		// Require Manager Access
		Auth::RequireManager();
	}
	
	public function ConnectedPlayers()
	{
		
		$this->template->setData("SelectedTab", "Connected players");
		
		// Get Connected Players
		$this->Players = $this->db->query(
		"	SELECT COALESCE(SUM(payments.amount), 0) AS TotalOwned, accounts.first_name, accounts.last_name, accounts.id, accounts.avatar
			FROM players_managers
			LEFT JOIN accounts ON accounts.id = players_managers.player_id
			LEFT JOIN payments ON players_managers.player_id = payments.account_id AND payments.paid = 0 AND payments.status = 'active'
			LEFT JOIN fixtures ON fixtures.id = payments.fixture_id
			WHERE players_managers.manager_id = ".Auth::Get('id')." AND fixtures.owner = ".Auth::Get('id')."
			GROUP BY accounts.id
		")->result(); 
				
		$this->load->view("manager/ConnectedPlayers");	
	}
	
	public function PlayerPayments($player = 0)
	{
		
		$this->template->setData("SelectedTab", "Connected players");
		
		// Find the player
		$this->Player = new Account( $player );
		
		// Make sure the player exists
		if( ! $this->Player->exists() )
		{
			Session::GoBack("Player could not be found.");
		}
		
		// Make sure manager is related to this player
		if( ! Player_Manager::isRelatedTo($this->Player->id, Auth::Get("id")) )
		{
			Session::GoBack("You are not related to this player.");
		}
	
		// Get Payments
		$this->Payments = $this->Player->payments
											->include_related("fixture", array("id", "location"))
											->include_related("game_block", array("time"))
											->where(array(
												"fixtures.owner" => Auth::Get("id")
											))
											->order_by("id", "DESC")
											->get_iterated();
												
		$this->load->view("manager/PlayerPayments");
	}
	
	public function Payment($id = 0, $status = 0)
	{
		$this->Payment = new Payment();
		
		// Try finding the payment
		$this->Payment->include_related("fixture", array("owner"))->where("id", $id)->get();
		
		// Make sure the payment exists
		if( ! $this->Payment->exists() )
		{
			$this->json->error("Payment could not be found.");
		}
		
		// Make sure the payment has right status
		if( ! in_array( strtolower($this->Payment->status), array("active") ) )
		{
			$this->json->error("Payment has invalid status.");
		}
		
		// Make sure you are the owner of paid fixture
		if( $this->Payment->fixture_owner != Auth::Get("id") )
		{
			$this->json->error("Payment could not be found.");
		}
		
		// Make sure status is valid
		if( ! in_array($status, array( 0, 1 )) )
		{
			$this->json->error("Invalid payment status.");
		}
		
		// Set and save the status
		$this->Payment->paid = $status;
		$this->Payment->save();
		
		// Send success response
		$this->json->success();
	}
}

?>
<?php
	class Dashboard extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			Auth::RequireLogin();
		}
		
		public function index()
		{
			$this->template->setData("SelectedTab", "Dashboard");
			
			if( Auth::Get("manager") )
			{
				// Get Next Game
				$this->NextGame = new Game_Block();
				$this->NextGame
						->include_related("fixture", array("id"))
						->include_related_count("account", "players_count", array("active" => 1))
						->where(array( 
							"fixtures.owner" => Auth::Get("id"), 
							"game_blocks.time > " => time() 
						))
						->limit(1)
						->order_by("game_blocks.time", "ASC")
						->get();
						
				// Get Total Owned Money
				$this->TotalOwned = $this->db->query(
				"	SELECT COALESCE(SUM(payments.amount), 0) AS TotalOwned
					FROM players_managers
					LEFT JOIN accounts ON accounts.id = players_managers.player_id
					LEFT JOIN payments ON players_managers.player_id = payments.account_id AND payments.paid = 0 AND payments.status = 'active'
					WHERE players_managers.manager_id = ".Auth::Get('id')."
				")->result();
				
				// Count Connected Players
				$this->ConnectedPlayers = new Player_Manager();
				
				$this->ConnectedPlayers = $this->ConnectedPlayers->where(array(
					"manager_id" => Auth::Get("id")
				))->count();
				
				// Get Confirmed Games
				$this->ConfirmedGames = new Game_Block();
				$this->ConfirmedGames->include_related("fixture", array("owner"))->where(array(
					"fixtures.owner" 	  => Auth::Get("id"),
					"game_blocks.time >=" => time()
				))->get_iterated();
						
				$this->load->view("dashboard/manager");
			}
			else
			{
				// Get Next Game Block
				$this->NextGame = new Account();
				$this->NextGame->where(array(
					"accounts.id" => Auth::Get("id"),
					"game_blocks.time >" => time()
				))
				->include_related("game_block")
				->limit(1)
				->order_by("game_blocks.time", "ASC")
				->get();
				
				// Count connected players
				$this->ConnectedPlayers = $this->db->query("
					SELECT count(DISTINCT accounts_game_blocks.account_id) as ConnectedPlayers
					FROM fixtures
					LEFT JOIN game_blocks ON game_blocks.fixture_id = fixtures.id
					LEFT JOIN accounts_game_blocks ON accounts_game_blocks.game_block_id = game_blocks.id
					WHERE accounts_game_blocks.account_id != ".Auth::Get("id")."
				")->result();
				
				$this->ConnectedPlayers = $this->ConnectedPlayers[0]->ConnectedPlayers;
				
				// Owned money to
				$this->OwnedTo = $this->db->query("SELECT COALESCE(SUM(payments.amount), 0) AS TotalOwned, accounts.first_name, accounts.last_name
				FROM payments
				LEFT JOIN fixtures ON fixtures.id = payments.fixture_id
				LEFT JOIN accounts ON accounts.id = fixtures.owner
				WHERE payments.paid = 0 AND payments.status = 'active' AND payments.account_id = ".Auth::Get('id')."
				GROUP BY accounts.id
				ORDER BY TotalOwned DESC
				")->result();
				
				// Confirmed Games
				$this->ConfirmedGames = new Account( Auth::Get("id") );
				
				if( ! $this->ConfirmedGames->exists() )
				{
					exit("Your account could not be loaded.");
				}
				
				// Get Games
				$this->ConfirmedGames = $this->ConfirmedGames->game_block->where(array(
					"time >=" => time()
				))->get_iterated();
				
				$this->load->view("dashboard/player");
			}
		}
	}
?>
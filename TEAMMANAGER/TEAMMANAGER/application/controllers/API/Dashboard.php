<?php
	class Dashboard extends CI_Controller
	{
		public function Get()
		{
			// Require Login
			Auth::RequireLogin(true);
			
			if( Auth::Get("manager") )
			{
				// Get Next Game
				$this->NextGame = new Game_Block();
				$this->NextGame
						->include_related("fixture", array("id"))
						->include_related_count("account", "players_count", array("active" => 1))
						->where(array( 
							"fixtures.owner"      => Auth::Get("id"), 
							"game_blocks.time > " => time() 
						))
						->limit(1)
						->order_by("game_blocks.time", "ASC")
						->get();
				
				// Count connected players		
				$this->ConnectedPlayers = new Player_Manager();
				
				$this->ConnectedPlayers = $this->ConnectedPlayers->where(array(
					"manager_id" => Auth::Get("id")
				))->count();
				
				// Get Next Game
				$NextGameTime = false;
				
				if( $this->NextGame->exists() && $this->NextGame->time )
				{
					$NextGameTime = date(TIME_FORMAT, $this->NextGame->time);
				}
				
				// Get Confirmed Games
				$this->ConfirmedGames = new Game_Block();
				$this->ConfirmedGames->include_related("fixture", array("owner", "id", "location"))->where(array(
					"fixtures.owner" 	  => Auth::Get("id"),
					"game_blocks.time >=" => time()
				))->get_iterated();
				
				$ConfirmedGames = array();
				
				foreach( $this->ConfirmedGames as $Game )
				{
					$ConfirmedGames[] = array(
						"day"        => date("l", $Game->time),
						"date"       => date(TIME_FORMAT, $Game->time),
						"future"	 => ( $Game->time > time() ) ? true : false,
						"game_id"	 => $Game->id,
						"location"   => $Game->fixture_location
					);
				}
						
				$this->json->send(array(
					"success" => "OK",
					
					"ConnectedPlayers" => $this->ConnectedPlayers,
					"NextGame"         => $NextGameTime,
					"ConfirmedGames"   => $ConfirmedGames
				));
			}
			else
			{
				// Get Next Game Block
				$this->NextGame = new Account();
				$this->NextGame->game_block->where(array(
					"accounts.id"        => Auth::Get("id"),
					"game_blocks.time >" => time()
				))
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
				
				// Get Next Game
				$NextGameTime = false;
				
				if( $this->NextGame->exists() && $this->NextGame->time )
				{
					$NextGameTime = date(TIME_FORMAT, $this->NextGame->time);
				}
				
				// Get Confirmed Games
				$this->ConfirmedGames = new Game_Block();
				$this->ConfirmedGames = $this->ConfirmedGames->where(array(
					"time >=" => time()
				))->include_related("fixture", array("location", "id"))->get_iterated();
				
				$ConfirmedGames = array();
				
				foreach( $this->ConfirmedGames as $Game )
				{
					$ConfirmedGames = array(
						"day"        => date("l", $Game->time),
						"date"       => date(TIME_FORMAT, $Game->time),
						"game_id" 	 => $Game->id,
						"location"   => $Game->fixture_location
					);
				}
						
				$this->json->send(array(
					"success" => "OK",
					
					"ConnectedPlayers" => $this->ConnectedPlayers,
					"NextGame"         => $NextGameTime,
					"ConfirmedGames"   => $ConfirmedGames
				));
			}
		}
	}
?>
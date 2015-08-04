<?php
	class Accounts extends CI_Controller
	{
		public $errors;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function index()
		{
			Auth::RequireLogin();
			
			$this->template->setData("SelectedTab", "My profile");	

			$this->Account = new Account(Auth::Get("id"));

			if( $this->input->post() )
			{
				$this->Account->first_name    = $this->input->post("first_name");
				$this->Account->last_name     = $this->input->post("last_name");
				$this->Account->mobile_number = $this->input->post("mobile_number");
				$this->Account->email         = $this->input->post("email");

				if( $this->Account->save() )
				{
					if( $_FILES['userfile']['name'] )
					{
						$config['upload_path']   = './public/uploads/';
						$config['allowed_types'] = 'gif|jpg|png';
						$config['max_size']      = '100';
						$config['max_width']     = '1024';
						$config['max_height']    = '768';
						$config['overwrite']	 = TRUE;
						$config['encrypt_name']	 = TRUE;

						$this->load->library('upload', $config);

						if ( ! $this->upload->do_upload())
						{
							$this->errors = $this->upload->display_errors();
						}
						else
						{
							$config = array();
						
							$data = $this->upload->data();
						
							$config['image_library']  = 'gd2';
							$config['source_image']   = './public/uploads/'.$data['file_name'];
							$config['create_thumb']   = FALSE;
							$config['maintain_ratio'] = FALSE;
							$config['width']          = 47;
							$config['height']         = 47;

							$this->load->library('image_lib', $config);

							$this->image_lib->resize();
						
							$this->Account->avatar = $data['file_name'];
							$this->Account->save();
						
							redirect('accounts');
						}
					}
					else
					{
						redirect('accounts');	
					}
					
				}
				else
				{
					$this->errors = $this->Account->error->string;
				}
			}
			
			if( Auth::Get("manager") == 1 )
			{
				// Get Payments - Manager
				$this->Payments = $this->db->query("
					SELECT fixtures.id as FixtureID, accounts.first_name, accounts.last_name, payments.amount, fixtures.location as FixtureLocation, game_blocks.time as GameBlockTime, payments.date as PaymentTime
					FROM payments
					LEFT JOIN game_blocks ON game_blocks.id = payments.game_block_id
					LEFT JOIN accounts ON accounts.id = payments.account_id
					LEFT JOIN fixtures ON fixtures.id = payments.fixture_id
					WHERE fixtures.owner = ".Auth::Get("id")."
					ORDER BY payments.id DESC 
				")->result();
			}
			else
			{
				// Get Payments - Player
				$this->Payments = $this->db->query("
					SELECT fixtures.id as FixtureID, payments.amount, fixtures.location as FixtureLocation, game_blocks.time as GameBlockTime, payments.date as PaymentTime, payments.type
					FROM payments
					LEFT JOIN game_blocks ON game_blocks.id = payments.game_block_id
					LEFT JOIN fixtures ON fixtures.id = payments.fixture_id
					WHERE payments.account_id = ".Auth::Get("id")."
					ORDER BY payments.id DESC 
				")->result();
			}

			$this->load->view("accounts/index");
		}
		
		public function register($code = "")
		{
			// Session::GoBack("Registration is disabled.");
			// 			
			// 			exit("Registration is Disabled.");
			
			$this->template->setData("SelectedTab", "Signup");
			
			$this->template->set("default_public");
			
			$this->code = $code;

			if( $this->input->post() )
			{
				$account = new Account();

				$account->first_name    = $this->input->post("first_name");
				$account->last_name     = $this->input->post("last_name");
				$account->mobile_number = $this->input->post("mobile_number");
				$account->email         = $this->input->post("email");
				$account->password      = $this->input->post("password");
				$account->repeat_password = $this->input->post("repeat_password");
				$account->manager		= $this->input->post("manager");
				
				// Give 100 credits
				$account->credits		= 100;

				if( $account->register() )
				{
					if( $this->input->post("tracking_code") )
					{
						redirect("accounts/login/".$this->input->post("tracking_code"));
					}
					
					redirect('accounts/login');
				}
				else
				{
					$this->errors = $account->error->string;
				}
			}

			$this->load->view('accounts/register');
		}
		
		public function login($code = "")
		{
			// // Force SSL
			// if ( $_SERVER['HTTP_X_FORWARDED_PORT'] != 443 )
			// {
			// 	header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			// }
			// 
			// // Set SSL URL
			// $this->config->set_item('base_url', str_replace("http", "https", $this->config->item('base_url')) );
			
			$this->template->setData("SelectedTab", "Login");
			
			$this->template->set("default_public");

			$this->code = $code;

			if( $this->input->post() )
			{
				if( Account::Login($this->input->post("email"), $this->input->post("password")) )
				{
					if( $this->input->post("tracking_code") )
					{
						redirect("fixtures/join_game/".$this->input->post("tracking_code"));
					}
					
					redirect("dashboard");
				}
				else
				{
					$this->errors = "Incorrect email or password.";
				}
			}

			$this->load->view("accounts/login");
		}
		
		public function join($code = "")
		{
			$this->code = $code;
			
			$this->load->view("accounts/join");
		}
		
		public function logout()
		{
			$this->session->sess_destroy();

			redirect("accounts/index");
		}
		
		public function payments()
		{
			Auth::RequireLogin();
			
			$this->template->setData("SelectedTab", "Payments");
			
			if( Auth::Get("manager") )
			{
				// Get Payments - Manager
				$this->Payments = $this->db->query("
					SELECT fixtures.id as FixtureID, accounts.first_name, accounts.last_name, payments.amount, fixtures.location as FixtureLocation, game_blocks.time as GameBlockTime, payments.date as PaymentTime
					FROM payments
					LEFT JOIN game_blocks ON game_blocks.id = payments.game_block_id
					LEFT JOIN accounts ON accounts.id = payments.account_id
					LEFT JOIN fixtures ON fixtures.id = game_blocks.fixture_id
					WHERE fixtures.owner = ".Auth::Get("id")."
					ORDER BY payments.id DESC 
				")->result();
				
				$this->load->view("accounts/payments_manager");
			}
			else
			{
				// Get Payments - Player
				$this->Payments = $this->db->query("
					SELECT fixtures.id as FixtureID, payments.amount, fixtures.location as FixtureLocation, game_blocks.time as GameBlockTime, payments.date as PaymentTime
					FROM payments
					LEFT JOIN game_blocks ON game_blocks.id = payments.game_block_id
					LEFT JOIN fixtures ON fixtures.id = game_blocks.fixture_id
					WHERE payments.account_id = ".Auth::Get("id")."
					ORDER BY payments.id DESC 
				")->result();
				
				$this->load->view("accounts/payments_player");
			}
			
		}
	}
?>
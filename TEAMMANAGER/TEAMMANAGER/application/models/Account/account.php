<?php

	class Account extends DataMapper
	{
		var $table = "accounts";
		
		var $has_many = array("fixture", "game_block", "payment");
		
		var $has_one = array();
		
		var $validation = array(
		
			"first_name" => array(
				"label" => "First Name",
				"rules" => array("required", "max_length" => 128)
			),
			
			"last_name" => array(
				"label" => "Last Name",
				"rules" => array("required", "max_length" => 128)
			),
			
			"mobile_number" => array(
				"label" => "Mobile Number",
				"rules" => array("required", "numeric", "unique")
			),
			
			"email" => array(
				"label" => "E-Mail",
				"rules" => array("required", "unique", "valid_email")
			),
			
			"password" => array(
				"label" => "Password",
				"rules" => array("required", "min_length" => 3, "max_length" => 32)
			),
			
			"manager" => array(
				'label' => "Manager",
				'rules' => array('required', 'valid_match' => array( 0, 1 ))
			),
			
			"credits" => array(
				'label' => "Credits",
				'rules' => array('numeric', 'min_size' => 0, 'max_size' => 10000)
			)
		
		);
			
		public function __construct($id = null) {
			parent::DataMapper($id);
		}
		
		public static function Login($email = "", $password = "")
		{
			$account = new Account;
			
			$account->email    = $email;
			$account->password = $password;
			
			$account->validate()->get();
		
			if( $account->exists() )
			{
				$CI =& get_instance();
				
				$CI->session->set_userdata(array(
					"logged"        => true,
					"id" 			=> $account->id,
					"first_name"    => $account->first_name,
					"last_name"     => $account->last_name,
					"access"        => $account->access,
					"email"         => $account->email,
					"mobile_number" => $account->mobile_number,
					"manager"		=> $account->manager
				));
				
				$account->lastlogin = time();
				$account->save();
				
				return $account;
			}
			
			return false;
		}
		
		public function register()
		{
			if( $this->password !== $this->repeat_password )
			{
				$this->error_message("register", "Passwords are not the same.");
				return false;
			}
			
			return $this->save();
		}
		
		public static function LoadMyAccount()
		{
			$Account = new Account( Auth::Get("id") );
			
			return ( $Account->exists() ) ? $Account : false;
		}
	}

?>
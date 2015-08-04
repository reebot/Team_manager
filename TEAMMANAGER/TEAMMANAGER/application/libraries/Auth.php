<?php

	class Auth
	{
		private $CI;
		private $username = "";
		private $logged   = false;
		private $access   = "";
		
		public function __construct()
		{
			$this->CI =& get_instance();
			
			$this->first_name    = $this->CI->session->userdata("first_name");
			$this->last_name     = $this->CI->session->userdata("last_name");
			$this->access        = $this->CI->session->userdata("access");
			$this->mobile_number = $this->CI->session->userdata("mobile_number");
			$this->email         = $this->CI->session->userdata("email");
			$this->id			 = $this->CI->session->userdata("id");
			$this->manager		 = $this->CI->session->userdata("manager");
			
			if( $this->id )
			{
				$this->logged = true;
			}
		}
		
		public function getAccess()
		{
			return $this->access;
		}
		
		public function isLogged()
		{
			return ( $this->logged ) ? true : false;
		}
		
		public static function RequireLogin($JSON = false)
		{
			if( ! $CI =& get_instance()->auth->isLogged() && ! $JSON )
			{
				redirect("accounts/login");
			}
			
			if( ! $CI =& get_instance()->auth->isLogged() && $JSON )
			{
				$CI->json->send(array(
					"LoginRequired" => true
				));
			}
			
		}
		
		public static function RequireAccess($access = array())
		{
			$CI =& get_instance();
		
			if( is_array($access) )
			{
				if( ! in_array($CI->auth->getAccess(), $access) )
				{
					redirect("accounts/login");
				}
				
				return true;
			}
			
			if( $CI->auth->getAccess() != $access )
			{
				redirect("accounts/login");
			}
		}
		
		public static function Get($key)
		{
			return $Auth =& get_instance()->auth->{$key};
		}
		
		public static function RequireManager($JSON = false)
		{
			if( ! Auth::Get("manager") && ! $JSON )
			{
				redirect("accounts/login");
			}
			
			if( ! Auth::Get("manager") && $JSON )
			{
				$CI->json->send(array(
					"LoginRequired" => true
				));
			}
			
		}
		
	}
	
?>
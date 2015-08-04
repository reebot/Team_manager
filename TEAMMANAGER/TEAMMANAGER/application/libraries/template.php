<?php

	class Template
	{
		private $template = "default_manager";
		private $content = "";
		private $data = array();
		private $CI;
		private $enabled = true;
		private $assets = array();
		
		public function __construct()
		{
			$this->CI =& get_instance();

			/* We need to select selected default icon for the template */
			$this->setData("SelectedTab", "Players");
		}
		
		public function setData($key = "", $value = "")
		{
			$this->data[$key] = $value;
		}
		
		public function getData($key = "", $default = "")
		{
			return (isset($this->data[$key])) ? $this->data[$key] : $default;
		}
		
		public function set($name = "")
		{
			$this->template = $name;
		}
		
		public function setContent($content = "")
		{
			$this->content = $content;
		}
		
		public function getContent()
		{
			return $this->content;
		}
		
		public function enable()
		{
			$this->enabled = true;
		}
		
		public function disable()
		{
			$this->enabled = false;
		}
		
		public function addAsset($type = "", $link = "")
		{
			$this->assets[$type][] = $link;
		}
		
		public function loadAssets()
		{
			// CSS Files
			if(isset($this->assets['css']))
			{
				foreach($this->assets['css'] as $css)
				{
					echo '<link rel="stylesheet" href="'.$css.'" />';
				}
			}
			
			// JS Files
			if(isset($this->assets['js']))
			{
				foreach($this->assets['js'] as $js)
				{
					echo '<script src="'.$js.'"></script>';
				}
			}
		}
		
		public function run()
		{
			// Set the content
			$this->setContent( ob_get_contents() );
			
			if( $this->enabled )
			{
				// Clear the output
				ob_end_clean();
				
				// Make sure template exists
				if( ! file_exists("public/templates/".$this->template.".php") )
				{
					show_error( "Template: ".$this->template." could not be loaded." );
				}
				
				// Save Previous URL
				$this->CI->session->set_flashdata("previous_url", current_url());
				
				// Load the template
				include( "public/templates/".$this->template.".php" );			
			}
		}
	}
	
?>
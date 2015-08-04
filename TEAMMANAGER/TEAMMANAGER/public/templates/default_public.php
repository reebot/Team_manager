<?php
	$CI =& get_instance();
	$template = $CI->template;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Team Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->

		<link rel="stylesheet" href="<?php echo base_url("public/css/bootstrap.css", true) ?>" type="text/css" media="screen" title="pink" />
		<link rel="stylesheet" href="<?php echo base_url("public/css/bootstrap-responsive.css", true) ?>" type="text/css" media="screen" title="pink" />		
		<link rel="stylesheet" href="<?php echo base_url("public/js/google-code-prettify/prettify.css", true) ?>" type="text/css" media="screen" title="pink" />
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

  </head>

  <body data-spy="scroll" data-target=".bs-docs-sidebar" style="background-color:white">

    <!-- Navbar
    ================================================== -->
    <div class="navbar navbar-inverse" style="position: static;">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo url('site/index') ?>">Team Manager</a>
          
          <div class="nav-collapse">
            <ul class="nav pull-right">
							<li class=""><a href="<?php echo url('accounts/register') ?>">Register</a></li>
              <li class="divider-vertical"></li>
							<li class=""><a href="<?php echo url('accounts/login') ?>">Login</a></li>
            </ul>
          </div><!-- /.nav-collapse -->
        </div>
      </div><!-- /navbar-inner -->
    </div><!-- /navbar -->

		<!-- Main Content
    ================================================== -->
		<div class="main">

			<div class="main-inner">

			    <div class="container">

				    <?php $CI->session->getFlash(); ?>
				    <?php echo $template->getContent() ?>

			    </div> <!-- /container -->

			</div> <!-- /main-inner -->

		</div> <!-- /main -->

    <!-- Footer
    ================================================== -->
    <footer class="footer">
      <div class="container">
        
       
      </div>
    </footer>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		<script src="<?php echo base_url("public/js/jquery.js", true) ?>" type="text/javascript"></script>
		<script src="<?php echo base_url("public/js/google-code-prettify/prettify.js", true) ?>" type="text/javascript"></script>
		<script src="<?php echo base_url("public/js/bootstrap-transition.js", true) ?>" type="text/javascript"></script>
		<script src="<?php echo base_url("public/js/bootstrap-alert.js", true) ?>" type="text/javascript"></script>
		<script src="<?php echo base_url("public/js/bootstrap-modal.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-dropdown.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-scrollspy.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-tab.js", true) ?>" type="text/javascript"></script>	
		<script src="<?php echo base_url("public/js/bootstrap-tooltip.js", true) ?>" type="text/javascript"></script>	
		<script src="<?php echo base_url("public/js/bootstrap-popover.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-button.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-collapse.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-carousel.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-typeahead.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/bootstrap-affix.js", true) ?>" type="text/javascript"></script>		
		<script src="<?php echo base_url("public/js/application.js", true) ?>" type="text/javascript"></script>


  </body>
</html>

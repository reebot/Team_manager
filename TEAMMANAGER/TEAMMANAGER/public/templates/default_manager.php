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
	<link rel="stylesheet" href="<?php echo base_url("public/css/docs.css", true) ?>" type="text/css" media="screen" title="pink" />		
	<link rel="stylesheet" href="<?php echo base_url("public/js/google-code-prettify/prettify.css", true) ?>" type="text/css" media="screen" title="pink" />
	<script src="<?php echo base_url("public/js/jquery.js", true) ?>" type="text/javascript"></script>
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

  <body data-spy="scroll" data-target=".bs-docs-sidebar">

    <!-- Navbar
    ================================================== -->
    <div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container">
        <a class="brand" href="#" style="float:left">Team Manager</a>
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          
          <div class="nav-collapse">
            <ul class="nav pull-right">
              <li class="divider-vertical"></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo url("accounts/index") ?>">Profile</a></li>
                  <li class="divider"></li>
                  <li><a href="<?php echo url("accounts/logout") ?>">Logout</a></li>
                </ul>
              </li>
            </ul>
          </div><!-- /.nav-collapse -->
        </div>
      </div><!-- /navbar-inner -->
    </div><!-- /navbar -->

		<!-- Main Content
    ================================================== -->
		<div class="main">

			<div class="main-inner">
				<div class="container" style="margin-top:10px">
						<div class="span3 bs-docs-sidebar" style="min-height:500px; width:190px">
							<!-- Secondary vertical navigation
					    ================================================== -->
							<ul class="nav nav-list bs-docs-sidenav" style="margin-top:50px">
							  <li <?php echo ($template->getData("SelectedTab") == "Dashboard") ? "class='active'" : ""; ?>><a href="<?php echo url("dashboard/index") ?>"> <i class="icon-chevron-right"></i>Dashboard</a></li>
							  <?php if( Auth::Get("manager") ): ?>
							  <li <?php echo ($template->getData("SelectedTab") == "Games I manage") ? "class='active'" : ""; ?>><a href="<?php echo url("fixtures/my_fixtures") ?>"><i class="icon-chevron-right"></i>Games I manage</a></li>
							  <li <?php echo ($template->getData("SelectedTab") == "Connected players") ? "class='active'" : ""; ?>><a href="<?php echo url("manager/ConnectedPlayers") ?>"><i class="icon-chevron-right"></i>Connected players</a></li>
							  <?php endif ?>
							  <li <?php echo ($template->getData("SelectedTab") == "Games I play") ? "class='active'" : ""; ?>><a href="<?php echo url("fixtures/my_games") ?>"><i class="icon-chevron-right"></i>Games I play</a></li>
							  <li <?php echo ($template->getData("SelectedTab") == "All games") ? "class='active'" : ""; ?>><a href="<?php echo url("fixtures/") ?>"><i class="icon-chevron-right"></i>All games</a></li>
							  <li <?php echo ($template->getData("SelectedTab") == "Payments") ? "class='active'" : ""; ?>><a href="<?php echo url("accounts/payments") ?>"><i class="icon-chevron-right"></i>Payments</a></li>
							  <li <?php echo ($template->getData("SelectedTab") == "My profile") ? "class='active'" : ""; ?>><a href="<?php echo url("accounts/index") ?>"><i class="icon-chevron-right"></i>My profile</a></li>
							  
							</ul>
						</div>

						

				    <?php $CI->session->getFlash(); ?>
				    <?php echo $template->getContent() ?>


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
		
		<script src="<?php echo url("public/js/jquery.tools.js", true) ?>" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" async="" src="https://d1ros97qkrwjf5.cloudfront.net/41/eum/rum.js"></script>
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
		
		<script type="text/javascript" charset="utf-8">

function CalculateFees()
{
	var PitchCost = parseFloat( $("input[name=pitch_cost]").val() );
	var Blocks = parseInt( $("select[name=blocks]").val() );
	var Type = parseInt($("select[name=type]").val() );
	
	if( PitchCost )
	{
		PitchCost = PitchCost.toFixed(2);
		$("#FeeCalculator").html("&pound;" + PitchCost + " / " + (Blocks) + " Games = &pound;" + ( PitchCost / Blocks ).toFixed(2) + " Per game<br/>" + ( Type * 2 ) + " Players = &pound;" + ( (( PitchCost / Blocks ).toFixed(2) / ( Type * 2) ).toFixed(2) ) ).show();	
	}
	else
	{
		$("#FeeCalculator").hide();
	}
	
}	

$(document).ready(function() {
	
	$("input[name=pitch_cost]").keyup(function() {
		CalculateFees();
	});
	
	$("select[name=blocks]").change(function() {
		CalculateFees();
	});
	
});
	
</script>
</body>
</html>

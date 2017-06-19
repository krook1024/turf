<?php
	include("./res/php/func.php");
?>

<html>
	<head>
		<meta charset="UTF-8" />
		<title>P:SA Turf Map</title>
		<link rel="prefetch" href="res/img/map.jpg">
		<link rel="stylesheet" href="res/css/style.css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="res/js/scrollsync.js"></script>
		<script type="text/javascript" src="res/js/dragscrollable.js"></script>

		<script type="text/javascript" data-cfasync="false">			
			var _factions = new Array();
			
			$('#viewport').scroll(function() {
			  $('#viewport').toggle();
			});
					
			
			$(function() {
				$('#viewport').
					scrollsync({targetSelector: '#viewport', axis : 'x'});
				$('#viewport').
					dragscrollable({dragSelector: '.dragger', acceptPropagatedEvent: false});
				$('#panel').
					scrollsync({targetSelector: '#panel', axis : 'x'});
				$('#panel').
					dragscrollable({dragSelector: '.dragger:first', acceptPropagatedEvent: true});
				 				
			});
			
			$(document).ready(function() {
				scrMapTo(5100, 4600);

				$(".turf").hide();
				$(".faction").click(function() {
					$(this).children(".turf").slideDown(500);
				});
			});
			
			function scrMapTo(x, y)
			{
				$("#viewport").animate({scrollTop: y-(($(window).height())*0.5), scrollLeft: x-(($(window).width())*0.5)},1000);
			}

		</script>

	</head>

	<body>
		<div id="panel">
			<div id="panel_head">
					<h1>Project San Andreas</h1>
					<h2>Frakció Területek</h2>
					<h3>by <strong>b1s</strong></h3>
			</div>
			<div id="panel_content">
				<hr>
				<?php
					loadTurfList();
				?>
			</div>
		</div>
		<div id="viewport">
			<div id="map" class="dragger"></div>

			<?php
				loadTurfs();
			?>	
		</div>
	</body>
</html>
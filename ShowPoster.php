<?php

require_once '../../Google/google-api-php-client-2.2.0/vendor/autoload.php';

require_once 'config.php';

require_once 'DataAccess/PosterDataAccess.php';

header('Content-Type: text/html; charset=utf-8');

try {
	$poster = getPosterData($_GET['PosterID'])[0];
} catch (Exception $e) {
	echo $e;
	exit;
}
?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="BasePosterStyle.css">
	<style>
		<?php echo $poster["Annotations Style"]; ?>
	</style>
</head>

<body>
	<div class="poster">
		<div id='mainBody'>
			<img id='mainPoster' src="<?php echo $poster["Poster URL"]; ?>" draggable="false" ondragstart="return false;"/>
			
			<?php echo $poster["Annotation DOM"]; ?>
		</div>
		
		<div id='outline'>
			<div id='outlineBody'>
				<div id='outlineHandle'>|||</div>
			</div>
		</div>
		
		<div id='hotspotBG'></div>
		
		<div id="hotspotPopup">
			<div id='hotspotClose'>&times;</div>
			
			<div id="hotspot">
				<h1 id="hotspotHeader"></h1>
			
				<div id="hotspotContent">
					
				</div>
			</div>
		</div>
	</div>
</body>

<script
  src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>

<script>
	var annotations = <?php echo $poster["Annotations"]; ?>;
	
	function hideHotspot() {
		$("#hotspotBG").hide("fast");
		$("#hotspotPopup").hide("fast");
		
		$("#hotspotHeader").html("");
		$("#hotspotContent").html("");
	}

	function showHotspot(ann) {
		$("#hotspotHeader").html(ann["name"]);
		
		$("#hotspotContent").html(ann["content"]);
		
		$("#hotspotBG").show("fast");
		
		$("#hotspotPopup").show("fast");
	}
	
	function showOutline() {
		$("#outline").animate({left: "0%"});
	}
	
	function hideOutline() {
		$("#outline").animate({left: "-30%"});
	}
	
	$(document).ready(function() {
		var isShowOutline = false;
		
		Object.keys(annotations).forEach(obj => {
			var ann = annotations[obj];
			
			$("#outlineBody").append("<div class='outlineLink' data-ann_id='" + obj + "'>" + ann["name"] + "</div>");
		});
		
		$("#outlineHandle").click(function() {
			if (isShowOutline) {
				hideOutline();
			} else {
				showOutline();
			}
			
			isShowOutline = !isShowOutline;
		});
		
		$(".hotspot, .outlineLink").on("click", function() {
			var ann = annotations[$(this).data("ann_id")];
			
			showHotspot(ann);
		});
		
		$("#hotspotClose").click(function() {
			hideHotspot();
		});
	});
</script>
</html>
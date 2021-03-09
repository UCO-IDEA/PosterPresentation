<html>

<head>
	<link rel="stylesheet" type="text/css" href="BasePosterStyle.css">

	<style>
		#Annotation_0 {
			top: 13%;
			left: 2%;
			width: 33%;
			height: 23%;
			border: 3px solid yellow;
		}
		
		#Annotation_1 {
			top: 12.5%;
			left: 38%;
			width: 27%;
			height: 25.5%;
		}
		
		#Annotation_2 {
			top: 44%;
			left: 1%;
			width: 16%;
			height: 9%;
			border-color: purple;
		}
		
		#Annotation_3 {
			top: 12%;
			left: 67%;
			width: 30%;
			height: 34%;
			border: 0px solid green;
		}
	</style>
</head>

<body>
	<div class="poster">
		<div id='mainBody'>
			<img id='mainPoster' src="government.jpg" draggable="false" ondragstart="return false;"/>
			
			<div id='annotationDom'>
				<div class="hotspot visibleHotspot highlight" id='Annotation_0' data-ann_id='0'></div>
				<div class="hotspot visibleHotspot highlight" id='Annotation_1' data-ann_id='1'></div>
				<div class="hotspot visibleHotspot highlight" id='Annotation_2' data-ann_id='2'></div>
				<div class="hotspot highlight"  id='Annotation_3' data-ann_id='3'></div>
			</div>
		</div>
		
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
  src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
  integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
  crossorigin="anonymous"></script>

<script>
	var annotations = {
		1: {"id": 1, "name":"Gender Differences in Creative Activities", "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."},
		2: {"id": 2, "name":"Crafts Include Stuff Like", "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."},
		0: {"id": 0, "name":"WTF is this pie chart?", "content": "This is not how pie charts work. WTF canada. It's not that hard."},
		3: {"id": 3, "name":"Employment Status:", "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."}
	};

	function showHotspot(ann) {
		$("#hotspotHeader").html(ann["name"]);
		
		$("#hotspotContent").html(ann["content"]);
		
		$("#hotspotPopup").show("fast");
	}
	
	$(document).ready(function() {
		$(".hotspot").click(function() {
			var ann = annotations[$(this).data("ann_id")];
			
			showHotspot(ann);
		});
		
		$("#hotspotClose").click(function() {
			$(this).parent().hide("fast");
		});
	});
</script>
</html>
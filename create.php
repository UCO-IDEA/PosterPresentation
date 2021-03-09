<!DOCTYPE html>
<html>
<head>
<?php 
	include("head.php");
?>

<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="createStyle.css">
<link rel="stylesheet" type="text/css" href="BasePosterStyle.css">

<style id="posterStyle">
	#hotspotPopup {
		background-color: #eeeeee;/*Popup_Color*/
		border-color: #efc832;/*Popup_Border_Color*/
	}
	.input-group{
		height: 12px!important;
	}
	.input-group input{
		width:60%;
	}
	.input-color{
		width: 2rem;
		height: 2rem;
		border: none;
		background:none;
	}
	.nav-tabs{
		border: none;
	}
	.divider{
		margin-left: 1rem;
	}
	.nav{
		padding-left: 15px;
	}
	#results a{
		color: #80e7ff;
		font-weight: bold;
	}
</style>

</head>

<body>
	<div class="container-fluid">
		<div class="container">
			<div class='row'>
					<div class="logo_img_small col-1">
						<img class="img-fluid" src="logo/Digital_Poster_Logo-Large.svg">
					</div>
					<div class="col-1 heading_small primary-color">
						<h4> Digital<br>Poster</h4>
					</div>
					<div class="ml-auto col-6 input-group pt-3">
						<input type="text" disabled="disabled" placeholder="Poster Title" id="posterName" aria-describedby="ChoosePoster" name="posterName" />
						<div class="input-group-append pointer">
							<span class="input-group-text launchToolPopup rightButton" id="ChoosePoster">Edit</span>
						</div>
					</div>
					<div class="col-2 pt-3">
						<button id='download' type='button' class="btn btn-primary">Finish</button>
					</div>
			</div>
		</div>
	</div>

	<div class="container-fluid" id="content-tools">	
		<div class="container">
		<div class="posterEdit">
			<div class='row'>
				<div class='ml-auto col-3 tool'>
					<h4>Mode</h4>
					
					<div class="btn-group btn-group-toggle" id="toolOption" data-toggle="buttons">
						<label class="btn btn-primary active">
							<input type="radio" name="annEditMode" id="editAnnMode" value="Edit" checked> Edit
						</label>
						<label class="btn btn-secondary">
							<input type="radio" name="annEditMode" id="previewAnnMode" value="Preview"> Preview
						</label>
					</div>
					
				</div>
				
				<div class="col-3 tool">
					<h4>Pop-Up Colors</h4>
					<div>
						<input type='color' class="input-color" style='display: inline-block' name="popupColor" id="popupColor" value="#eeeeee" />
						<label for="popupColor">Box</label>
					</div>
					<div>
						<input type='color' class="input-color" style='display: inline-block' name="popupBorderColor" id="popupBorderColor" value='#efc832' />
						<label for="popupBorderColor">Border</label>
					</div>
				</div>

				<div class='mr-auto col-3 tool'>
					
					<div id='results'></div>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	<div class="container-fluid" id="content-poster">
		<div class="posterDemo">
			<div id='mainBody'>
				<img id='mainPoster' src="" style="display:none" draggable="false" ondragstart="return false;"/>
				
				<div id='annotationDom'>
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
		
		<div class="toolPopup" id="ChoosePoster_popup">
			<div class='popupClose'>&times;</div>
			<h2 class="primary-color">Poster Image</h2>
			<div class="toolExplain">
				<p>Enter the URL of your image that you have created.</p>
				<p>Remember, you can host your image for free at Imgur.</p>
			</div>
			<label for="ChoosePosterURL" class="primary-color">Poster URL:</label>
			<input type='text' name="ChoosePosterURL" id="ChoosePosterURL" value='' />
			<div class="ErrorMessage" id="ChoosePosterURL_error"></div>
			<button class="btn btn-primary completePopup" id="completeChoosePoster" disabled="">Choose</button>
		</div>
		
		<div class="toolPopup" id="AnnotationEdit_popup" style="display:none;">
			<div class='popupClose'><span aria-hidden="true">&times;</span></div>
			<div class='popupContent row'>
				<h2 class="primary-color col-12">Edit Annotation</h2>
				<div class="toolExplain col-12"><p>Create appearance and content. Acceptable media sources include YouTube, Imgur, and uco.edu.</p></div>
				
				<ul class="nav nav-tabs col-12" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link btn btn-primary active" id="appearance-tab" data-toggle="tab" href="#appearance" role="tab" aria-controls="appearance" aria-selected="true">Appearance</a>
					</li>
					<li class="nav-item">
						<a class="nav-link btn btn-secondary" id="content-tab" data-toggle="tab" href="#content" role="tab" aria-controls="content" aria-selected="false">Content</a>
					</li>
				</ul>

				<div class="tab-content col-12" id="myTabContent">
					<div class="tab-pane fade show active" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
						<div class="col-12 mt-3 text-blue">
							<label for="AnnotationName"><h3>Header</h3></label>
							<input type='text' class="full-width" name="annotationName" id="annotationName" placeholder="Poster Title" />
						</div>
						
						<div class="col-12 mt-5 text-blue">
							<label class=""><h3>Poster Border Color</h3></label>
						</div>

						<div class="col-12 text-blue">
							<input type='color' class="input-color" value="#E9ECEF" name="visibleColor" id="visibleColor" />
							<label for="visibleColor">Border</label>
							
							<input type='checkbox' class="ml-4" name="isVisible" id="isVisible" />
							<label for="isVisible">Visible</label>
							
						</div>

						<hr class="col-2 divider">
						
						<div class="col-12 text-blue">
							<input type='color' class="input-color" value="#5A6169" name="highlightColor" id="highlightColor" />
							<label for="highlightColor">Highlight</label>
							
							<input type='checkbox'  class="ml-2" name="isHighlight" id="isHighlight" />
							<label for="isHighlight">Visible</label>
						</div>
						
					</div>
					<div class="tab-pane fade mt-4" id="content" role="tabpanel" aria-labelledby="content-tab">
						<div class="col-12">
							<textarea class='annotationContent'></textarea>
						</div>
					</div>
				</div>
								
				
				<input type='hidden' name="annotationID" id="annotationID" />
				
				<div class='toolButtons'>
					<button class="rightButton btn btn-danger completePopup redButton" style="padding: 0.5rem 2rem 0.5rem 2rem;" id="deleteAnnotation">Delete Annotation</button>
					<button class="leftButton btn btn-primary completePopup" id="confirmAnnotationChanges">Save</button>
					<button class="leftButton btn btn-primary" id="rejectAnnotationChanges">Cancel</button>
				</div>
			</div>
		</div>
		
		<div class='error'>
		
		</div>
		<div class="well">
		</div>
	</div>
	</div>

	<div class="container-fluid">
		
			<?php include("footer.php"); ?>
			
	
	</div>
</body>

<?php include("config.php"); ?>
<script>
	var allowedDomains = <?php echo json_encode($config['allowedDomains']); ?>;
	var allowedFileTypes = <?php echo "/\.(" . implode("|", $config['acceptedTypes']) . ")/gi";?>;
	
	var acceptedTypes = allowedFileTypes;
</script>

<script src="tinymce/tinymce.min.js"></script>
<script src="createPoster.js"></script>
<script>
$(document).ready(function() {
	$("#toolOption label:first-child").click(function(){
		if(!$(this).hasClass('btn-primary')){
			$(this).removeClass('btn-secondary');
			$(this).addClass('btn-primary');
			$("#toolOption label:nth-child(2)").addClass("btn-secondary");
			$("#toolOption label:nth-child(2)").removeClass("btn-primary");
		}
	});

	$("#toolOption label:nth-child(2)").click(function(){
		if(!$(this).hasClass('btn-primary')){
			$(this).removeClass('btn-secondary');
			$(this).addClass('btn-primary');
			$("#toolOption label:nth-child(1)").addClass("btn-secondary");
			$("#toolOption label:nth-child(1)").removeClass("btn-primary");
		}
	});


	$("#myTab .nav-item:nth-child(1)").click(function(){
		let child = $(this).children('.nav-link');
		let child2 = $("#myTab .nav-item:nth-child(2)").children(".nav-link");

		if(!$(child).hasClass('btn-primary')){
			$(child).removeClass('btn-secondary');
			$(child).addClass('btn-primary');
			$(child).attr('style', 'border:1px solid #17C671!important');
			
			$(child2).removeClass('btn-primary');
			$(child2).addClass('btn-secondary');
			$(child2).attr('style','border: 1px solid #5A6169!important; border-left: none!important');
		}
	});

	$("#myTab .nav-item:nth-child(2)").click(function(){
		let child = $(this).children('.nav-link');
		let child2 = $("#myTab .nav-item:nth-child(1)").children(".nav-link");

		if(!$(child).hasClass('btn-primary')){
			$(child).removeClass('btn-secondary');
			$(child).addClass('btn-primary');
			$(child).attr('style', 'border:1px solid #17C671!important');
			
			$(child2).removeClass('btn-primary');
			$(child2).addClass('btn-secondary');
			$(child2).attr('style','border: 1px solid #5A6169!important; border-right: none!important');
		}
	});

});

</script>
</html>
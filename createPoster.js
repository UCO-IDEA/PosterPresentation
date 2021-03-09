var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}'+ // domain name
    '(\\:\\d+)?)(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator

var mediaUrlPattern = new RegExp('^(https?:)?(\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}'+ // domain name
    '(\\:\\d+)?)(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
	


var posterURL = "";
var posterName = "";

var annEditMode = "Edit";
var annotationCount = 0;
var annotations = {};

var movingAnnotation = null;
var movingType = null;
var movingTypeFunctions = {"TL" : resizeTL, "TR": resizeTR, "BL": resizeBL, "BR": resizeBR, "MV": moveAnnotation};
var startDragEvent = null;

function changeStyle(name, regex, newValue) {
	/*
	console.log("Change Style");
	console.log(name);
	console.log(regex);
	console.log(newValue);
	
	console.log($('#posterStyle').html().match(regex));
	*/
	
	$('#posterStyle').html($('#posterStyle').html().replace(regex, newValue + ";/*" + name + "*/"));
}

function changeColor(colorName, newColor) {
	var regex = new RegExp("\\#[0-9A-z]+;\\/\\*" + colorName+ "\\*\\/", "gi");
	
	changeStyle(colorName, regex, newColor);
}

function changeBorderWidth(name, newValue) {
	var regex = new RegExp("[0-9A-z]+;\\/\\*" + name + "\\*\\/", "gi");
	
	var newVal = newValue + "px";
	
	changeStyle(name, regex, newVal);
}

function deleteAnnotationStyle(annID) {
	var regex = new RegExp("\\#Annotation_" + annID + " {\n.*}\\/\\*Annotation_" + annID + " End\\*\\/", "gis");
	
	var styleHTML = $('#posterStyle').html();
	
	var matches = styleHTML.match(regex);
	
	styleHTML = styleHTML.replace(regex, "\n");
	
	$('#posterStyle').html(styleHTML);
}

function createAnnotationStyle(ann) {
	var nameBase = "/*Annotation_" + ann["id"];
	
	var styleString = "\n#Annotation_" + ann["id"] + " {\n" +
			"top: " + ann["position"]["top"] + "%;" + nameBase + "_top*/\n" +
			"left: " + ann["position"]["left"] + "%;" + nameBase + "_left*/\n" +
			"width: " + ann["position"]["width"] + "%;" + nameBase + "_width*/\n" +
			"height: " + ann["position"]["height"] + "%;" + nameBase + "_height*/\n" +
			"border-color: " + ann["visibleColor"] + ";" + nameBase + "_visibleColor*/\n" +
			"border-width: " + (ann["visible"] ? 3 : 0) + "px;" + nameBase + "_visible*/\n" +
		"}#Annotation_" + ann["id"] + ":hover {\n" +
			"border-color: " + ann["highlightColor"] + ";" + nameBase + "_highlightColor*/\n" +
			"box-shadow: 0px 0px 10px " + ann["highlightColor"] + ";" + nameBase + "_highlightColor*/\n" +
			"border-width: " + (ann["highlight"] ? 3 : 0) + "px;" + nameBase + "_highlight*/\n" +
		"}" + nameBase + " End*/\n";
	
	$('#posterStyle').append(styleString);
}

function applyAnnotationChanges(ann) {
	var nameBase = "Annotation_" + ann["id"];
	
	if (ann["visible"]) {
		changeColor(nameBase + "_visibleColor", ann["visibleColor"]);
		changeBorderWidth(nameBase + "_visible", 3);
	} else {
		changeBorderWidth(nameBase + "_visible", 0);
	}
	
	
	if (ann["highlight"]) {
		changeColor(nameBase + "_highlightColor", ann["highlightColor"]);
		changeBorderWidth(nameBase + "_highlight", 3);
	} else {
		changeBorderWidth(nameBase + "_highlight", 0);
	}
}



function saveAnnotationChanges(ann) {
	var encodedStr = $("#AnnotationEdit_popup #annotationName").val().replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
	   return '&#'+i.charCodeAt(0)+';';
	});
	
	ann["name"] = encodedStr;
	
	ann["visible"] = $("#AnnotationEdit_popup #isVisible").prop( "checked");
	ann["visibleColor"] = $("#AnnotationEdit_popup #visibleColor").val();
	
	ann["highlight"] = $("#AnnotationEdit_popup #isHighlight").prop( "checked");
	ann["highlightColor"] = $("#AnnotationEdit_popup #highlightColor").val();
	
	ann["content"] = tinymce.activeEditor.getContent();
	
	applyAnnotationChanges(ann);
}

function hideHotspot() {
	$("#hotspotHeader").html("");
	$("#hotspotContent").html("");
	
	
	$("#hotspotBG").hide("fast");
	$("#hotspotPopup").hide("fast");
}

function showHotspot(ann) {
	$("#hotspotHeader").html(ann["name"]);
	$("#hotspotContent").html(ann["content"]);
	
	$("#hotspotBG").show("fast");
	$("#hotspotPopup").show("fast");
}

function validImgURL(str) {
	var matches = str.trim().match(urlPattern);
	
	if (matches == null) {
		return {"result": false, "reason": "Not a valid URL"};
	} else {
		if (allowedDomains.indexOf(matches[2]) == -1) {
			return {"result": false, "reason": "Not allowed domain."};
		} else if (!acceptedTypes.test(matches[7])) {
			return {"result": false, "reason": "Only jpg, pdf, png, and gifv files are accepted."};
		}
	}
	
	posterName = matches[7].substring(1);
	posterURL = str;
	
	return {"result": true, "name": posterName};
}

var moveAnnDom = "<div class='resizeBox topLeft' data-type='TL'></div><div class='resizeBox topRight' data-type='TR'></div><div class='resizeBox bottomLeft' data-type='BL'></div><div class='resizeBox bottomRight' data-type='BR'></div><div class='centerMove' data-type='MV'></div>";
var contentAnnDom = "<div class='contentEdit'></div>";

function createAnnotationMove() {
	$(".hotspot").html(moveAnnDom);
}

function removeAllAnnotationMove() {
	$(".hotspot").html("");
}

function createAnnotationContent() {
	$(".hotspot").html(contentAnnDom);
}

function removeAllAnnotationContent() {
	$(".hotspot").html("");
}



function loadAnnotationToEdit(ann) {
	$(".toolPopup:not(#AnnotationEdit_popup)").hide("fast");
	
	$("#AnnotationEdit_popup").hide("fast");
	
	$("#AnnotationEdit_popup").show("fast");
	
	$("#AnnotationEdit_popup #annotationID").val(ann["id"]);
	
	$("#AnnotationEdit_popup #annotationName").val(ann["name"]);
	
	$("#AnnotationEdit_popup #isVisible").prop( "checked", ann["visible"]);
	$("#AnnotationEdit_popup #visibleColor").val(ann["visibleColor"]);
	
	$("#AnnotationEdit_popup #isHighlight").prop( "checked", ann["highlight"]);
	$("#AnnotationEdit_popup #highlightColor").val(ann["highlightColor"]);
	
	
	tinymce.activeEditor.setContent(ann["content"], {format: "raw"});
}

var dragOffsetX = 0;
var dragOffsetY = 0;
var imgWidth = $("#mainPoster").outerWidth();
var imgHeight = $("#mainBody").height();

function dragWidth(X){
	var newWidth = (X - parseFloat($(movingAnnotation).css("left"))) / imgWidth * 100;
	
	var name = movingAnnotation[0].id + "_width";
	
	var regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, newWidth + "%");
} 

function dragHeight(Y) {
	var newHeight = (Y - parseFloat($(movingAnnotation).css("top"))) / imgHeight * 100;
	
	var name = movingAnnotation[0].id + "_height";
	
	var regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, newHeight + "%");
}

function dragTop(Y) {
	var newTop = Y / imgHeight;
	var topDiff = Y - parseFloat($(movingAnnotation).css("top"));
	var newHeight = (parseFloat($(movingAnnotation).css("height")) - topDiff) / imgHeight;
	
	var name = movingAnnotation[0].id + "_top";
	
	var regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, (newTop * 100) + "%");
	
	name = movingAnnotation[0].id + "_height";
	
	regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, (newHeight * 100) + "%");
}

function dragLeft(X) {
	var newLeft = X / imgWidth;
	var leftDiff = X - parseFloat($(movingAnnotation).css("left"));
	var newWidth = (parseFloat($(movingAnnotation).css("width")) - leftDiff) / imgWidth;
	
	var name = movingAnnotation[0].id + "_left";
	
	var regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, (newLeft * 100) + "%");
	
	name = movingAnnotation[0].id + "_width";
	
	regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, (newWidth * 100) + "%");
	
}

function moveAnnotation(X, Y) {
	X -= dragOffsetX;
	Y -= dragOffsetY;
	
	cmOffset = $(movingAnnotation).children(".centerMove").offset();
	
	X -= (cmOffset["left"] - $(movingAnnotation).offset()["left"]);
	Y -= (cmOffset["top"] - $(movingAnnotation).offset()["top"]);
	
	var newTop = Y / imgHeight;
	var newLeft = X / imgWidth;
	
	var name = movingAnnotation[0].id + "_top";
	
	var regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, (newTop * 100) + "%");
	
	name = movingAnnotation[0].id + "_left";
	
	regex = new RegExp("[0-9\.]+%;\\/\\*" + name + "\\*\\/", "gi");
	
	changeStyle(name, regex, (newLeft * 100) + "%");
}

function resizeTL(X, Y) {
	dragTop(Y - dragOffsetY);
	dragLeft(X - dragOffsetX);
}

function resizeTR(X, Y) {
	dragWidth(X + dragOffsetX);
	dragTop(Y - dragOffsetY);
}

function resizeBL(X, Y) {
	dragHeight(Y + dragOffsetY);
	dragLeft(X - dragOffsetX);
}

function resizeBR(X, Y) {
	dragWidth(X + dragOffsetX);
	dragHeight(Y + dragOffsetY);
}

$(document).ready(function() {
	$("#mainBody").on("mousedown", ".resizeBox, .centerMove", function(e) {
		movingAnnotation = $(this).parent(".hotspot");
		movingType = movingTypeFunctions[$(this).data("type")];
		
		startDragEvent = e;
		
		imgWidth = $("#mainPoster").outerWidth();
		imgHeight = $("#mainBody").height();
		
		dragOffsetX = e.offsetX;
		dragOffsetY = e.offsetY;
	});
	
	$("#mainBody").on("mouseup", function() {
		movingAnnotation = undefined;
		movingType = undefined;
	});
	
	$("#mainBody").on("mousemove", function(e) {
		if (movingAnnotation != undefined && movingType != undefined) {
			movingType(e.pageX - $("#mainPoster").offset()["left"], e.pageY - $("#mainPoster").offset()["top"]);
		}
	});
	
	$("#popupColor").on("input", function(e) {
		changeColor("Popup_Color", $(this).val());
	});
	
	$("#popupBorderColor").on("input", function(e) {
		changeColor("Popup_Border_Color", $(this).val());
	});
	
	//#####################################################
	// Resize & Move Stuff
	//#####################################################
	$(".launchToolPopup").click(function() {
		$(".toolPopup:not(#" + this.id + "_popup)").hide("fast");
		
		$("#" + this.id + "_popup").show("fast");
	});
	
	$("#ChoosePosterURL").on("input", function(e) {
		newVal = $(this).val();
		
		var testResult = validImgURL(newVal);
		
		if (testResult["result"] === true) {
			$("#ChoosePosterURL_error").html("");
			$("#completeChoosePoster").prop('disabled', false);
		} else {
			$("#completeChoosePoster").prop('disabled', true);
			$("#ChoosePosterURL_error").html(testResult["reason"]);
		}
	});
	
	//############################################
	//Annotation Popup Events
	//############################################
	
	$("#confirmAnnotationChanges").click(function() {
		var annID = $("#annotationID").val();
		
		saveAnnotationChanges(annotations[annID]);
		
		$("#AnnotationEdit_popup").hide("fast");
	});
	
	$("#deleteAnnotation").click(function() {
		var annID = $("#annotationID").val();
		
		var conf = confirm("Click 'OK' to confirm and delete the annotation \"" + $("#annotationName").val() + "\"");
		
		if (conf == true) {
			$("#Annotation_" + annID).remove();
			
			deleteAnnotationStyle(annID);
			
			delete annotations[annID];
			
			$("#AnnotationEdit_popup").hide("fast");
		}
	});
	
	$("#rejectAnnotationChanges").click(function() {
		$("#AnnotationEdit_popup").hide("fast");
	});
	
	//############################################
	//Annotation Popup Events End
	//############################################
	
	$("#completeChoosePoster").click(function() {
		$("#mainPoster").prop("src", posterURL);
		
		$("#mainPoster").show("fast");
		
		$("#posterName").val(posterName);		
	});
	
	$('input[type=radio][name=annEditMode]').change(function() {
		annEditMode = this.value;
		
		if (annEditMode == "Edit") {
			createAnnotationMove();
		} else {
			removeAllAnnotationMove();
		}
	});
	
	$("#mainBody").on("dblclick", ".hotspot", function() {
		var ann = annotations[$(this).data("ann_id")];
		
		switch(annEditMode) {
			case "Edit": loadAnnotationToEdit(ann); break;
			case "Preview": showHotspot(ann); break;
		}
	});
	
	$("#hotspotClose").click(function() {
		hideHotspot();
	});
	
	$(".popupClose, .completePopup").click(function() {
		$(this).parent(".toolPopup").hide("fast");
	});
	
	$("#mainPoster").on("dblclick", function(e) {
		var imgWidth = $("#mainPoster").outerWidth();
		var imgHeight = $("#mainBody").height();
		
		var top = e.offsetY / imgHeight * 100
		var left = e.offsetX / imgWidth * 100
		
		var height = Math.min(20, 100 - top);
		var width = Math.min(25, 100 - left);
		
		annotationCount++;
		
		annotations[annotationCount] = {"id": annotationCount, "name":"Annotation " + annotationCount, "visible": true, "highlight": true,
		"visibleColor": "#002d61", "highlightColor": "#2ec1f1", "position": {"top": top,  "left": left, "width": width, "height": height}, "content": "Enter your content."};
		
		$("#annotationDom").append("<div class=\"hotspot visibleHotspot highlight\" data-ann_id=\"" + annotationCount + "\" id=\"Annotation_" + annotationCount + "\"></div>");
		
		createAnnotationStyle(annotations[annotationCount]);
		
		loadAnnotationToEdit(annotations[annotationCount]);
		
		createAnnotationMove();
	});
	
	$("#download").click(function() {
		removeAllAnnotationMove();
		
		var toPass =  {
				'annotations': annotations,
				'annotationStyle': $('#posterStyle').html(),
				'annotationDOM': $('#annotationDom').html(),
				'posterURL': posterURL
			};
		
		$("#results").html("Loading...");
		
		$.ajax({url: "StorePoster.php",
			data: toPass,
			method: 'POST',
			success: function(result){
				//$(".error").html("<pre>" + result + "</pre>");
				if (result.startsWith("-1")) {
					$("#results").html("An error has occurred. Try again later.");
					
					error_log("Digital Poster - " + result);
				} else {
					$("#results").html("Success! Your Poster can be found <a href='ShowPoster.php?PosterID="+result+"'>here</a>");
				}
			}
		});
	});
});

tinymce.init({
	selector: '.annotationContent',
	//plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
	//menubar: false,
	relative_urls: false,
	toolbar_mode: 'floating',
	plugins: "media image autolink link lists hr pagebreak",
	menubar: "insert",
	media_alt_source: false,
	media_poster: false,
	/*media_url_resolver: function (data, resolve, reject) {
		var matches = data['url'].match(urlPattern);
		
		if (matches == null) {
			reject({msg: 'Invalid URL.'});
		} else {
			var domain = matches[2];
			
			if (allowedDomains.indexOf(domain) == -1) {
				reject({msg: 'Not in allowed domains for videos.'});
			} else {
				resolve({html: ''}); //fall back to default url resolver
			}
		}
	},*/
	urlconverter_callback: function(url, node, on_save, name) {
		if (url != '') {
			var matches = url.match(mediaUrlPattern);
			
			console.log(url);
			console.log(matches);
			
			if (matches == null) {
				tinymce.activeEditor.windowManager.alert('Invalid URL');
				return "";
			} else {
				var domain = matches[3];
				
				if (allowedDomains.indexOf(domain) == -1) {
					tinymce.activeEditor.windowManager.alert('Not allowed domain.');
					url = "";
					return "";
				} else {
					//Force URL replace
					/*if (matches[1] == undefined) {
						if (matches[2] == "//") {
							return "https:" + url;
						} else {
							return "https://" + url;
						}
					} else {
						if (matches[2] == "//") {
							return "https:" + url;
						} else {
							url = url.replace(/^http:(\/\/)?/i, 'https://');
							return url;
						}
					}*/
					
					return url;
				}
			}
		} else {
			return "";
		}
	}
});
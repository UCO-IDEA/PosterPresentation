<?php

$client = new \Google_Client();
$client->setApplicationName("SheetReader");
$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
$client->setAccessType('offline');
$client->setAuthConfig($config["Credentials"]);
$client->setDeveloperKey("/*~~~~~~~~~~~~~Developer Key~~~~~~~~~~~~~*/");

$service = new Google_Service_Sheets($client);

$spreadsheetId = $config["GoogleSheetID"];

function getPosterData($id) {
	GLOBAL $service, $spreadsheetId;
	
	$range = 'Sheet1!A'.$id.":D".$id;
	$options = array('valueRenderOption' => 'FORMATTED_VALUE');

	$response = $service->spreadsheets_values->get($spreadsheetId, $range, $options);

	//$response = $service->spreadsheets_values->get($spreadsheetId, $range, ['valueRenderOption' => 'FORMATTED_VALUE']);
	$values = $response->getValues();
	$myVals = Array();

	$ids = ["Annotations","Annotations Style","Annotation DOM","Poster URL"];

	for ($i = 0; $i < count($values); $i++) { 
		$myJSON[] = array_combine($ids, $values[$i]);
	}
	
	return $myJSON;
}

function putPosterData($Annotations, $AnnotationsStyle, $AnnotationDOM, $PosterURL) {
	GLOBAL $service, $spreadsheetId;
	
	// The A1 notation of a range to search for a logical table of data.
	// Values will be appended after the last row of the table.
	$range = 'Sheet1!A:D';  // TODO: Update placeholder value.

	// TODO: Assign values to desired properties of `requestBody`:
	$requestBody = new Google_Service_Sheets_ValueRange();

	// You need to specify the values you insert
	$values = [$Annotations, $AnnotationsStyle, $AnnotationDOM, $PosterURL];
	
	$requestBody->setValues(["values" => $values]);
	
	// Then you need to add some configuration
	$conf = ["valueInputOption" => "RAW"];
	
	$response = $service->spreadsheets_values->append($spreadsheetId, $range, $requestBody, $conf);
	
	$updatedRange = $response["updates"]->updatedRange;
	
	$startSub = strpos($updatedRange, "A") + 1;
	
	return substr($updatedRange, $startSub, strrpos($updatedRange, ":") - $startSub);
}
?>
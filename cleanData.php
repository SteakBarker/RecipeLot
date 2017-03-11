<?php	
function cleanData_Default($data, $maxLength){
	
	if($maxLength != 0 || !$maxLength){
		$data = substr($data,0,$maxLength);
	}
	
	$data = trim($data);
	$data = strip_tags($data);
	//$data = stripcslashes($data);
	return htmlentities($data);
}
function cleanData_Text($data, $maxLength){	
	$data = preg_replace('/([^A-Za-z0-9 .,!?"\'()\/])*/', "", $data);
	$data = cleanData_Default($data, $maxLength);
	return $data;
}
function cleanData_SpecialText($data, $maxLength){	
	$data = preg_replace('/([^A-Za-z0-9 .,!?"\'()\/\r\n:;&-])*/', "", $data);
	$data = cleanData_Default($data, $maxLength);
	return $data;
}
function cleanData_Alphanumeric($data, $maxLength){	
	$data = preg_replace('/([^A-Za-z0-9])*/', "", $data);
	$data = cleanData_Default($data, $maxLength);
	return $data;
}
function cleanData_Numeric($data, $maxLength){	
	$data = preg_replace('/([^0-9])*/', "", $data);
	$data = cleanData_Default($data, $maxLength);
	return $data;
}
function cleanData_Alpha($data, $maxLength){	
	$data = preg_replace('/([^A-Za-z])*/', "", $data);
	$data = cleanData_Default($data, $maxLength);
	return $data;
}
?>
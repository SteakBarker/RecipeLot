<?php
require_once('../../mysqlConnect.php');
require_once('../../cleanData.php');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	
	//These are the things that should be sent:
	//Title, author, descrip, ingred(Ingredients), steps
	
	//The message that will be sent back is
	//success, msg.
	$title = cleanData_Text($_POST["title"], 20);
	$author = cleanData_Text($_POST["author"], 20);
	$descrip = cleanData_SpecialText($_POST["descrip"], 250);
	$ingred = cleanData_SpecialText($_POST["ingred"], 450);
	$steps = cleanData_SpecialText($_POST["steps"], 650);
	
	$tags = json_decode($_POST["tags"]);
	foreach ($tags as $t) {
		$t = cleanData_Numeric($t, 6);
	}
	
	if(empty($title) | empty($ingred) | empty($steps) | empty($descrip)){
		echo json_encode(array('success' => 0, 'msg' => 'Invalid data'));
		exit();
	}
	
	session_start();
	if($_SESSION["lastRecipeCreated"]>time()-120){
		error_log("User attemping to create recipes too fast",0);
		echo json_encode(array('success' => 0, 'msg' => 'Error'));
		exit();
	}
	
	$_SESSION["lastGameCreated"] = time();
	
	$dbc = createDefaultConnection('recipes');
	
	$stmt = $dbc->prepare('INSERT INTO recipe(id, timestamp, title, author, descrip, ingred, steps) VALUES (null,null,?,?,?,?,?)');
	
	$stmt->bind_param('sssss',$title, $author, $descrip, $ingred, $steps);
	
	$worked = $stmt->execute();
	$newId = $stmt->insert_id;
	
	foreach ($tags as $t) {
		$stmt->close();
		$stmt = $dbc->prepare('INSERT into tagentery (id, recipeId, tagId) VALUES(null, ?, ?)');
		$stmt->bind_param('ii',$newId,$t);
		
		$worked = $stmt->execute();
		if(!$worked){
			error_log("Unable to create tagEntery - ".$stmt->error, 0);
			$dbc->close();$stmt->close();
			echo json_encode(array('success' => 0, 'msg' => 'Error'));
			exit();
		}
	}
	if($worked){
		$dbc->close();$stmt->close();
		
		$arr = array('success' => 1, 'msg' => 'recipePage.php?recipe='.$newId);
		echo json_encode($arr);
	}else{
		error_log("Unable to create recipe - ".$stmt->error, 0);
		$dbc->close();$stmt->close();
		echo json_encode(array('success' => 0, 'msg' => 'Error'));
		exit();
	}
}else{
	exit("Error");
}
?>
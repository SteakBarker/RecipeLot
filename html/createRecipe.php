<html>
<head>
	<title>RecipeLot - Create Recipe</title>
	
	<link rel="stylesheet" type="text/css" href="css/recipeStyle.css">
	<link rel="stylesheet" type="text/css" href="css/createRecipeStyle.css">
	<script src="js/zeptojs.js"></script>
	<script src="js/createRecipe.js"></script>
</head>
<body>
	<!-- Copy and pasted because I'm lazy. -->
	<link rel="stylesheet" type="text/css" href="css/menuBar.css">
	<ul id="menubar">
	  <li><a href="home.php">Home</a></li>
	  <li><a href="tags.php">View Tags</a></li>
	  <li><a href="viewRecipes.php">View All Recipes</a></li>
	  <li><a href="createRecipe.php">Create Recipe</a></li>
	  <li><a href="about.php">About</a></li>
	</ul>
	
	
	
	<script>
	$(document).ready(function(){
		<?php
			require_once("../mysqlConnect.php");
			
			$dbc = createDefaultConnection("recipes");
			
			$query = "SELECT id, name FROM tags";
			
			$stmt = $dbc->stmt_init();
			
			if(!$stmt->prepare($query)){
				error_log("createRecipe statment failed to prepare - ".$stmt->error,0);
				$dbc->close(); $stmt->close();
				echo 'Error';
				exit();
			}
			
			$worked = $stmt->execute();
			
			if(!$worked){
				error_log("createRecipe statement failed - ".$stmt->error,0);
				$dbc->close(); $stmt->close();
				echo 'Error';
				exit();
			}
			
			$result = $stmt->get_result();
			
			echo "var tags = [";
			while ($row = $result->fetch_array()){
				echo '{id:'.$row["id"].', name:"'.$row["name"].'"}';
				echo ',';
			}
			echo "];";
			$dbc->close(); $stmt->close();
		?>
		loadTags(tags);
	})
	</script>
	<div>
		
		<div id="recipeInfo" style="height:auto; padding-bottom:20px;">
			<input type="text" id="title" value="Recipe Name">
			<h2>By </h2><input type="text" id="author" value="Recipe Author">
			<textarea id="descrip" style="height:100px; width:100%;">Recipe Description</textarea>
		</div>
		
			
		<div class="stepList">
			<h3>Ingredients:</h3>
			
			<textarea id="ingred" rows="4" cols="50"></textarea>
		</div>
		<div class="stepList">
			<h3>Steps:</h3>
			<textarea id="steps" rows="4" cols="50"></textarea>
		</div>
		
		<div>
			<!--<h3>Selected Tags: </h3>
			<div id="slectedTags" class="tags">
				<p>App</p><p>This</p>
			</div>-->
			
			<h3>Available Tags: </h3>
			<div id="availableTags" class="tags"></div>
		</div>
	</div>
	<button id="submit" type="button" onclick="addRecipe()">Create Recipe</button>
</body>
</html>
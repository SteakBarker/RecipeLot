<html>
<head>
	<title>RecipeLot - Recipes</title>

	<link rel="stylesheet" type="text/css" href="css/viewRecipeStyle.css">
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
		function onClick(id){
			window.location.href = "recipePage.php?recipe="+id;
		}
	</script>
	<div style="margin-top:50px; margin-left:5%; margin-right:5%;">
		
		<?php
			require_once("../mysqlConnect.php");
			require_once("../cleanData.php");
			
			$dbc = createDefaultConnection("recipes");
			
			$tag;
			$query;
			if(empty($_GET["tag"])){
				$query = "SELECT id, title, descrip FROM recipe";
			}else{
				$tag = cleanData_Numeric($_GET["tag"],6);
				$query = "SELECT id, title, descrip from recipe where id in (select recipeId from tagentery where tagId=?)";
			}
			
			$stmt = $dbc->stmt_init();
			
			if(!$stmt->prepare($query)){
				error_log("viewRecipes statment failed to prepare - ".$stmt->error,0);
				echo 'Error';
				$dbc->close(); $stmt->close();
				exit();
			}
			if(!empty($tag)){
				$stmt->bind_param("i",$tag);
			}
			$worked = $stmt->execute();
			
			if(!$worked){
				error_log("viewRecipes statement failed - ".$stmt->error,0);
				$dbc->close(); $stmt->close();
				echo 'Error';
				exit();
			}
			
			$result = $stmt->get_result();

			while ($row = $result->fetch_array()){
				echo '<div class="item" onclick="onClick(\''.$row["id"].'\')">';
				echo '<h3>'.$row["title"].'</h3>';
				echo '<p>'.$row["descrip"].'</p>';
				echo '</div>';
			}
			$dbc->close(); $stmt->close();
		?>
		
	</div>
</body> 
</html>
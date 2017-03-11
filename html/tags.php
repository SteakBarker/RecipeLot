<?php
class Tag{
	public $id;
	public $name;
}
require_once("../mysqlConnect.php");
require_once("../cleanData.php");

$dbc = createDefaultConnection("recipes");
$stmt;

$query = "SELECT id, name from tags";
$stmt = $dbc->stmt_init();
$stmt->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
$tags = [];
while ($row = $result->fetch_array()){
	$tag = new Tag();
	$tag->id=$row["id"];
	$tag->name=$row["name"];
	array_push($tags, $tag);
}

$dbc->close();$stmt->close();
?>

<html>
<head>
	<title>RecipeLot - Tags</title>
	
	<link rel="stylesheet" type="text/css" href="css/recipeStyle.css">
</head>
<body>
	<link rel="stylesheet" type="text/css" href="css/menuBar.css">
	<ul id="menubar">
	  <li><a href="home.php">Home</a></li>
	  <li><a href="tags.php">View Tags</a></li>
	  <li><a href="viewRecipes.php">View All Recipes</a></li>
	  <li><a href="createRecipe.php">Create Recipe</a></li>
	  <li><a href="about.php">About</a></li>
	</ul>
	
	<script>
		function tagClicked(id){
			location.href = ("viewRecipes.php?tag="+id);
		}
	</script>
	
	<div>
		<h3>All Tags: </h3>
		<div id="availableTags" class="tags"><?php
			foreach ($tags as $t) {
				echo "<p class='tag' onclick='tagClicked(".$t->id.")'>".$t->name."</p>";
			}
		?></div>
	</div>
</body>
</html>
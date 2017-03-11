<?php
class Tag{
	public $id;
	public $name;
}
require_once("../mysqlConnect.php");
require_once("../cleanData.php");

$dbc = createDefaultConnection("recipes");
$stmt;

$query = "SELECT title,timestamp,author,descrip,ingred,steps FROM recipe WHERE id=?";

$stmt = $dbc->stmt_init();
$stmt->prepare($query);
	
$id = cleanData_Numeric($_GET["recipe"],11);

$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_array();

if(!$row){
	$dbc->close();
	$stmt->close();
	echo "Error";
	exit();
}
$title = $row["title"];
$author = $row["author"];
$descrip = $row["descrip"];
$ingred = $row["ingred"];
$steps = $row["steps"];
$timestamp = $row["timestamp"];

$stmt->close();

$query = "SELECT tagId from tagentery where recipeId=?";
$stmt = $dbc->stmt_init();
$stmt->prepare($query);
$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();
$tags = [];
while ($row = $result->fetch_array()){
	$stmt->close();
	$query = "SELECT name from tags where id=?";
	$stmt = $dbc->stmt_init();
	$stmt->prepare($query);
	$stmt->bind_param("i",$row["tagId"]);
	$stmt->execute();
	$taqResult = $stmt->get_result();
	$tagRow = $taqResult->fetch_array();
	$tag = new Tag();
	$tag->id=$row["tagId"];
	$tag->name=$tagRow["name"];
	array_push($tags, $tag);
}

$dbc->close();$stmt->close();

?>

<html>
<head>
	<title>RecipeLot - <?php echo $title; ?></title>
	
	<link rel="stylesheet" type="text/css" href="css/recipeStyle.css">
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
	
	<div id="recipeInfo" style="height:auto;">
		<h1><?php echo $title; ?></h1>
		<h2><?php echo $author; ?></h2>
		<p><?php echo $descrip; ?></p>
		<p id="timestamp" style="position:absolute;top:35;right:30;"><?php echo $timestamp; ?></p>
	</div>
	
	
	<div class="stepList">
		<h3>Ingredients:</h3>
		<ul>
			<?php
			$pieces = preg_split("/(\r|\n|\r\n)/", $ingred);
			foreach ($pieces as &$piece) {
				if(!empty($piece)){
					echo "<li>".$piece."</li>";
				}
			}
			?>
		</ul>
	</div>
	
	<div class="stepList">
		<h3>Steps:</h3>
		<dl>
		
			<?php
			$pieces = preg_split("/(\r|\n|\r\n)/", $steps);
			foreach ($pieces as &$piece) {
				if(!empty($piece)){
					if(preg_match("/^[ ]+.*$/", $piece)){
						echo "<dd>".$piece."</dd>";
					}else{
						echo "<dt>".$piece."</dt>";
					}
				}
			}
			?>
		</dl>
	</div>
	
	
	<script>
		function tagClicked(id){
			location.href = ("viewRecipes.php?tag="+id);
		}
	</script>
	
	<div>
		<div id="availableTags" class="tags" style="margin-top:10px;"><?php
			foreach ($tags as $t) {
				//echo '<p class="tag" onclick="tagClicked("'.$t->id.'")">'.$t->name.'</p>';
				//echo "<p class=\"tag\" onclick=\"tagClicked(\"$t->id\")\">$t->name</p>";
				echo "<p class='tag' onclick='tagClicked(".$t->id.")'>".$t->name."</p>";
			}
		?></div>
	</div>
	
	<script>
		function printPage(){
			document.getElementById("menubar").style.display = "none";
			document.getElementById("timestamp").style.display = "none";
			document.getElementById("printbutton").style.display = "none";
			window.print();
			document.getElementById("menubar").style.display = "block";
			document.getElementById("timestamp").style.display = "block";
			document.getElementById("printbutton").style.display = "block";
		}
	</script>
	<button id="printbutton" type="button" onclick="printPage()">Print</button>
</body>
</html>
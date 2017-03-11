var availableTags = [];
var selectedTags = [];

function tagClicked(id){
	
	var contains = arrayContains(selectedTags, id);
	
	if(contains === -1){
		document.getElementById("tag"+id).setAttribute("class", "selectedTag");
		selectedTags.push(id);
	}else{
		document.getElementById("tag"+id).setAttribute("class", "tag");
		selectedTags.splice(contains, 1);
	}
}

function arrayContains(a,c){
	var i = a.length;
	while (i--) {
	   if (a[i] === c) {
		   return i;
	   }
	}
	return -1;
}

function loadTags(tags){
	availableTags = tags;
	tags.forEach(function(i) {
		var para = document.createElement("p");
		var node = document.createTextNode(i["name"]);
		para.appendChild(node);
		para.setAttribute("id", "tag"+i["id"]);
		para.setAttribute("class", "tag");
		para.setAttribute("onclick", "tagClicked("+i["id"]+")");
		
		var element = document.getElementById("availableTags");
		element.appendChild(para);
	});
}
function addRecipe(){	
	sendAjax(function(output){
		var results = JSON.parse(output);
		
		if(results["success"] === 1){
			location.href = results["msg"];
		}else{
			alert(results["msg"]);
		}
	});
}
	
function sendAjax(handleData) {
	$.ajax({
		url:"backend/uploadRecipe.php",
		type:'POST',
		data:
		{
			title:(document.getElementById("title").value),
			author:(document.getElementById("author").value),
			descrip:(document.getElementById("descrip").value),
			ingred:(document.getElementById("ingred").value),
			steps:(document.getElementById("steps").value),
			tags:JSON.stringify(selectedTags),
		},beforeSend: function() {
			//$("#loading").show();
		},success:function(data) {
			handleData(data); 
		},error: function(){
			//$("#loading").hide();
			alert("Request Failed");
		},
		timeout: 5000
	});
}
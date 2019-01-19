<!DOCTYPE html>
<html>
<head>
	<title>projects</title>
	<?php include("template/config.php") ?>
	<meta name="title" content ="my projects">
    <meta name="description" content="random demos that i made for fun">
    <meta property="og:title" content="my projects" />
    <meta property="og:description" content="random demos that i made for fun" />
    <meta property="og:image" content="<?= $domain ?>/projects/images/maze.jpg" />
</head>
<body>
	<?php $title = "projects" ?>
	<?php include("template/HaS.php");?>
	<div id="content">
		<?php 
			$root = '/var/www/html/projects';
			$files = array_slice(scandir($root),2);
			$ignore = ["index.php"];
	
			foreach($files as $file){
				if(substr($file, -3) === "php" && !in_array($file, $ignore)){
					$cardTitle = (explode(".",$file)[0]);
					$tags = get_meta_tags($file);
					$description = (strlen($tags["description"]) < 100)? $tags["description"] : substr($tags["description"], 0, 100)."...";

					echo '<a href="'.$file.'" class="previewCard hvr-wobble-vertical"><div class="cardImage"><img src="/projects/images/'.$cardTitle.'.jpg"/></div><div class="cardTitle"><p id="cardTitle">'.$tags["title"].'</p><p id="cardDesc">'.$description.'</p></div></a>';
				}
			}
		?>
	</div>
	<script src="javascript/functionality.js"></script>
</body>
</html>
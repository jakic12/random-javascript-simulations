<div id="header">
	<div class="sideButton" onClick="toggleSidebar()">=</div>
	<h1><?php if(!$title){
			echo explode(".",end(explode("/",$_SERVER["SCRIPT_NAME"])))[0];
		}else{
			echo $title;
		} 
		?></h1>
</div>
<div id="sidebar">
	<div class="sideHead"><div class="sideButton" onClick="toggleSidebar()">=</div><a href = "/projects">Home</a></div>
	<div class="files">
		<?php 
			$root = '/var/www/html/projects';
			$files = array_slice(scandir($root),2);
	
			foreach($files as $file){
				if(substr($file, -3) === "php"){
					echo '<div class="file"><a href="'.$file.'"">'.(explode(".",$file)[0]).'</a></div>';
				}
			}
		?>
	</div>
</div>

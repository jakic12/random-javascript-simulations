<!DOCTYPE html>
<html>
<head>
	<title>projects</title>
	<?php include("template/config.php") ?>
	<meta name="title" content="sierpinski chaos game" />
	<meta name="description" content="An 'interactive' demo for the famous chaos game">
    <meta property="og:title" content="sierpinski chaos game" />
    <meta property="og:description" content="An 'interactive' demo for the famous chaos gam" />
    <meta property="og:image" content="<?= $domain ?>/projects/images/sierpinski.jpg" />
</head>
<body>
	<?php $title = "sierpinski demo" ?>
	<?php include("template/HaS.php");?>
	<div id="content">
		<div id="main-body"><canvas id="myCanvas" style="border:1px solid #d3d3d3;" width="500px" height="500px"></div>
		<div class="slidecontainer">
  			speed: <input type="range" min="0" max="1000" value="900" class="slider" id="myRange">
		
		<div id="controls"><button onClick="getNewPoint()">new point</button><button onClick="stop1 = false; yeet()">automaticly do this</button><button onClick="stopExe()">stop</button> <h1 id="pointCoord">random num: </h1></div>
		</div>
		<div id="explanation">
			<h1>how it works:</h1>
			<ol>
				<li>Draw three suitably-distant dots.</li>
				<li>Label one dot "1, 2, 3". Label another dot "4, 5, 6". Label the remaining dot "7, 8, 9".</li>
				<li>Arbitrarily pick one of the dots as your starting point.</li>
				<li>Get random number from 1 - 9.</li>
				<li>Determine the exact halfway point between your current dot and the dot indicated by the random number.</li>
				<li>Place a dot at the halfway point. This new dot becomes your current dot.</li>
				<li>Repeat steps 4-6 until you get bored.</li>
			</ol>

			<h1>links</h1>
			<a href="https://youtu.be/kbKtFN71Lfs">Numberphile - Chaos Game</a>
		</div>
	
		<script>
			var c = document.getElementById("myCanvas");
			var ctx = c.getContext("2d");
			ctx.font = "10px Arial";
			var stop1 = false;
	
			const pointsX = [50,250,450];
			const pointsY = [390,90,390];
	
			ctx.beginPath();
			ctx.arc(pointsX[0], pointsY[0], 1, 0, 2 * Math.PI);
			ctx.closePath();
			ctx.stroke();
	
			ctx.beginPath();
			ctx.arc(pointsX[1], pointsY[1], 1, 0, 2 * Math.PI);
			ctx.closePath();
			ctx.stroke();
	
			ctx.beginPath();
			ctx.arc(pointsX[2], pointsY[2], 1, 0, 2 * Math.PI);
			ctx.closePath();
			ctx.stroke();
	
			ctx.fillText("1,2,3",pointsX[0]-20, pointsY[0]+20);
			ctx.fillText("4,5,6",pointsX[1], pointsY[1]-10);
			ctx.fillText("7,8,9",pointsX[2], pointsY[2]-10);
	
			var pointX = pointsX[0];
			var pointY = pointsY[0];
	
			var timeoutTime = 100;
	
			function yeet(){
				timeoutTime = 1000-document.getElementById("myRange").value;
	
				setTimeout(function(){
					getNewPoint();
					if(!stop1){
						yeet();
					}
				}, timeoutTime);
	
				
			}
	
			function stopExe(){
				stop1 = true;
			}
	
			function getNewPoint(){
				var random = parseInt(Math.random()*10);
				var index = -1;
				
				if(random <= 3){
					index = 0;
				}else if(random > 3 && random <= 6){
					index = 1;
				}else{
					index = 2;
				}
	
				pointX = (pointsX[index] + pointX)/2;
				pointY = (pointsY[index] + pointY)/2;
	
				ctx.beginPath();
				ctx.arc(pointX, pointY, 1, 0, 2 * Math.PI);
				ctx.closePath();
				ctx.stroke();
	
				document.getElementById("pointCoord").innerHTML = "random num: " + random;
			}
	
	
		</script>
	</div>
	<script src="javascript/functionality.js"></script>
</body>
</html>
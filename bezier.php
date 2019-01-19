<!DOCTYPE html>
<html style="height:100%">
<head>
    <title>bezier demo</title>
    <?php include("template/config.php") ?>
    <meta name="title" content="bezier demo" />
    <meta name="description" content="An interactive bezier demo">
    <meta property="og:title" content="bezier demo" />
    <meta property="og:description" content="An interactive bezier demo" />
    <meta property="og:image" content="<?= $domain ?>/projects/images/bezier.jpg" />
</head>
<body>
    <?php $title = "bezier demo" ?>
    <?php include("template/HaS.php");?>
    <div id="content">
        <div id="main-body"><canvas id="myCanvas" style="border:1px solid #d3d3d3;" width="500px" height="500px" onmousedown="MsDwn(event)" onmouseup="MsUp(event)"></canvas></div>
        <div id="controls">
            <div class="slidecontainer">
                t: <input type="range" min="0" max="100" value="50" class="slider" id="myRange" oninput="updateSlider(this.value)">
            </div>
            <button onClick="drawWhole = !drawWhole; drawPoints(t*100)">draw whole</button><button onClick="stop1 = false; yeet(0);t = 0;">automaticly do this</button><button onClick="stop1 = true;">stop</button>
        </div>
        <div id="variables"><h1 id="timeIndicator">time: </h1></div>
        
        <div id="explanation">
            <h1>how works:</h1>
        </div>

        <script>
                var c = document.getElementById("myCanvas");
                var ctx = c.getContext("2d");
                ctx.font = "10px Arial";

                const pointsX = [50,250,450];
                const pointsY = [390,90,390];
                var stop1 = false;
		var drawWhole = false;
                var point = -1;
		var rising = true;
                var t = 0.5;

                drawPoints(100);

                function MsDwn(event){
                        console.log("MsDwn");
                        if(event != null){
                                point = checkClickedPoint(event);  
                        }
                }

                function MsUp(event){
                        console.log("MsUp");
                        if(event != null){
                                console.log("MsUp");
                                var rect = c.getBoundingClientRect();
                                if(point != -1){
                                        pointsX[point] = event.x - rect.left;
                                        pointsY[point] = event.y - rect.top;  
                                        drawPoints(100);
                                }
                                clicked = checkClickedPoint(event);
                        }
                }

                function checkClickedPoint(event){
                        var rect = c.getBoundingClientRect();
                        cx = event.x - rect.left;
                        cy = event.y - rect.top;        
                        for(var i = 0; i < pointsX.length; i++){
                                distance = Math.sqrt(Math.pow(cx - pointsX[i], 2) + Math.pow(cy - pointsY[i], 2));
                                if(distance < 10){
                                        console.log(i);
                                        return i;
                                }
                        }
                        return -1;
                }
		
		function yeet(i){
			if(rising && t > 1){
				rising = false;
			}else if(!rising && t < 0){
                rising = true
            }

			if(!stop1){
                drawPoints(t*100);
				if(rising){
					t += (0.5)/100;
				}else{
                    t -= (0.5)/100;
                }
			}
			setTimeout(function(){yeet();}, 10);
		}

        function updateSlider(value){
                t = value/100;
                drawPoints(value);
        }

		var point3X = 0;
		var point3Y = 0;

        function drawPoints(len){
            ctx.clearRect(0, 0, c.width, c.height);
			
			if(drawWhole){
				drawCurve(100);
			}else{
				drawCurve(len);
			}

            ctx.beginPath();
            ctx.arc(pointsX[0], pointsY[0], 10, 0, 2 * Math.PI);
            ctx.closePath();
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(pointsX[1], pointsY[1], 10, 0, 2 * Math.PI);
            ctx.closePath();
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(pointsX[2], pointsY[2], 10, 0, 2 * Math.PI);
            ctx.closePath();
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(pointsX[0],pointsY[0]);
            ctx.lineTo(pointsX[1],pointsY[1]);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(pointsX[1],pointsY[1]);
            ctx.lineTo(pointsX[2],pointsY[2]);
            ctx.stroke();
            point1X = (pointsX[1] - pointsX[0])*t + pointsX[0];
            point1Y = (pointsY[1] - pointsY[0])*t + pointsY[0];
            ctx.beginPath();
            ctx.arc(point1X, point1Y, 10, 0, 2 * Math.PI);
            ctx.closePath();
            ctx.stroke();

			point2X = (pointsX[2] - pointsX[1]) * t + pointsX[1];
			point2Y = (pointsY[2] - pointsY[1]) * t + pointsY[1];

			ctx.beginPath();
            ctx.arc(point2X, point2Y, 10, 0, 2 * Math.PI);
            ctx.closePath();
            ctx.stroke();

			point3X = (point2X - point1X) * t + point1X;
			point3Y = (point2Y - point1Y) * t + point1Y;

			ctx.beginPath();
			ctx.arc(point3X, point3Y, 10, 0, 2 * Math.PI);
            ctx.closePath();
            ctx.stroke();

			ctx.beginPath();
			ctx.moveTo(point1X, point1Y);
			ctx.lineTo(point2X, point2Y);
			ctx.stroke();
		}

		function drawCurve(len){
			document.getElementById("timeIndicator").innerHTML = "time: " + t;
			for(var i = 0; i < len; i += 0.5){
			point1X = (pointsX[1] - pointsX[0])*(i/100) + pointsX[0];
            point1Y = (pointsY[1] - pointsY[0])*(i/100) + pointsY[0];
            point2X = (pointsX[2] - pointsX[1]) * (i/100) + pointsX[1];
            point2Y = (pointsY[2] - pointsY[1]) * (i/100) + pointsY[1];
            point3X = (point2X - point1X) * (i/100) + point1X;
	        point3Y = (point2Y - point1Y) * (i/100) + point1Y;

			ctx.beginPath();
			ctx.arc(point3X, point3Y, 5, 0, 2 * Math.PI);
            ctx.closePath();
         	ctx.stroke();
			}
		}

        </script>
    </div>
    <script src="javascript/functionality.js"></script>
</body>
</html>

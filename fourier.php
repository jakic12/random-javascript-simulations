<!DOCTYPE html>
<html style="height:100%">
    <head>
        <title>fourier demo</title>
        <?php include("template/config.php") ?>
        <meta name="title" content="fourier transform visualisation" />
        <meta name="description" content="A demo, that visualises the fourier transform, made after 3Blue1Browns video">
        <meta property="og:title" content="fourier transform visualisation" />
        <meta property="og:description" content="A demo, that visualises the fourier transform, made after 3Blue1Browns video" />
        <meta property="og:image" content="<?= $domain ?>/projects/images/fourier.jpg" />
    </head>
    <body>
        <?php $title = "fourier demo" ?>
        <?php include("template/HaS.php");?>
        <div id="content">
            <div id="main-body"><canvas id="myCanvas" style="border:1px solid #d3d3d3;" width="1000px" height="500px"/></div>
            <div id="controls">
                <div class="slidecontainer">
                    sizeX: <input type="range" min="1" max="100" value="12" class="slider" id="sizeX" oninput="sizeX = this.value/1000; document.getElementById('sizeX_out').innerHTML = this.value/1000; graph()"/><p id="sizeX_out"></p><br>
                    sizeY: <input type="range" min="1" max="100" value="100" class="slider" id="sizeY" oninput="sizeY = this.value; document.getElementById('sizeY_out').innerHTML = this.value; graph()"/><p id="sizeY_out"></p><br>
                </div>
                f(x)=<input type="text" id="function" value = "sin(x*2) + sin(x*4)" oninput="graph();"/>
            </div>
            <hr>
            <div id="secondary-body">
                <canvas id="myCanvas1" style="border:1px solid #d3d3d3;" width="500px" height="500px"></canvas><canvas id="myCanvas2" style="border:1px solid #d3d3d3;" width="1000px" height="500px"></canvas>
            </div>
            <div id="controls1">
                <div class="slidecontainer">
                    rot/ 10*period: <input type="range" min="0" max="500" value="0" class="slider" id="rotP" style="width:400px" oninput="wrapGraph(this.value/100); document.getElementById('rot_per').innerHTML = this.value/100;"/><p id="rot_per"></p><br>
                </div>
                <button onClick="drawGraphF(5);">draw whole</button><button onClick="stop1 = false; yeet();t = 0;">automaticly do this</button><button onClick="stop1 = true;">stop</button>
            </div>
    
            <div id="variables"></div>
            
            <div id="explanation">
                <h1>links:</h1>
                <a href="https://youtu.be/spUNpyF58BY">3Blue1Brown - But what is the Fourier Transform? A visual introduction.</a>
            </div>
            
    
            <script>
                var c = document.getElementById("myCanvas");
                var ctx = c.getContext("2d");
    
                var c1 = document.getElementById("myCanvas1");
                var ctx1 = c1.getContext("2d");
    
                var c2 = document.getElementById("myCanvas2");
                var ctx2 = c2.getContext("2d");
    
                
    
                sizeX = 1;
                sizeY = 1;
                ctx2.font = "20px Arial";
                funct = "";
                rising = true;
                stop1 = false;
    
                sizeY = document.getElementById("sizeY").value; document.getElementById('sizeY_out').innerHTML = document.getElementById("sizeY").value;
                sizeX = document.getElementById("sizeX").value/1000; document.getElementById('sizeX_out').innerHTML = document.getElementById("sizeX").value/1000; 
                graph();
    
                wrapGraph(0);
    
                function graph(){
                    getFunction();
                    drawFunction();
                }
    
                function wrapGraph(len){
                    ctx1.clearRect(0, 0, c.width, c.height);
                    ctx2.clearRect(0, 0, c.width, c.height);
    
                    for(i = 0; i < 1000; i += 200){
                    	ctx2.beginPath();
                    	ctx2.moveTo(i,250);
                    	ctx2.lineTo(i,270);
                    	ctx2.stroke();
    
                    	ctx2.fillStyle = 'black';
                    	ctx2.fillText((i/200)+"",i-5,290);
                    }
    
                    length = (10*Math.PI);
                    step = (length/(360))/((len<1)? 1 : len);
    
                    ctx1.beginPath();
                    ctx1.moveTo(250,0);
                    ctx1.lineTo(250,500);
                    ctx1.stroke();
        
                    ctx1.beginPath();
                    ctx1.moveTo(0,250);
                    ctx1.lineTo(500,250);
                    ctx1.stroke();
    
                    ctx2.beginPath();
                    ctx2.moveTo(0,250);
                    ctx2.lineTo(1000,250);
                    ctx2.stroke();
		  
                    ctx1.beginPath();
                    ctx1.moveTo(250,250);
                    ctx1.lineTo(400*Math.cos(-length*len)+250, 400*Math.sin(-length*len)+250);
                    ctx1.stroke();
    
                    drawGraphF(len);
    
                    center_of_massX = 0;
                    center_of_massY = 0;
                    total = 0;
    
                    for(alp = 0.0; alp < length-step; alp += step){
                        r = (f1(alp)*sizeY)+125;
                        r1 = (f1(alp+step)*sizeY)+125;
    
                        x = r*Math.cos(-alp*len)+250;
                        x1 = r1*Math.cos(-(alp+step)*len)+250;
    
                        y = r*Math.sin(-alp*len)+250;
                        y1 = r1*Math.sin(-(alp+step)*len)+250;
    
                        ctx1.beginPath();
                        ctx1.moveTo(x,y);
                        ctx1.lineTo(x1,y1);
                        ctx1.stroke();
    
                        center_of_massX += x;
                        center_of_massY += y;
    
                        total++;
                    }
    
                    center_of_massX /= total;
                    center_of_massY /= total;
    
                    ctx1.beginPath();
                    ctx1.moveTo(0,center_of_massY);
                    ctx1.lineTo(500, center_of_massY);
                    ctx1.strokeStyle = 'red';
                    ctx1.stroke();
    
                    ctx2.beginPath();
                    ctx2.moveTo(0,center_of_massY);
                    ctx2.lineTo(1000, center_of_massY);
                    ctx2.strokeStyle = 'red';
                    ctx2.stroke();
    
                    
                    ctx1.strokeStyle = 'black';
                    ctx2.strokeStyle = 'black';
    
                    ctx1.beginPath();
                    ctx1.arc(center_of_massX, center_of_massY, 10, 0, 2 * Math.PI);
                    ctx1.fillStyle = 'white';
                    ctx1.fill();
                    ctx1.closePath();
                    ctx1.stroke();
    
                    ctx2.beginPath();
                    ctx2.moveTo(1000/5*len,250);
                    ctx2.lineTo(1000/5*len,center_of_massY);
                    ctx2.strokeStyle = 'black';
                    ctx2.stroke();
    
                    ctx2.beginPath();
                    ctx2.arc(1000/5*len, center_of_massY, 10, 0, 2 * Math.PI);
                    ctx2.fillStyle = 'white';
                    ctx2.fill();
                    ctx2.closePath();
                    ctx2.stroke();
    
                    
                }
    
                function drawGraphF(len){
                	for(i = 0; i < len; i += 0.04){
                		length = (10*Math.PI);
                    	step = (length/(360))/((i<1)? 1 : i);
    
                    	center_of_massX = 0;
                    	center_of_massY = 0;
                    	total = 0;
    
	   
                    	for(alp = 0.0; alp < length-step; alp += step){
                    	    r = (f1(alp)*sizeY)+125;
                    	    y = r*Math.sin(-alp*i)+250;
                        	x = r*Math.cos(-alp*len)+250;
    
                    	    center_of_massX += x;
                    	    center_of_massY += y;
	   
                    	    total++;
                    	}
	   
                    	center_of_massX /= total;
                    	center_of_massY /= total;
    
                    	x2 = 1000/5*i;
    
                    	ctx2.beginPath();
                    	ctx2.arc(x2, center_of_massY, 10, 0, 2 * Math.PI);
                    	ctx2.fillStyle = 'white';
                    	ctx2.fill();
                    	ctx2.closePath();
                    	ctx2.stroke();
    
    
                	}
                }
                
                function getFunction(){
                    funct = document.getElementById("function").value;
                    funct = funct.replace(/sin/g, "Math.sin").replace(/cos/g, "Math.cos").replace(/pow/g, "Math.pow");
                    console.log("function: " + funct);
                }
                
                function f1(x){
                    return eval(funct);
                }
    
                function drawFunction(){
                    ctx.clearRect(0, 0, c.width, c.height);
                    for(x = 0; x < 999; x+=3){
                        y = (f1(x*sizeX)*sizeY)+250;
                        y1 = (f1((x+3)*sizeX)*sizeY)+250;
    
                        ctx.beginPath();
                        ctx.moveTo(x,y);
                        ctx.lineTo(x+3,y1);
                        ctx.stroke();
                        
                    }
                }
		  
		  	function yeet(){
            	    one = parseFloat(document.getElementById("rotP").value);
	   
            	    if(rising && one == 500){
		  			rising = false;
		  		}else if(!rising && one == 0){
            	        rising = true;
            	    }else{
            	        
            	    }
	   
		  		if(!stop1){
		  			if(rising){
		  				document.getElementById("rotP").value = one + 1;
		  			}else{
            	            document.getElementById("rotP").value = one - 1;
            	        }
            	        wrapGraph(one/100); 
            	        document.getElementById('rot_per').innerHTML = one/100;
	   
            	        setTimeout(function(){yeet();},20);
		  		}
		  	}
    
            </script>
        </div>
        <script src="javascript/functionality.js"></script>
    </body>
</html>

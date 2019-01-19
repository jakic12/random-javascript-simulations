<!DOCTYPE html>
<html style="height:100%">
<head>
    <title>maze generator and solver</title>
    <?php include("template/config.php") ?>
    <meta name="title" content="maze generator and solver" />
    <meta name="description" content="A maze generation and solving algorithm demo">
    <meta property="og:title" content="maze generator and solver" />
    <meta property="og:description" content="A maze generation and solving algorithm demo" />
    <meta property="og:image" content="<?= $domain ?>/projects/images/maze.jpg" />
</head>
<body>
    <?php include("template/HaS.php");?>
    <div id="content">
        <div id="main-body">
		  <canvas style="image-rendering: -moz-crisp-edges; image-rendering: -webkit-crisp-edges; image-rendering: pixelated; image-rendering: crisp-edges;" id="myCanvas" style="border:1px solid #d3d3d3;" width="500px" height="500px"></canvas>
        </div>
        <div id="controls">
            <div class="slidecontainer">
                grid resolution <input id="resolutionInput" type="text" value = "50" /> (best if divisible by 500)
            </div>
            <br>
            <div class="settings">
                <button onClick="start();">generate</button>
                <button id="solveButton" style="display:none" onClick="solver();">solve</button>
            </div>
            <div id="explanation">
                <h1>links:</h1>
                <a href="https://en.wikipedia.org/wiki/Maze_generation_algorithm">wikipedia - Maze generation algorithm</a>
            </div>
        </div>
        <script>
            var interval;
            var solver;
            function start(){

            document.getElementById("solveButton").style.display = "none";

            try{
                clearInterval(interval);
            }catch(err){}
            

            var c = document.getElementById("myCanvas");
            var ctx = c.getContext("2d");
            ctx.imageSmoothingEnabled = false;
            var resolution = parseInt(document.getElementById("resolutionInput").value);
            var spacing = 0;

            generateMaze();

            function drawLine(x,y,x1,y1){
                ctx.beginPath();
                ctx.moveTo(x, y);
                ctx.lineTo(x1, y1);
                ctx.stroke();
            }

            function segment(x, y, size, posGridX, posGridY){
                this.x = x;
                this.y = y;
                this.largeness = size-spacing;
                this.visited = false;
                this.backTracked = false;
                this.posGridX = posGridX;
                this.posGridY = posGridY;

                this.special = 0; //0 - no, 1 - in, 2 - out

                this.draw = function(){
                    if(this.posGridX == character.x && this.posGridY == character.y){

                        this.visited = true;

                        ctx.fillStyle = "red";
                        ctx.fillRect(this.x+spacing/2, this.y+spacing/2, this.largeness-spacing, this.largeness-spacing);

                    }else if(characterHistoryS && XYcoordExists({x:this.posGridX, y:this.posGridY},characterHistoryS)){

                        ctx.fillStyle = "orange";
                        ctx.fillRect(this.x+spacing/2, this.y+spacing/2, this.largeness-spacing, this.largeness-spacing);

                    }else if(this.special == 2){

                        ctx.fillStyle = "green";
                        ctx.fillRect(this.x+spacing/2, this.y+spacing/2, this.largeness-spacing, this.largeness-spacing);

                    }else if(this.backTracked){

                        ctx.fillStyle = "blue";
                        ctx.fillRect(this.x+spacing/2, this.y+spacing/2, this.largeness-spacing, this.largeness-spacing);

                    }else if(this.visited){

                        ctx.fillStyle = "white";
                        ctx.fillRect(this.x+spacing/2, this.y+spacing/2, this.largeness-spacing, this.largeness-spacing);

                    }
                }
            }

            var character = {x : parseInt(Math.random()*resolution), y:0,orientation:""};//grid positions
            character.y = ((character.x == 0 || character.x == resolution-1)? parseInt(Math.random()*resolution): Math.round(Math.random())*(resolution-1));//set position od edge

            var start = character;
            var end;

            if(character.y == 0){
                character.orientation = "down";
            }else if(character.x == 0){
                character.orientation = "right";
            }else if(character.x == resolution-1){
                character.orientation = "left";
            }else if(character.y == resolution-1){
                character.orientation = "up";
            }

            var characterHistory = [character,character];

            var characterHistoryS;
            var characterSearch;

            var grid = [];

            for(let i = 0; i < resolution; i++){
                let column = [];
                for(let j = 0; j < resolution; j++){
                    column.push(new segment(i*(500/resolution), j*(500/resolution), 500/resolution, i,j));
                }
                grid.push(column);
            }

            grid[character.x][character.y].special = 1;

            function generateMaze(){
                interval = setInterval(function(){
                    ctx.fillStyle = "black";
                    ctx.clearRect(0, 0, 500, 500);
                    ctx.fillRect(0, 0, 500, 500);
    
                    for(let i = 0; i < resolution; i++){
                        for(let j = 0; j < resolution; j++){
                            grid[i][j].draw();
                        }
                    }
    
                    let nextMove = getAvailable();
                    if(nextMove != false){
                        character = nextMove;
                        characterHistory.push(character);
                    }else{
                        backTrackOne();
                    }

                    if(character == characterHistory[0]){
                        clearInterval(interval);

                        ctx.fillStyle = "black";
                        ctx.clearRect(0, 0, 500, 500);
                        ctx.fillRect(0, 0, 500, 500);

                        let exit = getExit();
                        grid[exit.x][exit.y].visited = true;
                        grid[exit.x][exit.y].special = 2;

                        for(let i = 0; i < resolution; i++){
                            for(let j = 0; j < resolution; j++){
                                grid[i][j].backTracked = false;
                                grid[i][j].draw();

                                if(grid[i][j].special == 2){
                                    end = grid[i][j];
                                }
                            }
                        }

                        solver = solveMaze;

                        document.getElementById("solveButton").style.display = "inline";
                        console.log("maze generated");

                    }
                    
                }, 0);

            }

            function solveMaze(){
                
                character = {x:character.x, y:character.y};
                characterHistoryS = [character];

                interval = setInterval(function(){
                    let option = getOption();
                    if(option == false){
                        grid[character.x][character.y].backTracked = true;
                        character = characterHistoryS[characterHistoryS.length-2];
                        characterHistoryS.splice(characterHistoryS.length-1, 1);
                    }else{
                        character = option;
                        characterHistoryS.push(character)
                    }

                    for(let i = 0; i < resolution; i++){
                        for(let j = 0; j < resolution; j++){
                            grid[i][j].draw();
                        }
                    }

                    if(character.x == end.posGridX && character.y == end.posGridY){
                        clearInterval(interval);

                        for(let i = 0; i < resolution; i++){
                            for(let j = 0; j < resolution; j++){
                                grid[i][j].backTracked = false;
                                grid[i][j].draw();
                            }
                        }

                    }

                    console.log(option);
                }, 0);
                

            }

            function getOption(){
                let options = [];
                for(var i = -1; i < 2; i++){
                    for(var j = -1; j < 2; j++){
                        if(!(i == 0 && j == 0) && !(Math.abs(i) == 1 && Math.abs(j) == 1) && (pointValid({x:character.x+i, y:character.y+j}) || (end.posGridX == character.x+i && end.posGridY == character.y+j)) && !XYcoordExists({x:character.x+i, y:character.y+j},characterHistoryS) && !grid[character.x+i][character.y+j].backTracked){
                            if(grid[character.x+i][character.y+j].visited){
                                options.push({x:character.x+i, y:character.y+j});
                            }
                        }
                    }
                }

                if(options.length == 0){
                    return false;
                }else{
                    return options[parseInt(Math.random()*options.length)];
                }
            }

            function XYcoordExists(a, array){
                for(let i = 0; i < array.length; i++){
                    if(array[i].x == a.x && array[i].y == a.y){
                        return true;
                    }
                }
                return false;
            }



            function testDrawExits(){
                for(let i = 0; i < 10000; i++){let exit = getExit(); grid[exit.x][exit.y].special = 2; grid[exit.x][exit.y].draw();}
            }

            function backTrackOne(){
                grid[character.x][character.y].backTracked = true;
                character = characterHistory[characterHistory.length-2];
                characterHistory.splice(characterHistory.length-1,1);
            }

            function getExit(){
                let valid = false;
                let exit = {x:-1, y:-1};

                let iteration = 0;
                while(!valid){
                    exit = {x : parseInt(Math.random()*resolution), y:0};//grid positions
                    exit.y = ((exit.x == 0 || exit.x == resolution-1)? parseInt(Math.random()*resolution): Math.round(Math.random())*(resolution-1));//set position od edge
    
                    for(var i = -1; i < 2; i++){
                        for(var j = -1; j < 2; j++){
                            if((i == 0 || j == 0) && pointValid({x:exit.x+i, y:exit.y+j}) && !(exit.x == character.x && exit.y == character.y) && ((Math.abs(exit.x - character.x) != 1) && (Math.abs(exit.y - character.y) != 1))){
                                if(grid[exit.x+i][exit.y+j].visited){
                                    valid = true;
                                }
                            }
                        }
                    }
                    if(iteration > resolution*5){
                        console.log("can not find exit");
                        break;
                    }
                    iteration++
                }
                return exit;
            }

            function getAvailable(){
                
                let toCheck = [{x:character.x-1,y:character.y, orientation:"left"}/* left */, {x:character.x+1,y:character.y, orientation:"right"}/* right */, {x:character.x,y:character.y-1, orientation:"up"}/* up */, {x:character.x,y:character.y+1, orientation:"down"}/* down */];
                let found = false;

                //no jagged lines

                let removeOption = [];
                if(character.orientation != characterHistory[characterHistory.length-2].orientation){
                    toCheck.forEach(function(element,i){
                        if(element.orientation != character.orientation){
                            removeOption.push(i);
                        }
                    });
                }

                removeOption = removeOption.reverse();

                removeOption.forEach(function(element){
                    toCheck.splice(element, 1);
                });

                while(toCheck.length > 0 && !found){
                    let indexToCheck = parseInt(Math.random()*(toCheck.length));
                    if(pointValid(toCheck[indexToCheck])){

                        let toCheckForNeighbours = [];

                        
                        for(var i = -1; i < 2; i++){
                            for(var j = -1; j < 2; j++){
                                if(!(i == 0 && j == 0)){
                                    toCheckForNeighbours.push({x:toCheck[indexToCheck].x+i, y:toCheck[indexToCheck].y+j});
                                }
                            }
                        }

                        let hasNeighbours = false;

                        toCheckForNeighbours.forEach(function(element, i){
                            if(pointValid(element) && !(element.x == character.x && element.y == character.y) && !(element.x == characterHistory[characterHistory.length-2].x && element.y == characterHistory[characterHistory.length-2].y)){
                                //dont check if: 1) point is out of the map, 2) point is it self, 3) point belongs to the last move
                                if(grid[element.x][element.y].visited){
                                    hasNeighbours = true;
                                }
                            }
                        });

                        if(!grid[ toCheck[indexToCheck].x ][ toCheck[indexToCheck].y ].visited && !hasNeighbours){
                            found = {x:toCheck[indexToCheck].x,y:toCheck[indexToCheck].y, orientation:toCheck[indexToCheck].orientation};
                            //console.log('found:', found.orientation);
                        }
                    }
                    toCheck.splice(indexToCheck,1);
                }

                return found;
            }

            function pointValid(element){
                return !(element.x <= 0 || element.x >= resolution-1 || element.y <= 0 || element.y >= resolution-1);
            }

            }

        </script>
        <script src="javascript/functionality.js"></script>
    </div>
</body>
</html>

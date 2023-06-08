
// Pre game prep
const playerCharacters =['player-1-char-1-sprite', 'player-2-char-1-sprite', 'player-1-char-2-sprite', 'player-2-char-2-sprite'];
let turnCounter = -1;

// define turns
let turnUser1 = true;
let turnUser2 = false;

let glob;

let currentTurn = 0;

let turnPlayerId;
let currentTurnPlayer;
let turnDefenderPlayerId;

// changing the turn box order
let isInitiated = false;

// checking if a character has moved or attacked
let hasMoved = false;
let hasAttacked = false;

// the canvas that has to move or preform an action
let turnCharacterCanvas;

// Rotating the damage area
let clickCounter = 0;


let firstPlayer;
let secondPlayer;

// single character ID
let characterId;
let charNmbr;
// characterIDs
let characterPlayers = [];
let hpHoverCall;
let characterMovePlusIndex;
// roomID
let id;

// UserID
let thisPlayerIdNew;
let thisPlayer;
let userID;
let attackType;
let moveName;
function setRoomID(roomID){
    id = roomID;
}
function setThisPlayer(thisPlayerId){
    thisPlayer = thisPlayerId;
}
function setNewPlayerID(asd){
    thisPlayerIdNew = asd;
}

const tiles = document.querySelectorAll('div.tile');
const hpcont = document.querySelector('div#hp-number p');
const turn_banner = document.getElementById('turn-change');
const playerChangeTxt = document.getElementById('player-turn-txt');
const loadOverlay = document.getElementById('loading-overlay');
let secondaryCharachters;

// Update database every 5 seconds
function heartBeat(){
    let interval;
    clearInterval(interval);
    // return;
    interval = setInterval(function checkGameState() {

        fetchData(`../game-func-pages/heartbeat.php?id=${id}&request=WINNER&play1char1=${characterPlayers[0]}&play1char2=${characterPlayers[2]}&play2char1=${characterPlayers[1]}&play2char2=${characterPlayers[3]}`).then((data) => {
            if (data[1]["hp11"]["CHARACTER_HP"] <= 0){
                document.querySelector("#"+playerCharacters[0]).classList.add("deadCharacter");
            } if (data[1]["hp12"]["CHARACTER_HP"] <= 0){
                document.querySelector("#"+playerCharacters[2]).classList.add("deadCharacter");
            } if (data[1]["hp21"]["CHARACTER_HP"] <= 0){
                document.querySelector("#"+playerCharacters[1]).classList.add("deadCharacter");
            } if (data[1]["hp22"]["CHARACTER_HP"] <= 0){
                document.querySelector("#"+playerCharacters[3]).classList.add("deadCharacter");
            }
        
            if (
                (data[1]["hp11"]["CHARACTER_HP"] <= 0 && data[1]["hp12"]["CHARACTER_HP"] <=0) ||
                (data[1]["hp21"]["CHARACTER_HP"] <= 0 && data[1]["hp22"]["CHARACTER_HP"] <=0)
            ) {
                if (data[1]["hp11"]["CHARACTER_HP"] <= 0 && data[1]["hp12"]["CHARACTER_HP"] <=0)  {
                    alert("Well played player 2!");
                    jQuery.ajax(
                        {
                            url: "../game-func-pages/addVictoryPoint.php?player="+userID.player2,
                            method: 'GET',
                            
                            success: function (result){
                                window.location.href = "https://anthonytoons.nl/rpg_evo_clash/pages/startmenu.php";
                            } 
                        }
                    )
                } else if  (data[1]["hp21"]["CHARACTER_HP"] <= 0 && data[1]["hp22"]["CHARACTER_HP"] <=0){
                    alert("Well played player 1!");
                    jQuery.ajax(
                        {
                            url: `../game-func-pages/addVictoryPoint.php?player=${userID.player1}`,
                            method: 'GET',
                            
                            success: function (result){
                                window.location.href = "https://anthonytoons.nl/rpg_evo_clash/pages/startmenu.php";
                            } 
                        }
                    )
                }
            }
        }); // Add this closing brace to end the promise.
        

        loadOverlay.style.display="none";
        fetchData(`../game-func-pages/heartbeat.php?id=${id}&request=GETTURN`).then((data) => {
            currentTurnPlayer = data[0].player;
            thisPlayer = currentTurnPlayer;
            let turn_counter = data[0].turncount_;
            if 
            (((turn_counter % 2 === 1 && currentTurnPlayer === userID.player2 && turnCounter === 0) &&(
                hasMoved == false&&
                hasAttacked == false))
            || ((turn_counter % 2 === 0 && currentTurnPlayer === userID.player1 && turnCounter === 0) &&(
                hasMoved == false&&
                hasAttacked == false))
            ){
                for(let i = 0; i < turn_counter; i++){
                    turnReset('TIMEFORWARD');
                }
            }
        });

        if (!characterPlayers[1] && !characterPlayers[3]){
            getCharacters(id);
        }
        
        if (turnUser1 && !turnUser2) {
            turn_banner.style.background = "linear-gradient(15deg, #69b3e7, #2633a9, #3762ad)";
            playerChangeTxt.innerHTML = "Player 1's turn";
        } else if (!turnUser1 && turnUser2) {
            turn_banner.style.background = "linear-gradient(15deg, #e76969, #a92626, #ad3737)";
            playerChangeTxt.innerHTML = "Player 2's turn";
        }
        loadOverlay.style.display="none";
    }, 5000);
}
// reset turn
function turnReset(yep){ 
    turnCounter++;
    if (yep != 'TIMEFORWARD'){
        fetchData(`../game-func-pages/heartbeat.php?id=${id}&request=PUSHTURN`).then((data) => {
            currentTurnPlayer = data[0].player;
            thisPlayer = currentTurnPlayer;
            if (turnCounter === 1) return;
        });
    } else if (yep === 'load-in'){
        return checkMoveOptions(turnCharacterCanvas);
    }
document.querySelector('div#moves-4 div').innerHTML = "Pass";
    if (turnUser1 && !turnUser2){
        turnUser1 = false;
        turnUser2 = true;
        turnPlayerId = firstPlayer;
        turnDefenderPlayerId = secondPlayer;
    } else if (!turnUser1 && turnUser2){
        turnUser1 = true;
        turnUser2 = false;
        turnPlayerId = secondPlayer;
        turnDefenderPlayerId = firstPlayer;
    }
    hasMoved = false;
    hasAttacked = false;
    if (turnCounter > 3) {
        turnCounter = 0;
    }
    turnCharacterCanvas = document.querySelector(`#${playerCharacters[turnCounter]}`); 
    if (turnCharacterCanvas.classList.contains("deadCharacter")) turnReset();
    const img = document.querySelectorAll('div.attack-moves img');
    $.get("../js/json/characterRenders.json", json=>{
        for (let i = 0; i < img.length; i++){
            img[i].src = json.attackIcons[i].icon;
            if (i==3){
                if (characterPlayers[turnCounter] == -1) img[3].src=json.evolve_icons[characterPlayers[turnCounter]+=1].mark;
                else img[3].src=json.evolve_icons[characterPlayers[turnCounter]-1].mark
            }     
        }
    });
    advanceTurn();
    checkMoveOptions(turnCharacterCanvas);
    window.location.reload();
}

// Fetch call
async function fetchData(url = '') {
    // Default options are marked with *
    const response = await fetch(url, {
      method: 'GET', // *GET, POST, PUT, DELETE, etc.
      headers: {
        'Content-Type': 'application/json'
        // 'Content-Type': 'application/x-www-form-urlencoded',
      }
    });
    return response.json(); // parses JSON response into native JavaScript objects
}
// Get all characters
function getCharacters(id){
    fetchData("../game-func-pages/getCharacters.php?id="+id).then((data) => {
        getValues(id, data);
        setCharacters(data);
    });
}
// Set the characters and their spritesheets
function setCharacters(data){
    const source = [
        {
            id: 1,
            src: '../img/spritesheets/void-spritesheet-NEW2.png'
        },
        {
            id: 2,
            src: '../img/spritesheets/lilith-spritesheet.png'
        },
        {
            id: 3,
            src: '../img/spritesheets/ruby-spritesheet.png'
        },
        {
            id: 4,
            src: '../img/spritesheets/kitt-spritesheet.png'
        },
        {
            id: 5,
            src: '../img/spritesheets/paige-spritesheet.png'
        },
        {
            id: 6,
            src: '../img/spritesheets/kite-spritesheet.png'
        },
    ];
    const canvasID =['player-1-char-1-sprite', 'player-2-char-1-sprite', 'player-1-char-2-sprite', 'player-2-char-2-sprite'];  
    for (let player in data) {
        for (let i = 0; i < data[player].length; i++) {
          let character = data[player][i][0];
          characterId = data[player][i][1];
          characterPlayers.push(characterId);
      
          let match = source.find(element => element.id === characterId);
          if (match) {
            spriteSrc = source[characterId - 1].src;
            getSheet(spriteSrc, player, character, canvasID, source[characterId - 1].id);
          } else {
            console.log("Load failed for " + player + ", " + character);
          }
          setIcons(characterId);
      
          if (characterPlayers.length === 4) {
            let switcheroo = characterPlayers[1];
            characterPlayers[1] = characterPlayers[2];
            characterPlayers[2] = switcheroo;
          }
        }
      }
      secondaryCharachters = [characterPlayers[2], characterPlayers[3]]
}
// Get the stats (HP, ATK, DEF)
function getValues(id, characterIDs) {
    setTimeout(()=>{
        arr = [];
        for (let player in characterIDs) {
            for (let i = 0; i < characterIDs[player].length; i++) {
                let characterId = characterIDs[player][i][1];
                arr.push(characterId);
            }
        }
        for(let i = 0; i < arr.length; i++){
            document.getElementById(playerCharacters[i]).classList.add(arr[i]);
        }
    
        fetchData(`../game-func-pages/getCharacterData.php?characters=${arr.toString()}`, {
            'headers': {
                'Accept': 'text/html',
                'Content-Type': 'text/html'
            },
            'method':'GET',
            'body':'',
        }).then((character_data) => {
            let stats = character_data;
            heartBeat(id, stats);
        });

    })
}

// Create a battlefield with 28 tiles
function createBattlefield(){
    let tileAmt = 28;
    const grid = document.getElementById("grid");
    let rowCounter = 1;
    let columnCounter = 1;
    for (let i = 0; i < tileAmt; i++){
        let tile = document.createElement('div');
        tile.className = "tile";
        tile.dataset.tilex = columnCounter;
        tile.dataset.tiley= rowCounter;
        if (columnCounter < 7){
            columnCounter++;
        } else {
            columnCounter = 1;
            rowCounter++;
        }
        grid.appendChild(tile);
    }
}
// Get the sheets and animate them
function getSheet(src, player, characterID, canvasID, sheetID){
    if (player ==="player1" && characterID == 'character_id_1'){
        canvasLocation = 0;
    } else if (player==="player1" && characterID == 'character_id_2') {
        canvasLocation = 2;
    } else if (player==="player2" && characterID == 'character_id_1') {
        canvasLocation = 1;
    } else if (player==="player2" && characterID == 'character_id_2') {
        canvasLocation = 3;
    }
    const canvas = document.getElementById(canvasID[canvasLocation]);
    let playerState = 'idle';
    const ctx = canvas.getContext('2d');
    const CANVAS_WIDTH = canvas.width = 600;
    const CANVAS_HEIGHT = canvas.height = 600;
    const spriteIMAGE = new Image();
    spriteIMAGE.src = src;
    const spriteWidth = 1000;
    const spriteHeight = 1000;
    let gameFrame = 0;
    const staggerFrames = 9;
    const spriteAnimations = [];
    const animationState = [
        {
            name: "idle",
            frames: 5,
            // frames: 4,
        }
    ];

    if (
        (sheetID === 3 && player === 'player1')|| 
        (sheetID === 4 && player === 'player1')
    ){
        canvas.classList.add("spinToRight")
    }
    if (
        (sheetID === 1 && player === 'player2')|| 
        (sheetID === 2 && player === 'player2')
    ){
        canvas.classList.add("spinToLeft")
    }

    animationState.forEach((state, index) => {
        let frames = {
            loc: [],
        }
        for (let j = 0; j < state.frames; j++) {
            let positionX = j * spriteWidth;
            let positionY = index * spriteHeight;
            frames.loc.push({x: positionX, y: positionY});
        }
        spriteAnimations[state.name] = frames;
    });
    function animateSheet(){
        ctx.clearRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        let position = Math.floor(gameFrame/staggerFrames) % spriteAnimations[playerState].
        loc.length;
        let frameX = spriteWidth * position;
        let frameY = spriteAnimations[playerState].loc[position].y;
        ctx.drawImage(spriteIMAGE, frameX, frameY, spriteWidth, 
        spriteHeight, 0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        gameFrame++;
        requestAnimationFrame(animateSheet);
    };
    animateSheet();
}

// Game: Start!
window.addEventListener("DOMContentLoaded", ()=>{
    loadOverlay.style.display="flex";
    document.querySelector('#grid').addEventListener('click', checkAction, false);
    const turnboxes = document.querySelector('section#turn-counter');
    turnboxes.addEventListener('mouseover', seeHP, false);
    turnboxes.addEventListener('mouseout', delHP, false);
    const moveBoxes = document.querySelectorAll(".attack-moves");
    for (let i = 0; i < moveBoxes.length; i++){
        moveBoxes[i].addEventListener("click", ()=>{
            hoverUp(i);
        });
    }
    document.addEventListener("keydown", (event) => {   
        switch (event.key) {
            case '1':
                hoverUp(0);
                break;
            case '2':
                hoverUp(1);
                break;
            case '3':
                hoverUp(2);
                break;
            case '4':
                break;
            case '5':
                hoverUp(4);
                break;
            case 'Escape':
                hoverUp('down');
                break;
        }
    });  
});

// Tiny HP bar on hover of character
function delHP(event){
    const container = document.querySelector('div#small-hp-container');
    if(event){
        const ev = event.target;
        if (ev.classList.contains('renderBoxes')) return;
    }
    container.style.opacity = 0;
}
function seeHP(event, player){
    const container = document.querySelector('div#small-hp-container');
    if (!event.target){
        switch (player.id) {
            case 'player-1-char-1-sprite':
                getCurrentHP(userID.gameid, characterPlayers[0], 1, userID.player1);
                break;
            case 'player-2-char-1-sprite':
                getCurrentHP(userID.gameid, characterPlayers[1], 1, userID.player2);
                break;
            case 'player-1-char-2-sprite':
                getCurrentHP(userID.gameid, characterPlayers[2], 2, userID.player1);
                break;
            case 'player-2-char-2-sprite':
                getCurrentHP(userID.gameid, characterPlayers[3], 2, userID.player2);
                break;
        }
    } else {
        const ev = event.target;
        if (ev.classList.contains('renderBoxes')) return;
        var rect = ev.getBoundingClientRect();
        container.style.top = (rect.bottom-180) + 'px';
        container.style.left = (rect.left-50) + 'px';
        container.style.opacity = 1;
        switch (event.target.id) {
            case 'character-turn-1':
                getCurrentHP(userID.gameid, characterPlayers[0], 1, userID.player1, 0);
                break;
            case 'character-turn-2':
                getCurrentHP(userID.gameid, characterPlayers[1], 1, userID.player2, 1);
                break;
            case 'character-turn-3':
                getCurrentHP(userID.gameid, characterPlayers[2], 2, userID.player1, 2);
                break;
            case 'character-turn-4':
                getCurrentHP(userID.gameid, characterPlayers[3], 2, userID.player2, 3);
                break;
        }
    }
}
function hoverUp(moveNmbr){
    // const tiles = document.querySelectorAll('div.tile');
    const title = document.querySelector('#description-title h1');
    const desc = document.querySelector('#description-desc p');
    tiles.forEach(tile => {
        tile.classList.remove('ATKGlow');
    });
    const moves = document.getElementsByClassName("attack-moves");
    Array.from(moves).forEach(move => {
        move.classList.remove("selected");
    });      
    switch (moveNmbr) {
        case 0:
            moves[0].classList.add('selected');
            break;
        case 1:
            moves[1].classList.add('selected');
            break;
        case 2:
            moves[2].classList.add('selected');
            break;
        case 4:
            moves[4].classList.add('selected');
            break;
        case 5:
            moves[5].classList.add('selected');
            break;
        case 'down':
            Array.from(moves).forEach(move=>{
                move.classList.remove("selected");
            });
            document.querySelector('#atk-moves-description').style.opacity = '0';
            break;
    }
    if (moveNmbr < 4){
        switch (characterPlayers[turnCounter]) {
            case 1:
                characterMovePlusIndex = 1;
                break;
            case 2:
                characterMovePlusIndex = 4;
                break;
            case 3:
                characterMovePlusIndex = 7;
                break;
            case 4:
                characterMovePlusIndex = 10;
                break;
            case 5:
                characterMovePlusIndex = 13;
                break;
            case 6:
                characterMovePlusIndex = 16;
                break;
        }
        jQuery.ajax(
            {
                url: "../game-func-pages/getAttackMoves.php?move="+(Number(moveNmbr+characterMovePlusIndex))+"&char="+(characterPlayers[turnCounter]),
                method: 'GET',
                
                success: function (result){
                    glob = result.attack.name;
                    markDmgZone(result);
                    rotateMarkDmgZone(result);
                } 
            }
        )
        document.querySelector('#atk-moves-description').style.opacity = '1';
        $.get("../js/json/atkmoves.json", (json)=>{
            title.innerHTML = getTitles('title', json, moveNmbr);
            desc.innerHTML =  getTitles('desc', json, moveNmbr);

        });
    } else if (moveNmbr == 3) {
        console.log("evolve");
    }
};
// Mark the zone that the attack is going to take place
const markDmgZone = (range)=> {
    let rangeX = turnCharacterCanvas.parentElement.dataset.tilex;
    let rangeY = turnCharacterCanvas.parentElement.dataset.tiley;
    for (arr in range){
        for (let i = 0; i < range[arr].xRange; i++){
            if (document.querySelector('div.tile[data-tilex = "'+(parseInt(rangeX)+i+1) +'"][data-tiley = "'+parseInt(rangeY)+'"]') && (parseInt(rangeX)+i+1) > rangeX) document.querySelector('div.tile[data-tilex = "'+(parseInt(rangeX)+i+1) +'"][data-tiley = "'+parseInt(rangeY)+'"]').classList.add('ATKGlow');
            else if (document.querySelector('div.tile[data-tilex = "'+(parseInt(rangeX)-i-1) +'"][data-tiley = "'+parseInt(rangeY)+'"]')) document.querySelector('div.tile[data-tilex = "'+(parseInt(rangeX)-i-1) +'"][data-tiley = "'+parseInt(rangeY)+'"]').classList.add('ATKGlow');
        }
    }
}
// rotate the damage area
function rotateMarkDmgZone(range) {
    delHP('');
    clickCounter++;
    removeGlow('atk');
    let rangeX = turnCharacterCanvas.parentElement.dataset.tilex;
    let rangeY = turnCharacterCanvas.parentElement.dataset.tiley;
    const allPlayers = document.querySelectorAll(".sprites");
    switch(clickCounter) {
    default: // Default direction (right)
        for (arr in range) {
        for (let i = 0; i < range[arr].xRange; i++) {
            if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)+i+1}"][data-tiley="${parseInt(rangeY)}"]`) && (parseInt(rangeX)+i+1) > rangeX) {
                document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)+i+1}"][data-tiley="${parseInt(rangeY)}"]`).classList.add('ATKGlow');
            allPlayers.forEach(player=>{
                if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)+i+1}"][data-tiley="${parseInt(rangeY)}"]`).contains(player)){
                    document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)+i+1}"][data-tiley="${parseInt(rangeY)}"]`).classList.add('ATKTarget');
                    document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)+i+1}"][data-tiley="${parseInt(rangeY)}"]`).classList.remove(".ATKGlow");
                    seeHP('', player);
                    battle(range.attack.name);
                    const container = document.querySelector('div#small-hp-container');
                    let rect = player.getBoundingClientRect();
                    container.style.top = (rect.bottom-250) + 'px';
                    container.style.left = (rect.left+30) + 'px';
                    container.style.opacity = 1;
                }
            });
            }
            else if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`) && (parseInt(rangeX)-i-1) > rangeX) {
            document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`).classList.add('ATKGlow');
            }
        }
        }
        clickCounter=0;
        break;
        case 1: // Down direction
        for (arr in range) {
        for (let i = 0; i < range[arr].xRange; i++) {
            if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)+i+1}"]`)) {
                document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)+i+1}"]`).classList.add('ATKGlow');
                allPlayers.forEach(player=>{
                    if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)+i+1}"]`).contains(player)){
                        document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)+i+1}"]`).classList.add('ATKTarget');
                        document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)+i+1}"]`).classList.remove('ATKGlow');
                        seeHP('', player);
                        battle(range.attack.name);
                        const container = document.querySelector('div#small-hp-container');
                        let rect = player.getBoundingClientRect();
                        container.style.top = (rect.bottom-250) + 'px';
                        container.style.left = (rect.left+30) + 'px';
                        container.style.opacity = 1;
                    }
                });
            }
            else if(document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)-i-1}"]`) && (parseInt(rangeY)-i-1) > rangeY){
                document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)-i-1}"]`).classList.add('ATKGlow');
            }
        }
        }
        break;
    case 2: // Left direction
        for (arr in range) {
        for (let i = 0; i < range[arr].xRange; i++) {
            if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`) )
            // && (parseInt(rangeX)-i-1) < rangeX) 
            {
            document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`).classList.add('ATKGlow');
            allPlayers.forEach(player=>{
                if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`).contains(player)){
                    document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`).classList.add('ATKTarget');
                    document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`).classList.remove('ATKGlow');
                    seeHP('', player);
                    battle(range.attack.name);
                    const container = document.querySelector('div#small-hp-container');
                    let rect = player.getBoundingClientRect();
                    container.style.top = (rect.bottom-250) + 'px';
                    container.style.left = (rect.left+30) + 'px';
                    container.style.opacity = 1;
                }
            });
            }
            else if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`)){
            document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)-i-1}"][data-tiley="${parseInt(rangeY)}"]`).classList.add('ATKGlow');
            }
        }
        }
        break;
    case 3: // Up direction
        for (arr in range) {
        for (let i = 0; i < range[arr].xRange; i++) {
            if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)-i-1}"]`) && (rangeY-i-1) < rangeY) {
            document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)-i-1}"]`).classList.add('ATKGlow');
            allPlayers.forEach(player=>{
                if (document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)-i-1}"]`).contains(player)){
                    document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)-i-1}"]`).classList.remove('ATKGlow');
                    document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)-i-1}"]`).classList.add('ATKTarget');
                    seeHP('', player);
                    battle(range.attack.name);
                    const container = document.querySelector('div#small-hp-container');
                    let rect = player.getBoundingClientRect();
                    container.style.top = (rect.bottom-250) + 'px';
                    container.style.left = (rect.left+30) + 'px';
                    container.style.opacity = 1;
                }
            });
            }
            else if(document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)+i+1}"]`) && (rangeY-i-1) > rangeY) {
            document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)+i+1}"]`).classList.add('ATKGlow');
            }
        }
        }
        break;
    }
    removeGlow('characterglow');
    if (range.attack.xRange === 0 && range.attack.yRange === 0){
        switch (range.attack.name) {
            case 'Riposte':
                document.querySelector(`div.tile[data-tilex="${parseInt(rangeX)}"][data-tiley="${parseInt(rangeY)}"]`).classList.add('ATKTarget');
                attackPlayer('define', 'Riposte');
                break;
            case 2:
                // code block
                break;
            case 3:
                // code block
                break;
            case 4:
                // code block
                break;
            case 5:
                // code block
                break;
            default:
                // code block
                break;
        }
    } else {
        attackPlayer('define', range.attack.name);
    }
}
// Check action
function checkAction(event){
    if (currentTurnPlayer != thisPlayerIdNew){
        console.log("Don't touch that, it's not your turn yet");
        return;
    }
    const clickTarget = event.target;
    if (clickTarget.classList.contains('glow') && currentTurnPlayer == thisPlayerIdNew){
        move (event);
    } else if (clickTarget.classList.contains('ATKTarget') && currentTurnPlayer == thisPlayerIdNew){
        attackPlayer(event, glob);
    } 
    else console.log('No move possible on this tile');
}
// Moving
function checkMoveOptions(turnCharacterCanvas){
    removeGlow();
    let positionX = turnCharacterCanvas.parentElement.dataset.tilex;
    let positionY = turnCharacterCanvas.parentElement.dataset.tiley;
    const options = [
        document.querySelector('div.tile[data-tilex = "'+(parseInt(positionX)+1) +'"][data-tiley = "'+parseInt(positionY)+'"]'),
        document.querySelector('div.tile[data-tilex = "'+(parseInt(positionX)-1) +'"][data-tiley = "'+parseInt(positionY)+'"]'),
        document.querySelector('div.tile[data-tilex = "'+parseInt(positionX)+'"][data-tiley = "'+(parseInt(positionY)+1) +'"]'),
        document.querySelector('div.tile[data-tilex = "'+parseInt(positionX)+'"][data-tiley = "'+(parseInt(positionY)-1) +'"]'),
    ];
    options.forEach(element => {
        if (element && !element.querySelector('canvas.sprites')) element.classList.add('glow');
    });
}
function move(event){
    const allPlayers = document.querySelectorAll(".sprites");
    let moving = event.target;
    if (moving.classList.contains('glow')){
        allPlayers.forEach(player =>{
            if (moving.contains(player)) return;
        });
        moving.appendChild(document.querySelector('#'+playerCharacters[turnCounter]));
        hasMoved = true;
    }; if (moving === turnCharacterCanvas.parentElement){
        hasMoved = true;
    }; if (hasMoved === true){
        removeGlow();
    };
    posX = turnCharacterCanvas.parentElement.dataset.tilex;
    posY = turnCharacterCanvas.parentElement.dataset.tiley;

    if (turnCounter > 1){
        jQuery.ajax({
            type: "POST",
            url: `../game-func-pages/pushMovement.php?id=${id}&character=${characterPlayers[turnCounter]}&x=${posX}&y=${posY}&player=${turnPlayerId}&charcount=${2}`,
            success: function (result) {
                
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
                console.log("Status: " + status);
                console.log("Response: " + xhr.responseText);
            },
            dataType: "text" // Set dataType to "text";
        });
    } else {
        jQuery.ajax({
            type: "POST",
            url: `../game-func-pages/pushMovement.php?id=${id}&character=${characterPlayers[turnCounter]}&x=${posX}&y=${posY}&player=${turnPlayerId}&charcount=${1}`,
            success: function (result) {
                // console.log(result);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
                console.log("Status: " + status);
                console.log("Response: " + xhr.responseText);
            },
            dataType: "text" // Set dataType to "text"
        });
    }
};

// Attack
function attackPlayer(yes, pushName){
    if (yes === 'define') moveName = pushName;
    else {
        battle(moveName, 'ATTACK');
    }
}
let newres;
function battle(name, action){
    const smallHPbar = document.querySelector('#small-hp-container');
    let defender = document.querySelector('.ATKTarget .sprites');
    if (turnPlayerId === userID) return;
    if (hasMoved === true){
        removeGlow("movement");
    };
    // prevent team attack
    if ((characterPlayers[turnCounter] === characterPlayers[0] && getcharacterID(defender) === secondaryCharachters[0]) ||
        (characterPlayers[turnCounter] === secondaryCharachters[0] && getcharacterID(defender) === characterPlayers[0]) ||
        // for team 2
        (characterPlayers[turnCounter] === characterPlayers[1] && getcharacterID(defender) === secondaryCharachters[1]) ||
        (characterPlayers[turnCounter] === secondaryCharachters[1] && getcharacterID(defender) === characterPlayers[1])
    ) return console.log('No attacking teammates!');

    if (action != 'ATTACK'){
        // If character ID 1 attacks character ID 2
        if ((getcharacterID(defender) === secondaryCharachters[0] || getcharacterID(defender) === secondaryCharachters[1]) && 
        (characterPlayers[turnCounter] == characterPlayers[0] || characterPlayers[turnCounter] == characterPlayers[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=2&ATKcount=1&atkname=${name}&type=SIM`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }

        // If character ID 2 attacks character ID 1
        else if ((getcharacterID(defender) === characterPlayers[0] || getcharacterID(defender) === characterPlayers[1]) && 
        (characterPlayers[turnCounter] == secondaryCharachters[0] || characterPlayers[turnCounter] == secondaryCharachters[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=1&ATKcount=2&atkname=${name}&type=SIM`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }

        // If character ID 1 attacks character ID 1
        else if ((getcharacterID(defender) === characterPlayers[0] || getcharacterID(defender) === characterPlayers[1]) &&
        (characterPlayers[turnCounter] == characterPlayers[0] || characterPlayers[turnCounter] == characterPlayers[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=1&ATKcount=1&atkname=${name}&type=SIM`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }

        // If character ID 2 attacks character ID 2
        else if ((getcharacterID(defender) === secondaryCharachters[0] || getcharacterID(defender) === secondaryCharachters[1]) && 
        (characterPlayers[turnCounter] === secondaryCharachters[0] || characterPlayers[turnCounter] === secondaryCharachters[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=2&ATKcount=2&atkname=${name}&type=SIM`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }
    } else {
        hasAttacked = true;
        // If character ID 1 attacks character ID 2
        if ((getcharacterID(defender) === secondaryCharachters[0] || getcharacterID(defender) === secondaryCharachters[1]) && 
        (characterPlayers[turnCounter] == characterPlayers[0] || characterPlayers[turnCounter] == characterPlayers[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=2&ATKcount=1&atkname=${name}&type=ATK`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }

        // If character ID 2 attacks character ID 1
        else if ((getcharacterID(defender) === characterPlayers[0] || getcharacterID(defender) === characterPlayers[1]) && 
        (characterPlayers[turnCounter] == secondaryCharachters[0] || characterPlayers[turnCounter] == secondaryCharachters[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=1&ATKcount=2&atkname=${name}&type=ATK`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }

        // If character ID 1 attacks character ID 1
        else if ((getcharacterID(defender) === characterPlayers[0] || getcharacterID(defender) === characterPlayers[1]) &&
        (characterPlayers[turnCounter] == characterPlayers[0] || characterPlayers[turnCounter] == characterPlayers[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=1&ATKcount=1&atkname=${name}&type=ATK`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }

        // If character ID 2 attacks character ID 2
        else if ((getcharacterID(defender) === secondaryCharachters[0] || getcharacterID(defender) === secondaryCharachters[1]) && 
        (characterPlayers[turnCounter] === secondaryCharachters[0] || characterPlayers[turnCounter] === secondaryCharachters[1])){
            jQuery.ajax({
                type: "POST",
                url: `../game-func-pages/simulateBattle.php?id=${id}&attacker=${characterPlayers[turnCounter]}&defender=${getcharacterID(defender)}&attackingplayer=${turnPlayerId}&defendingplayer=${turnDefenderPlayerId}&DEFcharcount=2&ATKcount=2&atkname=${name}&type=ATK`,
                success: function (result) {
                    newres = JSON.parse(result);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                },
                dataType: "text" // Set dataType to "text"
            });
        }
        removeGlow('atk');
        smallHPbar.style.opacity=0;
        document.body.classList.add('screenshake');
        setTimeout(()=>{
            document.body.classList.remove('screenshake');
        },1000);
    }
    setTimeout(function() {
        if (newres){
            hasAttacked = true;
            let bar = document.getElementById('hp-bar-filler');
            let dmgBar = document.getElementById('hp-bar-filler-damage');
            
            setTimeout(function() {
                hpcont.innerHTML = `${newres[0].maxhp} - ${newres[0].damage}`;
            }, 100);

            let max = getCurrentHP(0,getcharacterID(defender),0,0,0,'get');

            calcwidth = Number(newres[0].leftHp / max.hp * 100);
            bar.style.width = calcwidth +'%';

            calcDmgWidth = Number(newres[0].damage / max.hp * 100);
            dmgBar.style.width = calcDmgWidth +'%';

            if (newres[0].leftHp <= 0){
                bar.style.width = 0 +'%';
                dmgBar.style.width = calcwidth + '%';
            }
            // AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
        }
    }, 200);
}

let currentBoxIndex = 0;
function setIcons(character) {
    const renderBoxes = document.getElementsByClassName('renderBoxes');
    const boxIndices = [0, 2, 1, 3];
  const boxIndex = boxIndices[currentBoxIndex];
  $.get("../js/json/characterRenders.json", json=>{
    renderBoxes[boxIndex].style.backgroundImage = `url('${json.renders[character-1].render}')`;
  });
  currentBoxIndex = (currentBoxIndex + 1) % boxIndices.length;
}

function advanceTurn() {
    const boxes = Array.from(document.querySelectorAll('#turn-counter > .character-turn-box'));
    let newOrder = null;
    if (isInitiated) {
      newOrder = [
        boxes[(turnCounter + 0) % boxes.length],
        boxes[(turnCounter + 1) % boxes.length],
        boxes[(turnCounter + 2) % boxes.length],
        boxes[(turnCounter + 3) % boxes.length],
      ];
    } else {
      newOrder = boxes;
      isInitiated = true;
    }
    // Update the order of the boxes in the DOM
    newOrder.forEach((box, index) => {
      box.style.order = index + 1;
    });
}

function removeGlow(test){
    const tiles = document.querySelectorAll('.tile');
    if (!test){
        tiles.forEach(tile => {
            tile.classList.remove('glow');
            tile.classList.remove('ATKGlow');
            tile.classList.remove('ATKTarget')
        });
    } else if (test == 'atk'){
        tiles.forEach(tile => {
            tile.classList.remove('ATKGlow');
            tile.classList.remove('ATKTarget')
        });
    } else if (test == 'characterglow'){
        tiles.forEach(tile => {
            if (tile.hasChildNodes() && tile != document.querySelector(`#${playerCharacters[turnCounter]}`).parentElement) {
                tile.classList.remove('ATKGlow');
            }
        });
    } else if (test = "movement"){
        tiles.forEach(tile => {
            tile.classList.remove('glow');
        });
    }
}
function getTitles (request, jsonobj, moveNmbr){
    if (request == 'title'){
        switch (characterPlayers[turnCounter]) {
            case 1:
                return jsonobj.atks_title_void[moveNmbr].void_moves_title;
            case 2:
                return jsonobj.atks_title_lilith[moveNmbr].lilith_moves_title;
            case 3:
                return jsonobj.atks_title_ruby[moveNmbr].ruby_moves_title;
            case 4:
                return jsonobj.atks_title_kitt[moveNmbr].kitt_moves_title;
            case 5:
                return jsonobj.atks_title_paige[moveNmbr].paige_moves_title;
            case 6:
                return jsonobj.atks_title_kite[moveNmbr].kite_moves_title;

        }
    } else if (request == 'desc'){
        switch (characterPlayers[turnCounter]) {
            case 1:
                return jsonobj.atks_desc_void[moveNmbr].void_moves_desc;
            case 2:
                return jsonobj.atks_desc_lilith[moveNmbr].lilith_moves_desc;
            case 3:
                return jsonobj.atks_desc_ruby[moveNmbr].ruby_moves_desc;
            case 4:
                return jsonobj.atks_desc_kitt[moveNmbr].kitt_moves_desc;
            case 5:
                return jsonobj.atks_desc_paige[moveNmbr].paige_moves_desc;
            case 6:
                return jsonobj.atks_desc_kite[moveNmbr].kite_moves_desc;

        }
    }
}
let maxhp;
function getCurrentHP(id, charID, charNmbr, ui, player, quick){
    if (quick == 'get'){
        fetchData(`../game-func-pages/getMaxHp.php?characterid=${charID}`).then((data) => {
            return maxhp = data;
        });
        return maxhp;
    }
    let bar = document.getElementById('hp-bar-filler');
    fetchData(`../game-func-pages/getMaxHp.php?characterid=${charID}`).then((data) => {
        maxhp = data;
        return maxhp;
    });
    fetchData(`../game-func-pages/getCharacterHP.php?id=${id}&characterid=${charID}&nmbr=${charNmbr}&userid=${ui}`).then((data) => {
        const smallHPbar = document.querySelectorAll('.small-hp-bar');
        let dmgBar = document.getElementById('hp-bar-filler-damage');
        hpcont.innerHTML=data.hp;
        calcwidth = Number((data.hp / maxhp.hp) * 100);
        dmgBar.style.width = 0 + '%';
        bar.style.width = calcwidth +'%';
        if (!smallHPbar[player]) return;
        smallHPbar[player].style.width = calcwidth + '%';
        if (data.hp <= 0) {
            document.querySelectorAll('div.character-turn-box')[player].classList.add('deadCharacter');
            bar.style.width= 0 + '%';
            hpcont.innerHTML = 'DEAD';
        }

        calcwidth <= 50 && calcwidth >= 25 ? smallHPbar[player].style.borderColor = 'orange' : smallHPbar[player].style.borderColor= 'yellow';

        if (calcwidth <= 25){
            smallHPbar[player].style.borderColor = 'red';
            smallHPbar[player].parentElement.classList.add('dangerHP');
        } else if (calcwidth >= 25 && calcwidth <= 50){
            smallHPbar[player].style.borderColor= 'orange';
            smallHPbar[player].parentElement.classList.remove('dangerHP');
        }
    });
}
function getUserIDs(id){
    fetchData("../game-func-pages/getusers.php?id="+id).then((data) => {
        userID = data;
        firstPlayer = data.player1;
        secondPlayer = data.player2;
    });
}
function checkAttackProperties(character, move){
    fetchData("../game-func-pages/getCharacterHP.php?id="+id+"&characterid="+charID+"&nmbr="+charNmbr+"&userid="+ui).then((data) => {
        const hpcont = document.querySelector('div#hp-number p');
        console.log(charID);
        hpcont.innerHTML=data.hp;
        bar.style.width = Number((data.hp / maxhp.hp) * 100)+'%';
    });
}
function getcharacterID(target){
    let targetchar;
    switch (target.id) {
        case 'player-1-char-1-sprite':
            targetchar = characterPlayers[0];
            break;
        case 'player-2-char-1-sprite':
            targetchar = characterPlayers[1];
            break;
        case 'player-1-char-2-sprite':
            targetchar = characterPlayers[2];
            break;
        case 'player-2-char-2-sprite':
            targetchar = characterPlayers[3];
            break;
    }
    return targetchar;
}
function addCanvas(id){
    fetchData("../game-func-pages/getPositions.php?id="+id).then((data) => {
        for(let i = 0; i<=3;i++){
            let canvas = document.createElement('canvas');
            canvas.id = playerCharacters[i];
            
            if (playerCharacters[i].includes('player-2-char-1-sprite') || playerCharacters[i].includes('player-2-char-2-sprite')) canvas.classList.add("player-2-sprites");
            canvas.classList.add("sprites");

            switch (i) {
                case 1:
                    document.querySelector('div.tile[data-tilex = "'+(parseInt(data.p2c1X)) +'"][data-tiley = "'+parseInt(data.p2c1Y)+'"]').appendChild(canvas);
                    break;
                case 2:
                    document.querySelector('div.tile[data-tilex = "'+(parseInt(data.p1c2X)) +'"][data-tiley = "'+parseInt(data.p1c2Y)+'"]').appendChild(canvas);
                    break;
                case 3:
                    document.querySelector('div.tile[data-tilex = "'+(parseInt(data.p2c2X)) +'"][data-tiley = "'+parseInt(data.p2c2Y)+'"]').appendChild(canvas);
                    break;
                default:
                    document.querySelector('div.tile[data-tilex = "'+(parseInt(data.p1c1X)) +'"][data-tiley = "'+parseInt(data.p1c1Y)+'"]').appendChild(canvas);
                    break;
            }
        }
    });
}
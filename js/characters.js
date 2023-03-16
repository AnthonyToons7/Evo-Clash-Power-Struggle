let y;
let changeTxt;
let versionCount = 1;
window.addEventListener('DOMContentLoaded',()=>{
    const characterBoxes = document.querySelectorAll('.character-selection-box');
    document.querySelector('.characters-container').addEventListener('click', select, false);
    function select(event){
        characterBoxes.forEach(box =>{
            box.classList.remove('selected');
        });
        let character = event.target;
        if (!character.classList.contains('character-selection-box')) {
            character = character.closest('.character-selection-box');
        }
        const index = Array.from(characterBoxes).indexOf(character);
        character.classList.add('selected');
        getCharacterData(index);
    }
    getCharacterData(0);

    const btnLeft = document.getElementById('btn-left');
    const btnRight = document.getElementById('btn-right');
    btnRight.addEventListener('click', switchTxt);
    btnLeft.addEventListener('click', switchTxt); 
    function switchTxt() {
        const characterFlexboxRight = document.getElementById('character-flexbox-right');
        $.get("../js/json/characterProfiles.json", function(json){
            console.log(versionCount);
            if (versionCount === 2){
                for (let i = 0; i<changeTxt.length; i++) {
                    changeTxt[i].innerHTML = json.bloodVoid[i].bloodb_info;
                }
                versionCount = 0;
            } else if (versionCount ==1){
                for (let i = 0; i<changeTxt.length; i++) {
                    changeTxt[i].innerHTML = json.fatedVoid[i].fated_info;
                }
                versionCount++;
            } else if (versionCount ==0){
                for (let i = 0; i<changeTxt.length; i++) {
                    changeTxt[i].innerHTML = json.void[i].void_info;
                }
                versionCount++;
            }
        });
        characterFlexboxRight.classList.add('offscreen');
        setTimeout(() => {
          characterFlexboxRight.classList.remove('offscreen');
          characterFlexboxRight.classList.add('offscreen2');
          setTimeout(() => {
              characterFlexboxRight.classList.remove('offscreen2');
              characterFlexboxRight.classList.add('onscreen');
              setTimeout(() => {
                characterFlexboxRight.classList.remove('onscreen');
            }, 900);
          },500)
        }, 500); 
        window.scrollTo(0, 0);
    };
});
function getCharacterData(index, version) {
    const main = document.querySelector('main');
    const render = document.querySelector('#render');
    const svg = document.querySelector('svg');
    const bg = [
        "url(../img/evolve-marks/void-mark.png)",
        "url(../img/evolve-marks/lilith-mark.png)",
        "url(../img/evolve-marks/kite-mark.png)",
        "url(../img/evolve-marks/kitt-mark.png)",
        "url(../img/evolve-marks/paige-mark.png)",
        "url(../img/evolve-marks/ruby-mark.png)"
    ];
    const renders = [
        
    ]
    const bgSize = ["50%", "25%", "30%", "30%", "40%", "25%"];
    posY = ["-220", "-120", "-20", "85", "185", "285"];
    $.get("../js/json/characterProfiles.json", function(json){
        changeTxt=document.querySelectorAll('.changeTxt');
        for (let i = 0; i<changeTxt.length; i++) {
            main.style.backgroundImage=bg[index];
            main.style.backgroundSize=bgSize[index];
            y = posY[index];
        switch (index) {
            case 1:
                // render.src = "../img/character-renders/lilith-render.png";
                changeTxt[i].innerHTML = json.lilith[i].lilith_info;
                break;
            case 2:
                changeTxt[i].innerHTML = json.kite[i].kite_info;
                break;
            case 3:
                changeTxt[i].innerHTML = json.kitt[i].kitt_info;
                break;
            case 4:
                changeTxt[i].innerHTML = json.paige[i].paige_info;
                break;
            case 5:
                changeTxt[i].innerHTML = json.ruby[i].ruby_info;
                break;
            default:
                changeTxt[i].innerHTML = json.void[i].void_info;
                document.querySelectorAll('.atk-box-img')[0].src = "../img/attack-icons/railgun_icon.png";
                document.querySelectorAll('.atk-box-img')[1].src = "../img/attack-icons/designated_target_icon.png";
                document.querySelectorAll('.atk-box-img')[2].src = "../img/attack-icons/railgun_icon.png";
                break;
            }
        }
        svg.style.transform = 'translate(20px, ' + y + 'px)';
    });
}

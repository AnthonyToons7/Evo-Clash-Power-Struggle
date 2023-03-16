const menu_box = document.getElementsByClassName('menu-boxes');
const room_menu_box = document.getElementsByClassName('room-menu-boxes');
const start_box = document.querySelector('.start-border-overlay');
const arena_box = document.getElementById('arena-box');
const practice_box = document.getElementById('practice-box');
const character_box = document.getElementById('character-box');
const updates_box = document.getElementById('updates-box');
const title = document.getElementById('title-box');
const double_room_box = document.getElementsByClassName('double-room-border-overlay');
const room_box = document.getElementsByClassName('room-border-overlay');
const double_border_overlay = document.getElementsByClassName('double-border-overlay');
const border_overlay = document.getElementsByClassName('border-overlay');
const menu_text_box = document.getElementsByClassName('menu-text-box');
const welcome_box = document.querySelector('.welcome_box');
let delayCounter = 0;
let delayCounter2 = 0;
function setPage(page){
    function addHoverClass(result, i){
        for (let j = 0; j < border_overlay.length; j++) {
            if (result=="add") {
                menu_text_box[i].classList.add("textShadow");
                double_border_overlay[i].classList.add("double-border-on");
                border_overlay[i].classList.add("border-on");
                menu_box[i].classList.add("hover");
            } else if (result=="remove"){
                menu_text_box[i].classList.remove("textShadow");
                double_border_overlay[i].classList.remove("double-border-on");
                border_overlay[i].classList.remove("border-on");
                menu_box[i].classList.remove("hover");
            } else if (result=="arena-add"){
                menu_text_box[i].classList.add("textShadow2");
                double_room_box[i].classList.add("double-border-on2");
                border_overlay[i].classList.add("border-on2");
                room_menu_box[i].classList.add("hover2");
            } else if (result=="arena-remove"){
                menu_text_box[i].classList.remove("textShadow2");
                double_room_box[i].classList.remove("double-border-on2");
                border_overlay[i].classList.remove("border-on2");
                room_menu_box[i].classList.remove("hover2");
            };
        };
    };
    if (page == 'startmenu'){
        welcome_box.classList.add('hoverIn');
        start_box.addEventListener('click', ()=>{
            start_box.style.transform = 'skew(15deg) translateX(-1200px) translateY(300%)';
            welcome_box.classList.remove('hoverIn');
            delayLoop();
            title.style.display="none";
        });
        for (let i = 0; i < (border_overlay.length); i++) {
            menu_box[i].addEventListener('mouseover', ()=>{
                addHoverClass("add", i);
            });
            menu_box[i].addEventListener('mouseout', ()=>{
                addHoverClass("remove", i);
            });
        }
    } else if (page == 'arena'){
        delayLoop2();
        for (let i = 0; i < (room_menu_box.length); i++) {
            room_menu_box[i].addEventListener('mouseover', ()=>{
                addHoverClass("arena-add", i);
            });
            room_menu_box[i].addEventListener('mouseout', ()=>{
                addHoverClass("arena-remove", i);
            });
        }
    } else;
};
function delayLoop() {
    document.getElementById('bg-img').style.backgroundPosition = "-20%";
    setTimeout(function() {
        double_border_overlay[delayCounter].style.transform = "skew(15deg) translateX(120px)";
        delayCounter++;
        start_box.style.display = 'none';
        if (delayCounter < double_border_overlay.length) {
            delayLoop();
        }
    }, 600)
}
function delayLoop2() {
    setTimeout(function() {
        double_room_box[delayCounter2].style.transform = "skew(15deg) translateX(400px)";
        delayCounter2++;
        if (delayCounter2 < double_room_box.length) {
            delayLoop2();
        };
    },600)
};
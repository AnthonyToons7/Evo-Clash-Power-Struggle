const versions = document.querySelectorAll('.versions');
const arrows = document.querySelectorAll('.arrow');
document.querySelector('#version-container').addEventListener('click', showUpdate, false);
function showUpdate(event){
    versions.forEach(ver =>{
        ver.classList.remove('active');
    });
    arrows.forEach(arr => {
        arr.innerHTML='';
    });
    let version = event.target;
    const index = Array.from(versions).indexOf(version);
    versions[index].classList.add('active');
    arrows[index].innerHTML = '>';
    getInformation(index);
}
function getInformation(index) {
    fetchData("getUpdates.php?update="+Number(index+1)).then((data) => {
        let desc = data.updates.desc;
        document.getElementById('updateInfo').innerHTML = desc;
    });
}

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
versions[0].classList.add('active');
arrows[0].innerHTML = '>';
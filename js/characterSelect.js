function limitCheckBoxes(limit){
    var checkgroup=document.querySelectorAll('.checkbox');
    var limit=limit;
    for (let i=0; i<checkgroup.length; i++){
        checkgroup[i].onclick=function(){
        let checkedcount=0;
        for (let i=0; i<checkgroup.length; i++)
            checkedcount+=(checkgroup[i].checked)? 1 : 0
        if (checkedcount >= 2){
            document.getElementById('ready').classList.add('ready');
        } else
            document.getElementById('ready').classList.remove('ready');
        if (checkedcount>limit){
            this.checked=false
            }
        }
    }
}
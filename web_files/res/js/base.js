$(document).ready(function(){
    setDateInfo();
    getData(true, true, true, true);
    
    setInterval(updateData, 60 * 1000);
    
});

function updateData(){
    if(new Date().getMinutes() % 15 === 1){
        getData(true, true, false, false);
    }
    else{
        getData(true, false, false, false);
    }
}
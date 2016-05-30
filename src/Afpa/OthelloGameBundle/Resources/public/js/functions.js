$(function () {
    alert('rrr');
    //Executer l'effet
    function runEffect() {
        $("#effect").show('scale', null, 500);
    }


    $("#effect").hide();
    setTimeout(runEffect, 2000);
});



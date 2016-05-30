$(function () {
    //Executer l'effet
    function runEffect() {
        $("#popup").show('scale', null, 500);
    }

    $("#popup").hide();
    setTimeout(runEffect, 2000);
});



$(function () {
    //Executer l'effet
    function runEffect() {
        $("#popup").show('scale', null, 500);
    }
    $("#popup").hide();
    //setTimeout(runEffect, 2000);
    
    
    $('#board td').click(function() {
        //$(this).data(data-x);
        console.log($(this).data('x') + ' ' + $(this).data('y'));
        
        // test : est-ce que la case est vide
        // si oui, appel AJAX
        if ($(this).html().indexOf("img") >= 0){
          console.log('case occupée');  
        }
            
        else {
           // si oui, appel AJAX
           console.log('case libre'); 
        }
            
    });
});




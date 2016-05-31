$(function () {
    //Executer l'effet
    function displayPopupEndGame() {
        $("#popup").show('scale', null, 500);
    }
    $("#popup").hide();

    //setTimeout(displayPopupEndGame, 2000);


    $('#board td').click(function () {
        //$(this).data(data-x);
        console.log($(this).data('x') + ' ' + $(this).data('y'));

        // test : est-ce que la case est vide
        if ($(this).html().indexOf("img") == -1){
           // si oui, appel AJAX obtenir les x,y
           console.log('case libre'); 
           $.ajax({
		async: true,
		type: 'POST',
		url: "game/action",
		data: {
                    x : $(this).data('x'),
                    y : $(this).data('y')
                },

		error:function(errorData){
                    console.log(errorData);
                },
		success:function(data){
                    console.log(data);
                }
            });
        }
    });
});




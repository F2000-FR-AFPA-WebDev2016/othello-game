$(function () {
    //Executer l'effet
    function displayPopupEndGame() {
        $("#popup").show('scale', null, 500);
    }
    $("#popup").hide();

    //setTimeout(displayPopupEndGame, 2000);


    $(document).on('click', '#board td', function () {
        //$(this).data(data-x);
        console.log($(this).data('x') + ' ' + $(this).data('y'));

        // test : est-ce que la case est vide
        if ($(this).html().indexOf("img") == -1){
           // si oui, appel AJAX obtenir les x,y
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
                    if(data.status == 'success') {
                        refresh();
                    }
                }
            });
        }
    });
    
    function refresh() {
        $.ajax({
            async: true,
            type: 'POST',
            url: "game/view",
            success:function(view){
                $('#game').html(view); // rafraichi la DIV
            },
        });
    }
    
    $('#refreshBtn').click(function () {
        refresh();
    });
    
    
    
   /* function reset(){
        $.ajax({
            async: true,
            type: 'POST',
            url: "",
            
        })
        
        
    }*/
});    




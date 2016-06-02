$(function () {
    //Executer l'effet
    function displayPopupEndGame() {
        // Appel Ajax
        $.ajax({
            async: true,
            type: 'POST',
            url: "game/end",
            error: function (errorData) {
                console.log(errorData);
            },
            success: function (data) {
                console.log(data);

                $('#popup').html(data);
                $('#popup').show('scale', null, 500);
            }    
        });
    };

    $(document).on('click', '#board td', function () {
        console.log($(this).data('l') + ' ' + $(this).data('c'));

        // test : est-ce que la case est vide
        if ($(this).html().indexOf("img") == -1) {
            // si oui, appel AJAX obtenir les x,y
            $.ajax({
                async: true,
                type: 'POST',
                url: "game/action",
                data: {
                    l: $(this).data('l'),
                    c: $(this).data('c')
                },
                error: function (errorData) {
                    console.log(errorData);
                },
                success: function (data) {
                    console.log(data);
                    if (data.status == 'success') {
                        refresh();
                    }
                    if (data.bEndGame){
                        displayPopupEndGame();
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
            success: function (view) {
                $('#game').html(view); // rafraichi la DIV
            },
        });
    }

    $('#refreshBtn').click(function () {
        refresh();
    });

    //Bouton reset de partie à tout moment
    function reset() {
        $.ajax({
            async: true,
            type: 'POST',
            url: "game/reset",
            success: function () {
                refresh();
            }

        });
    }

    $('#resetBtn').click(function () {
        reset();
    });
});




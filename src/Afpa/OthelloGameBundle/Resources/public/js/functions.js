$(function () {

    //Fct reutiliser url
    function getStartUrl() {
        //récupérer url
        var url = window.location.pathname;
        //séparer les élements de l'url : "", "othello-game" / "web" / "app_dev.php"
        var url_parts = url.split('/');
        console.log(url);
        console.log(url_parts);
        //retirer le dernier element de l'url : app_dev.php : .pop
        //derniere cellule vide => faire pop
        // pour gerer si quelu'un ajoute un "/"
        // url_parts -1
        url_parts.pop();
        //Ajout / a la fin de la chaine de caractère
        var final_url = url_parts.join('/') + '/app_dev.php';
        console.log(final_url);

        return final_url;
    }

    //Executer l'effet
    function displayPopupEndGame() {
        // Appel Ajax
        $.ajax({
            async: true,
            type: 'POST',
            url: getStartUrl() + "/game/end",
            error: function (errorData) {
                console.log(errorData);
            },
            success: function (data) {
                console.log(data);

                $('#popup').html(data);
                $('#popup').show('scale', null, 500);
            }
        });
    }
    ;

    $(document).on('click', '#board td', function () {
        console.log($(this).data('l') + ' ' + $(this).data('c'));

        // test : est-ce que la case est vide
        if ($(this).html().indexOf("img") == -1) {
            // si oui, appel AJAX obtenir les x,y
            $.ajax({
                async: true,
                type: 'POST',
                url: getStartUrl() + "/game/action",
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
                    if (data.bEndGame) {
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
            url: getStartUrl() + "/game/view",
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
            url: getStartUrl() + "/game/reset",
            success: function () {
                refresh();
            }

        });
    }

    $('#resetBtn').click(function () {
        reset();
    });
});




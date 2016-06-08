$(function () {
    var bAide = false;
    var aPossibilities = false;

    //Fct reutiliser url
    function getStartUrl() {
        //récupérer url
        var url = window.location.pathname;
        //séparer les élements de l'url : "", "othello-game" / "web" / "app_dev.php"
        var url_parts = url.split('/');
        //retirer le dernier element de l'url : app_dev.php : .pop
        //derniere cellule vide => faire pop
        // pour gerer si quelu'un ajoute un "/" :
        if (url_parts[url_parts.length - 1] == '') {
            url_parts.pop();
        }
        //Ajout / a la fin de la chaine de caractère
        var final_url = url_parts.join('/');

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
            doAction($(this).data('l'), $(this).data('c'));
        }
    });

    function doAction(l, c) {
        $.ajax({
            async: true,
            type: 'POST',
            url: getStartUrl() + "/game/action",
            data: {
                l: l,
                c: c
            },
            error: function (errorData) {
                console.log(errorData);
            },
            success: function (data) {
                console.log(data);
                aPossibilities = data.possibilities;

                if (data.status == 'success') {
                    refresh();
                }
                if (data.bEndGame) {
                    displayPopupEndGame();
                }
            }
        });
    }

    function refresh() {
        $.ajax({
            async: true,
            type: 'POST',
            url: getStartUrl() + "/game/view",
            success: function (view) {

                $('#game').html(view); // rafraichi la DIV
                if (bAide) {
                    $('.helpBtn').attr('checked', 'checked');
                    for (var i = 0; i < aPossibilities.length; i++) {
                        console.log(aPossibilities[i]);
                        x = aPossibilities[i][0];
                        y = aPossibilities[i][1];
                        console.log($('#L' + x + 'C' + y));
                        $('#L' + x + 'C' + y).css('background-color', '#90cf7d');
                    }
                }
            }
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

    $(document).on('click', '#resetBtn', function () {
        reset();
    });

    $(document).on('click', '.helpBtn', function () {
        bAide = !bAide;
        refresh();
    });

    doAction(-1, -1);
});




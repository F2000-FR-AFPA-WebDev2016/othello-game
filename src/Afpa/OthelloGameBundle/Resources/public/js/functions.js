$(function () {
    var bAide = false;
    var aPossibilities = false;
    console.log('set aide false');

    function getGameId() {
        return $('#board').data('id');
    }

    //Executer l'effet
    function displayPopupEndGame() {
        url = START_URL + "/game/end";
        idGame = getGameId();
        if (idGame) {
            url += "/" + idGame;
        }

        $.ajax({
            async: true,
            type: 'POST',
            url: url,
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

    function doAction(l, c) {
        url = START_URL + "/game/action";
        idGame = getGameId();
        if (idGame) {
            url += "/" + idGame;
        }

        $.ajax({
            async: true,
            type: 'POST',
            url: url,
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
        url = START_URL + "/game/refresh";
        idGame = getGameId();
        if (idGame) {
            url += "/" + idGame;
        }

        $.ajax({
            async: true,
            type: 'POST',
            url: url,
            success: function (view) {
                $('#game').html(view); // rafraichi la DIV
                if (bAide) {
                    for (var i = 0; i < aPossibilities.length; i++) {
                        console.log(aPossibilities[i]);
                        x = aPossibilities[i][0];
                        y = aPossibilities[i][1];
                        console.log($('#L' + x + 'C' + y));
                        $('#L' + x + 'C' + y).css('background-color', '#9cd590');
                    }
                }
            }
        });
    }

    //Bouton reset de partie à tout moment
    function reset() {
        url = START_URL + "/game/reset";
        idGame = getGameId();
        if (idGame) {
            url += "/" + idGame;
        }

        $.ajax({
            async: true,
            type: 'POST',
            url: url,
            success: function () {
                refresh();
            }
        });
    }

    $(document).on('click', '#board td', function () {
        console.log($(this).data('l') + ' ' + $(this).data('c'));

        // test : est-ce que la case est vide
        if ($(this).html().indexOf("img") == -1) {
            // si oui, appel AJAX obtenir les x,y
            doAction($(this).data('l'), $(this).data('c'));
        }
    });

    $('#resetBtn').click(function () {
        reset();
    });
    $('.helpBtn').click(function () {
        bAide = !bAide;
        refresh();
    });

    doAction(-1, -1);
});
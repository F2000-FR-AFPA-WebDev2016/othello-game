$(function () {
    var bAide = false;
    var aPossibilities = false;

    function getGameId() {
        return $('#board').data('id');
    }

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

    $(document).on('click', '#resetBtn', function () {
        reset();
    });

    $(document).on('click', '#board td', function () {
        console.log($(this).data('l') + ' ' + $(this).data('c'));

        // test : est-ce que la case est vide
        if ($(this).html().indexOf("img") == -1) {
            // si oui, appel AJAX obtenir les x,y
            doAction($(this).data('l'), $(this).data('c'));
        }
    });

    $(document).on('click', '.helpBtn', function () {
        bAide = !bAide;
        refresh();
    });

    doAction(-1, -1);
});
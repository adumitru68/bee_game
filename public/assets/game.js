var gameService = (function () {

    function selectGame(element) {
        var url = '/game/select'
        $.ajax({
            url: url,
            data: {id: element.data('id')},
            method: 'GET',
            success: function (data, status) {
                console.log(data.data['gameHtml']);
                $('#game_play').html(data.data['gameHtml'])
            }
        })
    }

    function hitGame(element) {
        var url = '/game/hit'
        $.ajax({
            url: url,
            data: {id: element.data('id')},
            method: 'POST',
            success: function (data, status) {
                $('#game_play').html(data.data['gameHtml'])
                if(1 === data.data['ended']) {
                    alert('Game over!')
                }
            }
        })
    }

    function createGame(element) {
        var url = '/game/add'
        $.ajax({
            url: url,
            data: {id: element.data('id')},
            method: 'POST',
            success: function (data, status) {
                location.reload();
            }
        })
    }

    function bindElements() {
        $( ".game" ).click(function(evt) {
            selectGame($(this))
        });

        $('#game_play').on('click', '#js_hit', function (e) {
            hitGame($(this));
        })

        $('#js_new_game').on('click', function (){
            createGame($(this))
        })
    }

    return {
        bindElements: bindElements
    }
})()

$(function() {
    gameService.bindElements()
});
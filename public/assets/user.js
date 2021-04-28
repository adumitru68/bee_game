var userService = (function () {

    function createUser(element) {
        var url = '/user/add'
        $.ajax({
            url: url,
            data: {},
            method: 'POST',
            success: function (data, status) {
                location.reload();
            }
        })
    }

    function bindElements() {
        $( "#js_new_user" ).click(function(evt) {
            createUser()
        });

    }

    return {
        bindElements: bindElements
    }
})()

$(function() {
    userService.bindElements()
});
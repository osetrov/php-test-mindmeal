/**
 * Created by apple on 20.07.14.
 */

function send_post(form_object) {
    var form = $(form_object);
    var url_action = form.attr('ajax_action');

    $.ajax({
        url: url_action,
        type: 'post',
        data: form.serialize(),
        //dataType: 'json',
        error: function(data) {
            alert('Ошибка: ' + data);
            $('#message').val('');
        },
        success: function (data) {
            $('#poster').prepend(
            '<div class="panel panel-default" id="post' + data + '">' +
            '<div class="panel-heading">' + UserActive.login + '<button type="button" class="btn btn-danger btn-xs" onclick="post_del(' + data + ')" style="float: right">Удалить</button>' + '</div>' +
                '<div class="panel-body">' +
                    $('#message').val() +
                '</div>' +
            '</div>');
            $('#message').val('');
        }
    });

}

function post_del(post_id) {

    if (confirm('Вы уверены?')) {
        $.ajax({
            url: '/u' + UserView.id + '/poster/post_del',
            type: 'post',
            data: {
                'ID' : post_id
            },
            //dataType: 'json',
            error: function(data) {
                alert('Ошибка: ' + data);
            },
            success: function (data) {
                if (data == 'Ok') {
                    $('#post' + post_id).remove();
                } else {
                    alert('Ошибка: ' + data);
                }
            }
        });
    }
}
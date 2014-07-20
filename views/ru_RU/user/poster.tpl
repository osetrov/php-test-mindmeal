<h3>Примитивнейшие твитты</h3>

{{BEGIN SendMessage}}
<form action="javascript: void(0)" ajax_action="/u{{$view_user_id}}/poster/addpost" onsubmit="send_post(this)" accept-charset="utf-8" method="post" id="user_register" name="user_register">
    <textarea class="form-control" rows="3" placeholder="Введите сообщение" id="message" name="message"></textarea>
    <div class="clearfix" style="height: 5px;"></div>
    <button type="submit" class="btn btn-primary" style="float: right">Твитнуть</button>
    <div class="clearfix" style="height: 80px;"></div>
</form>
{{END SendMessage}}

{{BEGIN NotAuth}}
    <a href="/user/login">Авторизуйтесь</a> или <a href="/user/register">зарегистрируйтесь</a> чтобы твиттить
<div class="clearfix" style="height: 40px;"></div>
{{END NotAuth}}

<div id="poster">
{{BEGIN Poster}}

    {{BEGIN Item}}
    <div class="panel panel-default" id="post{{$id}}">
        <div class="panel-heading">
            {{$post_owner_login}}
            {{IF($mypost, '<button type="button" class="btn btn-danger btn-xs" onclick="post_del(')}}
            {{IF($mypost, $id)}}
            {{IF($mypost, ')" style="float: right">Удалить</button>')}}
        </div>
        <div class="panel-body">
            {{$message}}
        </div>
    </div>
    {{END Item}}

{{END Poster}}

</div>
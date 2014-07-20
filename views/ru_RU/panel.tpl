{{BEGIN Not_Login}}
<div class="well">
    <a href="/user/login">Войдите</a> или <a href="/user/register">зарегистрируйтесь</a>.
</div>
{{END Not_Login}}

{{BEGIN MyPanel}}
    <h4>Привет, <a href="/u{{$user_id}}">{{$user_login}}</a>!</h4>

    <a href="/user/logout">Выйти</a>
{{END MyPanel}}

{{BEGIN NotMyPanel}}
    Твиттер пользователя <strong>{{$user_login}}</strong>
{{END NotMyPanel}}
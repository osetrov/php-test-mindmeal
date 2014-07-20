{{BEGIN Errors}}
<div class="alert alert-danger" role="alert">
    <strong>Ошибка!</strong>
    <ul>
        {{BEGIN Item}}
        <li>
            {{$title}}
        </li>
        {{END Item}}
    </ul>
</div>
{{END Errors}}

<h1>Регистрация</h1>

<form action="/user/register" accept-charset="utf-8" method="post" id="user_register" name="user_register">
    <div class="form-group">
        <input type="text" class="form-control" id="userLogin" name="userLogin" placeholder="Логин">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Пароль">
    </div>
    <button type="submit" class="btn btn-default">Продолжить</button>
</form>
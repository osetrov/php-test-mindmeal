{{BEGIN Users}}
<h2>Список пользователей</h2>
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    </thead>

    <tbody>
    {{BEGIN Item}}
    <tr>
        <td>
            {{user_id}}
        </td>
        <td>
            <a href="/u{{user_id}}">{{user_login}}</a>
        </td>
    </tr>
    {{END Item}}
    </tbody>
</table>
{{END Users}}

{{BEGIN NotUsers}}
    Нет пользователей, <a href="/user/register">станьте первым</a>
{{END NotUsers}}
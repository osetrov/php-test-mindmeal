<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <title>Тестовое задание Осетрова Павла для компании MindMeal</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="/public/css/style.css" rel="stylesheet">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-theme.min.css" rel="stylesheet">

    {{$css}}

    <script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>

    {{$js}}

    <script language="JavaScript" type="text/javascript">
        var UserActive = {
            id: '{{$active_user_id}}',
            login: '{{$active_user_login}}'
        }
        var UserView = {
            id: '{{$view_user_id}}',
            login: '{{$view_user_login}}'
        }
    </script>
</head>

<body>

<div class="wrapper">

    <header class="header">
        {{$header}}
    </header><!-- .header-->

    <div class="middle">

        <div class="container">
            <main class="content">

                {{$content}}

            </main><!-- .content -->
        </div><!-- .container-->

        <aside class="right-sidebar">
            {{$panel}}
        </aside><!-- .right-sidebar -->

    </div><!-- .middle-->

</div><!-- .wrapper -->

<footer class="footer">
    Разработал <a href="http://vk.com/osetrov.pavel">Осетров Павел</a> в качестве тестового задания для MindMeal
</footer><!-- .footer -->

</body>
</html>
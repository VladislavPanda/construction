<!DOCTYPE html>
<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<meta http-equiv="X-UA-Compatible" content="ie=edge">-->
    <title>Отчёт на печать</title>
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">-->
    <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" /> -->
    <style type="text/css">
        * {
          /*font-family: Helvetica, sans-serif;*/
          font-family: "DejaVu Sans", sans-serif;
        }

        h2{
            font-size: 20px;
            /*margin-top: -20px;*/
            text-align: center;
        }

        /*.box2{
            position: absolute;
            width: 230px;
            height: 241px;
            left: 500px;
            top: 160px;
            border: 1px solid #D9D9D9;
            box-sizing: border-box;
            border-radius: 10px;
            padding: 10px, 0px, 0px, 10px;
        }*/

        p{
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="box1">
        <h2><strong>Отчёт на печать</strong></h2>
        <p>Номер объекта: {{ $project->id }}</p> 
        <p>Адрес: {{ $project->address }}</p>
        <p>Описание: {{ $project->description }}</p> 
        <p>Дата создания: {{ $project->created_at }}</p>
        <p>Дата завершения: {{ $project->end_date }}</p>
        <p>Прораб: {{ $project->foreman }}</p> 
    </div>
</body>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Заявка</title>
</head>
<body>
<table style="width: 100%; text-align: center; border-collapse: collapse; align-items: center">
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <th>ФИО клиента:</th>
        <td>{{$fullname}}</td>
    </tr>
    <tr>
        <th>Телефон:</th>
        <td><a href="tel:{{preg_replace('/\s+/', '', $tel)}}">{{$tel}}</a></td>
    </tr>
    <tr>
        <th>Тип услуги:</th>
        <td>{{$services}}</td>
    </tr>
    <tr>
        <th>Описание:</th>
        <td>{{$desc}}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
</table>
</body>
</html>

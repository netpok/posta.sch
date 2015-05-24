<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>posta.sch.bme.hu</title>

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
@if(sizeof($mails=MailModel::orderBy("name","asc")->get()))
<table class="table table-bordered table-condensed" style="width: 600px; margin: 20px auto;">
    <thead style="font-weight: 200">
    <tr>
        <td>Név</td>
        <td width="120">Szoba</td>
        <td width="120">Érkezett</td>
    </tr>
    </thead>
    <tbody>
    @foreach($mails as $mail)
    <tr>
        <td>{{$mail->name}}</td>
        <td>{{$mail->room}}</td>
        <td>{{$mail->created_at->format("Y.m.d.")}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
@else
<div style="text-align: center; margin: 20px auto;">Jelenleg egyetlen levél sincs a portán</div>
@endif
</body>
</html>
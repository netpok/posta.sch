@extends("main")

@section("body")
    @if(sizeof($mails=MailModel::orderBy("name","asc")->get()))
        <table class="table table-hover table-bordered" style="width: 600px;margin: 0 auto;">
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
        <div style="text-align: center">Jelenleg egyetlen levél sincs a portán</div>
    @endif
@stop

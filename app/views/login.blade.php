@extends("main")

@section("body")
    @if(Session::has("error"))
        <div class="alert alert-danger" role="alert" style="text-align: center;">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Hiba:</span>
            {{ Session::get("error") }}
        </div>
    @endif
    <div style="text-align: center">
        {{ Form::open(array('url' => '/login',"class"=>"form-inline")) }}
        {{ Form::label('password', 'Jelszó:',array("class"=>"sr-only")) }} {{ Form::password('password', array("class"=>"form-control","placeholder"=>"Jelszó")) }}
        {{ Form::submit('Bejelentkezés',array("class"=>"btn")) }}
        {{ Form::close() }}
    </div>
@stop
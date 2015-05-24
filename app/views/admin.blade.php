@extends("main")

@section("style")
    <link href="https://code.jquery.com/ui/1.11.2/themes/start/jquery-ui.css" rel="stylesheet">
@stop

@section("body")
    {{--<div style="text-align: center;">
        {{ Form::open(array('url' => '',"class"=>"form-inline","onsubmit"=>"addMail();return false;")) }}
        {{ Form::label('stname', 'Név:',array("class"=>"sr-only")) }} {{ Form::text('stname', null, array("class"=>"form-control","placeholder"=>"Név")) }}
        {{ Form::label('room', 'Szoba:',array("class"=>"sr-only")) }} {{ Form::text('room', null, array("class"=>"form-control","placeholder"=>"Szoba")) }}
        {{ Form::submit('Hozzáadás',array("class"=>"btn")) }}
        {{ Form::close() }}
    </div>--}}
    {{ Form::open(array('url' => '',"onsubmit"=>"addMail();return false;")) }}
    <table class="table" style="width: 600px;margin: 0 auto; border: none;">
        <tbody>
        <tr>
            <td style="width: 317px;">{{ Form::label('stname', 'Név:',array("class"=>"sr-only")) }} {{ Form::text('stname', null, array("class"=>"form-control","placeholder"=>"Név")) }}</td>
            <td style="width: 120px;">{{ Form::label('room', 'Szoba:',array("class"=>"sr-only")) }} {{ Form::text('room', null, array("class"=>"form-control","placeholder"=>"Szoba")) }}</td>
            <td style="width: 120px;">
                <button type="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
                <a href="/printable" id="print" class="btn btn-info pull-right{{count($mails)?"":" disabled"}}">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                </a>
            </td>
            <td style="padding-right: 1px;padding-left: 1px;">
                <a href="/logout" class="btn btn-warning pull-right">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
    {{ Form::close() }}
    <table class="table table-hover table-bordered" style="width: 600px;margin: 0 auto;">
        <thead style="font-weight: 200">
        <tr>
            <td>Név</td>
            <td style="width: 120px;">Szoba</td>
            <td style="width: 120px;">Érkezett</td>
            <td style="width: 42px;"></td>
        </tr>
        </thead>
        <tbody id="mails">
        @foreach($mails as $mail)
            <tr id="mail-{{$mail->id}}">
                <td>{{$mail->name}}</td>
                <td>{{$mail->room}}</td>
                <td>{{$mail->created_at->format("Y.m.d.")}}</td>
                <td>
                    <button id="mail-btn-{{$mail->id}}" type="button" class="btn btn-xs btn-danger"
                            onclick="deleteMail({{$mail->id}});">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tbody id="nomail"{{count($mails)?' style="display: none;"':''}}>
        <tr>
            <td colspan="4" style="text-align: center">Jelenleg egyetlen levél sincs a portán</td>
        </tr>
        </tbody>
    </table>
@stop
@section("script")
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
    <script>
        function deleteMail(id) {
            $("#mail-btn-" + id).attr('disabled', true);
            var element = $("#mail-" + id);
            element.addClass("danger");
            $.ajax({
                url: "/ajax/delete",
                context: element,
                type: "POST",
                data: {
                    id: id
                },
                success: function () {
                    this.fadeOut(500, function () {
                        this.remove();
                        if ($("#mails").children().length < 1) {
                            $("#nomail").fadeIn();
                            $("#print").addClass('disabled');
                        }
                    });
                },
                error: function (request) {
                    alert("Szerverhiba: " + request.responseText);
                    $("#mail-btn-" + id).removeAttr('disabled', false);
                }
            });
        }

        function addMail() {
            var name = $("#stname").val();
            var room = $("#room").val();
            $.ajax({
                url: "/ajax/add",
                type: "POST",
                data: {
                    stname: $("#stname").val(),
                    room: $("#room").val()
                },
                success: function (response) {
                    var d = new Date();
                    var strDate = d.getFullYear() + "." + (d.getMonth() < 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1) + "." + d.getDate() + ".";
                    var element = $("<tr>").attr("id", "mail-" + response).attr("style", "display: none;").addClass("success");
                    element.append($("<td>").text(name));
                    element.append($("<td>").text(room));
                    element.append($("<td>").text(strDate));
                    element.append(
                            $("<td>").append(
                                    $("<button>").attr("type", "button").attr("class", "btn btn-xs btn-danger")
                                            .attr("onclick", "deleteMail(" + response + ");").attr("id", "btn-mail-" + response).append(
                                            $("<span>").attr("class", "glyphicon glyphicon-remove").attr("" + "aria-hidden", "true"))));
                    var baseElement = $("#mails");
                    if (baseElement.children().length < 1) {
                        baseElement.append(element);
                    }
                    else {
                        if (name > baseElement.children().last().children().first().text()) {
                            baseElement.append(element);
                        } else {
                            $.each(baseElement.children(), function (index, iter) {
                                        if (name <= iter.children[0].innerHTML) {
                                            element.insertBefore($("#" + iter.id));
                                            return false;
                                        }
                                    }
                            );
                        }
                    }
                    element.fadeIn();
                    $("#nomail").fadeOut();
                    $("#print").removeClass('disabled');
                },
                error: function (request) {
                    alert("Szerverhiba: " + request.responseText);
                }
            });
            $("#stname").val("");
            $("#room").val("");
        }

        $(function () {
            $.ajax({
                url: "/ajax/get",
                success: function (response) {
                    $("#stname").autocomplete({
                        source: response.students,
                        select: function (event, ui) {
                            if (ui.item)
                                $("#room").val(ui.item.room);
                            else
                                $("#room").val("");
                        }
                    });
                    $("#room").autocomplete({
                        source: response.rooms
                    });
                },
                error: function (request) {
                    alert("Szerverhiba: " + request.responseText);
                }
            });
            $("#stname").focus();
        });
    </script>
@stop

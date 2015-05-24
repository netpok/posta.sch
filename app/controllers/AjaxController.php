<?php

/**
 * Created by PhpStorm.
 * User: netpok
 * Date: 2015.01.12.
 * Time: 13:46
 */
class AjaxController extends Controller {
    public function getUsers() {
        return Response::json(array("students" => Student::select("label", "name as value", "room")->get()->toArray(),
            "rooms" => Student::groupBy("room")->orderBy("room", "asc")->lists("room")));
    }

    public function addMail() {
        $stname = Input::get("stname");
        $room = Input::get("room");
        if($room && !Student::where("room", $room)->first())
            return Response::make("Valós szobaszámot kell megadni!\nSzobaszám nélküli küldéshez hagyd üresen.", 418);
        if($stname) {
            $mail = new MailModel();
            $mail->name = $stname;
            $mail->room = $room;
            $mail->save();
            return Response::make($mail->id);
        }
        return Response::make("Meg kell adni nevet!", 418);
    }

    public function deleteMail() {
        if(!$mail = MailModel::find(Input::get("id")))
            return Response::make("A bejegyzés nem található", 404);
        $mail->delete();
        return Response::make("ok");
    }
}
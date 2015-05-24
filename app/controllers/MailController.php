<?php
/**
 * Created by PhpStorm.
 * User: netpok
 * Date: 2015.01.12.
 * Time: 13:47
 */

class MailController extends Controller{
    public function showMails()
    {
        return View::make("mails");
    }

    public function showPrintableMails()
    {
        return View::make("printablemails");
    }

    public function showAdmin()
    {
        return View::make("admin",array("mails"=>MailModel::orderBy("name","asc")->get()));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: netpok
 * Date: 2015.01.12.
 * Time: 14:45
 */

class MailModel extends Eloquent{
    protected $table="mails";
    protected $dates=array("created_at","lastNotification");
    public $timestamps=false;
}
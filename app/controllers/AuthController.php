<?php
/**
 * Created by PhpStorm.
 * User: netpok
 * Date: 2015.01.12.
 * Time: 13:46
 */

class AuthController extends Controller{
    public function showLogin()
    {
        if(Session::has("logged_in"))
            return Redirect::intended("/admin");
        return View::make("login");
    }

    public function processLogin()
    {
        if(Input::get("password")!="asd") //very simple authentication
            return Redirect::to("/login")->with("error","Sikertelen belépés!");
        Session::put("logged_in",true);
        return Redirect::intended("/admin");
    }

    public function logout()
    {
        Session::forget("logged_in");
        return Redirect::to("/");
    }
}

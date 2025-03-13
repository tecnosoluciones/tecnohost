<?php


class THSession{


    static  function create($arg_default){

        for($i=0;$i<count($arg_default);$i++){

            $_SESSION[$arg_default[$i]['name']] = $arg_default[$i]['value'];

        }
        return true;
    }


    static  function hasSession($session){

        return isset($_SESSION[$session]);
    }

    static  function getSession($session){

        if(!isset($_SESSION[$session])){
            return false;
        }

        return $_SESSION[$session];
    }





}
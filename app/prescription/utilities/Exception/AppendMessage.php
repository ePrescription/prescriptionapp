<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/16/2015
 * Time: 1:27 PM
 */

namespace App\prescription\utilities\Exception;
use Exception;
use App\prescription\utilities\Exception\BaseException;

class AppendMessage {

    public static function appendMessage(BaseException $ex)
    {
        //$ex->
        $msg = "Error code : ". $ex->getCode()
                .PHP_EOL." User Error code: ". $ex->getMessageForCode()
                .PHP_EOL." Error Message: ". $ex->getMessage()
                .PHP_EOL." Error File: ". $ex->getFile()
                .PHP_EOL." Error Line: ". $ex->getLine();

        return $msg;
    }

    public static function appendGeneralException(Exception $ex)
    {
        $msg = "Error code : ". $ex->getCode()
            .PHP_EOL." Error Message: ". $ex->getMessage()
            .PHP_EOL." Error File: ". $ex->getFile()
            .PHP_EOL." Error Line: ". $ex->getLine();

        return $msg;
    }

}
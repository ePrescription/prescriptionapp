<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/10/2016
 * Time: 2:18 PM
 */

namespace App\prescription\common;


class ResponseJson implements \JsonSerializable
{
    private $result;
    private $message;
    private $obj;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */

    public function getObj()
    {
        return $this->obj;
    }

    /**
     * @param mixed $obj
     */
    public function setObj($obj)
    {
        $this->obj = $obj;
    }

    public function __construct($result, $message = null)
    {
        $this->result = $result;
        $this->message = $message;
    }

    public function jsonSerialize()
    {
        $data = array(
          'isSuccess' => $this->result,
          'message' => $this->message,
          'result' => $this->obj
         );

        return $data;
    }
}
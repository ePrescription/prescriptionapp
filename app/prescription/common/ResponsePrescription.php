<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26/11/2016
 * Time: 11:43 AM
 */

namespace App\prescription\common;


use Symfony\Component\HttpFoundation\JsonResponse;
use App\prescription\utilities\Exception\BaseException;
use App\prescription\utilities\Exception\AppendMessage;
use Exception;
use Log;

class ResponsePrescription extends JsonResponse
{
    private $obj;
    private $result;
    private $message;
    private $httpCode;
    private $count = 0;

    const HTTP_SUCCESS_STATUS = 200;
    const HTTP_NO_CONTENT = 204;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    public function __construct($result, $message = null)
    {
        $this->result = $result;
        $this->message = $message;
    }

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
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param mixed $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
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

        //dd($obj);
        /*try{
            $this->obj = $obj;
            parent::__construct($obj, 422);
        }
        catch(\Exception $exc)
        {
            dd($exc);
        }*/

    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }


    public function sendSuccessResponse()
    {
        $data = null;

        if(!empty($this->obj))
        {
            //dd('Inside not empty');
            $data = array(
                'isSuccess' => $this->result,
                'message' => $this->getMessage(),
                'status' => $this::HTTP_SUCCESS_STATUS,
                'count' => $this->count,
                'result' => $this->obj
            );
        }
        else
        {
            $data = array(
                'isSuccess' => $this->result,
                'message' => $this->getMessage(),
                'status' => $this::HTTP_SUCCESS_STATUS,
                //'count' => $this->count,
                'result' => $this->obj
            );
        }
        /*if(!isEmpty($this->obj))
        {
            $data = array(
                'isSuccess' => $this->result,
                'message' => $this->getMessage(),
                'status' => $this::HTTP_SUCCESS_STATUS,
                'result' => $this->obj
            );
        }
        else
        {
            $data = array(
                'isSuccess' => $this->result,
                'message' => $this->getMessage(),
                'status' => $this::HTTP_SUCCESS_STATUS,
            );
        }*/

        parent::__construct($data, $this::HTTP_SUCCESS_STATUS);
    }

    public function sendErrorResponse(BaseException $exc)
    {
        $data = array(
            'isSuccess' => $this->result,
            'message' => $this->getMessage(),
            'status' => parent::HTTP_INTERNAL_SERVER_ERROR,
            'result' => $this->obj
        );
        //dd('Inside send error response');
        $errorMsg = $exc->getMessageForCode();
        //dd($errorMsg);
        $msg = AppendMessage::appendMessage($exc);
        Log::error($msg);

        parent::__construct($data, parent::HTTP_INTERNAL_SERVER_ERROR);

    }

    public function sendUnExpectedExpectionResponse(Exception $exc)
    {
        $data = array(
            'isSuccess' => $this->result,
            'message' => $this->getMessage(),
            'status' => parent::HTTP_INTERNAL_SERVER_ERROR,
            'result' => $this->obj
        );

        //$msg = AppendMessage::appendMessage($exc);
        //Log::error($msg);

        $msg = AppendMessage::appendGeneralException($exc);
        Log::error($msg);

        parent::__construct($data, parent::HTTP_INTERNAL_SERVER_ERROR);

    }

    public function sendValidationErrors()
    {
        $data = array(
            'isSuccess' => $this->result,
            'message' => $this->getMessage(),
            'status' => parent::HTTP_UNPROCESSABLE_ENTITY,
            'result' => $this->obj
        );
        //dd('Inside send error response');
        /*$errorMsg = $exc->getMessageForCode();
        $msg = AppendMessage::appendMessage($exc);
        Log::error($msg);*/

        parent::__construct($data, $this::HTTP_SUCCESS_STATUS);

    }

    /*public function jsonSerialize()
    {
        //dd($this->obj);
        return $this->getObj();
    }*/
}
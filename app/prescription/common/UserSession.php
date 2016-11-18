<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/10/2016
 * Time: 3:55 PM
 */

namespace App\prescription\common;


class UserSession {
    private $loginUserId;
    private $loginUserType;
    private $displayName;
    private $authDisplayName;

    /**
     * @return mixed
     */
    public function getLoginUserId()
    {
        return $this->loginUserId;
    }

    /**
     * @param mixed $loginUserId
     */
    public function setLoginUserId($loginUserId)
    {
        $this->loginUserId = $loginUserId;
    }

    /**
     * @return mixed
     */
    public function getLoginUserType()
    {
        return $this->loginUserType;
    }

    /**
     * @param mixed $loginUserType
     */
    public function setLoginUserType($loginUserType)
    {
        $this->loginUserType = $loginUserType;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getAuthDisplayName()
    {
        return $this->authDisplayName;
    }

    /**
     * @param mixed $authDisplayName
     */
    public function setAuthDisplayName($authDisplayName)
    {
        $this->authDisplayName = $authDisplayName;
    }



}
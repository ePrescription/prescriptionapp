<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/4/2016
 * Time: 11:46 AM
 */

namespace App\prescription\services;

use App\prescription\utilities\Exception\HelperException;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use App\prescription\repositories\repointerface\HelperInterface;
use Exception;

class HelperService
{
    protected $helperRepo;

    public function __construct(HelperInterface $helperRepo) {
        $this->helperRepo = $helperRepo;
    }

    /* Get all the cities
     * @params none
     * @throws HelperException
     * @return array | null
     * @author Baskar
     */

    public function getCities()
    {
        $cities = null;

        try
        {
            $cities = $this->helperRepo->getCities();
        }
        catch(HelperException $cityExc)
        {
            throw $cityExc;
        }
        catch (Exception $ex) {
            throw new HelperException(null, ErrorEnum::CITIES_LIST_ERROR, $ex);
        }
        return $cities;
    }
}
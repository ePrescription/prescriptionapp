<?php namespace App\prescription\utilities\HHSCache;

use Illuminate\Support\Facades\Redis;
use Cache;

class CacheService {
    
    //private static $redis = null;
    const CACHEKEY = 'PRESCCACHE';
    
    private function __construct() {}
    
    private static function initialize()
    {
        //self::$redis = Redis::connection();
    }
   
    public static function isCached($keyName)
    {
         //self::initialize();
         /*if (self::$redis != null)
         {
             if (self::$redis->exists(self::CACHEKEY.$keyName))
                 return true;
             else
                 return false;
         }*/

        if(Cache::has(self::CACHEKEY.$keyName))
        {
            return true;
        }
        else{
            return false;
        }

         /*if (self::$redis != null && self::$redis->exists(self::CACHEKEY.$keyName))
             return true;
         else
             return false;*/
    }
    
    public static function getFromCache($keyName)
    {
        return json_decode(Cache::get(self::CACHEKEY.$keyName));
        //$list = null;
        //self::initialize();
        
        /*if (self::isCached($keyName))
        {
            $list = json_decode(self::$redis->get(self::CACHEKEY.$keyName));
        }*/

        //return $list;
        //return json_decode(self::$redis->get(self::CACHEKEY.$keyName));
        //${self::CACHEKEY.$keyName} = 
    }
    
    public static function addToCache($keyName, $keyList)
    {
        Cache::put(self::CACHEKEY.$keyName, json_encode($keyList), 400);
        //self::initialize();
        //self::$redis->set(self::CACHEKEY.$keyName, json_encode($keyList));
        //self::$redis->expire(self::CACHEKEY.$keyName, 3600);
    }
}

<?php
namespace zRF\Query\Service;

use zRF\Query\Service\Interfaces\CaptchaInterface;

/**
*
*/
class DeathByCaptcha implements CaptchaInterface
{

    /**
     * [$credentials description]
     * @var array
     */
    private $credentials;

    /**
     * [$service description]
     * @var [type]
     */
    private $service;

    /**
     * [initialize description]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function initialize(){

    }

    /**
     * [decode description]
     * @param  [type] $file    [description]
     * @param  [type] $timeout [description]
     * @return [type]          [description]
     */
    public function decode($file, $timeout){

    }

    /**
     * [setCredentials description]
     * @param [type] $credentials [description]
     */
    public function setCredentials($credentials)
    {
        # code...
    }
}
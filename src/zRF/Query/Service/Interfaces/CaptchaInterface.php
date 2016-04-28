<?php
namespace zRF\Query\Service\Interfaces;

/**
*
*/
interface CaptchaInterface
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
     * @return [type] [description]
     */
    public function initialize();

    /**
     * [decode description]
     * @param  [type] $file    [description]
     * @param  [type] $timeout [description]
     * @return string
     */
    public function decode($file, $timeout);

    /**
     * [setCredentials description]
     */
    public function setCredentials($credentials);
}
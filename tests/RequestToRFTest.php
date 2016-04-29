<?php

use zRF\Query\Search as zRF;
use Intervention\Image\ImageManagerStatic as ImageIntervention;
use zRF\Query\Exception\InvalidCaptcha;

class CaptchaCaptureTest extends PHPUnit_Framework_TestCase
{
	public function testGetParam()
	{
		// get cookie string
		$cookie = zRF::cookie();

		// get array of image details
        $image = zRF::image();

        return [
        	'paramImage' => $image,
        	'paramCookie' => $cookie
        ];
	}

	/**
     * @depends testGetParam
     */
	public function testCaptureCaptchaImage($params)
	{
        // is array?
        $this->assertTrue(
	    		is_array($params['paramImage']), 
	    		'Not captcha returned'
	    );

        // image is valid?
        $this->assertTrue(
	    		(is_array($params['paramImage']) && array_key_exists('image', $params['paramImage'])), 
	    		'Cookie returned not is valid string'
	    );
	}

	/**
     * @depends testGetParam
     */
	public function testValidImage($params)
	{
		// try to make valid image
		try {
			$image = ImageIntervention::make($params['paramImage']['image']);
		} catch (\Exception $e) {
			return $this->assertNotTrue(true, $e->getMessage());
		}
	}

	/**
     * @depends testGetParam
     */
	public function testValidCookie($params)
	{
		// cookie returned is valid string?
		$this->assertTrue(
	    		is_string($params['paramCookie']), 
	    		'Cookie returned not is valid string'
	    );
	}

	/**
     * @depends testGetParam
     */
	public function testInvalidCaptchaReturn($params)
	{
		$cookie = $params['paramCookie'];
		$cnpj = '54787138000101';
		$captcha = '';

		$this->setExpectedException(InvalidCaptcha::class);

		$crawler = zRF::search($cnpj, $captcha, $cookie);
	}
}

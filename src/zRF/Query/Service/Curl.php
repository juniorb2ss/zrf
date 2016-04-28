<?php namespace zRF\Query\Service;

/**
* 
*/
class Curl
{	

	/**
	 * [$url description]
	 * @var [type]
	 */
	private $url;

	/**
	 * [$options description]
	 * @var [type]
	 */
	private $options;

	/**
	 * [$instance description]
	 * @var [type]
	 */
	private $instance;

	/**
	 * [$response description]
	 * @var [type]
	 */
	private $response;

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init($url)
	{
		$this->instance = curl_init($url);

		return $this;
	}

	/**
	 * [options description]
	 * @param  array  $options [description]
	 * @return [type]          [description]
	 */
	public function options(array $options)
	{
		$this->options = $options;

		curl_setopt_array($this->instance, $this->options);

		return $this;
	}

	/**
	 * [post description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function post(array $fields)
	{
		curl_setopt($this->instance, CURLOPT_POST, count($fields));
		curl_setopt($this->instance, CURLOPT_POSTFIELDS, http_build_query($fields));

		return $this;
	}

	/**
	 * [exec description]
	 * @return [type] [description]
	 */
	public function exec()
	{
		$this->response = curl_exec($this->instance);
	}

	/**
	 * [close description]
	 * @return [type] [description]
	 */
	public function close()
	{
		curl_close($this->instance);

		return $this;
	}

	/**
	 * [response description]
	 * @return [type] [description]
	 */
	public function response()
	{
		return $this->response;
	}
}
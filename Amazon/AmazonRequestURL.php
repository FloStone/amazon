<?php

namespace Flo\Amazon;

class AmazonRequestURL
{
	const URL = "http://webservices.amazon.";
	const URI = '/onca/xml?';
	const API_VERSION = '2013-08-01';

	/**
	 * Parameters for url
	 *
	 * @var array
	 */
	protected $params = ['ResponseGroup' => 'ItemAttributes,OfferFull'];

	/**
	 * URL as string
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * Amazon Instance
	 *
	 * @var App\Servcies\Amazon\Amazon
	 */
	protected $amazon;

	/**
	 * Amazon Signature instance
	 *
	 * @var AmazonSignature
	 */
	protected $signature;

	/**
	 * Region of search
	 *
	 * @var string
	 */
	protected $region = 'com';

	/**
	 * Cosntructor
	 *
	 * @param Amazon $amazon
	 */
	public function __construct(Amazon $amazon, $region)
	{
		$this->amazon = $amazon;
		$this->region = $region;
	}

	/**
	 * Set parameters
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->params[$key] = $value;
	}

	/**
	 * URL as string
	 *
	 * @return string
	 */
	public function __tostring()
	{
		return $this->url;
	}

	/**
	 * Make the URL string
	 *
	 * @return void
	 */
	private function make()
	{
		$params = $this->getAllParams();
		$paramstring = http_build_query($params + ['Signature' => $this->signature ? $this->signature->encode() : NULL]);
		$paramstring = urldecode($paramstring);
		$url = $this->buildURL() . $paramstring;
		$this->url = $url;
	}

	/**
	 * Get all parameters
	 *
	 * @return array
	 */
	public function getAllParams()
	{
		$keys = [
			'AWSAccessKeyId' => $this->amazon->accesskey(),
			'AssociateTag' => $this->amazon->associateid(),
			'Timestamp' => $this->amazon->timestamp,
			'Version' => static::API_VERSION
		];
		$params = $this->encodeParams($keys + $this->params);
		return $params;
	}

	/**
	 * URL Encode Parameters
	 *
	 * @param array $params
	 * @return array
	 */
	public function encodeParams($params)
	{
		$encoded = [];

		foreach($params as $key => $value)
		{
			$encoded[str_replace('%7E', '~', rawurlencode($key))] = str_replace('%7E', '~', rawurlencode($value));
		}

		return $encoded;
	}

	private function buildURL()
	{
		$this->url = self::URL . $this->region . self::URI;

		return $this->url;
	}

	/**
	 * Get URL
	 *
	 * @return string
	 */
	public function url()
	{
		return $this->url;
	}

	/**
	 * Get all URL parameters
	 *
	 * @return array
	 */
	public function params()
	{
		return $this->params;
	}

	/**
	 * Sign the URL
	 *
	 * @return void
	 */
	public function sign()
	{
		$this->make();
		$this->signature = new AmazonSignature($this->amazon, $this);
		$this->make();
	}

	/**
	 * Get URL signature
	 *
	 * @return AmazonSignature
	 */
	public function signature()
	{
		return $this->signature;
	}
}
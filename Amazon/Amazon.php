<?php

namespace Flo\Amazon;

use SimpleXMLElement;

class Amazon
{
	const US = 'us';
	const DE = 'de';
	const FR = 'fr';
	const UK = 'uk';
	const IT = 'it';
	const ES = 'es';

	/**
	 * Allowed countries for API call
	 *
	 * @var array
	 */
	public static $countries = ['es', 'de', 'us', 'it', 'uk'];

	/**
	 * Countries mapped to domain
	 *
	 * @var array
	 */
	protected $country_mapping = [
		'us' => 'com',
		'de' => 'de',
		'it' => 'it',
		'uk' => 'co.uk',
		'es' => 'es'
	];

	/**
	 * Return all pages after request
	 *
	 * @var bool
	 */
	public $allPages = false;

	/**
	 * Public Access Key
	 *
	 * @var string
	 */
	protected $accesskey;

	/**
	 * Private Access Key
	 */
	protected $secretaccesskey;

	/**
	 * Associate ID
	 *
	 * @var string
	 */
	protected $associateid;

	/**
	 * cURL Instance
	 *
	 * @var cURL
	 */
	protected $curl;

	/**
	 * Response from Amazon
	 *
	 * @var xml|AmazonPageCollection
	 */
	protected $response;

	/**
	 * Timestamp
	 *
	 * @var string
	 */
	public $timestamp;

	/**
	 * Amazon URL Instance
	 *
	 * @var AmazonRequestURL
	 */
	protected $url;

	/**
	 * Country for itemsearch
	 *
	 * @var string
	 */
	protected $country;

	/**
	 * See if the request is valid
	 *
	 * @var bool
	 */
	protected $isValid;

	/**
	 * Error if the request was not valid
	 *
	 * @var string
	 */
	protected $error;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct($key, $associate, $secret, $region = self::US)
	{
		$this->validateCountry($region);
		$this->country = $region;
		$this->accesskey = $key;
		$this->associateid = $associate;
		$this->secretaccesskey = $secret;
		$this->timestamp = gmdate("Y-m-d\TH:i:s\Z", time());

		$this->url = new AmazonRequestURL($this, $this->country_mapping[$region]);
	}

	/**
	 * Allow static call
	 *
	 * @return self
	 */
	public function make()
	{
		return new static;
	}

	/**
	 * cURL Initialization
	 *
	 * @return void
	 */
	private function initcurl()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, (string)$this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$this->curl = $ch;
	}

	/**
	 * Set URL parameters
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function addParam($key, $value)
	{
		$this->url->$key = $value;
	}

	/**
	 * Execute the request
	 *
	 * @return xml
	 */
	public function request()
	{
		if ($this->allPages)
		{
			$collection = new AmazonPageCollection;

			$firstrequest = $this->makeRequest();
			$collection->push($firstrequest);
			$maxpage = $firstrequest->maxpage();
			$page = 2;

			while($page <= $maxpage && $page < 11)
			{
				$this->url->ItemPage = $page;
				$collection->push($this->makeRequest());
				$page++;

				sleep(1);
			}

			$this->response = $collection;

			return $this->response;
		}
		else
		{
			$this->response = $this->makeRequest();

			return $this->response;
		}
	}

	/**
	 * Make the actual request
	 *
	 * @return xml
	 */
	private function makeRequest()
	{
		$this->url->sign();
		$this->initcurl();
		$xmlresponse = simplexml_load_string(curl_exec($this->curl));

		$this->validate($xmlresponse);

		return $xmlresponse;
	}

	/**
	 * Get Public Key
	 *
	 * @return string
	 */
	public function accesskey()
	{
		return $this->accesskey;
	}

	/**
	 * Get Private key
	 *
	 * @return string
	 */
	public function secretaccesskey()
	{
		return $this->secretaccesskey;
	}

	/**
	 * Get associate id
	 *
	 * @return string
	 */
	public function associateid()
	{
		return $this->associateid;
	}

	/**
	 * Get signature string
	 *
	 * @return string
	 */
	public function signature()
	{
		return (string)$this->url->signature();
	}

	/**
	 * Get response
	 *
	 * @return XML|AmazonPageCollection
	 */
	public function response()
	{
		return $this->response;
	}

	/**
	 * Return country code
	 *
	 * @return string
	 */
	public function country()
	{
		return $this->country;
	}

	/**
	 * Return domain of country code
	 *
	 * @return string
	 */
	public function domain()
	{
		return $this->country_mapping[$this->country];
	}

	/**
	 * Validate the request
	 *
	 * @param SimpleXMLElement $object
	 *
	 * @return void
	 * @throws AmazonRequestException
	 */
	public function validate(SimpleXMLElement $object)
	{
		if (isset($object->Error))
			throw new AmazonRequestException($object);

		$isvalid = (string)$object->Items->Request->IsValid;
		$this->isValid = $isvalid == 'True' ? true : false;

		if (!$this->isValid)
		{
			throw new AmazonRequestException($object->Items->Request->Errors);
		}
	}

	/**
	 * Validate the given country
	 *
	 * @return void
	 * @throws AmazonCountryNotAllowedException
	 */
	public function validateCountry($country)
	{
		if (array_search($country, self::$countries) === false)
			throw new AmazonCountryNotAllowedException($country);
	}
}

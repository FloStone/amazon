<?php

namespace FloStone\Amazon;

class AmazonSignature
{
	const PREPEND = "GET\nwebservices.amazon.{region}\n/onca/xml\n";
	/**
	 * Private Key
	 *
	 * @var string
	 */
	private $privatekey;

	/**
	 * Signature
	 *
	 * @var string
	 */
	private $signature = "";

	/**
	 * Amazon URL Instance
	 *
	 * @var AmazonRequestURL
	 */
	private $url;

	/**
	 * Amazon Instance
	 *
	 * @var Amazon
	 */
	private $amazon;

	/**
	 * String to sign
	 *
	 * @var string
	 * @see implodeParams
	 */
	private $string;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct(Amazon $amazon, AmazonRequestURL $url)
	{
		$this->amazon = $amazon;
		$this->privatekey = $amazon->secretaccesskey();
		$this->url = $url;

		$this->implodeParams();
		$this->runHash();
	}

	/**
	 * Implode URL parameters and create string
	 * That will be signed
	 *
	 * @return void
	 */
	private function implodeParams()
	{
		$params = $this->url->getAllParams();
		$combined = [];

		foreach($params as $key => $value)
		{
			$combined[] = $key . "=" . $value;
		}

		sort($combined);

		$this->string = implode("&", $combined);
		$this->prepend();
	}

	/**
	 * Prepend constant before string
	 *
	 * @return void
	 */
	public function prepend()
	{
		$this->string = str_replace('{region}', $this->amazon->domain(), self::PREPEND) . $this->string;
	}

	/**
	 * Hash string
	 *
	 * @return void
	 */
	public function runHash()
	{
		$this->signature = base64_encode(hash_hmac('sha256', $this->string, $this->privatekey, true));
	}

	/**
	 * String representation of signature
	 *
	 * @return string
	 */
	public function __tostring()
	{
		return $this->signature;
	}

	/**
	 * Encode the signature
	 *
	 * @return string
	 */
	public function encode()
	{
		return rawurlencode((string)$this->signature);
	}
}
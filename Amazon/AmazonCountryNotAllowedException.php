<?php

namespace Flo\Amazon;

class AmazonCountryNotAllowedException extends \Exception
{
	public function __construct($country)
	{
		parent::__construct("The region requested is not allowed! ($country)");
	}
}
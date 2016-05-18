<?php

namespace Flo\Amazon;

interface AmazonCountry
{
	const US = 'us'; // USA
	const DE = 'de'; // Germany
	const FR = 'fr'; // France
	const UK = 'uk'; // United Kingdom
	const IT = 'it'; // Italy
	const ES = 'es'; // Spain
	const BR = 'br'; // Brasil
	const CA = 'ca'; // Canada
	const CN = 'cn'; // China
	const IN = 'in'; // India
	const JP = 'jp'; // Japan
	const MX = 'mx'; // Mexico

	/**
	 * Validate the given country
	 *
	 * @param string $country
	 * @return void
	 * @throws AmazonCountryNotAllowedException
	 */
	public function validateCountry($country);
}
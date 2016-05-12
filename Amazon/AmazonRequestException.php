<?php

namespace Flo\Amazon;

class AmazonRequestException extends \Exception
{
	public function __construct($errors)
	{
		if (is_array($errors))
		{
			$error = $errors[0]->Error;
		}
		else
		{
			$error = $errors->Error;
		}

		parent::__construct($error->Message);
	}
}
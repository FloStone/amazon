<?php

namespace App\Services\Amazon;

use Illuminate\Support\Collection;

class AmazonPageCollection extends Collection
{
	public function toDBResult()
	{
		foreach($this->items as $item)
		{
			$result_id = $item->toDBResult();
			\Queue::push(new \App\Commands\ProcessResult($result_id));
		}
	}
}
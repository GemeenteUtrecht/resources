<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Sjabloon;

class SjabloonController
{
	public function __invoke(Sjabloon $data): Sjabloon
	{
		//$this->bookPublishingHandler->handle($data);
		
		return $data;
	}
}
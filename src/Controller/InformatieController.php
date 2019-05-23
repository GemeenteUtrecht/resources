<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Informatie;

class InformatieController
{
	public function __invoke(Informatie $data): Informatie
	{
		//$this->bookPublishingHandler->handle($data);
		
		return $data;
	}
}
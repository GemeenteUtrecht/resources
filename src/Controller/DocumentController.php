<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Document;

class DocumentController
{
	public function __invoke(Document $data): Document
	{
		//$this->bookPublishingHandler->handle($data);
		
		return $data;
	}
}
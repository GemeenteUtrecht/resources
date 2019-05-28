<?php

namespace App\Entity\Informatie;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ActivityLogBundle\Entity\Interfaces\StringableInterface;

/**
 * Document
 * 
 * Beschrijving
 * 
 * @category   	Entity
 *
 * @author     	Ruben van der Linde <ruben@conduction.nl>
 * @license    	EUPL 1.2 https://opensource.org/licenses/EUPL-1.2 *
 * @version    	1.0
 *
 * @link   		http//:www.conduction.nl
 * @package		Common Ground
 * @subpackage  Documenten
 * 
 *  @ApiResource( 
 *  collectionOperations={
 *  	"get"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:schrijven"}},
 *      	"path"="/films",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van Films op."
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:maken"}},
 *      	"path"="/films",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een Film aan."
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:schrijven"}},
 *      	"path"="/films/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifieke Film op."
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:schrijven"}},
 *      	"path"="/films/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifieke Film."
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:verwijderen"}},
 *      	"path"="/films/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifieke Film."
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/films/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"informatie:lezen"}},
 *     		"denormalization_context"={"groups"={"informatie:lezen"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van deze object",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/films/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"informatie:lezen"}},
 *     		"denormalization_context"={"groups"={"informatie:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie herstellen",
 *         		"description" = "Herstel een eerdere versie van dit object. Dit is een destructieve actie die niet ongedaan kan worden gemaakt",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	},
 *             	"produces" = {
 *         			"application/json"
 *            	},
 *             	"responses" = {
 *         			"202" = {
 *         				"description" = "Terug gedraaid naar eerdere versie"
 *         			},	
 *         			"400" = {
 *         				"description" = "Ongeldige aanvraag"
 *         			},
 *         			"404" = {
 *         				"description" = "Film niet gevonden"
 *         			}
 *            	}            
 *         }
 *     }
 *  }
 * )
 * @ORM\Entity
 * @Gedmo\Loggable(logEntryClass="ActivityLogBundle\Entity\LogEntry")
 * @ORM\HasLifecycleCallbacks
 */
class Film implements StringableInterface
{
	/**
	 * Het identificatie nummer van deze Film <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @Groups({"informatie:lezen"})
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
	
	/**
	 * Een document hoort altijd bij een informatie object
	 *
	 *
	 * @ORM\OneToOne(targetEntity="App\Entity\Informatie", inversedBy="document")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	public $informatieObject;
	
	/**
	 * @return string
	 */
	public function toString(){
		return $this->orgineleNaam;
	}
	
	/**
	 * Vanuit rendering perspectief (voor bijvoorbeeld loging of berichten) is het belangrijk dat we een entiteit altijd naar string kunnen omzetten.
	 */
	public function __toString()
	{
		return $this->toString();
	}
	
}

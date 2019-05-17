<?php

namespace App\Entity\Sjabloon;

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
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:lezen"}},
 *      	"path"="/berichten",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van documenten op"
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:maken"}},
 *      	"path"="/berichten",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een document aan"
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:lezen"}},
 *      	"path"="/berichten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifiek document op"
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/berichten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifiek document"
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/berichten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifiek document"
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/berichten/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"sjabloon:lezen"}},
 *     		"denormalization_context"={"groups"={"pagina:lezen"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van dit object",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "render"={
 *         	"method"="GET",
 *         	"path"="/berichten/{id}/render",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"pagina:lezen"}},
 *     		"denormalization_context"={"groups"={"pagina:weergeven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Render",
 *         		"description" = "Vervang ingestelde variabelen in de pagina door meeggen array",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/berichten/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"pagina:lezen"}},
 *     		"denormalization_context"={"groups"={"pagina:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie terugdraaid",
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
 *         				"description" = "Document niet gevonden"
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
class Bericht implements StringableInterface
{
	/**
	 * Het identificatie nummer van dit Document <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @Groups({"pagina:lezen", "pagina:weergeven"})
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
	
	
	/**
	 * @return string
	 */
	public function toString(){
		return $this->orgineleNaam;
	}
	
	/**
	 * Vanuit rendering perspectief (voor bijvoorbeeld loging of berichten) is het belangrijk dat we een entiteit altijd naar string kunnen omzetten
	 */
	public function __toString()
	{
		return $this->toString();
	}
	
	/**
	 * The pre persist function is called when the enity is first saved to the database and allows us to set some aditional first values
	 *
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->registratieDatum = new \ Datetime();
		// We want to add some default stuff here like products, productgroups, paymentproviders, templates, clientGroups, mailinglists and ledgers
		return $this;
	}
	public function getUrl()
	{
		return 'http://resources.demo.zaakonline.nl/paginas/'.$this->id;
	}
}

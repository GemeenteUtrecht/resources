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
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ActivityLogBundle\Entity\Interfaces\StringableInterface;

/**
 * Afbeelding
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
 *  		"denormalizationContext"={"groups"={"informatie:lezen"}},
 *      	"path"="/afbeeldingen",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een verzameling van Afbeeldingen op."
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:maken"}},
 *      	"path"="/afbeeldingen",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een Afbeelding aan."
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:lezen"}},
 *      	"path"="/afbeeldingen/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifieke Afbeelding op."
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:verwijderen"}},
 *      	"path"="/afbeeldingen/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifieke Afbeelding."
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"}},
 *  		"denormalizationContext"={"groups"={"informatie:verwijderen"}},
 *      	"path"="/afbeeldingen/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifieke Afbeelding."
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/afbeeldingen/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"informatie:lezen"}},
 *     		"denormalization_context"={"groups"={"informatie:logboek"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van dit object.",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/afbeeldingen/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"informatie:lezen"}},
 *     		"denormalization_context"={"groups"={"informatie:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie herstellen",
 *         		"description" = "Herstel een eerdere versie van dit object. Dit is een destructieve actie die niet ongedaan kan worden gemaakt.",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	},
 *             	"produces" = {
 *         			"application/json"
 *            	},
 *             	"responses" = {
 *         			"202" = {
 *         				"description" = "Een eerdere versie hersteld"
 *         			},	
 *         			"400" = {
 *         				"description" = "Ongeldige aanvraag"
 *         			},
 *         			"404" = {
 *         				"description" = "Afbeelding niet gevonden"
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
class Afbeelding implements StringableInterface
{
	/**
	 * Het identificatienummer van deze Afbeelding <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @Groups({"read", "write"})
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
	
	/**
	 * Een document hoort altijd bij een informatie object
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 40,
	 *     nullable=true
	 * )
	 * @Assert\Length(
	 *      max = 40,
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn."
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van de organisatie die deze Afbeelding heeft gecreëerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * Het RSIN van de organisatie waartoe deze Afbeelding behoort. Dit moet een geldig RSIN zijn van 9 nummers en voldoen aan https://nl.wikipedia.org/wiki/Burgerservicenummer#11-proef. <br> Het RSIN wordt bepaald aan de hand van de geauthenticeerde applicatie en kan niet worden overschreven.
	 *
	 * @var integer
	 * @ORM\Column(
	 *     type     = "integer",
	 *     length   = 9
	 * )
	 * @Assert\Length(
	 *      min = 8,
	 *      max = 9,
	 *      minMessage = "Het RSIN moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read"})
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "title"="bronOrganisatie",
	 *             "type"="string",
	 *             "example"="123456789",
	 *             "required"="true",
	 *             "maxLength"=9,
	 *             "minLength"=8
	 *         }
	 *     }
	 * )
	 */
	public $bronOrganisatie;	
	
	/**
	 * @var string De naam van deze Afbeelding.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"read"})
	 */
	public $naam;
	
	/**
	 * @var string De originele naam van deze Afbeelding.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"read"})
	 */
	public $origineleNaam;
	/* @todo ruben er zit hier een spelfout in de attribuut naam $orgineleNaam --> $origineleNaam */
	
	/**
	 * @var string  De grote van deze Afbeelding in bytes, waar 1024 bytes ,1KB vertegenwoordigd en 1048576 bytes, 1MB.
	 *
	 * @ORM\Column(
	 * 		type="integer", 		
	 * 		nullable=true
	 * )
	 * @Assert\NotBlank
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="integer",
	 *             "example"="1024"
	 *         }
	 *     }
	 * )
	 * @Groups({"read"})
	 */
	public $size;
	
	/**
	 * @var string De extensie van dit bestand.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @ApiProperty(
	 * 	   iri="https://www.iana.org/assignments/media-types/media-types.xhtml",
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="png"
	 *         }
	 *     }
	 * )
	 * @Groups({"read"})
	 */
	public $extension;
	/* @todo typo in extention moet extension zijn */
	
	/**
	 * @var string Het type Afbeelding volgens: <br> https://www.iana.org/assignments/media-types/media-types.xhtml.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Assert\NotBlank
	 * @ApiProperty(
	 * 	   iri="https://www.iana.org/assignments/media-types/media-types.xhtml",
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="image/png"
	 *         }
	 *     }
	 * )
	 * @Groups({"read"})
	 */
	public $mimeType;
	
	/**
	 * @var string De locatie van deze Afbeelding (url).
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $url;
	
	/**
	 * @var string De base64 representatie van deze file.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $base64;
	
	/**
	 * Het tijdstip waarop deze Afbeelding is aangemaakt
	 *
	 * @ORM\OneToOne(targetEntity="App\Entity\Informatie", inversedBy="document")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop dit Afbeelding voor het laatst is gewijzigd.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="update")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime",
	 *     nullable	= true
	 * )
	 * @Groups({"read"})
	 */
	public $wijzigingsdatum;
	
	/**
	 * De contactpersoon voor deze Afbeelding.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     nullable = true
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "title"="Contactpersoon",
	 *             "type"="url",
	 *             "example"="https://ref.tst.vng.cloud/zrc/api/v1/zaken/24524f1c-1c14-4801-9535-22007b8d1b65",
	 *             "required"="true",
	 *             "maxLength"=255,
	 *             "format"="uri"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $contactPersoon;
	
	/**
	 * Met eigenaar wordt bijgehouden welke applicatie verantwoordelijk is voor het object, en daarvoor de rechten beheert en uitgeeft. De eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
	 *
	 * @var App\Entity\Applicatie $eigenaar
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="App\Entity\Applicatie")
	 * @Groups({"read"})
	 */
	public $eigenaar;
	
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
}

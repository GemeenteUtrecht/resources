<?php

namespace App\Entity;

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
 *  		"normalizationContext"={"groups"={"document:lezen"}},
 *  		"denormalizationContext"={"groups"={"document:lezen"}},
 *      	"path"="/documenten",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van informatie objecten op."
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"document:lezen"}},
 *  		"denormalizationContext"={"groups"={"document:maken"}},
 *      	"path"="/documenten",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een informatie object aan."
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"document:lezen"}},
 *  		"denormalizationContext"={"groups"={"document:lezen"}},
 *      	"path"="/documenten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifiek informatie object op."
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"document:lezen"}},
 *  		"denormalizationContext"={"groups"={"document:schrijven"}},
 *      	"path"="/documenten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifiek informatie object."
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"document:lezen"}},
 *  		"denormalizationContext"={"groups"={"document:verwijderen"}},
 *      	"path"="/documenten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifiek informatie object."
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/documenten/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"document:lezen"}},
 *     		"denormalization_context"={"groups"={"document:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van dit informatie object.",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/documenten/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"document:lezen"}},
 *     		"denormalization_context"={"groups"={"document:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie herstellen",
 *         		"description" = "Herstel een eerdere versie van dit informatie object. Dit is een destructieve actie die niet ongedaan kan worden gemaakt",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	},
 *             	"produces" = {
 *         			"application/json"
 *            	},
 *             	"responses" = {
 *         			"202" = {
 *         				"description" = "Hersteld naar eerdere versie"
 *         			},	
 *         			"400" = {
 *         				"description" = "Ongeldige aanvraag"
 *         			},
 *         			"404" = {
 *         				"description" = "Informatie object niet gevonden"
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
class Informatie implements StringableInterface
{
	/**
	 * Het identificatie nummer van dit informatie object. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
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
	 * De unieke identificatie van dit informatie object binnen de organisatie die dit informatie object heeft gecreëerd. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 40,
	 *     nullable=true
	 * )
	 * @Assert\Length(
	 *      max = 40,
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van dit informatie object van de organisatie die dit object heeft gecreëerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * Het RSIN van de organisatie waartoe dit informatie object behoort. Dit moet een geldig RSIN zijn van 9 nummers en voldoen aan https://nl.wikipedia.org/wiki/Burgerservicenummer#11-proef. <br> Het RSIN word bepaald aan de hand van de geauthenticeerde applicatie en kan niet worden overschreven
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
	 * @var string Het soort sjabloon.
	 *
	 * @ORM\Column
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "enum"={"afbeelding", "film", "applicatie"},
	 *             "example"="simple",
	 *             "required"="true"
	 *         }
	 *     }
	 * )
	 * @Assert\NotBlank
	 * @Assert\Choice(
	 *     choices = { "bericht", "pagina"},
	 *     message = "Kies bericht of pagina"
	 * )
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 * @ApiFilter(OrderFilter::class)
	 * @Groups({"read", "write"})
	 */
	public $type;
	
	/**
	 * @var string De naam van dit informatie object.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"read"})
	 */
	public $naam;
	
	/**
	 * @var string De originele naam van dit informatie object.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"read"})
	 */
	public $orgineleNaam;
	
	/**
	 * @var string De grote van dit informatie object in bytes, waar 1024 gelijk is aan 1KB en 1048576 aan 1MB.
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
	 * @var string De extentie van dit informatie object.
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
	public $extention;
	
	/**
	 * @var string Het bestandstype van dit informatie object volgens: <br> https://www.iana.org/assignments/media-types/media-types.xhtml.
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
	 * @var string De locatie van dit informatie object.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $url;
	
	/**
	 * @var string De base64 representatie van dit informatie object.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $base64;
	
	/**
	 * Het tijdstip waarop dit informatie object object is aangemaakt
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"read"})
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop dit informatie object voor het laatst is gewijzigd.
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
	 * De contactpersoon voor dit informatie object.
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
	 * Met eigenaar wordt bijgehouden welke applicatie verantwoordelijk is voor het informatie object, en daarvoor de rechten beheerd en uitgeeft. In die zin moet de eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
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
		return 'http://resources.demo.zaakonline.nl/documenten/'.$this->id;
	}
}

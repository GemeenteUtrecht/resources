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
 *  		"normalizationContext"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/documenten",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van informatieobjecten op."
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"informatie:maken"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/documenten",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een informatieobject aan."
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/documenten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifiek informatieobject op."
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"informatie:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/documenten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifiek informatieobject."
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"informatie:verwijderen"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/documenten/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifiek informatieobject."
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/documenten/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"informatie:lezen","enable_max_depth" = true, "circular_reference_handler"}},
 *     		"denormalization_context"={"groups"={"informatie:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van dit informatieobject.",
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
 *     		"normalization_context"={"groups"={"informatie:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *     		"denormalization_context"={"groups"={"informatie:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *         	"openapi_context" = {
 *         		"summary" = "Versie herstellen",
 *         		"description" = "Herstel een eerdere versie van dit informatieobject. Dit is een destructieve actie die niet ongedaan kan worden gemaakt",
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
 *         				"description" = "informatieobject niet gevonden"
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
	 * Het identificatienummer van dit informatieobject. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
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
	 * @var string De locatie van dit informatie object.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $url;
	
	/**
	 * De unieke identificatie van dit informatieobject binnen de organisatie die dit informatieobject heeft gecreëerd. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
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
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van dit informatieobject van de organisatie die dit object heeft gecreëerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * Het RSIN van de organisatie waartoe dit informatieobject behoort. Dit moet een geldig RSIN zijn van 9 nummers en voldoen aan https://nl.wikipedia.org/wiki/Burgerservicenummer#11-proef. <br> Het RSIN wordt bepaald aan de hand van de geauthenticeerde applicatie en kan niet worden overschreven.
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
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
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
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $type;
	
	/**
	 * @var string De naam van dit informatieobject.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $naam;
	
	/**
	 * @var string De originele naam van dit informatieobject.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $orgineleNaam;
	
	/**
	 * @var string De grote van dit informatieobject in bytes, waar 1024 gelijk is aan 1KB en 1048576 aan 1MB.
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
	 * @Groups({"informatie:lezen"})
	 */
	public $size;
	
	/**
	 * @var string De extensie van dit informatieobject.
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
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $extension;
	
	/**
	 * @var string Het bestandstype van dit informatieobject volgens: <br> https://www.iana.org/assignments/media-types/media-types.xhtml.
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
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $mimeType;
	
	/**
	 * @var string De locatie van dit informatieobject.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $locatie;
	
	/**
	 * @var string De base64 representatie van dit informatieobject.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 */
	public $base64;	
	
	/**	  
	 * Een infromatie object kan een document, film of afbeelding zijn.
	 * 
     * @MaxDepth(1)
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 * @ORM\OneToOne(targetEntity="App\Entity\Informatie\Document", mappedBy="informatieObject")
	 */
	public $document;
		
	/**
	 * Een infromatie object kan een document, film of afbeelding zijn.
	 * 
     * @MaxDepth(1)
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 * @ORM\OneToOne(targetEntity="App\Entity\Informatie\Film", mappedBy="informatieObject")
	 */
	public $film;
		
	/**
	 * Een infromatie object kan een document, film of afbeelding zijn.
	 * 
     * @MaxDepth(1)
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
	 * @ORM\OneToOne(targetEntity="App\Entity\Informatie\Afbeelding", mappedBy="informatieObject")
	 */
	public $afbeelding;
	
	/**
	 * De de auteur van dit object.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     nullable = true
	 * )
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
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
	public $auteur;
	
	/**
	 * Het tijdstip waarop dit informatieobject is aangemaakt.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"informatie:lezen"})
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop dit informatieobject voor het laatst is gewijzigd.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="update")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime",
	 *     nullable	= true
	 * )
	 * @Groups({"informatie:lezen"})
	 */
	public $wijzigingsdatum;
	
	/**
	 * De contactpersoon voor dit informatieobject.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     nullable = true
	 * )
	 * @Groups({"informatie:lezen","informatie:schrijven","informatie:maken"})
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
	 * Met eigenaar wordt bijgehouden welke applicatie verantwoordelijk is voor het informatieobject, en daarvoor de rechten beheerd en uitgeeft. In die zin moet de eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
	 *
	 * @var App\Entity\Applicatie $eigenaar
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="App\Entity\Applicatie")
	 * @Groups({"informatie:lezen"})
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

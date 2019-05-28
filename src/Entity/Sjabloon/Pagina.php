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
use Symfony\Component\Serializer\Annotation\MaxDepth;
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
 *      	"path"="/paginas",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van Pagina's op."
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:maken"}},
 *      	"path"="/paginas",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een Pagina aan."
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:lezen"}},
 *      	"path"="/paginas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifieke Pagina op."
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/paginas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifieke Pagina."
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/paginas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifieke Pagina."
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/paginas/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"sjabloon:lezen"}},
 *     		"denormalization_context"={"groups"={"pagina:lezen"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van dit Pagina object.",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "render"={
 *         	"method"="GET",
 *         	"path"="/sjablonen/{id}/render",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"pagina:lezen"}},
 *     		"denormalization_context"={"groups"={"pagina:weergeven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Render",
 *         		"description" = "Vervang ingestelde variabelen in de Pagina door mee gegeven array.",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/paginas/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"pagina:lezen"}},
 *     		"denormalization_context"={"groups"={"pagina:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie herstellen",
 *         		"description" = "Herstel een eerdere versie van dit Pagina object. Dit is een destructieve actie die niet ongedaan kan worden gemaakt.",
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
 *         				"description" = "Pagina niet gevonden"
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
class Pagina implements StringableInterface
{
	/**
	 * Het identificatie nummer van deze pagina. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
	
	/**
	 * Een pagina hoort altijd bij een sjabloon
	 *
	 * @ORM\OneToOne(targetEntity="App\Entity\Sjabloon", inversedBy="pagina")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	public $sjabloon;
	
	/**
	 * @var string De locaties (of url) waarop deze pagina wordt terug gevonden.
	 *	 
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 * @ApiFilter(OrderFilter::class)
	 */
	public $slug;	
	
	/**
	 * @var string De beschrijving van het doel van deze Pagina voor zoekmachines.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De beschrijving moeten minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De beschrijving mag maximaal  {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="Dit is een belangrijke Pagina die uitleg geeft over van alles en nog wat.",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaDescription;
	
	/**
	 * @var string Een samenvatting van de belangrijkste tref- of zoekwoorden die je op een Pagina gebruikt.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De keywords moeten minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De keywords mogen maximaal  {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="gemeente, pagina, burgers",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaKeywords;
	
	/**
	 * @var string Hoe ver mogen zoekbots je site doorzoeken en spideren?
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De bot moet minimaal {{ limit }} tekens bevatten",
	 *      maxMessage = "De bot kan maximaal {{ limit }} tekens bevatten"
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="INDEX, FOLLOW",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaRobots;
	
	/**
	 * @var string Wanneer wil je dat de zoekmachine spiders je site weer bezoeken?
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De revisist afther moet minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De revisist afther kan maximaal {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="7 days",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaRevisitAfter;
	
	/**
	 * @var string De auteur van de text. Dit mag ook een organisatie zijn.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De auteur moet tenminste {{ limit }} tekens bevatten.",
	 *      maxMessage = "De auteur kan maximaal {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="John Do",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaAuthor;
	
	/**
	 * @var string De copyright metatag wordt ook gebruikt om melding te maken van: trademarks, patent nummers van intelectueel eigendom.
	 * 
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De copyright verwijzing moet minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De copyright verwijzing kan maximaal {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="https://opensource.org/licenses/MIT",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaCopyright;
	
	/**
	 * @var string De meta contact naam is in gebruik voor het vermelden van een contact emailadres. 
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De contact verwijzing moet minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De contact verwijzing kan maximaal {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="emailadres@domeinnaam.nl",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaContact;
	
	/**
	 * @var string Als een Pagina ter verduidelijking van een andere Pagina dient, kan de hier mee naar de andere pagina worden verwezen.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De oorspronkelijke bron verwijzing moet minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De oorspronkelijke bron verwijzing kan maximaal {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="http://www.mijngemeente.nl",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaOriginalSource;
		
	
	/**
	 * @var string Geeft bij het herhalen van tekst op meerdere paginas aan welke pagina de zoekmachines moeten gebruiken.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven", "sjabloon:weergeven", "sjabloon:maken"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De conical verwijzing moet minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De conical verwijzing kan maximaal {{ limit }} tekens bevatten."
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="http://www.mijngemeente.nl",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaCanonical;
	
	
	/**
	 * Het tijdstip waarop dit Pagina object is aangemaakt.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde."
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"pagina:lezen"})
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop dit Pagina object voor het laatst is gewijzigd.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde."
	 * @Gedmo\Timestampable(on="update")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime",
	 *     nullable	= true
	 * )
	 * @Groups({"pagina:lezen"})
	 */
	public $wijzigingsdatum;
	
	/**
	 * De contactpersoon voor deze Pagina.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     nullable = true
	 * )
	 * @Groups({"pagina:lezen", "pagina:weergeven"})
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
	 * Met eigenaar wordt bijgehouden welke applicatie verantwoordelijk is voor het Pagina object, en daarvoor de rechten beheerd en uitgeeft. In die zin moet de eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
	 *
	 * @var App\Entity\Applicatie $eigenaar
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="App\Entity\Applicatie")
	 * @Groups({"pagina:lezen"})
	 */
	public $eigenaar;
	
	/*
	 * Dan hebben we uiteraard nog een paar call specificieke properties.
	 * 
	 */
	
	/**
	 * Variabelen die worden gebruikt in het criëeren van een weergaven voor deze Pagina.
	 *
	 * @Groups({"pagina:weergeven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "title"="variabelen",
	 *             "type"="array",
	 *             "example"="[]",
	 *             "format"="array"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $variabelen;
	
	/**
	 * Een overzicht van alle op deze Pagina uitgevoerde wijzigingen.
 	 *
	 * @Groups({"pagina:logboek"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "title"="logboek",
	 *             "type"="array",
	 *             "example"="[]",
	 *             "format"="array"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $logboek;
	
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
	
	/**
	 * The pre persist function is called when the enity is first saved to the database and allows us to set some aditional first values.
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

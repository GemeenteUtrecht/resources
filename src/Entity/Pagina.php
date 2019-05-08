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
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:lezen"}},
 *      	"path"="/paginas",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van documenten op"
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:maken"}},
 *      	"path"="/paginas",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een document aan"
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"pagina:lezen"}},
 *      	"path"="/paginas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifiek document op"
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/paginas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifiek document"
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"pagina:lezen"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/paginas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifiek document"
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
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van dit object",
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
 *         		"description" = "Vervang ingestelde variabelen in de pagina door meeggen array",
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
class Pagina implements StringableInterface
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
	 * De unieke identificatie van dit object binnen de organisatie die dit object heeft gecreeerd. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
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
	 * @Groups({"pagina:lezen", "pagina:weergeven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van dit object de organisatie die dit object heeft gecreeerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * Het RSIN van de organisatie waartoe deze document behoort. Dit moet een geldig RSIN zijn van 9 nummers en voldoen aan https://nl.wikipedia.org/wiki/Burgerservicenummer#11-proef. <br> Het RSIN word bepaald aan de hand van de gauthenticeerde applicatie en kan niet worden overschreven
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
	 * @Groups({"pagina:lezen"})
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
	 * @var string De naam van de pagina (voor intern gebruik)
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255
	 * )
	 * @Assert\NotBlank
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De titel moeten tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De titel mag maximaal  {{ limit }} tekens bevatten"
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="Start pagina van belangrijk procces",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 * @ApiFilter(OrderFilter::class)
	 */
	public $naam;		
	
	/**
	 * @var string De titel van de pagina (voor extern gebruik)
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255
	 * )
	 * @Assert\NotBlank
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De titel moeten tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De titel mag maximaal  {{ limit }} tekens bevatten"
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="Welkom bij het procces!",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 * @ApiFilter(OrderFilter::class)
	 */
	public $titel;	
	
	/**
	 * @var string De locaties (of url deel) waarop deze pagina wordt terug gevonden
	 *	 
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen"})
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 * @ApiFilter(OrderFilter::class)
	 */
	public $slug;	
	
	/**
	 * @var string De beschrijving van het doel van deze pagina (voor intern gebruik)
	 *	 
	 * @ORM\Column(
	 *     type     = "text",
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De beschrijving moeten tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De beschrijving moag maximaal  {{ limit }} tekens bevatten"
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="Dit is een belangrijke pagina die uitleg geeft over van alles en nog wat",
	 *             "maxLength"=0,
	 *             "minLength"=2500
	 *         }
	 *     }
	 * )
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 */
	public $beschrijving;
	
	/**
	 * @var string De daadwerlijke (twig) inhoud van deze pagina
	 *	 
	 * @ORM\Column(
	 *     type     = "text"
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 2500,
	 *      minMessage = "De inhoud moeten tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De inhoud moag maximaal  {{ limit }} tekens bevatten"
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="Het is erg belangrijk dat u",
	 *             "maxLength"=0,
	 *             "minLength"=2500
	 *         }
	 *     }
	 * )
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 */
	public $inhoud;
	
	/**
	 * @var string De beschrijving van het doel van deze pagina voor zoekmachines
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De beschrijving moeten tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De beschrijving moag maximaal  {{ limit }} tekens bevatten"
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="Dit is een belangrijke pagina die uitleg geeft over van alles en nog wat",
	 *             "maxLength"=0,
	 *             "minLength"=255
	 *         }
	 *     }
	 * )
	 */
	public $metaDescription;
	
	/**
	 * @var string Een samenvatting van de belangrijkste tref- of zoekwoorden die je op een pagina gebruikt.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De keywords moeten tesamen tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De keywords mogen tesamen  {{ limit }} tekens bevatten"
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
	 * @var string Hoe ver mogen zoekrobots je site doorzoeken en spideren?
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De robots moet tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De robots kan maximaal {{ limit }} tekens bevatten"
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
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De revisist afther moet tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De revisist afther kan maximaal {{ limit }} tekens bevatten"
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
	 * @var string De auteur van de text, mag ook een organisatie zijn
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De auteur moet tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De auteur kan maximaal {{ limit }} tekens bevatten"
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
	 * @var string De copyright metatag wordt ook gebruikt om melding te maken van : trademarks, patent nummers of intellectual property
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De copyright verwijzing moet tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De copyright verwijzing kan maximaal {{ limit }} tekens bevatten"
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
	 * @var string De meta contact name was in gebruik voor het vermelden van een contact email adres. 
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De contact verwijzing moet tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De contact verwijzing kan maximaal {{ limit }} tekens bevatten"
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
	 * @var string Als een pagina ter verduidelijking van een andere pagina dient, kan deze andere pagina hiermee worden aangehaald
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De oorspronkelijke bron moet tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De oorspronkelijke bron verwijzing kan maximaal {{ limit }} tekens bevatten"
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
	 * @var string Geeft bij het herhalen van tekst op meerdere paginas aan welke pagina de zoekmachines moet worden gebruikt
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable=true
	 * )
	 * @Groups({"pagina:lezen","pagina:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De conical moet tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De conical verwijzing kan maximaal {{ limit }} tekens bevatten"
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
	 * Het tijdstip waarop dit Ambtenaren object is aangemaakt
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"pagina:lezen"})
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop dit Ambtenaren object voor het laatst is gewijzigd.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
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
	 * Het contact persoon voor dit document
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
	 * Met eigenaar wordt bijgehouden welke  applicatie verantwoordelijk is voor het object, en daarvoor de rechten beheerd en uitgeeft. In die zin moet de eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
	 *
	 * @var App\Entity\Applicatie $eigenaar
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="App\Entity\Applicatie")
	 * @Groups({"pagina:lezen"})
	 */
	public $eigenaar;
	
	/*
	 * Dan hebben we uiteraard nog een paar call specificieke properties
	 * 
	 */
	
	/**
	 * Variabelen die worden gebruikt in het criëren van een weergaven voor deze pagina
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
	 * Een overzicht van alle op deze pagina uitgevoerde wijzigingen
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

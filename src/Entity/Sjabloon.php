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
 *  		"normalizationContext"={"groups"={"sjabloon:lezen"}},
 *  		"denormalizationContext"={"groups"={"sjabloon:lezen"}},
 *      	"path"="/sjablonen",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van Sjablonen op."
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"sjabloon:lezen"}},
 *  		"denormalizationContext"={"groups"={"sjabloon:maken"}},
 *      	"path"="/sjablonen",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een Sjabloon aan."
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"sjabloon:lezen"}},
 *  		"denormalizationContext"={"groups"={"sjabloon:lezen"}},
 *      	"path"="/sjablonen/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifiek Sjabloon op."
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"sjabloon:lezen"}},
 *  		"denormalizationContext"={"groups"={"sjabloon:schrijven"}},
 *      	"path"="/sjablonen/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifiek Sjabloon."
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"sjabloon:lezen"}},
 *  		"denormalizationContext"={"groups"={"sjabloon:verwijderen"}},
 *      	"path"="/sjablonen/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifiek Sjabloon."
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/sjablonen/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"sjabloon:lezen"}},
 *     		"denormalization_context"={"groups"={"sjabloon:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van dit Sjabloon.",
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
 *     		"normalization_context"={"groups"={"sjabloon:lezen"}},
 *     		"denormalization_context"={"groups"={"sjabloon:weergeven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Render",
 *         		"description" = "Vervang ingestelde variabelen in het sjabloon door mee gegeven array.",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}           
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/sjablonen/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"sjabloon:lezen"}},
 *     		"denormalization_context"={"groups"={"sjabloon:schrijven"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie herstellen",
 *         		"description" = "Herstel een eerdere versie van dit Sjabloon. Dit is een destructieve actie die niet ongedaan kan worden gemaakt",
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
 *         				"description" = "Sjabloon niet gevonden"
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
class Sjabloon implements StringableInterface
{
	/**
	 * Het identificatie nummer van dit Sjabloon. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @Groups({"sjabloon:lezen"})
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
	
	/**
	 * De unieke identificatie van dit Sjabloon binnen de organisatie die dit object heeft gecreëerd. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
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
	 * @Groups({"sjabloon:lezen", "sjabloon:schrijven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van dit object de organisatie die dit object heeft gecreëerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * Het RSIN van de organisatie waartoe dit Sjabloon behoort. Dit moet een geldig RSIN zijn van 9 nummers en voldoen aan https://nl.wikipedia.org/wiki/Burgerservicenummer#11-proef. <br> Het RSIN word bepaald aan de hand van de gauthenticeerde applicatie en kan niet worden overschreven
	 *
	 * @var integer
	 * @ORM\Column(
	 *     type     = "integer",
	 *     length   = 9
	 * )
	 * @Assert\Length(
	 *      min = 8,
	 *      max = 9,
	 *      minMessage = "Het RSIN moet minimaal {{ limit }} karakters lang zijn",
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"sjabloon:lezen"})
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
	 *             "enum"={"bericht", "pagina"},
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
	 * @var string De naam van dit Sjabloon (voor intern gebruik).
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255
	 * )
	 * @Assert\NotBlank
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De titel moeten minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De titel mag maximaal  {{ limit }} tekens bevatten."
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
	 * @var string De titel van dit Sjabloon (voor extern gebruik).
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255
	 * )
	 * @Assert\NotBlank
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven"})
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 255,
	 *      minMessage = "De titel moeten minimaal {{ limit }} tekens bevatten.",
	 *      maxMessage = "De titel mag maximaal  {{ limit }} tekens bevatten."
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
	 * @var string De beschrijving van het doel van dit Sjabloon (voor intern gebruik).
	 *	 
	 * @ORM\Column(
	 *     type     = "text",
	 *     nullable=true
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven"})
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
	 * @var string De daadwerlijke (twig) inhoud van dit Sjabloon.
	 *	 
	 * @ORM\Column(
	 *     type     = "text"
	 * )
	 * @Groups({"sjabloon:lezen","sjabloon:schrijven"})
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *      min = 0,
	 *      max = 2500,
	 *      minMessage = "De inhoud moeten minimaal {{ limit }} tekens bevatten",
	 *      maxMessage = "De inhoud mag maximaal  {{ limit }} tekens bevatten"
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
	 * Het tijdstip waarop dit Sjabloon object is aangemaakt
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"sjabloon:lezen"})
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop dit Sjabloon object voor het laatst is gewijzigd.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="update")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime",
	 *     nullable	= true
	 * )
	 * @Groups({"sjabloon:lezen"})
	 */
	public $wijzigingsdatum;
	
	/**
	 * De contactpersoon voor dit Sjabloon.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     nullable = true
	 * )
	 * @Groups({"sjabloon:lezen", "sjabloon:schrijven"})
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
	 * Met eigenaar wordt bijgehouden welke applicatie verantwoordelijk is voor het Sjabloon, en daarvoor de rechten beheerd en uitgeeft. In die zin moet de eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
	 *
	 * @var App\Entity\Applicatie $eigenaar
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="App\Entity\Applicatie")
	 * @Groups({"sjabloon:lezen"})
	 */
	public $eigenaar;
		
	/*
	 * Dan hebben we uiteraard nog een paar call specificieke properties
	 *
	 */
	
	/**
	 * Variabelen die worden gebruikt in het crieëren van een weergaven voor dit Sjabloon.
	 *
	 * @Groups({"sjabloon:weergeven"})
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
	 * Een overzicht van alle op dit Sjabloon uitgevoerde wijzigingen.
	 *
	 * @Groups({"sjabloon:logboek"})
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
		return 'http://resources.demo.zaakonline.nl/sjablonen/'.$this->id;
	}
}

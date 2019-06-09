<?php

namespace App\Entity\Informatie;

use App\Entity\Applicatie;
use App\Entity\Informatie;
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
	 * Het identificatie nummer van deze Film. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
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
	 *             "description"="De unieke identificatie van de organisatie die deze Film heeft gecreëerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * Het RSIN van de organisatie waartoe deze Film behoort. Dit moet een geldig RSIN zijn van 9 nummers en voldoen aan https://nl.wikipedia.org/wiki/Burgerservicenummer#11-proef. <br> Het RSIN word bepaald aan de hand van de geauthenticeerde applicatie en kan niet worden overschreven.
	 *
	 * @var integer
	 * @ORM\Column(
	 *     type     = "integer",
	 *     length   = 9
	 * )
	 * @Assert\Length(
	 *      min = 8,
	 *      max = 9,
	 *      minMessage = "Het RSIN moet minimaal {{ limit }} karakters lang zijn.",
	 *      maxMessage = "Het RSIN mag maximaal {{ limit }} karakters zijn."
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
	 * @var string De naam van deze Film.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"read"})
	 */
	public $naam;
	
	/**
	 * @var string De orgiginele naam van deze Film.
	 *
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Groups({"read"})
	 */
	public $orgineleNaam;
	
	/**
	 * @var string De grote van deze Film in bytes, waar 1024 bytes overeenkomen met 1KB en 1048576 bytes met 1MB.
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
	 * @var string De extensie van deze Film.
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
	/* @todo ruben het moet extension zijn */
	
	/**
	 * @var string The type of the file acording to https://www.iana.org/assignments/media-types/media-types.xhtml.
	 *
	 * @ORM\OneToOne(targetEntity="App\Entity\Informatie", inversedBy="document")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	public $mimeType;
	
	/**
	 * @var string De locatie van deze Film (url).
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $url;
	
	/**
	 * @var string De base64 representatie van deze Film.
	 *
	 * @ORM\Column(
	 * 		nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $base64;
	
	/**
	 * Het tijdstip waarop dit Film object is aangemaakt.
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
	 * Het tijdstip waarop dit Film object voor het laatst is gewijzigd.
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
	 * De contactpersoon voor dit document.
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
	 * Met eigenaar wordt bijgehouden welke applicatie verantwoordelijk is voor het Film object, en daarvoor de rechten beheerd en uitgeeft. In die zin moet de eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
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
	public function toString()
                                                                                                                                                                        	{
                                                                                                                                                                        		return $this->orgineleNaam;
                                                                                                                                                                        	}
	
	/**
	 * Vanuit rendering perspectief (voor bijvoorbeeld loging of berichten) is het belangrijk dat we een entiteit altijd naar string kunnen omzetten.
	 */
	public function __toString()
                                                                                                                                                                        	{
                                                                                                                                                                        		return $this->toString();
                                                                                                                                                                        	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificatie(): ?string
    {
        return $this->identificatie;
    }

    public function setIdentificatie(?string $identificatie): self
    {
        $this->identificatie = $identificatie;

        return $this;
    }

    public function getBronOrganisatie(): ?int
    {
        return $this->bronOrganisatie;
    }

    public function setBronOrganisatie(int $bronOrganisatie): self
    {
        $this->bronOrganisatie = $bronOrganisatie;

        return $this;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

        return $this;
    }

    public function getOrgineleNaam(): ?string
    {
        return $this->orgineleNaam;
    }

    public function setOrgineleNaam(string $orgineleNaam): self
    {
        $this->orgineleNaam = $orgineleNaam;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getBase64(): ?string
    {
        return $this->base64;
    }

    public function setBase64(?string $base64): self
    {
        $this->base64 = $base64;

        return $this;
    }

    public function getRegistratiedatum(): ?\DateTimeInterface
    {
        return $this->registratiedatum;
    }

    public function setRegistratiedatum(\DateTimeInterface $registratiedatum): self
    {
        $this->registratiedatum = $registratiedatum;

        return $this;
    }

    public function getWijzigingsdatum(): ?\DateTimeInterface
    {
        return $this->wijzigingsdatum;
    }

    public function setWijzigingsdatum(?\DateTimeInterface $wijzigingsdatum): self
    {
        $this->wijzigingsdatum = $wijzigingsdatum;

        return $this;
    }

    public function getContactPersoon(): ?string
    {
        return $this->contactPersoon;
    }

    public function setContactPersoon(?string $contactPersoon): self
    {
        $this->contactPersoon = $contactPersoon;

        return $this;
    }

    public function getMimeType(): ?Informatie
    {
        return $this->mimeType;
    }

    public function setMimeType(?Informatie $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getEigenaar(): ?Applicatie
    {
        return $this->eigenaar;
    }

    public function setEigenaar(?Applicatie $eigenaar): self
    {
        $this->eigenaar = $eigenaar;

        return $this;
    }
	
}

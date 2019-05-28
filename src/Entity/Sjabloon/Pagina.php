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
 * @ApiResource 
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
	 * @var string De beschrijving van het doel van deze Pagina voor zoekmachines,
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
	 *      minMessage = "De beschrijving moeten tenminste {{ limit }} tekens bevatten",
	 *      maxMessage = "De beschrijving mag maximaal  {{ limit }} tekens bevatten"
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
	 *      minMessage = "De keywords moeten minimaal {{ limit }} tekens bevatten",
	 *      maxMessage = "De keywords mogen maximaal  {{ limit }} tekens bevatten"
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
	 *      minMessage = "De revisist afther moet minimaal {{ limit }} tekens bevatten",
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
	 * @var string De copyright metatag wordt ook gebruikt om melding te maken van : trademarks, patent nummers van intelectueel eigendom.
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
	 *      minMessage = "De copyright verwijzing moet minimaal {{ limit }} tekens bevatten",
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
	 *      minMessage = "De contact verwijzing moet minimaal {{ limit }} tekens bevatten",
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

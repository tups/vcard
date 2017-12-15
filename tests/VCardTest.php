<?php

namespace JeroenDesloovere\VCard;

use JeroenDesloovere\VCard\Exception\ParserException;
use JeroenDesloovere\VCard\Formatter\Formatter;
use JeroenDesloovere\VCard\Formatter\VcfFormatter;
use JeroenDesloovere\VCard\Parser\Parser;
use JeroenDesloovere\VCard\Parser\VcfParser;
use JeroenDesloovere\VCard\Property\Address;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Note;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
class VCardTest extends TestCase
{
    /**
     * @var VCard 
     */
    private $firstVCard;

    /**
     * @var VCard 
     */
    private $secondVCard;

    public function setUp(): void
    {
        // Building one or multiple vCards
        $this->firstVCard = (new VCard())
            ->add(new Name('Jeroen', 'Desloovere'))
            ->add(new Address(null, null, 'Markt 1', 'Brugge', 'West-Vlaanderen', '8000', 'België', Type::work()))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::home()))
            ->add(new Note('VCard library is amazing.'));
        $this->secondVCard = (new VCard())
            ->add(new Name('John', 'Doe'))
            ->add(new Address(null, 'Penthouse', 'Korenmarkt 1', 'Gent', 'Oost-Vlaanderen', '9000', 'België', Type::work()));
    }

    public function testFormatterSavingMultipleVCardsToVcfFile(): void
    {
        // Saving "vcards.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcards');
        $formatter->addVCard($this->firstVCard);
        $formatter->addVCard($this->secondVCard);
        $formatter->save(__DIR__);

        $this->assertFalse(false);
    }

    public function testFormatterSavingOneVCardToVcfFile(): void
    {
        // Saving "vcard.vcf"
        $formatter = new Formatter(new VcfFormatter(), 'vcard');
        $formatter->addVCard($this->firstVCard);
        $formatter->save(__DIR__);

        $this->assertFalse(false);
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ParserException
     */
    public function testParserCorruptVCard(): void
    {
        new Parser(new VcfParser(), 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
    }

    /**
     * @expectedException \JeroenDesloovere\VCard\Exception\ParserException
     */
    public function testParserEmptyVCard(): void
    {
        new Parser(new VcfParser(), '');
    }

    public function testParserMultipleVCardsFromVcfFile(): void
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/assets/vcards.vcf'));

        // @todo
        //$this->assertEquals($this->firstVCard, $parser->getVCards()[0]);
        //$this->assertEquals($this->secondVCard, $parser->getVCards()[1]);

        $this->assertFalse(false);
    }

    public function testParserOneVCardFromVcfFile(): void
    {
        $parser = new Parser(new VcfParser(), Parser::getFileContents(__DIR__ . '/assets/vcard.vcf'));

        $this->assertEquals($this->firstVCard, $parser->getVCards()[0]);
    }

    public function testVCardGetProperties(): void
    {
        $this->assertCount(4, $this->firstVCard->getProperties());
        $this->assertCount(1, $this->firstVCard->getProperties(Name::class));
        $this->assertCount(2, $this->firstVCard->getProperties(Address::class));
        $this->assertCount(1, $this->firstVCard->getProperties(Note::class));
        $this->assertCount(2, $this->secondVCard->getProperties());
        $this->assertCount(1, $this->secondVCard->getProperties(Name::class));
        $this->assertCount(1, $this->secondVCard->getProperties(Address::class));
    }
}

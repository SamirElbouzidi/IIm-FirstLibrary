<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

final class WikipediaPresidentScraperTest extends TestCase
{
    public function testGetPresidents()
    {
        $scraper = new WikipediaPresidentScraper('https://fr.wikipedia.org/wiki/Liste_des_pr%C3%A9sidents_de_la_R%C3%A9publique_fran%C3%A7aise');
        $presidents = $scraper->getPresidents();

        // Test if getPresidents return an array
        $this->assertIsArray($presidents);

        // Test if the array is not empty
        $this->assertNotEmpty($presidents);

        // Test if the first element in the array is a President object
        $this->assertInstanceOf(President::class, $presidents[0]);

        // Test if the President object has the necessary attributes
        $this->assertObjectHasAttribute('name', $presidents[0]);
        $this->assertObjectHasAttribute('birthAndDeath', $presidents[0]);
        $this->assertObjectHasAttribute('mandateStart', $presidents[0]);
        $this->assertObjectHasAttribute('mandateEnd', $presidents[0]);
    }
}
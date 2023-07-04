<?php

require '../vendor/autoload.php';

use Goutte\Client;

class President {
    public string $name;
    public string $birthAndDeath;
    public string $mandateStart;
    public string $mandateEnd;

    public function __toString() {
        return "Nom : " . $this->name . "\nDates de naissance et de décès : " . $this->birthAndDeath . "\nDébut du mandat : " . $this->mandateStart . "\nFin du mandat : " . $this->mandateEnd . "\n\n";
    }
}

class WikipediaPresidentScraper {
    private Client $client;
    private string $url;

    public function __construct(string $url) {
        $this->client = new Client();
        $this->url = $url;
    }

    /**
     * @return array<President>
     */
    public function getPresidents(): array {
        $crawler = $this->client->request('GET', $this->url);
        $presidents = [];

        $crawler->filter('table.wikitable tr')->each(function ($node) use (&$presidents) {
            $tds = $node->filter('td');
            if(count($tds) > 0) {
                $president = new President();

                $nameNode = $tds->eq(2)->filter('a');
                $president->name = count($nameNode) > 0 ? $nameNode->first()->text() : '';

                $birthAndDeathNode = $tds->eq(2)->filter('small');
                $president->birthAndDeath = count($birthAndDeathNode) > 0 ? $birthAndDeathNode->first()->text() : '';

                $mandateStartNode = $tds->eq(3)->filter('time');
                $president->mandateStart = count($mandateStartNode) > 0 ? $mandateStartNode->first()->text() : '';

                $mandateEndNode = $tds->eq(4)->filter('time');
                $president->mandateEnd = count($mandateEndNode) > 0 ? $mandateEndNode->first()->text() : '';

                // Seulement ajouter le président à la liste si toutes les informations sont présentes
                if ($president->name !== '' && $president->birthAndDeath !== '' && $president->mandateStart !== '' && $president->mandateEnd !== '') {
                    $presidents[] = $president;
                }
            }
        });

        return $presidents;
    }
}

$scraper = new WikipediaPresidentScraper('https://fr.wikipedia.org/wiki/Liste_des_pr%C3%A9sidents_de_la_R%C3%A9publique_fran%C3%A7aise');
$presidents = $scraper->getPresidents();

foreach($presidents as $president) {
    echo $president;
}

?>

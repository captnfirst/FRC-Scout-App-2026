<?php

class TBA {
    private $apiKey;
    private $baseUrl = 'https://www.thebluealliance.com/api/v3';

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    // Ana API İstek Fonksiyonu
    private function request($endpoint) {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Sunucudaki olası SSL sertifika hatalarını es geçmek için:
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // TBA'in bizden istediği API anahtarını Header (başlık) olarak gönderiyoruz
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-TBA-Auth-Key: ' . $this->apiKey,
            'accept: application/json'
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => true, 'message' => $error];
        }

        return json_decode($response, true);
    }

    // 1- Takımın Belirli Bir Yılda Katıldığı Turnuvaları Getirir
    public function getTeamEvents($teamKey, $year) {
        // '/simple' uç noktasını kullanıyoruz ki veritabanını yormayacak hafif veriler gelsin
        return $this->request("/team/{$teamKey}/events/{$year}/simple");
    }

    // 2- Bir Turnuvaya Katılan Takımların Listesini Getirir
    public function getEventTeams($eventKey) {
        return $this->request("/event/{$eventKey}/teams/simple");
    }

    // 3- Takımın Belirli Bir Turnuvadaki Maçlarını Getirir
    public function getTeamEventMatches($teamKey, $eventKey) {
        return $this->request("/team/{$teamKey}/event/{$eventKey}/matches/simple");
    }

    /**
     * Get event rankings
     * @param string $eventKey
     * @return array
     */
    public function getEventRankings($eventKey) {
        return $this->request('event/' . $eventKey . '/rankings');
    }

    // 4- Takımın Belirli Bir Turnuvadaki DETAYLI Maçlarını Getirir (Videolar Dahil)
    public function getTeamEventMatchesDetailed($teamKey, $eventKey) {
        // Sonunda /simple YOK. Bu yüzden videolar ve detaylı skorlar gelir.
        return $this->request("/team/{$teamKey}/event/{$eventKey}/matches");
    }
}
?>
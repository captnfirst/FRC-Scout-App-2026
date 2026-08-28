<?php

class TBA {
    private $apiKey;
    private $baseUrl = 'https://www.thebluealliance.com/api/v3';

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Executes HTTP GET request to The Blue Alliance API v3
     */
    public function request($endpoint) {
        if (empty($this->apiKey)) {
            return ['error' => true, 'message' => 'TBA API key is not configured. Please enter your API key in Settings.'];
        }

        $url = $this->baseUrl . (strpos($endpoint, '/') === 0 ? $endpoint : '/' . $endpoint);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-TBA-Auth-Key: ' . $this->apiKey,
            'accept: application/json',
            'User-Agent: FRC-Scouting-App/2.0'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => true, 'message' => 'Connection error: ' . $error];
        }

        if ($httpCode === 401 || $httpCode === 403) {
            return ['error' => true, 'message' => 'Invalid TBA API Key. Please verify your API key in Settings.', 'http_code' => $httpCode];
        }

        if ($httpCode === 404) {
            return ['error' => true, 'message' => 'Requested team or event was not found on The Blue Alliance.', 'http_code' => 404];
        }

        $result = json_decode($response, true);
        if (is_array($result) && isset($result['Error'])) {
            return ['error' => true, 'message' => $result['Error']];
        }
        if (is_array($result) && isset($result['error'])) {
            return ['error' => true, 'message' => is_string($result['error']) ? $result['error'] : 'Unknown API error.'];
        }

        return $result;
    }

    /**
     * Test connection to TBA by checking team validity
     */
    public function testConnection($teamKey) {
        $teamKey = strtolower(trim($teamKey));
        if (!empty($teamKey) && is_numeric($teamKey)) {
            $teamKey = 'frc' . $teamKey;
        }
        $endpoint = "/team/{$teamKey}";
        $result = $this->request($endpoint);
        if (isset($result['error']) && $result['error']) {
            return ['success' => false, 'message' => $result['message']];
        }
        if (isset($result['key'])) {
            return ['success' => true, 'team_name' => $result['nickname'] ?? ($result['team_number'] ?? $teamKey)];
        }
        return ['success' => false, 'message' => 'Team not found or API key is invalid.'];
    }

    public function getTeamEvents($teamKey, $year) {
        return $this->request("/team/{$teamKey}/events/{$year}/simple");
    }

    public function getEventTeams($eventKey) {
        return $this->request("/event/{$eventKey}/teams/simple");
    }

    public function getTeamEventMatches($teamKey, $eventKey) {
        return $this->request("/team/{$teamKey}/event/{$eventKey}/matches/simple");
    }

    public function getEventRankings($eventKey) {
        return $this->request("/event/{$eventKey}/rankings");
    }

    public function getTeamEventMatchesDetailed($teamKey, $eventKey) {
        return $this->request("/team/{$teamKey}/event/{$eventKey}/matches");
    }

    public function getMatch($matchKey) {
        return $this->request("/match/{$matchKey}");
    }
}
?>
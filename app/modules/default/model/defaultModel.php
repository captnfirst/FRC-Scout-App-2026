<?php
class defaultModel extends Model
{
    public function getLoginModel(){
        $this->db->where("eposta", base64_encode($_POST['eposta']));
        $this->db->where("password", base64_encode($_POST['password']));
        $kullanici = $this->db->getOne('admin_score');

        if (isset($kullanici['admin_id'])) {
            $_SESSION['admin'] = $kullanici;
            return "ok";
        }
        return "kullanıcı yok";
    }

    public function adminekleModel(){
        $insert = array();
        $insert['name'] = $_POST['name'];
        $insert['eposta'] = base64_encode($_POST['eposta']);
        $insert['password'] = base64_encode($_POST['password']);
        $insert['administrator'] = $_POST['administrator'];
        $this->db->insert("admin_score", $insert);
    }

    public function getAdminModel(){
        return $this->db->get("admin_score");
    }

    public function deleteAdminModel($id){
        $this->db->where("admin_id", $id);
        return $this->db->delete("admin_score");
    }

    private $tba;

    public function __construct() {
        parent::__construct();

        require_once __DIR__ . '/../../../core/TBA.php';
        $apiKey = ''; // your api key
        $this->tba = new TBA($apiKey);
    }

    public function getTournamentsModel($teamKey, $year) {
        return $this->tba->getTeamEvents($teamKey, $year);
    }

    public function getTeamsModel($eventKey) {
        return $this->tba->getEventTeams($eventKey);
    }

    public function getMatchesModel($teamKey, $eventKey) {
        // TBA'den takımın tüm maçlarını çek
        $allMatches = $this->tba->getTeamEventMatches($teamKey, $eventKey);

        $qmMatches = [];

        if (!empty($allMatches) && is_array($allMatches)) {
            foreach ($allMatches as $match) {
                // Sadece 'qm' (Qualification Match / Eleme Maçı) olanları diziye ekle
                if (isset($match['comp_level']) && $match['comp_level'] === 'qm') {
                    $qmMatches[] = $match;
                }
            }
        }

        return $qmMatches;
    }

    public function getMatchesModelDetailed($teamKey, $eventKey) {
        return $this->tba->getTeamEventMatchesDetailed($teamKey, $eventKey);
    }

    public function saveScoutModel($postData) {
        $insertData = array(
            'tournament_key' => $postData['event_key'],
            'match_key'      => $postData['match_key'],
            'team_key'       => $postData['team_key'],
            'scout_name'     => isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : 'Bilinmeyen Scout',

            'auto_fuel'      => $postData['auto_fuel'],
            'auto_climb'     => isset($postData['auto_climb']) ? $postData['auto_climb'] : 'none',
            'auto_path'      => $postData['auto_path'],

            'teleop_fuel'    => $postData['teleop_fuel'],
            'teleop_shooting'=> isset($postData['teleop_shooting']) ? $postData['teleop_shooting'] : null,
            'teleop_climb'   => isset($postData['teleop_climb']) ? $postData['teleop_climb'] : 'none',
            'teleop_driver'  => isset($postData['teleop_driver']) ? $postData['teleop_driver'] : null,
            'teleop_robot_role'      => isset($postData['teleop_robot_role']) ? $postData['teleop_robot_role'] : null,
            'teleop_defense_quality' => isset($postData['teleop_defense_quality']) ? $postData['teleop_defense_quality'] : null
        );

        return $this->db->insert("scout_data", $insertData);
    }

    public function getScoutedMatchesModel($teamKey, $eventKey) {
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        $results = $this->db->get("scout_data");

        $scoutedMatches = [];
        if ($results) {
            foreach ($results as $row) {
                $scoutedMatches[] = $row['match_key'];
            }
        }
        return $scoutedMatches;
    }

    public function isMatchScoutedModel($matchKey, $teamKey) {
        $this->db->where("match_key", $matchKey);
        $this->db->where("team_key", $teamKey);
        $result = $this->db->getOne("scout_data");

        return $result ? true : false;
    }

    public function getPitScoutedTeamsModel($eventKey) {
        $this->db->where("tournament_key", $eventKey);
        $results = $this->db->get("pit_scout_data");

        $scoutedTeams = [];

        if (!empty($results) && is_array($results)) {
            foreach ($results as $row) {
                $scoutedTeams[] = $row['team_key'];
            }
        }

        return $scoutedTeams;
    }

    public function isTeamPitScoutedModel($teamKey, $eventKey) {
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        $result = $this->db->getOne("pit_scout_data");
        return $result ? true : false;
    }

    public function savePitScoutModel($postData, $fileData) {
        $photoPath = null;

        if (isset($fileData['robot_photo']) && $fileData['robot_photo']['error'] == 0) {

            // FİZİKSEL YOL: Sunucunun kök dizinini otomatik bulan, en güvenli yöntem
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/web/dist/img/pit_photos/';

            // Klasör yoksa oluştur
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Dosya uzantısını alıp yeni isim oluştur
            $fileExt = strtolower(pathinfo($fileData['robot_photo']['name'], PATHINFO_EXTENSION));
            // Güvenlik: Sadece resim formatlarına izin ver
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedExts)) {
                $newFileName = $postData['event_key'] . '_' . $postData['team_key'] . '_' . time() . '.' . $fileExt;
                $targetFilePath = $uploadDir . $newFileName;

                // Dosyayı sunucuya yükle
                if (move_uploaded_file($fileData['robot_photo']['tmp_name'], $targetFilePath)) {
                    // WEB YOLU: Veritabanına yazılacak ve tarayıcıda görünecek kısım
                    $photoPath = '/dist/img/pit_photos/' . $newFileName;
                }
            }
        }

        $insertData = array(
            'tournament_key'   => $postData['event_key'],
            'team_key'         => $postData['team_key'],
            'scout_name'       => isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : 'Scout',
            'robot_weight'     => $postData['robot_weight'],
            'robot_dimensions' => $postData['robot_dimensions'],
            'drivetrain_type'  => $postData['drivetrain_type'],
            'swerve_type'      => ($postData['drivetrain_type'] == 'swerve') ? $postData['swerve_type'] : null,
            'mechanism_type'   => $postData['mechanism_type'],
            'auto_climb'       => isset($postData['auto_climb']) ? 1 : 0,
            'teleop_climb'     => isset($postData['teleop_climb']) ? 1 : 0,
            'scout_comments'   => $postData['scout_comments'],
            'photo_path'       => $photoPath
        );

        return $this->db->insert("pit_scout_data", $insertData);
    }

    public function getStatboticsEPA($eventKey) {
        $url = "https://api.statbotics.io/v3/team_events?event=" . $eventKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $epaData = [];

        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                $teamKey = 'frc' . $item['team']; // frc formatına çeviriyoruz

                // Toplam EPA ve Auto EPA değerlerini alıyoruz
                $epaData[$teamKey] = [
                    'toplam_epa' => isset($item['epa']['total_points']['mean']) ? round($item['epa']['total_points']['mean'], 1) : 0,
                    'auto_epa'   => isset($item['epa']['auto_points']['mean']) ? round($item['epa']['auto_points']['mean'], 1) : 0
                ];
            }
        }
        return $epaData;
    }

    public function getEventScoutStatsModel($eventKey) {
        // SQL ile takımlara göre gruplayıp ortalamaları (AVG) ve toplamları (SUM) hesaplıyoruz
        $query = "SELECT team_key, 
                         COUNT(id) as match_count, 
                         AVG(auto_fuel) as avg_auto_fuel, 
                         AVG(teleop_fuel) as avg_teleop_fuel,
                         SUM(IF(teleop_climb != 'none', 1, 0)) as total_teleop_climb
                  FROM scout_data 
                  WHERE tournament_key = ? 
                  GROUP BY team_key";

        $results = $this->db->rawQuery($query, [$eventKey]);
        $stats = [];

        if ($results) {
            foreach ($results as $row) {
                $row['avg_auto_fuel'] = round($row['avg_auto_fuel'], 1);
                $row['avg_teleop_fuel'] = round($row['avg_teleop_fuel'], 1);
                $stats[$row['team_key']] = $row;
            }
        }
        return $stats;
    }

    public function getEventRankingsModel($eventKey) {
        // 1. Güvenlik: Event Key'in etrafındaki olası boşlukları temizle
        $eventKey = trim($eventKey);

        // 2. Doğrudan TBA v3 API Adresi
        $url = "https://www.thebluealliance.com/api/v3/event/" . $eventKey . "/rankings";
        $apiKey = ''; // your api

        // 3. cURL ile Bağlantı Kur
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-TBA-Auth-Key: ' . $apiKey,
            'accept: application/json'
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        $rankings = json_decode($response, true);
        $rankData = [];

        // Eğer API hata döndürdüyse veya boşsa direkt boş dizi döndür (Sistemin çökmesini engeller)
        if (empty($rankings) || !is_array($rankings) || isset($rankings['Error'])) {
            return $rankData;
        }

        // TBA sıralamaları 'rankings' anahtarı altında döner
        if (isset($rankings['rankings'])) {
            foreach ($rankings['rankings'] as $rank) {
                // frc1234 formatından sadece 1234 numarasını alıyoruz
                $teamKey = str_replace('frc', '', $rank['team_key']);

                // Record (W-L-T) boş gelme ihtimaline karşı GÜVENLİK
                $record = '0-0-0';
                if (isset($rank['record']) && is_array($rank['record'])) {
                    $record = $rank['record']['wins'] . '-' . $rank['record']['losses'] . '-' . $rank['record']['ties'];
                }

                $rankData[$teamKey] = [
                    'rank' => isset($rank['rank']) ? $rank['rank'] : '-',
                    'record' => $record,
                    'rp' => isset($rank['extra_stats'][0]) ? $rank['extra_stats'][0] : 0
                ];
            }
        }

        return $rankData;
    }

    public function getScoutMatchesByTeam($teamKey, $eventKey) {
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        $this->db->orderBy("id", "ASC");
        return $this->db->get("scout_data");
    }

    public function getPitDataByTeam($teamKey, $eventKey) {
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        return $this->db->getOne("pit_scout_data");
    }

    // --- SKOR AĞIRLIKLARI (AGR SCORE) ---

    // Ağırlıkları veritabanından çeker
    public function getScoreWeightsModel() {
        $this->db->where("id", 1);
        $result = $this->db->getOne("score_weights");

        // Eğer veritabanında henüz bir kayıt yoksa sistemin çökmemesi için varsayılan bir dizi döndürür
        if (!$result) {
            return ['epa' => 30, 'auto' => 20, 'teleop' => 40, 'climb' => 10];
        }
        return $result;
    }

    // Ağırlıkları veritabanında günceller
    public function updateScoreWeightsModel($postData) {
        $updateData = array(
            'epa'    => floatval($postData['epa_weight']),
            'auto'   => floatval($postData['auto_weight']),
            'teleop' => floatval($postData['teleop_weight']),
            'climb'  => floatval($postData['climb_weight'])
        );

        $this->db->where("id", 1);
        return $this->db->update("score_weights", $updateData);
    }

    // --- AGR SİMÜLATÖRÜ İÇİN VERİ ÇEKİCİLER ---

    // TBA'dan Seçilen Tek Bir Maçın Kırmızı ve Mavi İttifak Bilgilerini Çeker
    public function getSingleMatchModel($matchKey) {
        $url = "https://www.thebluealliance.com/api/v3/match/" . trim($matchKey);
        $apiKey = ''; // your api

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-TBA-Auth-Key: ' . $apiKey, 'accept: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    // Takımların sadece ortalamalarını değil, son maçlarındaki bozulma/defans durumlarını da çeker
    public function getSimulatorDataModel($eventKey) {
        $this->db->where("tournament_key", $eventKey);
        $this->db->orderBy("id", "ASC"); // Eskiden yeniye sırala ki son satır en güncel veriyi tutsun
        $results = $this->db->get("scout_data");

        $stats = [];
        if ($results) {
            foreach ($results as $row) {
                $tKey = $row['team_key'];
                if (!isset($stats[$tKey])) {
                    $stats[$tKey] = ['matches' => 0, 'auto_total' => 0, 'teleop_total' => 0, 'last_role' => '', 'last_defense' => ''];
                }
                $stats[$tKey]['matches']++;
                $stats[$tKey]['auto_total'] += $row['auto_fuel'];
                $stats[$tKey]['teleop_total'] += $row['teleop_fuel'];

                // En son maçın rolü ve defans kalitesi bellekte kalır
                $stats[$tKey]['last_role'] = $row['teleop_robot_role'];
                $stats[$tKey]['last_defense'] = $row['teleop_defense_quality'];
            }
        }
        return $stats;
    }
}
?>
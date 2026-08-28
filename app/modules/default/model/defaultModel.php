<?php
class defaultModel extends Model
{
    private $tba;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../../../core/TBA.php';
        $this->initTBA();
    }

    public function getCurrentTeamKey() {
        if (isset($_SESSION['admin']['team_number']) && !empty($_SESSION['admin']['team_number'])) {
            $team = trim($_SESSION['admin']['team_number']);
            if (is_numeric($team)) {
                return 'frc' . $team;
            }
            if (strpos($team, 'frc') !== 0) {
                return 'frc' . $team;
            }
            return strtolower($team);
        }
        return 'frc6459';
    }

    public function initTBA() {
        $settings = $this->getTeamSettingsModel($this->getCurrentTeamKey());
        $apiKey = !empty($settings['tba_api_key']) ? $settings['tba_api_key'] : '';
        $this->tba = new TBA($apiKey);
    }

    public function getTeamSettingsModel($teamKey = null) {
        if ($teamKey === null) {
            $teamKey = $this->getCurrentTeamKey();
        }
        $teamKey = strtolower(trim($teamKey));

        $this->db->where("team_key", $teamKey);
        $settings = $this->db->getOne("team_settings");

        if (!$settings) {
            $defaultSettings = [
                'team_key'    => $teamKey,
                'tba_api_key' => '',
                'active_year' => intval(date('Y'))
            ];
            $this->db->insert("team_settings", $defaultSettings);
            $this->db->where("team_key", $teamKey);
            $settings = $this->db->getOne("team_settings");
        }

        return $settings ?: [
            'team_key'    => $teamKey,
            'tba_api_key' => '',
            'active_year' => intval(date('Y'))
        ];
    }

    public function registerTeamModel($postData) {
        $teamNum = trim($postData['team_number'] ?? '');
        $name = htmlspecialchars(trim($postData['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $eposta = filter_var(trim($postData['eposta'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = trim($postData['password'] ?? '');
        $password_confirm = trim($postData['password_confirm'] ?? '');

        if (empty($teamNum) || empty($name) || empty($eposta) || empty($password)) {
            return ['success' => false, 'message' => 'Lütfen tüm alanları doldurun.'];
        }

        if (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Lütfen geçerli bir e-posta adresi girin.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Şifreniz en az 6 karakter olmalıdır.'];
        }

        if ($password !== $password_confirm) {
            return ['success' => false, 'message' => 'Şifreler birbiriyle eşleşmiyor.'];
        }

        $this->db->where("eposta", $eposta);
        $existsUser = $this->db->getOne("admin_score");
        if ($existsUser) {
            return ['success' => false, 'message' => 'Bu e-posta adresi ile zaten kayıtlı bir hesap var.'];
        }

        $cleanTeam = preg_replace('/[^0-9]/', '', $teamNum);
        if (empty($cleanTeam)) {
            return ['success' => false, 'message' => 'Lütfen geçerli bir takım numarası girin (örn: 6459).'];
        }
        $teamKey = 'frc' . $cleanTeam;

        $this->db->where("team_key", $teamKey);
        $teamSettings = $this->db->getOne("team_settings");
        if (!$teamSettings) {
            $this->db->insert("team_settings", [
                'team_key'    => $teamKey,
                'tba_api_key' => '',
                'active_year' => intval(date('Y'))
            ]);
        }

        $newUser = [
            'name'          => $name,
            'eposta'        => $eposta,
            'password'      => password_hash($password, PASSWORD_BCRYPT),
            'team_number'   => $teamKey,
            'administrator' => 1
        ];

        $insertId = $this->db->insert("admin_score", $newUser);
        if ($insertId) {
            $newUser['admin_id'] = $insertId;
            if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
                session_regenerate_id(true);
            }
            $_SESSION['admin'] = $newUser;
            $this->initTBA();
            return ['success' => true, 'message' => 'Kaydınız başarıyla tamamlandı!'];
        }

        return ['success' => false, 'message' => 'Kayıt sırasında veritabanı hatası oluştu.'];
    }

    public function updateTeamSettingsModel($postData) {
        $currentTeam = $this->getCurrentTeamKey();
        $apiKey = isset($postData['tba_api_key']) ? trim($postData['tba_api_key']) : '';
        $activeYear = intval(date('Y'));

        $this->db->where("team_key", $currentTeam);
        $exists = $this->db->getOne("team_settings");

        if ($exists) {
            $this->db->where("team_key", $currentTeam);
            $success = $this->db->update("team_settings", [
                'tba_api_key' => $apiKey,
                'active_year' => $activeYear
            ]);
        } else {
            $success = $this->db->insert("team_settings", [
                'team_key'    => $currentTeam,
                'tba_api_key' => $apiKey,
                'active_year' => $activeYear
            ]);
        }

        if ($success !== false) {
            $this->initTBA();
            return true;
        }
        return false;
    }

    public function getLoginModel(){
        $eposta = isset($_POST['eposta']) ? trim($_POST['eposta']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($eposta) || empty($password)) {
            return "Lütfen e-posta ve şifrenizi girin.";
        }

        // Support plain email and legacy base64 format
        $this->db->where("(eposta = ? OR eposta = ?)", [$eposta, base64_encode($eposta)]);
        $kullanici = $this->db->getOne('admin_score');

        if ($kullanici && isset($kullanici['admin_id'])) {
            $dbPass = $kullanici['password'];
            $passValid = false;

            // 1. Standard password_verify (bcrypt)
            if (password_verify($password, $dbPass)) {
                $passValid = true;
            } 
            // 2. Legacy password check and automatic bcrypt upgrade
            elseif ($dbPass === base64_encode($password) || $dbPass === $password) {
                $passValid = true;
                $newHash = password_hash($password, PASSWORD_BCRYPT);
                $this->db->where("admin_id", $kullanici['admin_id']);
                $this->db->update('admin_score', [
                    'password' => $newHash,
                    'eposta'   => $eposta
                ]);
                $kullanici['password'] = $newHash;
                $kullanici['eposta'] = $eposta;
            }

            if ($passValid) {
                if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
                    session_regenerate_id(true);
                }
                
                if (empty($kullanici['team_number'])) {
                    $kullanici['team_number'] = 'frc6459';
                }
                $_SESSION['admin'] = $kullanici;

                // Initialize team settings and TBA client
                $this->getTeamSettingsModel($kullanici['team_number']);
                $this->initTBA();

                return "ok";
            }
        }
        return "E-posta veya şifre hatalı!";
    }

    public function addMemberModel(){
        return $this->adminekleModel();
    }

    public function getMembersModel(){
        return $this->getAdminModel();
    }

    public function deleteMemberModel($id){
        return $this->deleteAdminModel($id);
    }

    public function adminekleModel(){
        $currentTeam = $this->getCurrentTeamKey();
        $rawPass = isset($_POST['password']) ? trim($_POST['password']) : '';

        $insert = array();
        $insert['name'] = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $insert['eposta'] = filter_var(trim($_POST['eposta'] ?? ''), FILTER_SANITIZE_EMAIL);
        $insert['password'] = password_hash($rawPass, PASSWORD_BCRYPT);
        $insert['team_number'] = $currentTeam;
        $insert['administrator'] = isset($_POST['administrator']) ? intval($_POST['administrator']) : 0;
        
        return $this->db->insert("admin_score", $insert);
    }

    public function getAdminModel(){
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("team_number", $currentTeam);
        return $this->db->get("admin_score");
    }

    public function deleteAdminModel($id){
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("admin_id", $id);
        $this->db->where("team_number", $currentTeam);
        return $this->db->delete("admin_score");
    }

    public function getTournamentsModel($teamKey = null, $year = null) {
        if ($teamKey === null) $teamKey = $this->getCurrentTeamKey();
        if ($year === null) {
            $year = intval(date('Y'));
        }
        return $this->tba->getTeamEvents($teamKey, $year);
    }

    public function getTeamsModel($eventKey) {
        return $this->tba->getEventTeams($eventKey);
    }

    public function getMatchesModel($teamKey, $eventKey) {
        $allMatches = $this->tba->getTeamEventMatches($teamKey, $eventKey);
        $qmMatches = [];

        if (!empty($allMatches) && is_array($allMatches)) {
            foreach ($allMatches as $match) {
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
        $currentTeam = $this->getCurrentTeamKey();
        $is_practice = (strpos($postData['match_key'], '_pm') !== false) ? 1 : 0;

        $def_quality = null;
        if (isset($postData['teleop_robot_role']) && $postData['teleop_robot_role'] === 'defans') {
            $def_quality = isset($postData['teleop_defense_quality']) ? $postData['teleop_defense_quality'] : null;
        }

        $insertData = array(
            'tournament_key'   => htmlspecialchars($postData['event_key'], ENT_QUOTES, 'UTF-8'),
            'match_key'        => htmlspecialchars($postData['match_key'], ENT_QUOTES, 'UTF-8'),
            'team_key'         => htmlspecialchars($postData['team_key'], ENT_QUOTES, 'UTF-8'),
            'scouted_by_team'  => $currentTeam,
            'is_practice'      => $is_practice,
            'scout_name'       => isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : 'Bilinmeyen Scout',

            'auto_fuel'        => intval($postData['auto_fuel'] ?? 0),
            'auto_climb'       => isset($postData['auto_climb']) ? $postData['auto_climb'] : 'none',
            'auto_path'        => $postData['auto_path'] ?? '',

            'teleop_fuel'      => intval($postData['teleop_fuel'] ?? 0),
            'teleop_climb'     => isset($postData['teleop_climb']) ? $postData['teleop_climb'] : 'none',

            'teleop_feed_quality'   => isset($postData['feed_quality']) ? $postData['feed_quality'] : null,
            'teleop_damper_quality' => isset($postData['damper_quality']) ? $postData['damper_quality'] : null,
            'cycle_speed'           => isset($postData['cycle_speed']) ? $postData['cycle_speed'] : null,

            'teleop_robot_role'      => isset($postData['teleop_robot_role']) ? $postData['teleop_robot_role'] : null,
            'teleop_defense_quality' => $def_quality,
            'driver_evasion'         => isset($postData['driver_evasion']) ? $postData['driver_evasion'] : null,

            'breakdown_reason'       => isset($postData['breakdown_reason']) ? $postData['breakdown_reason'] : null
        );

        return $this->db->insert("scout_data", $insertData);
    }

    public function getScoutedMatchesModel($teamKey, $eventKey) {
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        $this->db->where("scouted_by_team", $currentTeam);
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
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("match_key", $matchKey);
        $this->db->where("team_key", $teamKey);
        $this->db->where("scouted_by_team", $currentTeam);
        $result = $this->db->getOne("scout_data");

        return $result ? true : false;
    }

    public function getPitScoutedTeamsModel($eventKey) {
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("tournament_key", $eventKey);
        $this->db->where("scouted_by_team", $currentTeam);
        $results = $this->db->get("pit_scout_data");

        $scoutedTeams = [];
        if (!empty($results) && is_array($results)) {
            foreach ($results as $row) {
                if (!empty($row['robot_weight'])) {
                    $scoutedTeams[] = $row['team_key'];
                }
            }
        }
        return $scoutedTeams;
    }

    public function getAllPitDataForEventModel($eventKey) {
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("tournament_key", $eventKey);
        $this->db->where("scouted_by_team", $currentTeam);
        $results = $this->db->get("pit_scout_data");
        $pitData = [];
        if (!empty($results) && is_array($results)) {
            foreach ($results as $row) {
                $pitData[$row['team_key']] = $row;
            }
        }
        return $pitData;
    }

    public function isTeamPitScoutedModel($teamKey, $eventKey) {
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        $this->db->where("scouted_by_team", $currentTeam);
        $result = $this->db->getOne("pit_scout_data");

        if ($result && !empty($result['robot_weight'])) {
            return true;
        }
        return false;
    }

    public function savePitScoutModel($postData, $fileData) {
        $currentTeam = $this->getCurrentTeamKey();
        $photoPath = null;

        if (isset($fileData['robot_photo']) && $fileData['robot_photo']['error'] == 0) {
            $uploadDir = realpath(__DIR__ . '/../../../../') . '/dist/img/pit_photos/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExt = strtolower(pathinfo($fileData['robot_photo']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExt, $allowedExts)) {
                $newFileName = preg_replace('/[^a-zA-Z0-9_-]/', '', $postData['event_key']) . '_' . 
                               preg_replace('/[^a-zA-Z0-9_-]/', '', $postData['team_key']) . '_' . time() . '.' . $fileExt;
                $targetFilePath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileData['robot_photo']['tmp_name'], $targetFilePath)) {
                    $photoPath = '/dist/img/pit_photos/' . $newFileName;
                }
            }
        }

        $saveData = array(
            'scout_name'       => isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : 'Scout',
            'scouted_by_team'  => $currentTeam,
            'robot_weight'     => floatval($postData['robot_weight'] ?? 0),
            'robot_dimensions' => htmlspecialchars($postData['robot_dimensions'] ?? '', ENT_QUOTES, 'UTF-8'),
            'drivetrain_type'  => htmlspecialchars($postData['drivetrain_type'] ?? '', ENT_QUOTES, 'UTF-8'),
            'swerve_type'      => ($postData['drivetrain_type'] == 'swerve') ? htmlspecialchars($postData['swerve_type'] ?? '', ENT_QUOTES, 'UTF-8') : null,
            'mechanism_type'   => htmlspecialchars($postData['mechanism_type'] ?? '', ENT_QUOTES, 'UTF-8'),
            'hopper_capacity'  => isset($postData['hopper_capacity']) ? intval($postData['hopper_capacity']) : 0,
            'auto_climb'       => isset($postData['auto_climb']) ? 1 : 0,
            'teleop_climb'     => isset($postData['teleop_climb']) ? 1 : 0,
            'scout_comments'   => htmlspecialchars($postData['scout_comments'] ?? '', ENT_QUOTES, 'UTF-8')
        );

        if ($photoPath !== null) {
            $saveData['photo_path'] = $photoPath;
        }

        $this->db->where('team_key', $postData['team_key']);
        $this->db->where('tournament_key', $postData['event_key']);
        $this->db->where('scouted_by_team', $currentTeam);
        $exists = $this->db->getOne('pit_scout_data');

        if ($exists) {
            $this->db->where('team_key', $postData['team_key']);
            $this->db->where('tournament_key', $postData['event_key']);
            $this->db->where('scouted_by_team', $currentTeam);
            return $this->db->update("pit_scout_data", $saveData);
        } else {
            $saveData['tournament_key'] = $postData['event_key'];
            $saveData['team_key'] = $postData['team_key'];
            return $this->db->insert("pit_scout_data", $saveData);
        }
    }

    public function getStatboticsEPA($eventKey) {
        $eventKey = trim(strtolower($eventKey));
        $url = "https://api.statbotics.io/v3/team_events?event=" . $eventKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: FRC-Scouting-App/2.0'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $epaData = [];

        if ($httpCode === 200 && !empty($response)) {
            $data = json_decode($response, true);

            if (!empty($data) && is_array($data)) {
                foreach ($data as $item) {
                    if (!isset($item['team'])) {
                        continue;
                    }

                    $teamKey = 'frc' . $item['team'];

                    $totalEpa = 0;
                    if (isset($item['epa']['total_points']['mean'])) {
                        $totalEpa = $item['epa']['total_points']['mean'];
                    } elseif (isset($item['epa']['breakdown']['total_points'])) {
                        $totalEpa = $item['epa']['breakdown']['total_points'];
                    } elseif (isset($item['norm_epa']['current'])) {
                        $totalEpa = $item['norm_epa']['current'];
                    }

                    $autoEpa = 0;
                    if (isset($item['epa']['auto_points']['mean'])) {
                        $autoEpa = $item['epa']['auto_points']['mean'];
                    } elseif (isset($item['epa']['breakdown']['auto_points'])) {
                        $autoEpa = $item['epa']['breakdown']['auto_points'];
                    }

                    $epaData[$teamKey] = [
                        'toplam_epa' => round((float)$totalEpa, 1),
                        'auto_epa'   => round((float)$autoEpa, 1)
                    ];
                }
            }
        }

        return $epaData;
    }

    public function getEventScoutStatsModel($eventKey) {
        $currentTeam = $this->getCurrentTeamKey();
        $query = "SELECT team_key, 
                         COUNT(id) as match_count, 
                         AVG(auto_fuel) as avg_auto_fuel, 
                         AVG(teleop_fuel) as avg_teleop_fuel,
                         SUM(IF(teleop_climb != 'none', 1, 0)) as total_teleop_climb,
                         SUM(IF(teleop_robot_role = 'defans', 1, 0)) as defense_played_count,
                         SUM(IF(teleop_defense_quality = 'iyi', 1, 0)) as good_defense_count,
                         SUM(IF(teleop_defense_quality = 'orta', 1, 0)) as medium_defense_count,
                         SUM(IF(teleop_defense_quality = 'kötü', 1, 0)) as bad_defense_count,
                         SUM(IF(teleop_feed_quality = 'iyi', 1, 0)) as good_feed_count,
                         SUM(IF(teleop_feed_quality = 'orta', 1, 0)) as medium_feed_count,
                         SUM(IF(teleop_damper_quality = 'iyi', 1, 0)) as good_damper_count,
                         SUM(IF(teleop_damper_quality = 'orta', 1, 0)) as medium_damper_count
                  FROM scout_data 
                  WHERE tournament_key = ? AND is_practice = 0 AND scouted_by_team = ?
                  GROUP BY team_key";

        $results = $this->db->rawQuery($query, [$eventKey, $currentTeam]);
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
        $rankings = $this->tba->getEventRankings(trim($eventKey));
        $rankData = [];

        if (empty($rankings) || !is_array($rankings) || isset($rankings['Error']) || isset($rankings['error'])) {
            return $rankData;
        }

        if (isset($rankings['rankings'])) {
            foreach ($rankings['rankings'] as $rank) {
                $teamKey = str_replace('frc', '', $rank['team_key']);

                $record = '0-0-0';
                if (isset($rank['record']) && is_array($rank['record'])) {
                    $record = ($rank['record']['wins'] ?? 0) . '-' . ($rank['record']['losses'] ?? 0) . '-' . ($rank['record']['ties'] ?? 0);
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
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        $this->db->where("scouted_by_team", $currentTeam);
        $this->db->orderBy("id", "ASC");
        return $this->db->get("scout_data");
    }

    public function getPitDataByTeam($teamKey, $eventKey) {
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("team_key", $teamKey);
        $this->db->where("tournament_key", $eventKey);
        $this->db->where("scouted_by_team", $currentTeam);
        return $this->db->getOne("pit_scout_data");
    }

    public function getScoreWeightsModel() {
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("team_key", $currentTeam);
        $result = $this->db->getOne("score_weights");

        if (!$result) {
            $defaultWeights = [
                'team_key' => $currentTeam,
                'epa'      => 30,
                'auto'     => 20,
                'teleop'   => 40,
                'climb'    => 10
            ];
            $this->db->insert("score_weights", $defaultWeights);
            return $defaultWeights;
        }
        return $result;
    }

    public function updateScoreWeightsModel($postData) {
        $currentTeam = $this->getCurrentTeamKey();
        $updateData = array(
            'epa'    => floatval($postData['epa_weight'] ?? 30),
            'auto'   => floatval($postData['auto_weight'] ?? 20),
            'teleop' => floatval($postData['teleop_weight'] ?? 40),
            'climb'  => floatval($postData['climb_weight'] ?? 10)
        );

        $this->db->where("team_key", $currentTeam);
        $exists = $this->db->getOne("score_weights");

        if ($exists) {
            $this->db->where("team_key", $currentTeam);
            return $this->db->update("score_weights", $updateData);
        } else {
            $updateData['team_key'] = $currentTeam;
            return $this->db->insert("score_weights", $updateData);
        }
    }

    public function getSingleMatchModel($matchKey) {
        return $this->tba->getMatch(trim($matchKey));
    }

    public function getSimulatorDataModel($eventKey) {
        $currentTeam = $this->getCurrentTeamKey();
        $this->db->where("tournament_key", $eventKey);
        $this->db->where("scouted_by_team", $currentTeam);
        $this->db->orderBy("id", "ASC");
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

                $stats[$tKey]['last_role'] = $row['teleop_robot_role'];
                $stats[$tKey]['last_defense'] = $row['teleop_defense_quality'];
            }
        }
        return $stats;
    }

    public function insertPracticeMatch($data) {
        $matchKey = $data['tournament_id'] . "_pm" . $data['match_no'] . "_" . $data['team_id'];
        return $matchKey;
    }

    public function updateMentorDataModel($postData) {
        $currentTeam = $this->getCurrentTeamKey();
        $teamKey = $postData['team_key'];
        $eventKey = $postData['event_key'];

        $data = array(
            'scouted_by_team' => $currentTeam,
            'bps'             => floatval($postData['bps'] ?? 0),
            'mentor_comments' => htmlspecialchars($postData['mentor_comments'] ?? '', ENT_QUOTES, 'UTF-8')
        );

        $this->db->where('team_key', $teamKey);
        $this->db->where('tournament_key', $eventKey);
        $this->db->where('scouted_by_team', $currentTeam);
        $exists = $this->db->getOne('pit_scout_data');

        if ($exists) {
            $this->db->where('team_key', $teamKey);
            $this->db->where('tournament_key', $eventKey);
            $this->db->where('scouted_by_team', $currentTeam);
            return $this->db->update('pit_scout_data', $data);
        } else {
            $data['team_key'] = $teamKey;
            $data['tournament_key'] = $eventKey;
            return $this->db->insert('pit_scout_data', $data);
        }
    }

    // Password Reset Methods
    public function sendPasswordResetModel($email) {
        global $config;
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Please enter a valid email address.'];
        }

        $this->db->where("eposta", $email);
        $user = $this->db->getOne("admin_score");
        if (!$user) {
            return ['success' => false, 'message' => 'No account found matching this email address.'];
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // Token valid for 1 hour

        // Clear expired tokens for this email
        $this->db->where("eposta", $email);
        $this->db->delete("password_resets");

        // Insert new reset token
        $this->db->insert("password_resets", [
            'eposta'     => $email,
            'token'      => $token,
            'expires_at' => $expiresAt
        ]);

        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $resetLink = "{$protocol}{$host}/default/reset_password?token={$token}";

        // Send email via PHPMailer
        $mailSent = false;
        if (!empty($config['smtp']['username']) && !empty($config['smtp']['password'])) {
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = $config['smtp']['host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $config['smtp']['username'];
                $mail->Password   = $config['smtp']['password'];
                $mail->SMTPSecure = $config['smtp']['encryption'];
                $mail->Port       = $config['smtp']['port'];
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom($config['smtp']['from_email'], $config['smtp']['from_name']);
                $mail->addAddress($email, $user['name']);

                $mail->isHTML(true);
                $mail->Subject = 'FRC Scout App - Password Reset Request';
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
                        <h2 style='color: #1e3c72; text-align: center;'>FRC SCOUT APP</h2>
                        <p>Hello <strong>{$user['name']}</strong>,</p>
                        <p>We received a request to reset your password. You can set a new password by clicking the button below:</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='{$resetLink}' style='background: #1e3c72; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;'>Reset Password</a>
                        </div>
                        <p style='font-size: 0.85rem; color: #718096;'>This link expires in 1 hour. If you did not request this, please ignore this email.</p>
                    </div>
                ";
                $mail->send();
                $mailSent = true;
            } catch (\Exception $e) {
                $mailSent = false;
            }
        }

        if ($mailSent) {
            return [
                'success' => true,
                'message' => Lang::isTr() 
                    ? 'Şifre sıfırlama bağlantısı e-posta adresinize başarıyla gönderildi. Lütfen gelen kutunuzu (ve spam klasörünü) kontrol edin.' 
                    : 'A password reset link has been sent to your email address. Please check your inbox.'
            ];
        } else {
            return [
                'success' => false,
                'message' => Lang::isTr() 
                    ? 'E-posta servisi (SMTP) henüz yapılandırılmadığı için e-posta gönderilemedi. Lütfen sistem yöneticinizle iletişime geçin.' 
                    : 'Could not send reset email because SMTP service is not configured. Please contact the administrator.'
            ];
        }
    }

    public function verifyResetTokenModel($token) {
        if (empty($token)) return null;
        $this->db->where("token", trim($token));
        $this->db->where("expires_at >= NOW()");
        return $this->db->getOne("password_resets");
    }

    public function resetPasswordWithTokenModel($token, $newPassword) {
        $tokenRow = $this->verifyResetTokenModel($token);
        if (!$tokenRow) {
            return ['success' => false, 'message' => 'Invalid or expired reset token.'];
        }

        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'New password must be at least 6 characters.'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->db->where("eposta", $tokenRow['eposta']);
        $updated = $this->db->update("admin_score", ['password' => $hashedPassword]);

        if ($updated !== false) {
            $this->db->where("token", $token);
            $this->db->delete("password_resets");
            return ['success' => true, 'message' => 'Password reset successfully. You can now sign in.'];
        }

        return ['success' => false, 'message' => 'An error occurred while updating your password.'];
    }

    // Profile and User Management Methods
    public function updateProfileModel($userId, $postData) {
        $name = htmlspecialchars(trim($postData['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $eposta = filter_var(trim($postData['eposta'] ?? ''), FILTER_SANITIZE_EMAIL);

        if (empty($name) || empty($eposta) || !filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Please enter a valid name and email address.'];
        }

        // Check if email already exists for another user
        $this->db->where("eposta", $eposta);
        $this->db->where("admin_id", $userId, "!=");
        $exists = $this->db->getOne("admin_score");
        if ($exists) {
            return ['success' => false, 'message' => 'This email address is already registered to another account.'];
        }

        $this->db->where("admin_id", $userId);
        $updated = $this->db->update("admin_score", [
            'name'   => $name,
            'eposta' => $eposta
        ]);

        if ($updated !== false) {
            if (isset($_SESSION['admin']) && $_SESSION['admin']['admin_id'] == $userId) {
                $_SESSION['admin']['name'] = $name;
                $_SESSION['admin']['eposta'] = $eposta;
            }
            return ['success' => true, 'message' => 'Profile updated successfully.'];
        }
        return ['success' => false, 'message' => 'An error occurred while updating profile.'];
    }

    public function changePasswordModel($userId, $postData) {
        $currentPass = trim($postData['current_password'] ?? '');
        $newPass = trim($postData['new_password'] ?? '');
        $confirmPass = trim($postData['confirm_password'] ?? '');

        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            return ['success' => false, 'message' => 'Please fill in all password fields.'];
        }

        if (strlen($newPass) < 6) {
            return ['success' => false, 'message' => 'New password must be at least 6 characters.'];
        }

        if ($newPass !== $confirmPass) {
            return ['success' => false, 'message' => 'New passwords do not match.'];
        }

        $this->db->where("admin_id", $userId);
        $user = $this->db->getOne("admin_score");
        if (!$user || !password_verify($currentPass, $user['password'])) {
            return ['success' => false, 'message' => 'Incorrect current password.'];
        }

        $this->db->where("admin_id", $userId);
        $updated = $this->db->update("admin_score", [
            'password' => password_hash($newPass, PASSWORD_BCRYPT)
        ]);

        if ($updated !== false) {
            return ['success' => true, 'message' => 'Password changed successfully.'];
        }
        return ['success' => false, 'message' => 'An error occurred while changing password.'];
    }

    // Team Transfer & Join Request Methods
    public function requestTeamTransferModel($userId, $postData) {
        $targetTeamRaw = trim($postData['target_team'] ?? '');
        $cleanTarget = preg_replace('/[^0-9]/', '', $targetTeamRaw);
        if (empty($cleanTarget)) {
            return ['success' => false, 'message' => 'Please enter a valid target team number (e.g. 9483).'];
        }
        $targetTeam = 'frc' . $cleanTarget;
        $note = htmlspecialchars(trim($postData['request_note'] ?? ''), ENT_QUOTES, 'UTF-8');

        $this->db->where("admin_id", $userId);
        $user = $this->db->getOne("admin_score");
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        if (strtolower($user['team_number']) === strtolower($targetTeam)) {
            return ['success' => false, 'message' => 'You are already a member of this team.'];
        }

        // Check if the target team exists in the system (i.e. has at least 1 registered user/admin)
        $this->db->where("LOWER(team_number)", strtolower($targetTeam));
        $targetTeamMember = $this->db->getOne("admin_score");
        if (!$targetTeamMember) {
            return [
                'success' => false, 
                'message' => Lang::isTr() 
                    ? "FRC {$cleanTarget} takımı sistemde henüz kayıtlı değil. Transfer talebi gönderebilmek için önce bu takımın açılmış/kayıt edilmiş olması gerekmektedir." 
                    : "FRC {$cleanTarget} is not registered in the system yet. A team representative must register first."
            ];
        }

        // Check if there is already a pending transfer request
        $this->db->where("user_id", $userId);
        $this->db->where("target_team", $targetTeam);
        $this->db->where("status", "pending");
        $existsPending = $this->db->getOne("team_join_requests");
        if ($existsPending) {
            return [
                'success' => false, 
                'message' => Lang::isTr() 
                    ? 'Bu takıma ait zaten bekleyen bir transfer talebiniz bulunmaktadır.' 
                    : 'You already have a pending transfer request for this team.'
            ];
        }

        $insertId = $this->db->insert("team_join_requests", [
            'user_id'      => $userId,
            'user_name'    => $user['name'],
            'user_email'   => $user['eposta'],
            'current_team' => $user['team_number'],
            'target_team'  => $targetTeam,
            'status'       => 'pending',
            'request_note' => $note
        ]);

        if ($insertId) {
            return [
                'success' => true, 
                'message' => Lang::isTr() 
                    ? "Transfer talebi FRC {$cleanTarget} takım yöneticisine iletildi. Onaylandığında takımınız güncellenecektir." 
                    : "Transfer request sent to FRC {$cleanTarget} administrator. Your membership will update upon approval."
            ];
        }
        return [
            'success' => false, 
            'message' => Lang::isTr() 
                ? 'Transfer talebi oluşturulurken bir hata meydana geldi.' 
                : 'An error occurred while submitting transfer request.'
        ];
    }

    public function getUserTransferRequestsModel($userId) {
        $this->db->where("user_id", $userId);
        $this->db->orderBy("id", "DESC");
        return $this->db->get("team_join_requests") ?: [];
    }

    public function getPendingTransferRequestsModel($teamKey) {
        $teamKey = strtolower(trim($teamKey));
        $this->db->where("target_team", $teamKey);
        $this->db->where("status", "pending");
        $this->db->orderBy("id", "DESC");
        return $this->db->get("team_join_requests") ?: [];
    }

    public function approveTransferRequestModel($requestId, $adminTeamKey) {
        $adminTeamKey = strtolower(trim($adminTeamKey));
        $this->db->where("id", $requestId);
        $this->db->where("target_team", $adminTeamKey);
        $this->db->where("status", "pending");
        $request = $this->db->getOne("team_join_requests");

        if (!$request) {
            return ['success' => false, 'message' => Lang::isTr() ? 'Bekleyen bir transfer talebi bulunamadı.' : 'No pending transfer request found.'];
        }

        // Approve transfer request
        $this->db->where("id", $requestId);
        $this->db->update("team_join_requests", ['status' => 'approved']);

        // Update user team to target team
        $this->db->where("admin_id", $request['user_id']);
        $this->db->update("admin_score", [
            'team_number'   => $adminTeamKey,
            'administrator' => 0
        ]);

        return ['success' => true, 'message' => Lang::isTr() ? "{$request['user_name']} takımınıza başarıyla dahil edildi!" : "{$request['user_name']} has been approved into your team!"];
    }

    public function rejectTransferRequestModel($requestId, $adminTeamKey) {
        $adminTeamKey = strtolower(trim($adminTeamKey));
        $this->db->where("id", $requestId);
        $this->db->where("target_team", $adminTeamKey);
        $this->db->where("status", "pending");
        $request = $this->db->getOne("team_join_requests");

        if (!$request) {
            return ['success' => false, 'message' => Lang::isTr() ? 'Bekleyen bir transfer talebi bulunamadı.' : 'No pending transfer request found.'];
        }

        $this->db->where("id", $requestId);
        $this->db->update("team_join_requests", ['status' => 'rejected']);

        return ['success' => true, 'message' => Lang::isTr() ? 'Transfer talebi reddedildi.' : 'Transfer request has been rejected.'];
    }
}
?>
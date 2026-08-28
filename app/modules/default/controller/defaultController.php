<?php
class defaultController extends Controller {
    public function loginAction(){
        $data = array();
        if (isset($_POST['eposta']) && isset($_POST['password'])) {
            $defaultModel = new defaultModel();
            $result = $defaultModel->getLoginModel();

            if ($result == "ok") {
                Controller::redirect("/default/index");
                exit;
            } else {
                $data['msg'] = $result;
            }
        }
        $this->RenderLayout("login","default","login",$data);
    }

    public function registerAction(){
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $defaultModel = new defaultModel();
            $result = $defaultModel->registerTeamModel($_POST);

            if ($result['success']) {
                Controller::redirect("/default/settings");
                exit;
            } else {
                $data['msg'] = $result['message'];
            }
        }
        $this->RenderLayout("login","default","register",$data);
    }

    public function forgot_passwordAction(){
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eposta'])) {
            $defaultModel = new defaultModel();
            $result = $defaultModel->sendPasswordResetModel($_POST['eposta']);
            $data['result'] = $result;
        }
        $this->RenderLayout("login","default","forgot_password",$data);
    }

    public function reset_passwordAction($tokenParam = null){
        $data = array();
        $token = !empty($tokenParam) ? trim($tokenParam) : (isset($_GET['token']) ? trim($_GET['token']) : (isset($_POST['token']) ? trim($_POST['token']) : ''));
        $defaultModel = new defaultModel();
        
        $tokenRow = $defaultModel->verifyResetTokenModel($token);
        if (!$tokenRow) {
            $data['error'] = "Invalid or expired password reset link. Please request a new link.";
        } else {
            $data['token'] = $token;
            $data['eposta'] = $tokenRow['eposta'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
                $newPass = $_POST['password'];
                $confirmPass = $_POST['password_confirm'] ?? '';

                if ($newPass !== $confirmPass) {
                    $data['msg_error'] = "Passwords do not match.";
                } else {
                    $res = $defaultModel->resetPasswordWithTokenModel($token, $newPass);
                    if ($res['success']) {
                        Controller::redirect("/default/login?reset_success=1");
                        exit;
                    } else {
                        $data['msg_error'] = $res['message'];
                    }
                }
            }
        }
        $this->RenderLayout("login","default","reset_password",$data);
    }

    public function profileAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $userId = $_SESSION['admin']['admin_id'] ?? 0;
        
        $data['user_requests'] = $defaultModel->getUserTransferRequestsModel($userId);
        $data['current_team'] = $defaultModel->getCurrentTeamKey();
        $this->RenderLayout("score","default","profile",$data);
    }

    public function update_profileAction(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $defaultModel = new defaultModel();
            $userId = $_SESSION['admin']['admin_id'] ?? 0;
            $res = $defaultModel->updateProfileModel($userId, $_POST);
            $_SESSION['profile_flash'] = $res;
        }
        Controller::redirect("/default/profile");
        exit;
    }

    public function change_passwordAction(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $defaultModel = new defaultModel();
            $userId = $_SESSION['admin']['admin_id'] ?? 0;
            $res = $defaultModel->changePasswordModel($userId, $_POST);
            $_SESSION['password_flash'] = $res;
        }
        Controller::redirect("/default/profile");
        exit;
    }

    public function request_transferAction(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $defaultModel = new defaultModel();
            $userId = $_SESSION['admin']['admin_id'] ?? 0;
            $res = $defaultModel->requestTeamTransferModel($userId, $_POST);
            $_SESSION['transfer_flash'] = $res;
        }
        Controller::redirect("/default/profile");
        exit;
    }

    public function approve_transferAction($requestId){
        $defaultModel = new defaultModel();
        $currentTeam = $defaultModel->getCurrentTeamKey();
        $res = $defaultModel->approveTransferRequestModel(intval($requestId), $currentTeam);
        $_SESSION['admin_flash'] = $res;
        Controller::redirect("/default/members");
        exit;
    }

    public function reject_transferAction($requestId){
        $defaultModel = new defaultModel();
        $currentTeam = $defaultModel->getCurrentTeamKey();
        $res = $defaultModel->rejectTransferRequestModel(intval($requestId), $currentTeam);
        $_SESSION['admin_flash'] = $res;
        Controller::redirect("/default/members");
        exit;
    }

    public function logoutAction(){
        session_destroy();
        Controller::redirect("/default/login");
        exit;
    }

    public function set_languageAction($lang = 'tr'){
        $lang = strtolower(trim($lang));
        if (in_array($lang, ['tr', 'en'])) {
            $_SESSION['lang'] = $lang;
            setcookie('lang', $lang, time() + (86400 * 30), "/");
        }
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/default/index';
        Controller::redirect($referer);
        exit;
    }

    public function membersAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $currentTeam = $defaultModel->getCurrentTeamKey();
        $data['admin'] = $defaultModel->getAdminModel();
        $data['current_team'] = $currentTeam;
        $data['pending_requests'] = $defaultModel->getPendingTransferRequestsModel($currentTeam);
        $this->RenderLayout("score","default","members",$data);
    }

    public function adminekleAction(){
        $this->membersAction();
    }

    public function add_memberAction(){
        $defaultModel = new defaultModel();
        $defaultModel->adminekleModel();
        Controller::redirect("/default/members");
        exit;
    }

    public function addAdminAction(){
        $this->add_memberAction();
    }

    public function delete_memberAction($id){
        $defaultModel = new defaultModel();
        $defaultModel->deleteAdminModel($id);
        Controller::redirect("/default/members");
        exit;
    }

    public function deleteadminAction($id){
        $this->delete_memberAction($id);
    }

    public function indexAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $data['settings'] = $defaultModel->getTeamSettingsModel();
        $this->RenderLayout("score","default","index",$data);
    }

    public function tournamentsAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $settings = $defaultModel->getTeamSettingsModel();
        $currentYear = intval(date('Y'));
        $data['tournaments'] = $defaultModel->getTournamentsModel($settings['team_key'], $currentYear);
        $data['active_team'] = $settings['team_key'];
        $data['active_year'] = $currentYear;
        $this->RenderLayout("score", "default", "tournaments", $data);
    }

    public function teamsAction($eventKey = null){
        $data = array();
        if (!$eventKey) die("No event selected!");

        $defaultModel = new defaultModel();
        $data['takimlar'] = $defaultModel->getTeamsModel($eventKey);
        $data['secilen_turnuva'] = $eventKey;

        $this->RenderLayout("score", "default", "teams", $data);
    }

    public function matchesAction($teamKey = null, $eventKey = null){
        $data = array();
        if (!$teamKey || !$eventKey) die("Team or event not specified!");

        $defaultModel = new defaultModel();
        $data['maclar'] = $defaultModel->getMatchesModel($teamKey, $eventKey);
        $data['secilen_takim'] = $teamKey;
        $data['secilen_turnuva'] = $eventKey;
        $data['scouted_matches'] = $defaultModel->getScoutedMatchesModel($teamKey, $eventKey);

        $this->RenderLayout("score", "default", "matches", $data);
    }

    public function scoutAction($matchKey = null, $teamKey = null, $eventKey = null){
        $data = array();
        if (!$matchKey || !$teamKey) die("Match or team not specified!");

        $defaultModel = new defaultModel();

        if ($defaultModel->isMatchScoutedModel($matchKey, $teamKey)) {
            Controller::redirect("/default/matches/" . $teamKey . "/" . $eventKey);
            exit;
        }

        $data['match_key'] = $matchKey;
        $data['team_key'] = $teamKey;
        $data['event_key'] = $eventKey;
        $data['secilen_takim'] = $teamKey;
        $data['secilen_turnuva'] = $eventKey;

        $this->RenderLayout("score", "default", "scout", $data);
    }

    public function savescoutAction(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $defaultModel = new defaultModel();

            $teamKey = $_POST['team_key'];
            $matchKey = $_POST['match_key'];
            $eventKey = $_POST['event_key'];

            if ($defaultModel->isMatchScoutedModel($matchKey, $teamKey)) {
                Controller::redirect("/default/matches/" . $teamKey . "/" . $eventKey);
                exit;
            }

            $kayitDurumu = $defaultModel->saveScoutModel($_POST);

            if ($kayitDurumu) {
                Controller::redirect("/default/matches/" . $teamKey . "/" . $eventKey);
                exit;
            } else {
                die("An error occurred while saving scout data to database!");
            }
        } else {
            Controller::redirect("/default/index");
            exit;
        }
    }

    public function pit_tournamentsAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $settings = $defaultModel->getTeamSettingsModel();
        $currentYear = intval(date('Y'));
        $data['tournaments'] = $defaultModel->getTournamentsModel($settings['team_key'], $currentYear);
        $data['active_team'] = $settings['team_key'];
        $data['active_year'] = $currentYear;
        $this->RenderLayout("score", "default", "pit_tournaments", $data);
    }

    public function pit_teamsAction($eventKey = null){
        $data = array();
        if (!$eventKey) die("No event selected!");

        $defaultModel = new defaultModel();
        $data['takimlar'] = $defaultModel->getTeamsModel($eventKey);
        $data['secilen_turnuva'] = $eventKey;
        $data['pit_scouted_teams'] = $defaultModel->getPitScoutedTeamsModel($eventKey);

        $this->RenderLayout("score", "default", "pit_teams", $data);
    }

    public function pit_scoutAction($teamKey = null, $eventKey = null){
        $data = array();
        if (!$teamKey || !$eventKey) die("Team or event not specified!");

        $defaultModel = new defaultModel();
        if ($defaultModel->isTeamPitScoutedModel($teamKey, $eventKey)) {
            Controller::redirect("/default/pit_teams/" . $eventKey);
            exit;
        }

        $data['team_key'] = $teamKey;
        $data['event_key'] = $eventKey;

        $this->RenderLayout("score", "default", "pit_scout", $data);
    }

    public function savepitscoutAction(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $defaultModel = new defaultModel();
            $teamKey = $_POST['team_key'];
            $eventKey = $_POST['event_key'];

            if ($defaultModel->isTeamPitScoutedModel($teamKey, $eventKey)) {
                Controller::redirect("/default/pit_teams/" . $eventKey);
                exit;
            }

            $kayitDurumu = $defaultModel->savePitScoutModel($_POST, $_FILES);

            if ($kayitDurumu) {
                Controller::redirect("/default/pit_teams/" . $eventKey);
                exit;
            } else {
                die("Pit scout record could not be saved!");
            }
        }
    }

    public function analysis_tournaments_listAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $settings = $defaultModel->getTeamSettingsModel();
        $currentYear = intval(date('Y'));
        $data['tournaments'] = $defaultModel->getTournamentsModel($settings['team_key'], $currentYear);
        $data['active_team'] = $settings['team_key'];
        $data['active_year'] = $currentYear;
        $this->RenderLayout("score", "default", "analysis_tournaments_list", $data);
    }

    public function analysis_tournamentAction($eventKey = null){
        $data = array();
        if (!$eventKey) die("No event selected!");

        $defaultModel = new defaultModel();
        $data['takimlar'] = $defaultModel->getTeamsModel($eventKey);
        $data['epa_data'] = $defaultModel->getStatboticsEPA($eventKey);
        $data['scout_stats'] = $defaultModel->getEventScoutStatsModel($eventKey);
        $data['live_rankings'] = $defaultModel->getEventRankingsModel($eventKey);
        $data['pit_data'] = $defaultModel->getAllPitDataForEventModel($eventKey);
        $data['weights'] = $defaultModel->getScoreWeightsModel();
        $data['secilen_turnuva'] = $eventKey;

        $this->RenderLayout("score", "default", "analysis_tournament", $data);
    }

    public function team_analysisAction($teamKey = null, $eventKey = null){
        $data = array();
        if (!$teamKey || !$eventKey) die("Team or event not specified!");

        $defaultModel = new defaultModel();
        $teams = $defaultModel->getTeamsModel($eventKey);
        if (!empty($teams) && is_array($teams)) {
            foreach($teams as $t) {
                if(isset($t['key']) && $t['key'] === $teamKey) {
                    $data['team_info'] = $t;
                    break;
                }
            }
        }

        $data['tba_matches'] = $defaultModel->getMatchesModelDetailed($teamKey, $eventKey);
        $data['scout_matches'] = $defaultModel->getScoutMatchesByTeam($teamKey, $eventKey);
        $data['pit_data'] = $defaultModel->getPitDataByTeam($teamKey, $eventKey);

        $data['team_key'] = $teamKey;
        $data['event_key'] = $eventKey;
        $data['secilen_takim'] = $teamKey;
        $data['secilen_turnuva'] = $eventKey;

        $this->RenderLayout("score", "default", "team_analysis", $data);
    }

    public function score_weightsAction() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin']['administrator'] != 1) {
            Controller::redirect("/default/index");
            exit;
        }

        $data = array();
        $defaultModel = new defaultModel();
        $data['weights'] = $defaultModel->getScoreWeightsModel();

        $this->RenderLayout("score", "default", "score_weights", $data);
    }

    public function save_weightsAction() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin']['administrator'] != 1) {
            Controller::redirect("/default/index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $defaultModel = new defaultModel();
            $kayitDurumu = $defaultModel->updateScoreWeightsModel($_POST);

            if ($kayitDurumu) {
                Controller::redirect("/default/score_weights");
                exit;
            } else {
                die("An error occurred while saving algorithm weights!");
            }
        } else {
            Controller::redirect("/default/index");
            exit;
        }
    }

    public function simulatorAction($matchKey = null, $eventKey = null){
        if (!$matchKey || !$eventKey) die("Match or event not specified!");

        $defaultModel = new defaultModel();
        $data['match_details'] = $defaultModel->getSingleMatchModel($matchKey);
        $data['epa_data'] = $defaultModel->getStatboticsEPA($eventKey);
        $data['scout_data'] = $defaultModel->getSimulatorDataModel($eventKey);
        $data['scout_stats'] = $defaultModel->getEventScoutStatsModel($eventKey);
        $data['pit_data'] = $defaultModel->getAllPitDataForEventModel($eventKey);
        $data['takimlar'] = $defaultModel->getTeamsModel($eventKey);

        $data['match_key'] = $matchKey;
        $data['event_key'] = $eventKey;

        $this->RenderLayout("score", "default", "simulator", $data);
    }

    public function addPracticeMatchAction() {
        if ($_POST) {
            $tournament_id = $_POST['tournament_id'];
            $team_id = $_POST['team_id'];
            $match_no = $_POST['match_no'];

            $match_key = $tournament_id . "_pm" . $match_no;

            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "match_key" => $match_key,
                "team_key" => $team_id,
                "event_key" => $tournament_id
            ]);
            exit;
        }
    }

    public function updateMentorDataAction() {
        if ($_POST) {
            $defaultModel = new defaultModel();
            $result = $defaultModel->updateMentorDataModel($_POST);

            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error!']);
            }
            exit;
        }
    }

    public function settingsAction() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin']['administrator'] != 1) {
            Controller::redirect("/default/index");
            exit;
        }

        $data = array();
        $defaultModel = new defaultModel();
        $data['settings'] = $defaultModel->getTeamSettingsModel();

        if (isset($_SESSION['settings_msg'])) {
            $data['msg'] = $_SESSION['settings_msg'];
            unset($_SESSION['settings_msg']);
        }

        $this->RenderLayout("score", "default", "settings", $data);
    }

    public function save_settingsAction() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin']['administrator'] != 1) {
            Controller::redirect("/default/index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $defaultModel = new defaultModel();
            $success = $defaultModel->updateTeamSettingsModel($_POST);

            if ($success) {
                $_SESSION['settings_msg'] = [
                    'type' => 'success',
                    'text' => 'Team settings and API key updated successfully!'
                ];
            } else {
                $_SESSION['settings_msg'] = [
                    'type' => 'danger',
                    'text' => 'An error occurred while updating settings!'
                ];
            }
            Controller::redirect("/default/settings");
            exit;
        } else {
            Controller::redirect("/default/index");
            exit;
        }
    }

    public function test_tbaAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $apiKey = trim($_POST['tba_api_key'] ?? '');
            $teamKey = trim($_POST['team_key'] ?? '');
            if (is_numeric($teamKey)) {
                $teamKey = 'frc' . $teamKey;
            }

            require_once __DIR__ . '/../../../core/TBA.php';
            $tba = new TBA($apiKey);
            $result = $tba->testConnection($teamKey);

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    }
}
?>
<?php
class defaultController extends Controller {
    public function loginAction(){
        $data = array();
        if (isset($_POST['eposta']) && isset($_POST['password'])) {
            $defaultModel = new defaultModel();
            $result = $defaultModel->getLoginModel();

            if ($result == "ok") {
                Controller::redirect("/default/index");
            }else{
                $data['msg'] = $result;
            }
        }
        $this->RenderLayout("login","default","login",$data);
    }

    public function logoutAction(){
        session_destroy();
        Controller::redirect("/default/login");
    }

    public function adminekleAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $data['admin'] = $defaultModel->getAdminModel();
        $this->RenderLayout("score","default","adminekle",$data);
    }

    public function addAdminAction(){
        $defaultModel = new defaultModel();
        $defaultModel->adminekleModel();
        Controller::redirect("/default/adminekle");
    }

    public function deleteadminAction($id){
        $defaultModel = new defaultModel();
        $defaultModel->deleteAdminModel($id);
        Controller::redirect("/default/adminekle");
    }

    public function indexAction(){
        $data = array();
        $this->RenderLayout("score","default","index",$data);
    }

    public function tournamentsAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $data['tournaments'] = $defaultModel->getTournamentsModel('frc6459', 2026);
        $this->RenderLayout("score", "default", "tournaments", $data);
    }

    public function teamsAction($eventKey = null){
        $data = array();
        if (!$eventKey) die("Turnuva seçilmedi!");

        $defaultModel = new defaultModel();
        $data['takimlar'] = $defaultModel->getTeamsModel($eventKey);
        $data['secilen_turnuva'] = $eventKey;

        $this->RenderLayout("score", "default", "teams", $data);
    }


    public function matchesAction($teamKey = null, $eventKey = null){
        $data = array();
        if (!$teamKey || !$eventKey) die("Takım veya Turnuva seçilmedi!");

        $defaultModel = new defaultModel();
        $data['maclar'] = $defaultModel->getMatchesModel($teamKey, $eventKey);
        $data['secilen_takim'] = $teamKey;
        $data['secilen_turnuva'] = $eventKey;

        $data['scouted_matches'] = $defaultModel->getScoutedMatchesModel($teamKey, $eventKey);

        $this->RenderLayout("score", "default", "matches", $data);
    }

    public function scoutAction($matchKey = null, $teamKey = null, $eventKey = null){
        $data = array();
        if (!$matchKey || !$teamKey) die("Maç veya Takım seçilmedi!");

        $defaultModel = new defaultModel();

        if ($defaultModel->isMatchScoutedModel($matchKey, $teamKey)) {
            Controller::redirect("/default/matches/" . $teamKey . "/" . $eventKey);
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
            }

            $kayitDurumu = $defaultModel->saveScoutModel($_POST);

            if ($kayitDurumu) {
                Controller::redirect("/default/matches/" . $teamKey . "/" . $eventKey);
            } else {
                die("Veriler kaydedilirken veritabanında bir hata oluştu!");
            }
        } else {
            Controller::redirect("/default/index");
        }
    }

    public function pit_tournamentsAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $data['tournaments'] = $defaultModel->getTournamentsModel('frc6459', 2026);
        $this->RenderLayout("score", "default", "pit_tournaments", $data);
    }

    public function pit_teamsAction($eventKey = null){
        $data = array();
        if (!$eventKey) die("Turnuva seçilmedi!");

        $defaultModel = new defaultModel();
        $data['takimlar'] = $defaultModel->getTeamsModel($eventKey);
        $data['secilen_turnuva'] = $eventKey;
        $data['pit_scouted_teams'] = $defaultModel->getPitScoutedTeamsModel($eventKey);

        $this->RenderLayout("score", "default", "pit_teams", $data);
    }

    public function pit_scoutAction($teamKey = null, $eventKey = null){
        $data = array();
        if (!$teamKey || !$eventKey) die("Takım veya Turnuva seçilmedi!");

        $defaultModel = new defaultModel();
        if ($defaultModel->isTeamPitScoutedModel($teamKey, $eventKey)) {
            Controller::redirect("/default/pit_teams/" . $eventKey);
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
            }

            $kayitDurumu = $defaultModel->savePitScoutModel($_POST, $_FILES);

            if ($kayitDurumu) {
                Controller::redirect("/default/pit_teams/" . $eventKey);
            } else {
                die("Pit verisi kaydedilemedi!");
            }
        }
    }

    public function analysis_tournaments_listAction(){
        $data = array();
        $defaultModel = new defaultModel();
        $data['tournaments'] = $defaultModel->getTournamentsModel('frc6459', 2026);
        $this->RenderLayout("score", "default", "analysis_tournaments_list", $data);
    }

    public function analysis_tournamentAction($eventKey = null){
        $data = array();
        if (!$eventKey) die("Turnuva seçilmedi!");

        $defaultModel = new defaultModel();

        $data['takimlar'] = $defaultModel->getTeamsModel($eventKey);

        $data['epa_data'] = $defaultModel->getStatboticsEPA($eventKey);

        $data['scout_stats'] = $defaultModel->getEventScoutStatsModel($eventKey);

        $data['live_rankings'] = $defaultModel->getEventRankingsModel($eventKey);

        $data['weights'] = $defaultModel->getScoreWeightsModel();

        $data['secilen_turnuva'] = $eventKey;

        $this->RenderLayout("score", "default", "analysis_tournament", $data);
    }

    public function team_analysisAction($teamKey = null, $eventKey = null){
        $data = array();
        if (!$teamKey || !$eventKey) die("Takım veya Turnuva seçilmedi!");

        $defaultModel = new defaultModel();

        $teams = $defaultModel->getTeamsModel($eventKey);
        foreach($teams as $t) {
            if($t['key'] === $teamKey) {
                $data['team_info'] = $t;
                break;
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
            } else {
                die("Ağırlıklar kaydedilirken veritabanında bir hata oluştu!");
            }
        } else {
            Controller::redirect("/default/index");
        }
    }

    public function simulatorAction($matchKey = null, $eventKey = null){
        if (!$matchKey || !$eventKey) die("Maç veya Turnuva seçilmedi!");

        $defaultModel = new defaultModel();

        $data['match_details'] = $defaultModel->getSingleMatchModel($matchKey);

        $data['epa_data'] = $defaultModel->getStatboticsEPA($eventKey);

        $data['scout_data'] = $defaultModel->getSimulatorDataModel($eventKey);

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
}
?>
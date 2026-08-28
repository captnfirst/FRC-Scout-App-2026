<style>
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white; border-radius: 12px; padding: 20px 25px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .back-btn-custom {
        background-color: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px; padding: 8px 16px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;
    }
    .back-btn-custom:hover { background-color: white; color: #1e3c72; }

    .alliance-card { border: none; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); overflow: hidden; }
    .card-red { border-top: 6px solid #dc3545; }
    .card-blue { border-top: 6px solid #0d6efd; }

    .team-badge {
        font-size: 1.1rem;
        font-weight: 800;
        border-radius: 8px;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .team-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        text-decoration: none;
    }

    .team-red { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid #dc3545; }
    .team-red:hover { background-color: #dc3545; color: white; }

    .team-blue { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; border: 1px solid #0d6efd; }
    .team-blue:hover { background-color: #0d6efd; color: white; }

    .team-nickname {
        font-size: 0.95rem;
        font-weight: 600;
        opacity: 0.9;
        text-transform: uppercase;
        max-width: 60%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sim-progress-bg { background-color: #e9ecef; border-radius: 10px; height: 35px; overflow: hidden; display: flex; box-shadow: inset 0 2px 5px rgba(0,0,0,0.1); }
    .sim-progress-bar { height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 900; color: white; transition: width 1s ease-in-out; }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-magic text-warning me-2"></i> <?= __('simulator_title') ?>
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-dark fs-6 shadow-sm">
                            Match: <?= strtoupper(str_replace('qm', 'QM ', $data['match_key'])) ?>
                        </span>
                    </div>
                </div>
                <a href="#" onclick="history.back()" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline"><?= __('back') ?></span>
                </a>
            </div>

            <?php
            $teamNames = [];
            if (!empty($data['takimlar'])) {
                foreach ($data['takimlar'] as $team) {
                    $teamNames[$team['key']] = $team['nickname'];
                }
            }

            $redTeams = $data['match_details']['alliances']['red']['team_keys'] ?? [];
            $blueTeams = $data['match_details']['alliances']['blue']['team_keys'] ?? [];

            $redEPA = 0; $blueEPA = 0;
            $redAGR = 0; $blueAGR = 0;
            $alerts = [];

            foreach($redTeams as $tKey) {
                $redEPA += isset($data['epa_data'][$tKey]) ? $data['epa_data'][$tKey]['toplam_epa'] : 0;

                if(isset($data['scout_data'][$tKey]) && $data['scout_data'][$tKey]['matches'] > 0) {
                    $s = $data['scout_data'][$tKey];
                    $avgScout = ($s['auto_total'] + $s['teleop_total']) / $s['matches'];
                    $redAGR += $avgScout;

                    $teamName = isset($teamNames[$tKey]) ? $teamNames[$tKey] : str_replace('frc','',$tKey);

                    if($s['last_role'] == 'calismadi') {
                        $alerts[] = "<div class='alert alert-danger shadow-sm'>🚨 <strong>" . (Lang::isTr() ? 'KIRMIZI İTTİFAK RİSKİ:' : 'RED ALLIANCE VULNERABILITY:') . "</strong> FRC " . str_replace('frc','',$tKey) . " (<b>" . $teamName . "</b>) " . (Lang::isTr() ? 'son maçında bozuldu/çalışmadı.' : 'reported disabled in last match.') . "</div>";
                    }
                    if($s['last_role'] == 'defans' && $s['last_defense'] == 'iyi') {
                        $alerts[] = "<div class='alert alert-warning shadow-sm text-dark'>🛡️ <strong>" . (Lang::isTr() ? 'KIRMIZI DEFANS UYARISI:' : 'RED DEFENSE ALERT:') . "</strong> FRC " . str_replace('frc','',$tKey) . " (<b>" . $teamName . "</b>) " . (Lang::isTr() ? 'son maçında çok etkili defans yaptı.' : 'played elite defense in last match.') . "</div>";
                    }
                }
            }

            foreach($blueTeams as $tKey) {
                $blueEPA += isset($data['epa_data'][$tKey]) ? $data['epa_data'][$tKey]['toplam_epa'] : 0;

                if(isset($data['scout_data'][$tKey]) && $data['scout_data'][$tKey]['matches'] > 0) {
                    $s = $data['scout_data'][$tKey];
                    $avgScout = ($s['auto_total'] + $s['teleop_total']) / $s['matches'];
                    $blueAGR += $avgScout;

                    $teamName = isset($teamNames[$tKey]) ? $teamNames[$tKey] : str_replace('frc','',$tKey);

                    if($s['last_role'] == 'calismadi') {
                        $alerts[] = "<div class='alert alert-danger shadow-sm'>🚨 <strong>" . (Lang::isTr() ? 'MAVİ İTTİFAK RİSKİ:' : 'BLUE ALLIANCE VULNERABILITY:') . "</strong> FRC " . str_replace('frc','',$tKey) . " (<b>" . $teamName . "</b>) " . (Lang::isTr() ? 'son maçında bozuldu/çalışmadı.' : 'reported disabled in last match.') . "</div>";
                    }
                    if($s['last_role'] == 'defans' && $s['last_defense'] == 'iyi') {
                        $alerts[] = "<div class='alert alert-info shadow-sm'>🛡️ <strong>" . (Lang::isTr() ? 'MAVİ DEFANS UYARISI:' : 'BLUE DEFENSE ALERT:') . "</strong> FRC " . str_replace('frc','',$tKey) . " (<b>" . $teamName . "</b>) " . (Lang::isTr() ? 'son maçında çok etkili defans yaptı.' : 'executes heavy defense.') . "</div>";
                    }
                }
            }

            $totalEPA = $redEPA + $blueEPA;
            $redEpaPct = $totalEPA > 0 ? round(($redEPA / $totalEPA) * 100) : 0;
            $blueEpaPct = $totalEPA > 0 ? (100 - $redEpaPct) : 0;

            $totalAGR = $redAGR + $blueAGR;
            $redAgrPct = $totalAGR > 0 ? round(($redAGR / $totalAGR) * 100) : 0;
            $blueAgrPct = $totalAGR > 0 ? (100 - $redAgrPct) : 0;
            ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-4">

                        <!-- 1. Statbotics EPA Prediction -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                <span class="badge bg-danger px-3 py-2 fs-6 mb-1 mb-md-0">
                                    <i class="fas fa-shield-alt me-1"></i> Red: <?= $redEPA ?> EPA
                                </span>
                                <h5 class="fw-bold text-center text-secondary m-0">
                                    <i class="fas fa-robot text-muted me-1"></i> <?= __('statbotics_prediction') ?>
                                </h5>
                                <span class="badge bg-primary px-3 py-2 fs-6 mt-1 mt-md-0">
                                    Blue: <?= $blueEPA ?> EPA <i class="fas fa-shield-alt ms-1"></i>
                                </span>
                            </div>

                            <?php if ($totalEPA > 0): ?>
                                <div class="sim-progress-bg shadow-sm">
                                    <div class="sim-progress-bar bg-danger" style="width: <?= $redEpaPct ?>%;">
                                        <?= $redEpaPct > 5 ? $redEpaPct . '%' : '' ?>
                                    </div>
                                    <div class="sim-progress-bar bg-primary" style="width: <?= $blueEpaPct ?>%;">
                                        <?= $blueEpaPct > 5 ? $blueEpaPct . '%' : '' ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="p-3 text-center text-muted bg-light rounded border">
                                    <i class="fas fa-info-circle text-info me-1"></i> <?= Lang::isTr() ? 'Bu takımlar için henüz Statbotics verisi hesaplanmamış.' : 'Statbotics EPA data has not been computed for these teams yet.' ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr class="my-3">

                        <!-- 2. Human Scout Prediction -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                <span class="badge bg-danger px-3 py-2 fs-6 mb-1 mb-md-0">
                                    <i class="fas fa-clipboard-check me-1"></i> Red: <?= round($redAGR, 1) ?> Avg Pts
                                </span>
                                <h5 class="fw-bold text-center text-dark m-0">
                                    <i class="fas fa-eye text-warning me-1"></i> <?= __('human_scout_prediction') ?>
                                </h5>
                                <span class="badge bg-primary px-3 py-2 fs-6 mt-1 mt-md-0">
                                    Blue: <?= round($blueAGR, 1) ?> Avg Pts <i class="fas fa-clipboard-check ms-1"></i>
                                </span>
                            </div>

                            <?php if ($totalAGR > 0): ?>
                                <div class="sim-progress-bg shadow-sm">
                                    <div class="sim-progress-bar bg-danger" style="width: <?= $redAgrPct ?>%; box-shadow: inset 0 0 10px rgba(0,0,0,0.2);">
                                        <?= $redAgrPct > 5 ? $redAgrPct . '%' : '' ?>
                                    </div>
                                    <div class="sim-progress-bar bg-primary" style="width: <?= $blueAgrPct ?>%; box-shadow: inset 0 0 10px rgba(0,0,0,0.2);">
                                        <?= $blueAgrPct > 5 ? $blueAgrPct . '%' : '' ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="p-3 text-center text-muted bg-light rounded border">
                                    <i class="fas fa-clipboard-list text-warning me-1"></i> <?= Lang::isTr() ? 'Bu maçtaki takımlar için henüz maç gözlem verisi girilmemiş.' : 'No scouting data recorded yet for teams in this match.' ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-3" style="background-color: #f8f9fa;">
                        <h5 class="fw-bold text-center text-secondary mb-3"><i class="fas fa-chalkboard text-dark"></i> <?= __('tactical_whiteboard') ?></h5>
                        <p class="text-muted small text-center mb-2"><?= __('tactical_whiteboard_desc') ?></p>
                        <div class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-warning btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#strategyModal" style="border-radius: 15px;">
                                <i class="fas fa-map-marked-alt fa-2x d-block mb-2 text-dark"></i>
                                <?= __('open_whiteboard') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card alliance-card card-red h-100 bg-light">
                        <div class="card-body">
                            <h4 class="text-center text-danger fw-bold mb-4"><?= __('red_alliance') ?></h4>
                            <?php foreach($redTeams as $t):
                                $nick = isset($teamNames[$t]) ? $teamNames[$t] : 'Unnamed Team';
                                ?>
                                <a href="/default/team_analysis/<?= $t ?>/<?= $data['event_key'] ?>" class="team-badge team-red" title="Dossier">
                                    <span>FRC <?= str_replace('frc','', $t) ?></span>
                                    <span class="team-nickname"><?= htmlspecialchars($nick) ?> <i class="fas fa-external-link-alt ms-1" style="font-size:0.8rem;"></i></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card alliance-card card-blue h-100 bg-light">
                        <div class="card-body">
                            <h4 class="text-center text-primary fw-bold mb-4"><?= __('blue_alliance') ?></h4>
                            <?php foreach($blueTeams as $t):
                                $nick = isset($teamNames[$t]) ? $teamNames[$t] : 'Unnamed Team';
                                ?>
                                <a href="/default/team_analysis/<?= $t ?>/<?= $data['event_key'] ?>" class="team-badge team-blue" title="Dossier">
                                    <span>FRC <?= str_replace('frc','', $t) ?></span>
                                    <span class="team-nickname"><?= htmlspecialchars($nick) ?> <i class="fas fa-external-link-alt ms-1" style="font-size:0.8rem;"></i></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!empty($alerts)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-left: 5px solid #ffc107 !important;">
                            <div class="card-header bg-white border-0 pt-4 pb-2">
                                <h4 class="fw-bold text-dark m-0"><i class="fas fa-lightbulb text-warning me-2"></i> <?= __('tactical_intel') ?></h4>
                            </div>
                            <div class="card-body">
                                <?php foreach($alerts as $alert) echo $alert; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>

<!-- Strategy Board Modal -->
<div class="modal fade" id="strategyModal" tabindex="-1" aria-labelledby="strategyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="border-radius: 20px; overflow: hidden; background: #2b2b2b;">
      <div class="modal-header border-bottom-0 bg-dark text-white p-3">
        <h5 class="modal-title fw-bold m-0" id="strategyModalLabel"><i class="fas fa-chalkboard-teacher text-warning me-2"></i> <?= __('tactical_whiteboard') ?></h5>
        <div class="ms-auto d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger fw-bold" onclick="clearStrategyBoard()"><i class="fas fa-eraser"></i> <?= __('clear_board') ?></button>
            <button type="button" class="btn btn-sm btn-outline-light" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="modal-body p-0 position-relative" style="background-color: #333;">
        <div style="position: relative; width: 100%; padding-bottom: 50%; min-height: 400px; background-image: url('/dist/img/SAHA.png'); background-size: 100% 100%; background-repeat: no-repeat;">
            <canvas id="strategyCanvas" style="position: absolute; top:0; left:0; width:100%; height:100%; cursor: crosshair;"></canvas>
        </div>
      </div>
      <div class="modal-footer border-top-0 bg-dark p-2 d-flex justify-content-center gap-3">
          <button class="btn btn-sm" style="background-color: red; width: 30px; height: 30px; border-radius: 50%; border: 2px solid white;" onclick="setDrawColor('red')"></button>
          <button class="btn btn-sm" style="background-color: blue; width: 30px; height: 30px; border-radius: 50%; border: 2px solid white;" onclick="setDrawColor('blue')"></button>
          <button class="btn btn-sm" style="background-color: yellow; width: 30px; height: 30px; border-radius: 50%; border: 2px solid white;" onclick="setDrawColor('yellow')"></button>
          <button class="btn btn-sm" style="background-color: #00ff00; width: 30px; height: 30px; border-radius: 50%; border: 2px solid white;" onclick="setDrawColor('#00ff00')"></button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var canvas = document.getElementById('strategyCanvas');
    var ctx = null;
    var isDrawing = false;
    var drawColor = 'yellow';

    if(canvas) {
        ctx = canvas.getContext('2d');
        
        document.getElementById('strategyModal').addEventListener('shown.bs.modal', function () {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.lineWidth = 4;
        });

        function getPos(e) {
            var rect = canvas.getBoundingClientRect();
            var clientX = e.touches ? e.touches[0].clientX : e.clientX;
            var clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: (clientX - rect.left) * (canvas.width / rect.width),
                y: (clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        function startDraw(e) {
            e.preventDefault();
            isDrawing = true;
            var pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        }

        function draw(e) {
            if (!isDrawing) return;
            e.preventDefault();
            var pos = getPos(e);
            ctx.strokeStyle = drawColor;
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
        }

        function endDraw(e) {
            if (!isDrawing) return;
            e.preventDefault();
            isDrawing = false;
            ctx.closePath();
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', endDraw);
        canvas.addEventListener('mouseout', endDraw);

        canvas.addEventListener('touchstart', startDraw, {passive: false});
        canvas.addEventListener('touchmove', draw, {passive: false});
        canvas.addEventListener('touchend', endDraw);
        canvas.addEventListener('touchcancel', endDraw);
    }

    window.setDrawColor = function(color) {
        drawColor = color;
    }

    window.clearStrategyBoard = function() {
        if(ctx && canvas) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }
});
</script>
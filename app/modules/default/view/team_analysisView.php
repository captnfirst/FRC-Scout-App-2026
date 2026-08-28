<style>
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .back-btn-custom {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 600;
        transition: all 0.2s ease;
        backdrop-filter: blur(5px);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .back-btn-custom:hover {
        background-color: white;
        color: #1e3c72;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .match-card {
        border-radius: 12px;
        transition: all 0.2s;
    }
    .match-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
    .pit-photo-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: #000;
    }
    .pit-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .pit-photo-container:hover img {
        transform: scale(1.05);
    }
    .zoom-overlay {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .pit-photo-container:hover .zoom-overlay {
        opacity: 1;
    }

    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }
    .stat-box-label {
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
    .stat-box-value {
        font-weight: 900;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-search-plus text-info me-2"></i> <?= __('deep_dive_dossier') ?>
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-primary fs-6 me-2 shadow-sm d-inline-block mb-1 mb-md-0">
                            <i class="fas fa-robot me-1"></i> FRC <?= htmlspecialchars($data['team_info']['team_number']) ?>
                        </span>
                        <span class="badge bg-info text-dark fs-6 shadow-sm d-inline-block">
                            <?= htmlspecialchars($data['team_info']['nickname']) ?>
                        </span>
                    </div>
                </div>

                <a href="/default/analysis_tournament/<?= $data['event_key'] ?>" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline"><?= __('back_to_table') ?></span>
                </a>
            </div>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="m-0 fw-bold"><i class="fas fa-user-shield text-warning me-2"></i> <?= __('mentor_panel') ?></h5>
                </div>
                <div class="card-body bg-light">
                    <div id="mentorViewMode">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded border shadow-sm text-center">
                                    <small class="text-muted d-block mb-1"><?= __('bps_score') ?></small>
                                    <h3 class="fw-bold text-primary m-0" id="display_bps"><?= $data['pit_data']['bps'] ?? '0.0' ?></h3>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="p-3 bg-white rounded border shadow-sm h-100">
                                    <small class="text-muted d-block mb-1"><?= __('mentor_notes') ?></small>
                                    <p class="m-0 fst-italic" id="display_comments"><?= !empty($data['pit_data']['mentor_comments']) ? nl2br(htmlspecialchars($data['pit_data']['mentor_comments'])) : (Lang::isTr() ? 'Henüz mentor değerlendirmesi girilmemiş...' : 'No mentor evaluation submitted yet...') ?></p>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm w-100 fw-bold" onclick="toggleMentorEdit(true)">
                            <i class="fas fa-edit me-1"></i> <?= __('edit_evaluation') ?>
                        </button>
                    </div>

                    <div id="mentorEditMode" style="display:none;">
                        <div class="form-group mb-3">
                            <label class="fw-bold small text-muted"><?= __('bps_score') ?></label>
                            <input type="number" step="0.1" id="edit_bps" class="form-control shadow-sm" value="<?= $data['pit_data']['bps'] ?? 0 ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold small text-muted"><?= __('mentor_notes') ?></label>
                            <textarea id="edit_comments" class="form-control shadow-sm" rows="4"><?= htmlspecialchars($data['pit_data']['mentor_comments'] ?? '') ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-secondary btn-sm flex-fill" onclick="toggleMentorEdit(false)"><?= __('cancel') ?></button>
                            <button class="btn btn-success btn-sm flex-fill fw-bold" id="saveMentorDataBtn">
                                <i class="fas fa-check me-1"></i> <?= __('save_changes') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 mt-4">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-dark text-white border-0">
                            <h5 class="m-0 fw-bold"><i class="fas fa-wrench text-warning me-2"></i> <?= __('pit_scout_title') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($data['pit_data']) && !empty($data['pit_data']['robot_weight'])): ?>

                                <?php if (!empty($data['pit_data']['photo_path'])): ?>
                                    <div class="pit-photo-container mb-3 border shadow-sm" data-bs-toggle="modal" data-bs-target="#imageModal" title="Zoom">
                                        <img src="<?= htmlspecialchars($data['pit_data']['photo_path']) ?>" alt="Robot Photo">
                                        <div class="zoom-overlay"><i class="fas fa-search-plus"></i> Zoom</div>
                                    </div>
                                <?php else: ?>
                                    <div class="pit-photo-container mb-3 border bg-light text-muted">
                                        <i class="fas fa-camera fa-3x mb-2"></i><br><?= Lang::isTr() ? 'Fotoğraf Yok' : 'No Photo Available' ?>
                                    </div>
                                <?php endif; ?>

                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted"><?= __('robot_weight') ?>:</span>
                                        <strong><?= $data['pit_data']['robot_weight'] ?> kg / lbs</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted"><?= __('drivetrain') ?>:</span>
                                        <strong><?= strtoupper($data['pit_data']['drivetrain_type']) ?> <?= !empty($data['pit_data']['swerve_type']) ? '(' . strtoupper($data['pit_data']['swerve_type']) . ')' : '' ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted"><?= __('chassis_profile') ?>:</span>
                                        <strong class="text-capitalize"><?= $data['pit_data']['robot_dimensions'] ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted"><?= __('intake_mechanism') ?>:</span>
                                        <strong class="text-capitalize"><?= $data['pit_data']['mechanism_type'] ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted"><i class="fas fa-box-open me-1 text-primary"></i> <?= __('hopper_capacity') ?>:</span>
                                        <strong><?= isset($data['pit_data']['hopper_capacity']) && $data['pit_data']['hopper_capacity'] > 0 ? $data['pit_data']['hopper_capacity'] : (Lang::isTr() ? 'Bilinmiyor' : 'Unknown / N/A') ?></strong>
                                    </li>
                                </ul>

                                <?php if (!empty($data['pit_data']['scout_comments'])): ?>
                                    <div class="p-3 bg-light rounded-3 border border-warning border-start-0 border-end-0 shadow-sm">
                                        <small class="text-muted fw-bold d-block mb-1"><i class="fas fa-comment-dots text-warning me-1"></i> <?= __('general_comments') ?>:</small>
                                        <span class="fst-italic text-dark">"<?= htmlspecialchars($data['pit_data']['scout_comments']) ?>"</span>
                                    </div>
                                <?php endif; ?>

                            <?php else: ?>
                                <div class="alert alert-warning m-0 shadow-sm rounded-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i> <?= Lang::isTr() ? 'Bu robota ait pit verisi henüz girilmemiş.' : 'Pit scouting crew has not submitted physical specs for this robot yet.' ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-primary text-white border-0">
                            <h5 class="m-0 fw-bold"><i class="fas fa-chart-line text-info me-2"></i> <?= __('fuel_performance') ?></h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <?php if (empty($data['scout_matches'])): ?>
                                <div class="text-center text-muted p-4">
                                    <i class="fas fa-chart-bar fa-3x mb-3 opacity-50"></i><br>
                                    <?= Lang::isTr() ? 'Yeterli maç gözlem kaydı bulunmuyor.' : 'Not enough match scouting records.' ?>
                                </div>
                            <?php else: ?>
                                <div class="chart-container">
                                    <canvas id="fuelChart"></canvas>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white border-0">
                    <h5 class="m-0 fw-bold"><i class="fas fa-clipboard-list text-warning me-2"></i> <?= __('match_breakdown_log') ?></h5>
                </div>
                <div class="card-body p-4 bg-light">

                    <div class="row">
                        <?php
                        if (!empty($data['scout_matches'])):
                            foreach ($data['scout_matches'] as $match):

                                $keyParts = explode('_', $match['match_key']);
                                $shortKey = isset($keyParts[1]) ? $keyParts[1] : $match['match_key'];
                                $prettyMatchName = strtoupper(preg_replace('/([a-zA-Z]+)(\d+)/', '$1 $2', $shortKey));

                                $videoKey = null;

                                if (!empty($data['tba_matches']) && is_array($data['tba_matches'])) {
                                    $dbMatchKey = strtolower(trim($match['match_key']));
                                    foreach ($data['tba_matches'] as $tbaM) {
                                        if (isset($tbaM['key']) && strtolower($tbaM['key']) === $dbMatchKey) {
                                            if (!empty($tbaM['videos']) && isset($tbaM['videos'][0]['key'])) {
                                                $videoKey = $tbaM['videos'][0]['key'];
                                            }
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <div class="col-lg-6 mb-4">
                                    <div class="card match-card h-100 shadow-sm border-0 <?= (!empty($match['breakdown_reason']) && $match['breakdown_reason'] !== 'none') ? 'border border-danger border-2' : '' ?>">
                                        <div class="card-body d-flex flex-column">

                                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                                <h4 class="text-primary fw-bold m-0"><i class="fas fa-gamepad text-secondary me-2"></i> <?= $prettyMatchName ?></h4>

                                                <?php if ($videoKey): ?>
                                                    <button class="btn btn-sm btn-danger fw-bold shadow-sm rounded-pill px-3"
                                                            data-bs-toggle="modal" data-bs-target="#videoModal"
                                                            onclick="loadVideo('<?= $videoKey ?>')">
                                                        <i class="fab fa-youtube"></i> <?= __('watch_match') ?>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary rounded-pill px-3 py-2 opacity-75"><i class="fas fa-video-slash me-1"></i> <?= __('no_video') ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <?php if (!empty($match['breakdown_reason']) && $match['breakdown_reason'] !== 'none'):
                                                $ariza = "";
                                                if($match['breakdown_reason'] == 'aku_baglanti') $ariza = __('battery_loss');
                                                if($match['breakdown_reason'] == 'mekanik_hasar') $ariza = __('mechanical_damage');
                                                if($match['breakdown_reason'] == 'devrildi') $ariza = __('tipped_over');
                                                if($match['breakdown_reason'] == 'olu_robot') $ariza = __('disabled');
                                                ?>
                                                <div class="alert alert-danger py-2 px-3 mb-3 fw-bold text-center">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> <?= __('breakdown_status') ?>: <?= $ariza ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="row text-center g-2 flex-grow-1">
                                                <div class="col-6 col-md-auto flex-fill">
                                                    <div class="p-2 bg-info rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-white stat-box-label d-block mb-1"><?= __('auto_period') ?></span>
                                                        <span class="fs-3 text-white stat-box-value"><?= $match['auto_fuel'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-auto flex-fill">
                                                    <div class="p-2 bg-success rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-white stat-box-label d-block mb-1"><?= __('teleop_period') ?></span>
                                                        <span class="fs-3 text-white stat-box-value"><?= $match['teleop_fuel'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-auto flex-fill">
                                                    <div class="p-2 bg-warning rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-dark stat-box-label d-block mb-1" style="text-shadow: none;"><?= __('teleop_climb') ?></span>
                                                        <span class="fs-5 text-dark stat-box-value text-capitalize lh-1 mt-1" style="text-shadow: none;"><?= $match['teleop_climb'] ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-auto flex-fill">
                                                    <div class="p-2 bg-dark rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-white stat-box-label d-block mb-1"><?= __('robot_role') ?></span>
                                                        <span class="fs-5 text-white stat-box-value text-capitalize lh-1 mt-1">
                                                            <?php
                                                             $rol = $match['teleop_robot_role'] ?? '-';
                                                             if ($rol === 'defans') {
                                                                 echo '🛡️ ' . ($match['teleop_defense_quality'] ?? '-');
                                                             } else if ($rol === 'skorlama') {
                                                                 echo '🎯 ' . (Lang::isTr() ? 'Skorlama' : 'Score');
                                                             } else {
                                                                 echo $rol;
                                                             }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row text-center g-2 mt-1">
                                                <div class="col-4">
                                                    <div class="p-1 border rounded h-100 bg-white">
                                                        <small class="text-muted fw-bold d-block" style="font-size: 0.7rem;"><?= __('cycle_speed') ?></small>
                                                        <span class="text-dark fw-bold text-capitalize"><?= $match['cycle_speed'] ?? '-' ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="p-1 border rounded h-100 bg-white">
                                                        <small class="text-muted fw-bold d-block" style="font-size: 0.7rem;"><?= __('driver_evasion') ?></small>
                                                        <span class="text-dark fw-bold text-capitalize"><?= $match['driver_evasion'] ?? '-' ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="p-1 border rounded h-100 bg-white">
                                                        <small class="text-muted fw-bold d-block" style="font-size: 0.7rem;"><?= __('feed_quality') ?></small>
                                                        <span class="text-dark fw-bold text-capitalize"><?= $match['teleop_feed_quality'] ?? '-' ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($match['auto_path'])): ?>
                                                <div class="mt-3 text-center border-top pt-3">
                                                    <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-route text-primary me-1"></i> <?= __('auto_path') ?>:</small>
                                                    <div class="rounded shadow-sm mx-auto overflow-hidden border border-secondary"
                                                         style="max-width: 300px; max-height: 150px; background-image: url('/dist/img/SAHA.png'); background-size: cover; background-position: center;">

                                                        <img src="<?= $match['auto_path'] ?>"
                                                             class="img-fluid w-100 h-100"
                                                             style="object-fit: contain;"
                                                             alt="Auto Trajectory">
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <div class="col-12">
                                <div class="alert alert-secondary text-center p-5 shadow-sm rounded-3">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 text-muted"></i><br>
                                    <h5 class="text-dark fw-bold"><?= Lang::isTr() ? 'Gözlem Verisi Yok' : 'No Scout Data' ?></h5>
                                    <?= Lang::isTr() ? 'Bu takım için henüz maç gözlem kaydı girilmemiş.' : 'No match scouting records have been entered for this team yet.' ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 90vw;">
        <div class="modal-content bg-dark border-0 shadow-lg">
            <div class="modal-header border-0 pb-1">
                <h4 class="modal-title text-white fw-bold"><i class="fab fa-youtube text-danger me-2"></i> <?= __('watch_match') ?></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopVideo()"></button>
            </div>
            <div class="modal-body p-0">
                <div style="position: relative; width: 100%; padding-bottom: 56.25%; background: #000;">
                    <iframe id="youtubePlayer"
                            src=""
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
            <div class="modal-footer border-0 bg-dark py-2 d-flex justify-content-between">
                <small class="text-secondary"><i class="fas fa-robot me-1"></i> FRC <?= htmlspecialchars($data['team_info']['team_number']) ?> Analytics Dossier</small>
                <button type="button" class="btn btn-sm btn-outline-secondary text-white" data-bs-dismiss="modal" onclick="stopVideo()"><?= __('close') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 d-flex justify-content-end pb-0 position-absolute w-100" style="z-index: 10;">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: drop-shadow(0 0 5px rgba(0,0,0,1)); m-3"></button>
            </div>
            <div class="modal-body text-center p-0">
                <?php if (!empty($data['pit_data']['photo_path'])): ?>
                    <img src="<?= htmlspecialchars($data['pit_data']['photo_path']) ?>" class="img-fluid rounded shadow-lg" alt="Full Size Robot Photo" style="max-height: 85vh; object-fit: contain; background: rgba(0,0,0,0.5);">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleMentorEdit(show) {
        if(show) {
            $('#mentorViewMode').hide();
            $('#mentorEditMode').fadeIn();
        } else {
            $('#mentorEditMode').hide();
            $('#mentorViewMode').fadeIn();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {

        <?php if (!empty($data['scout_matches'])): 
            $labels = [];
            $autoFuel = [];
            $teleopFuel = [];
            foreach ($data['scout_matches'] as $m) {
                $keyParts = explode('_', $m['match_key']);
                $labels[] = isset($keyParts[1]) ? strtoupper($keyParts[1]) : $m['match_key'];
                $autoFuel[] = intval($m['auto_fuel'] ?? 0);
                $teleopFuel[] = intval($m['teleop_fuel'] ?? 0);
            }
        ?>
        const chartCanvas = document.getElementById('fuelChart');
        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [
                        {
                            label: isTurkish ? 'Otonom Yakıt' : 'Auto Fuel',
                            data: <?= json_encode($autoFuel) ?>,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            borderWidth: 3,
                            fill: true
                        },
                        {
                            label: isTurkish ? 'Teleop Yakıt' : 'Teleop Fuel',
                            data: <?= json_encode($teleopFuel) ?>,
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            borderWidth: 3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
        <?php endif; ?>

        $('#saveMentorDataBtn').click(function() {
            const bps = $('#edit_bps').val();
            const comments = $('#edit_comments').val();
            const $btn = $(this);

            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> ' + (isTurkish ? 'Kaydediliyor...' : 'Saving...'));

            $.ajax({
                url: '/default/save_mentor_evaluation',
                type: 'POST',
                data: {
                    team_key: '<?= $data['team_key'] ?>',
                    event_key: '<?= $data['event_key'] ?>',
                    bps: bps,
                    mentor_comments: comments
                },
                dataType: 'json',
                success: function(res) {
                    $btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> ' + (isTurkish ? 'Değişiklikleri Kaydet' : 'Save Changes'));
                    if(res.success) {
                        $('#display_bps').text(bps || '0.0');
                        $('#display_comments').html(comments ? comments.replace(/\n/g, '<br>') : (isTurkish ? 'Henüz mentor değerlendirmesi girilmemiş...' : 'No mentor evaluation submitted yet...'));
                        toggleMentorEdit(false);
                    } else {
                        alert(res.message || (isTurkish ? 'Kayıt sırasında hata oluştu!' : 'Error saving mentor evaluation!'));
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> ' + (isTurkish ? 'Değişiklikleri Kaydet' : 'Save Changes'));
                    alert(isTurkish ? 'Sunucu bağlantı hatası!' : 'Server connection error!');
                }
            });
        });

    });
</script>
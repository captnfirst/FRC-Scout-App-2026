<style>
    .scout-section { display: none; }
    .scout-section.active { display: block; animation: fadeInUp 0.4s ease-out; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .cancel-btn-custom {
        background-color: rgba(220, 53, 69, 0.2);
        color: white;
        border: 1px solid rgba(220, 53, 69, 0.4);
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
        transition: all 0.2s ease;
        backdrop-filter: blur(5px);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .cancel-btn-custom:hover {
        background-color: #dc3545;
        color: white !important;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
    }

    .scout-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .scout-card-header {
        font-weight: 700;
        letter-spacing: 0.02em;
        padding: 1.25rem;
    }

    .fuel-display-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .fuel-display {
        font-size: 3.5rem;
        font-weight: 800;
        color: #0d6efd;
        background: #f8f9fa;
        border: 4px solid #e9ecef;
        border-radius: 20px;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: inset 0 0.25rem 0.5rem rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    .fuel-btn {
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        min-width: 60px;
        transition: transform 0.1s;
    }
    .fuel-btn:active { transform: scale(0.92); }

    .custom-radio-label {
        border-radius: 8px !important;
        padding: 12px 0;
        font-weight: 600;
        margin: 0;
        text-align: center;
        cursor: pointer;
    }

    .canvas-container {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        border: 3px solid #dee2e6;
        border-radius: 12px;
        overflow: hidden;
        background-image: url('/dist/img/SAHA.png');
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    #pathCanvas {
        display: block;
        width: 100%;
        height: auto;
        cursor: crosshair;
        touch-action: none;
    }

    .step-indicator {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
    }
    .step-pill {
        flex: 1;
        text-align: center;
        padding: 12px 10px;
        border-radius: 10px;
        font-weight: 700;
        color: #6c757d;
        background-color: #e9ecef;
        transition: all 0.3s;
        font-size: 0.9rem;
    }
    @media (min-width: 768px) {
        .step-pill { font-size: 1.1rem; }
    }
    .step-pill.active-auto { background-color: #0d6efd; color: white; box-shadow: 0 4px 10px rgba(13,110,253,0.3); }
    .step-pill.active-teleop { background-color: #198754; color: white; box-shadow: 0 4px 10px rgba(25,135,84,0.3); }
</style>

<div class="container my-4 mb-5">

    <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                <i class="fas fa-clipboard-check text-warning me-2"></i> <?= __('scout_form_title') ?>
            </h2>
            <div class="mt-2">
                <span class="badge bg-white text-primary fs-6 me-2 shadow-sm mb-1 mb-md-0 d-inline-block">
                    <i class="fas fa-robot me-1"></i> FRC <?= htmlspecialchars(str_replace('frc', '', $data['team_key'])) ?>
                </span>
                <span class="badge bg-info text-dark fs-6 shadow-sm d-inline-block">
                    <i class="fas fa-flag-checkered me-1"></i> <?= strtoupper(htmlspecialchars($data['match_key'])) ?>
                </span>
            </div>
        </div>

        <a href="/default/matches/<?= htmlspecialchars($data['team_key']) ?>/<?= htmlspecialchars($data['event_key']) ?>" class="cancel-btn-custom flex-shrink-0">
            <i class="fas fa-times me-md-2"></i> <span class="d-none d-md-inline"><?= __('cancel') ?></span>
        </a>
    </div>

    <div class="step-indicator">
        <div id="step-1" class="step-pill active-auto">1. <?= __('auto_period') ?></div>
        <div id="step-2" class="step-pill">2. <?= __('teleop_period') ?></div>
    </div>

    <form id="scoutForm" action="/default/savescout" method="POST">
        <input type="hidden" name="match_key" value="<?= htmlspecialchars($data['match_key']) ?>">
        <input type="hidden" name="team_key" value="<?= htmlspecialchars($data['team_key']) ?>">
        <input type="hidden" name="event_key" value="<?= htmlspecialchars($data['event_key']) ?>">
        <input type="hidden" name="auto_path" id="auto_path_data" value="">

        <div id="section-auto" class="scout-section active">
            <div class="card scout-card border-primary mb-4">
                <div class="card-header bg-primary text-white scout-card-header fs-5">
                    🤖 <?= __('auto_period') ?>
                </div>
                <div class="card-body p-4">

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3 d-block text-center"><?= __('fuel_counter') ?></label>
                        <div class="fuel-display-container">
                            <div class="fuel-display" id="auto_fuel_display">0</div>
                            <input type="hidden" name="auto_fuel" id="auto_fuel_input" value="0">

                            <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                                <button type="button" class="btn btn-outline-danger fuel-btn" onclick="updateFuel('auto', -10)">-10</button>
                                <button type="button" class="btn btn-outline-warning fuel-btn" onclick="updateFuel('auto', -5)">-5</button>
                                <button type="button" class="btn btn-outline-secondary fuel-btn" onclick="updateFuel('auto', -1)">-1</button>
                                <button type="button" class="btn btn-outline-primary fuel-btn" onclick="updateFuel('auto', 1)">+1</button>
                                <button type="button" class="btn btn-outline-info fuel-btn" onclick="updateFuel('auto', 5)">+5</button>
                                <button type="button" class="btn btn-outline-success fuel-btn" onclick="updateFuel('auto', 10)">+10</button>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-4">
                        <label class="form-label fw-bold text-secondary mb-3"><?= __('auto_climb') ?></label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="auto_climb" id="ac_none" value="none" checked>
                            <label class="btn btn-outline-secondary custom-radio-label flex-fill" for="ac_none"><?= Lang::isTr() ? 'Yok' : 'None' ?></label>

                            <input type="radio" class="btn-check" name="auto_climb" id="ac_level1" value="level1">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="ac_level1">Level 1</label>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-2">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <label class="form-label fw-bold text-secondary mb-0"><?= __('auto_path') ?></label>
                            <button type="button" class="btn btn-sm btn-light text-danger fw-bold shadow-sm" onclick="clearCanvas()">
                                <i class="fas fa-trash-alt me-1"></i> <?= __('clear_canvas') ?>
                            </button>
                        </div>
                        <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i> <?= Lang::isTr() ? 'Robotun otonomda sahada izlediği rotayı parmağınızla / fareyle çizin.' : 'Draw the trajectory path taken by the robot on the field.' ?></p>

                        <div class="canvas-container">
                            <canvas id="pathCanvas" width="800" height="400"></canvas>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <button type="button" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" onclick="goToTeleop()">
                        <?= Lang::isTr() ? 'İleri: Teleop Dönemine Geç' : 'Next: Proceed to Teleop' ?> <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="section-teleop" class="scout-section">
            <div class="card scout-card border-success mb-4">
                <div class="card-header bg-success text-white scout-card-header fs-5">
                    🎮 <?= __('teleop_period') ?>
                </div>
                <div class="card-body p-4">

                    <div class="form-group mb-5 p-3 rounded-3 shadow-sm" style="background-color: #fff3cd; border: 1px solid #ffe69c;">
                        <label class="form-label fw-bold text-danger d-block mb-3 text-center"><i class="fas fa-exclamation-triangle me-1"></i> <?= __('breakdown_status') ?></label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check breakdown-radio" name="breakdown_reason" id="br_sorunsuz" value="" checked>
                            <label class="btn btn-outline-success custom-radio-label flex-fill" for="br_sorunsuz">✅ <?= __('no_breakdown') ?></label>

                            <input type="radio" class="btn-check breakdown-radio" name="breakdown_reason" id="br_aku" value="aku_baglanti">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="br_aku">🔋 <?= __('battery_loss') ?></label>

                            <input type="radio" class="btn-check breakdown-radio" name="breakdown_reason" id="br_mekanik" value="mekanik_hasar">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="br_mekanik">⚙️ <?= __('mechanical_damage') ?></label>

                            <input type="radio" class="btn-check breakdown-radio" name="breakdown_reason" id="br_devrildi" value="devrildi">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="br_devrildi">🔄 <?= __('tipped_over') ?></label>

                            <input type="radio" class="btn-check breakdown-radio" name="breakdown_reason" id="br_olu" value="olu_robot">
                            <label class="btn btn-outline-dark custom-radio-label flex-fill" for="br_olu">💀 <?= __('disabled') ?></label>
                        </div>

                        <div id="breakdown_warning" class="alert alert-danger mt-3 mb-0" style="display:none; font-size: 0.95rem;">
                            <i class="fas fa-info-circle me-1"></i> <?= Lang::isTr() ? 'Robot bozulduysa, bozulmadan önceki performansını puanlayın.' : 'If the robot broke down, rate its performance prior to breakdown.' ?>
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3 d-block text-center"><?= __('fuel_counter') ?></label>
                        <div class="fuel-display-container">
                            <div class="fuel-display text-success" id="teleop_fuel_display" style="border-color:#d1e7dd; background:#f8fcfa;">0</div>
                            <input type="hidden" name="teleop_fuel" id="teleop_fuel_input" value="0">

                            <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                                <button type="button" class="btn btn-outline-danger fuel-btn" onclick="updateFuel('teleop', -10)">-10</button>
                                <button type="button" class="btn btn-outline-warning fuel-btn" onclick="updateFuel('teleop', -5)">-5</button>
                                <button type="button" class="btn btn-outline-secondary fuel-btn" onclick="updateFuel('teleop', -1)">-1</button>
                                <button type="button" class="btn btn-outline-primary fuel-btn" onclick="updateFuel('teleop', 1)">+1</button>
                                <button type="button" class="btn btn-outline-info fuel-btn" onclick="updateFuel('teleop', 5)">+5</button>
                                <button type="button" class="btn btn-outline-success fuel-btn" onclick="updateFuel('teleop', 10)">+10</button>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-4">
                        <label class="form-label fw-bold text-primary mb-3"><i class="fas fa-stopwatch me-1"></i> <?= __('cycle_speed') ?></label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="cycle_speed" id="cs_na" value="calismadi" checked>
                            <label class="btn btn-outline-dark custom-radio-label flex-fill" for="cs_na"><?= Lang::isTr() ? 'Döngü Yapmadı' : 'No Cycles' ?></label>

                            <input type="radio" class="btn-check" name="cycle_speed" id="cs_yavas" value="yavas">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="cs_yavas"><?= Lang::isTr() ? 'Yavaş (>20s)' : 'Slow (>20s)' ?></label>

                            <input type="radio" class="btn-check" name="cycle_speed" id="cs_orta" value="orta">
                            <label class="btn btn-outline-warning custom-radio-label flex-fill" for="cs_orta"><?= Lang::isTr() ? 'Orta (10-20s)' : 'Medium (10-20s)' ?></label>

                            <input type="radio" class="btn-check" name="cycle_speed" id="cs_hizli" value="hizli">
                            <label class="btn btn-outline-success custom-radio-label flex-fill" for="cs_hizli"><?= Lang::isTr() ? 'Hızlı (<10s)' : 'Fast (<10s)' ?></label>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-secondary mb-2"><?= __('feed_quality') ?></label>
                            <div class="d-flex flex-wrap gap-1" role="group">
                                <input type="radio" class="btn-check" name="feed_quality" id="fq_calismadi" value="calismadi" checked>
                                <label class="btn btn-outline-dark custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="fq_calismadi"><?= Lang::isTr() ? 'Yapmadı' : 'None' ?></label>

                                <input type="radio" class="btn-check" name="feed_quality" id="fq_kotu" value="kötü">
                                <label class="btn btn-outline-danger custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="fq_kotu"><?= Lang::isTr() ? 'Kötü' : 'Poor' ?></label>

                                <input type="radio" class="btn-check" name="feed_quality" id="fq_orta" value="orta">
                                <label class="btn btn-outline-warning custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="fq_orta"><?= Lang::isTr() ? 'Orta' : 'Average' ?></label>

                                <input type="radio" class="btn-check" name="feed_quality" id="fq_iyi" value="iyi">
                                <label class="btn btn-outline-success custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="fq_iyi"><?= Lang::isTr() ? 'İyi' : 'Good' ?></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary mb-2"><?= Lang::isTr() ? 'DAMPER PERFORMANSI' : 'DUMPING PERFORMANCE' ?></label>
                            <div class="d-flex flex-wrap gap-1" role="group">
                                <input type="radio" class="btn-check" name="damper_quality" id="dq_calismadi" value="calismadi" checked>
                                <label class="btn btn-outline-dark custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="dq_calismadi"><?= Lang::isTr() ? 'Yapmadı' : 'None' ?></label>

                                <input type="radio" class="btn-check" name="damper_quality" id="dq_kotu" value="kötü">
                                <label class="btn btn-outline-danger custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="dq_kotu"><?= Lang::isTr() ? 'Kötü' : 'Poor' ?></label>

                                <input type="radio" class="btn-check" name="damper_quality" id="dq_orta" value="orta">
                                <label class="btn btn-outline-warning custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="dq_orta"><?= Lang::isTr() ? 'Orta' : 'Average' ?></label>

                                <input type="radio" class="btn-check" name="damper_quality" id="dq_iyi" value="iyi">
                                <label class="btn btn-outline-success custom-radio-label flex-fill p-2" style="font-size: 0.9rem;" for="dq_iyi"><?= Lang::isTr() ? 'İyi' : 'Good' ?></label>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-4">
                        <label class="form-label fw-bold text-primary mb-3"><i class="fas fa-shield-virus me-1"></i> <?= __('driver_evasion') ?></label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="driver_evasion" id="de_calismadi" value="calismadi">
                            <label class="btn btn-outline-dark custom-radio-label flex-fill" for="de_calismadi"><?= Lang::isTr() ? 'Çalışmadı' : 'Disabled' ?></label>

                            <input type="radio" class="btn-check" name="driver_evasion" id="de_yok" value="yok" checked>
                            <label class="btn btn-outline-secondary custom-radio-label flex-fill" for="de_yok"><?= Lang::isTr() ? 'Defans Görmedi' : 'No Defense' ?></label>

                            <input type="radio" class="btn-check" name="driver_evasion" id="de_kotu" value="kötü">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="de_kotu"><?= Lang::isTr() ? 'Kilitlendi' : 'Pinned' ?></label>

                            <input type="radio" class="btn-check" name="driver_evasion" id="de_orta" value="orta">
                            <label class="btn btn-outline-warning custom-radio-label flex-fill" for="de_orta"><?= Lang::isTr() ? 'Kurtuldu' : 'Managed' ?></label>

                            <input type="radio" class="btn-check" name="driver_evasion" id="de_iyi" value="iyi">
                            <label class="btn btn-outline-success custom-radio-label flex-fill" for="de_iyi"><?= Lang::isTr() ? 'Çok Çevik' : 'Highly Agile' ?></label>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-4 w-100">
                        <label class="form-label fw-bold text-secondary d-block mb-3"><?= __('robot_role') ?></label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check role-radio" name="teleop_robot_role" id="rr_skor" value="skorlama" checked>
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="rr_skor">🎯 <?= __('role_scoring') ?></label>

                            <input type="radio" class="btn-check role-radio" name="teleop_robot_role" id="rr_defans" value="defans">
                            <label class="btn btn-outline-dark custom-radio-label flex-fill" for="rr_defans">🛡️ <?= __('role_defense') ?></label>
                        </div>
                    </div>

                    <div class="form-group p-4 bg-light rounded-3 border border-warning shadow-sm" id="defense_quality_section" style="display:none; animation: fadeInUp 0.3s;">
                        <label class="form-label fw-bold text-danger mb-3"><i class="fas fa-shield-alt me-1"></i> <?= __('defense_quality') ?></label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="teleop_defense_quality" id="def_kotu" value="kötü">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="def_kotu"><?= Lang::isTr() ? 'Kötü' : 'Poor' ?></label>

                            <input type="radio" class="btn-check" name="teleop_defense_quality" id="def_orta" value="orta" checked>
                            <label class="btn btn-outline-warning custom-radio-label flex-fill" for="def_orta"><?= Lang::isTr() ? 'Orta' : 'Average' ?></label>

                            <input type="radio" class="btn-check" name="teleop_defense_quality" id="def_iyi" value="iyi">
                            <label class="btn btn-outline-success custom-radio-label flex-fill" for="def_iyi"><?= Lang::isTr() ? 'İyi / Yıkıcı' : 'Good / Disruptive' ?></label>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3"><?= __('teleop_climb') ?></label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_none" value="none" checked>
                            <label class="btn btn-outline-secondary custom-radio-label flex-fill" for="tc_none"><?= Lang::isTr() ? 'Yok' : 'None' ?></label>

                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_level1" value="level1">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="tc_level1">Level 1</label>

                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_level2" value="level2">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="tc_level2">Level 2</label>

                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_level3" value="level3">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="tc_level3">Level 3</label>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light btn-lg fw-bold shadow-sm" onclick="goToAuto()" style="width: 30%;">
                        <i class="fas fa-arrow-left"></i> <?= __('back') ?>
                    </button>
                    <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm" style="width: 70%;">
                        <?= __('save_record') ?> <i class="fas fa-check-circle ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
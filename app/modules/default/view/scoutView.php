<style>
    /* Arayüz ve Geçiş Animasyonları */
    .scout-section { display: none; }
    .scout-section.active { display: block; animation: fadeInUp 0.4s ease-out; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Üst Başlık Gradient Tasarımı */
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* İptal Butonu (Kırmızı Glassmorphism) */
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

    /* Modern Kart Tasarımı */
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

    /* Yakıt Göstergesi (Odak Noktası) */
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

    /* Kontrol Butonları (Dokunmatik ekran için optimize) */
    .fuel-btn {
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        min-width: 60px;
        transition: transform 0.1s;
    }
    .fuel-btn:active { transform: scale(0.92); }

    /* Özel Radyo Butonları */
    .custom-radio-label {
        border-radius: 8px !important;
        padding: 12px 0;
        font-weight: 600;
        margin: 0; /* Boşlukları artık gap-2 ile yöneteceğiz */
    }

    /* Harita Çizim Alanı */
    .canvas-container {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        border: 3px solid #dee2e6;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05);
    }

    #pathCanvas {
        width: 100%;
        height: auto;
        background-image: url('/dist/img/SAHA.png');
        background-size: 100% 100%;
        background-repeat: no-repeat;
        touch-action: none;
        cursor: crosshair;
        display: block;
    }

    /* Adım Göstergesi (Progres Bar) */
    .step-indicator {
        display: flex;
        gap: 10px;
        margin-bottom: 1.5rem;
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
                <i class="fas fa-clipboard-check text-warning me-2"></i> Saha Gözlemi
            </h2>
            <div class="mt-2">
                <span class="badge bg-white text-primary fs-6 me-2 shadow-sm mb-1 mb-md-0 d-inline-block">
                    <i class="fas fa-robot me-1"></i> FRC <?= htmlspecialchars(str_replace('frc', '', $data['team_key'])) ?>
                </span>
                <?php if (!empty($data['is_practice'])): ?>
                    <span class="badge bg-warning text-dark fs-6 shadow-sm d-inline-block">
                        <i class="fas fa-flask me-1"></i>
                        <?php
                            // practice_{eventKey}_pm{N} formatından PM {N} şeklinde göster
                            preg_match('/_pm(\d+)$/', $data['match_key'], $pmMatch);
                            echo 'DENEME - PM ' . (isset($pmMatch[1]) ? $pmMatch[1] : '');
                        ?>
                    </span>
                <?php else: ?>
                    <span class="badge bg-info text-dark fs-6 shadow-sm d-inline-block">
                        <i class="fas fa-flag-checkered me-1"></i> <?= strtoupper(htmlspecialchars($data['match_key'])) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <?php $cancelUrl = (!empty($data['is_practice']))
            ? "/default/practice_matches/" . htmlspecialchars($data['team_key']) . "/" . htmlspecialchars($data['event_key'])
            : "/default/matches/" . htmlspecialchars($data['team_key']) . "/" . htmlspecialchars($data['event_key']); ?>
        <a href="<?= $cancelUrl ?>" class="cancel-btn-custom flex-shrink-0">
            <i class="fas fa-times me-md-2"></i> <span class="d-none d-md-inline">İptal Et</span>
        </a>
    </div>

    <div class="step-indicator">
        <div id="step-1" class="step-pill active-auto">1. Otonom (Auto)</div>
        <div id="step-2" class="step-pill">2. Teleop & Oyun Sonu</div>
    </div>

    <form id="scoutForm" action="/default/savescout" method="POST">
        <input type="hidden" name="match_key" value="<?= htmlspecialchars($data['match_key']) ?>">
        <input type="hidden" name="team_key" value="<?= htmlspecialchars($data['team_key']) ?>">
        <input type="hidden" name="event_key" value="<?= htmlspecialchars($data['event_key']) ?>">
        <input type="hidden" name="auto_path" id="auto_path_data" value="">

        <div id="section-auto" class="scout-section active">
            <div class="card scout-card border-primary mb-4">
                <div class="card-header bg-primary text-white scout-card-header fs-5">
                    🤖 Otonom (Auto) Periyodu
                </div>
                <div class="card-body p-4">

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3 d-block text-center">AUTO YAKIT SKORU</label>
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

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3">AUTO TIRMANMA SEVİYESİ</label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="auto_climb" id="ac_none" value="none" checked>
                            <label class="btn btn-outline-secondary custom-radio-label flex-fill" for="ac_none">Yok</label>

                            <input type="radio" class="btn-check" name="auto_climb" id="ac_level1" value="level1">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="ac_level1">Level 1</label>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-2">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <label class="form-label fw-bold text-secondary mb-0">OTONOM ROTASI (PATH)</label>
                            <button type="button" class="btn btn-sm btn-light text-danger fw-bold shadow-sm" onclick="clearCanvas()">
                                <i class="fas fa-trash-alt me-1"></i> Temizle
                            </button>
                        </div>
                        <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i> Robotun izlediği yolu saha üzerinde çizin.</p>

                        <div class="canvas-container">
                            <canvas id="pathCanvas" width="800" height="400"></canvas>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <button type="button" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" onclick="goToTeleop()">
                        İleri: Teleop'a Geç <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="section-teleop" class="scout-section">
            <div class="card scout-card border-success mb-4">
                <div class="card-header bg-success text-white scout-card-header fs-5">
                    🎮 Teleop & Oyun Sonu
                </div>
                <div class="card-body p-4">

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3 d-block text-center">TELEOP YAKIT SKORU</label>
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
                        <label class="form-label fw-bold text-secondary mb-3">FIRLATMA / FEED PERFORMANSI</label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="teleop_shooting" id="ts_kotu" value="kötü">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="ts_kotu">Kötü</label>

                            <input type="radio" class="btn-check" name="teleop_shooting" id="ts_orta" value="orta" checked>
                            <label class="btn btn-outline-warning custom-radio-label flex-fill" for="ts_orta">Orta</label>

                            <input type="radio" class="btn-check" name="teleop_shooting" id="ts_iyi" value="iyi">
                            <label class="btn btn-outline-success custom-radio-label flex-fill" for="ts_iyi">İyi</label>
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3">SÜRÜCÜ (DRIVER) PERFORMANSI</label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="teleop_driver" id="td_kotu" value="kötü">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="td_kotu">Kötü</label>

                            <input type="radio" class="btn-check" name="teleop_driver" id="td_orta" value="orta" checked>
                            <label class="btn btn-outline-warning custom-radio-label flex-fill" for="td_orta">Orta</label>

                            <input type="radio" class="btn-check" name="teleop_driver" id="td_iyi" value="iyi">
                            <label class="btn btn-outline-success custom-radio-label flex-fill" for="td_iyi">İyi</label>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-5">
                        <label class="form-label fw-bold text-secondary mb-3">OYUN SONU (ENDGAME) TIRMANMA</label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_none" value="none" checked>
                            <label class="btn btn-outline-secondary custom-radio-label flex-fill" for="tc_none">Yok</label>

                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_level1" value="level1">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="tc_level1">Level 1</label>

                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_level2" value="level2">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="tc_level2">Level 2</label>

                            <input type="radio" class="btn-check" name="teleop_climb" id="tc_level3" value="level3">
                            <label class="btn btn-outline-primary custom-radio-label flex-fill" for="tc_level3">Level 3</label>
                        </div>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="form-group mb-4 w-100">
                        <label class="form-label fw-bold text-secondary mb-3 d-block">ROBOTUN MAÇTAKİ ANA ROLÜ</label>
                        <select class="form-select form-select-lg shadow-sm font-monospace w-100" name="teleop_robot_role" id="robot_role" onchange="checkDefense()" style="border-radius: 8px;">
                            <option value="skorlama">🎯 Skorlama Yaptı</option>
                            <option value="defans">🛡️ Defans Yaptı</option>
                            <option value="calismadi">⚠️ Çalışmadı / Bozuldu</option>
                        </select>
                    </div>

                    <div class="form-group p-4 bg-light rounded-3 border border-warning shadow-sm" id="defense_quality_section" style="display:none; animation: fadeInUp 0.3s;">
                        <label class="form-label fw-bold text-danger mb-3"><i class="fas fa-shield-alt me-1"></i> DEFANS KALİTESİ NASILDI?</label>
                        <div class="d-flex flex-wrap gap-2 w-100" role="group">
                            <input type="radio" class="btn-check" name="teleop_defense_quality" id="dq_kotu" value="kötü">
                            <label class="btn btn-outline-danger custom-radio-label flex-fill" for="dq_kotu">Kötü</label>

                            <input type="radio" class="btn-check" name="teleop_defense_quality" id="dq_orta" value="orta" checked>
                            <label class="btn btn-outline-warning custom-radio-label flex-fill" for="dq_orta">Orta</label>

                            <input type="radio" class="btn-check" name="teleop_defense_quality" id="dq_iyi" value="iyi">
                            <label class="btn btn-outline-success custom-radio-label flex-fill" for="dq_iyi">İyi</label>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light btn-lg fw-bold shadow-sm" onclick="goToAuto()" style="width: 30%;">
                        <i class="fas fa-arrow-left"></i> Geri
                    </button>
                    <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm" style="width: 70%;">
                        Kaydet <i class="fas fa-check-circle ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
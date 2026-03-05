<style>
    /* Özel Form Geçiş Animasyonları */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
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

    /* Form Bölümleri */
    .form-section {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 5px solid #dee2e6;
    }

    .section-fiziksel { border-left-color: #ffc107; } /* Sarı */
    .section-yuruyen { border-left-color: #0d6efd; } /* Mavi */
    .section-mekanizma { border-left-color: #198754; } /* Yeşil */
    .section-medya { border-left-color: #0dcaf0; } /* Turkuaz */

    .custom-radio-label {
        border-radius: 8px !important;
        padding: 12px 0;
        font-weight: 600;
        margin: 0;
        transition: all 0.2s;
    }

    .custom-radio-label:active { transform: scale(0.95); }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5" style="max-width: 800px; margin: 0 auto;">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-wrench text-warning me-2"></i> Pit Formu
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-primary fs-6 shadow-sm d-inline-block">
                            <i class="fas fa-robot me-1"></i> FRC <?= htmlspecialchars(str_replace('frc', '', $data['team_key'])) ?>
                        </span>
                    </div>
                </div>

                <a href="/default/pit_teams/<?= htmlspecialchars($data['event_key']) ?>" class="cancel-btn-custom flex-shrink-0">
                    <i class="fas fa-times me-md-2"></i> <span class="d-none d-md-inline">İptal Et</span>
                </a>
            </div>

            <form action="/default/savepitscout" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="team_key" value="<?= htmlspecialchars($data['team_key']) ?>">
                <input type="hidden" name="event_key" value="<?= htmlspecialchars($data['event_key']) ?>">

                <div class="form-section section-fiziksel">
                    <h5 class="fw-bold text-warning mb-4 border-bottom pb-2"><i class="fas fa-weight-hanging me-2"></i>Fiziksel Özellikler</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Robot Kütlesi (Lbs / Kg)</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-balance-scale"></i></span>
                            <input type="number" step="0.01" class="form-control border-start-0 ps-0" name="robot_weight" placeholder="Örn: 120" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-secondary mb-3">Şase Boyutu (Dimensions)</label>
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <input type="radio" class="btn-check" name="robot_dimensions" id="dim_trench" value="trench" required>
                            <label class="btn btn-outline-warning text-dark flex-fill custom-radio-label shadow-sm" for="dim_trench">📏 Trench (Kısa)</label>

                            <input type="radio" class="btn-check" name="robot_dimensions" id="dim_bump" value="bump">
                            <label class="btn btn-outline-warning text-dark flex-fill custom-radio-label shadow-sm" for="dim_bump">🏢 Bump (Uzun)</label>
                        </div>
                    </div>
                </div>

                <div class="form-section section-yuruyen">
                    <h5 class="fw-bold text-primary mb-4 border-bottom pb-2"><i class="fas fa-cogs me-2"></i>Yürüyen Aksam</h5>

                    <div class="mb-2 w-100">
                        <label class="form-label fw-bold text-secondary mb-3">Şase Türü Nedir?</label>

                        <div class="d-flex flex-wrap gap-2 w-100 mb-2">
                            <input type="radio" class="btn-check" name="drivetrain_type" id="dt_swerve" value="swerve" onchange="checkSwerve()" required>
                            <label class="btn btn-outline-primary flex-fill custom-radio-label shadow-sm" for="dt_swerve">Swerve</label>

                            <input type="radio" class="btn-check" name="drivetrain_type" id="dt_tank" value="tank" onchange="checkSwerve()">
                            <label class="btn btn-outline-primary flex-fill custom-radio-label shadow-sm" for="dt_tank">Tank</label>
                        </div>
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <input type="radio" class="btn-check" name="drivetrain_type" id="dt_mecanum" value="mecanum" onchange="checkSwerve()">
                            <label class="btn btn-outline-primary flex-fill custom-radio-label shadow-sm" for="dt_mecanum">Mecanum</label>

                            <input type="radio" class="btn-check" name="drivetrain_type" id="dt_kitbot" value="kitbot" onchange="checkSwerve()">
                            <label class="btn btn-outline-primary flex-fill custom-radio-label shadow-sm" for="dt_kitbot">Kitbot</label>
                        </div>
                    </div>

                    <div id="swerve_options" style="display:none; animation: fadeInUp 0.3s;" class="mt-4 p-3 bg-light rounded-3 border border-primary shadow-inner">
                        <label class="form-label fw-bold text-primary mb-3"><i class="fas fa-microchip me-1"></i> Hangi Swerve Modülü?</label>
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <input type="radio" class="btn-check swerve-req" name="swerve_type" id="sw_sds" value="sds">
                            <label class="btn btn-outline-info flex-fill custom-radio-label" for="sw_sds">SDS</label>

                            <input type="radio" class="btn-check swerve-req" name="swerve_type" id="sw_wcp" value="wcp">
                            <label class="btn btn-outline-info flex-fill custom-radio-label" for="sw_wcp">WCP</label>

                            <input type="radio" class="btn-check swerve-req" name="swerve_type" id="sw_rev" value="rev">
                            <label class="btn btn-outline-info flex-fill custom-radio-label" for="sw_rev">REV</label>
                        </div>
                        <div class="d-flex flex-wrap gap-2 w-100 mt-2">
                            <input type="radio" class="btn-check swerve-req" name="swerve_type" id="sw_thrifty" value="thrifty">
                            <label class="btn btn-outline-info flex-fill custom-radio-label" for="sw_thrifty">Thrifty</label>

                            <input type="radio" class="btn-check swerve-req" name="swerve_type" id="sw_nfr" value="nfr">
                            <label class="btn btn-outline-info flex-fill custom-radio-label" for="sw_nfr">NFR</label>
                        </div>
                    </div>
                </div>

                <div class="form-section section-mekanizma">
                    <h5 class="fw-bold text-success mb-4 border-bottom pb-2"><i class="fas fa-wrench me-2"></i>Mekanizma & Tırmanma</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary mb-3">Intake Tipi</label>
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <input type="radio" class="btn-check" name="mechanism_type" id="mech_spindexer" value="spindexer" required>
                            <label class="btn btn-outline-success flex-fill custom-radio-label shadow-sm" for="mech_spindexer">🔄 Spindexer</label>

                            <input type="radio" class="btn-check" name="mechanism_type" id="mech_roller" value="roller">
                            <label class="btn btn-outline-success flex-fill custom-radio-label shadow-sm" for="mech_roller">⚙️ Roller</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary mb-3 ">Tırmanma Yapabiliyor Mu?</label>
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <input type="checkbox" class="btn-check" name="auto_climb" id="auto_climb" value="1">
                            <label class="btn btn-outline-success flex-fill custom-radio-label shadow-sm" for="auto_climb">🤖 Auto Tırmanma</label>

                            <input type="checkbox" class="btn-check" name="teleop_climb" id="teleop_climb" value="1">
                            <label class="btn btn-outline-success flex-fill custom-radio-label shadow-sm" for="teleop_climb">🎮 Teleop Tırmanma</label>
                        </div>
                    </div>
                </div>

                <div class="form-section section-medya">
                    <h5 class="fw-bold text-info mb-4 border-bottom pb-2"><i class="fas fa-comment-alt me-2"></i>Notlar & Medya</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Öğrenci Yorumu</label>
                        <textarea class="form-control bg-light shadow-sm" name="scout_comments" rows="3" placeholder="Robotun öne çıkan özelliği nedir? Herhangi bir zayıf noktası var mı?"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-secondary"><i class="fas fa-camera text-info"></i> Robot Fotoğrafı (İzin Varsa)</label>

                        <input type="file" id="robot_photo_input" name="robot_photo" style="display: none;" onchange="document.getElementById('file-chosen-text').innerHTML = '<i class=\'fas fa-check-circle\'></i> Fotoğraf Eklendi: ' + (this.files[0] ? this.files[0].name : '');">

                        <button type="button" class="btn btn-outline-info btn-lg w-100 shadow-sm py-3" onclick="document.getElementById('robot_photo_input').click();">
                            <i class="fas fa-camera fs-4 mb-1 d-block"></i> Kamerayı Aç veya Dosya Seç
                        </button>

                        <div id="file-chosen-text" class="text-center text-success fw-bold mt-2" style="font-size: 0.9rem;"></div>

                        <small class="text-muted mt-2 d-block text-center"><i class="fas fa-info-circle"></i> Butona tıklandığında menü açılacaktır.</small>
                    </div>
                </div>

                <div class="mb-5 pb-5">
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-lg py-3 rounded-pill" style="font-size: 1.2rem;">
                        Tüm Verileri Kaydet <i class="fas fa-check-circle ms-2"></i>
                    </button>
                </div>

            </form>
        </div>
    </section>
</div>
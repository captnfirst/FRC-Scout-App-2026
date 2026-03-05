<style>
    /* --- YENİ EKLENEN APP BAR (KOMUTA MERKEZİ) STİLLERİ --- */
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

    /* --- MEVCUT DERİN ANALİZ STİLLERİ --- */
    .match-card {
        transition: transform 0.2s;
        border-left: 5px solid #0d6efd;
        box-shadow:0 .25rem .75rem rgba(0,0,0,.08);
    }

    .match-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .pit-photo-container {
        width: 100%;
        height: 350px;
        background-color: #212529;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .pit-photo-container:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }

    .pit-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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
        font-size: 0.9rem;
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
                        <i class="fas fa-search-plus text-info me-2"></i> Derin Analiz Dosyası
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
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline">Tabloya Dön</span>
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-dark text-white border-0">
                            <h5 class="m-0 fw-bold"><i class="fas fa-wrench text-warning me-2"></i> Robot Bilgileri (Pit)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($data['pit_data'])): ?>

                                <?php if (!empty($data['pit_data']['photo_path'])): ?>
                                    <div class="pit-photo-container mb-3 border shadow-sm" data-bs-toggle="modal" data-bs-target="#imageModal" title="Tam Ekran Görmek İçin Tıklayın">
                                        <img src="<?= htmlspecialchars($data['pit_data']['photo_path']) ?>" alt="Robot Fotoğrafı">
                                        <div class="zoom-overlay"><i class="fas fa-search-plus"></i> Büyüt</div>
                                    </div>
                                <?php else: ?>
                                    <div class="pit-photo-container mb-3 border bg-light text-muted">
                                        <i class="fas fa-camera fa-3x mb-2"></i><br>Fotoğraf Yok
                                    </div>
                                <?php endif; ?>

                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted">Kütle:</span>
                                        <strong><?= $data['pit_data']['robot_weight'] ?> kg</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted">Şase:</span>
                                        <strong><?= strtoupper($data['pit_data']['drivetrain_type']) ?> <?= $data['pit_data']['swerve_type'] ? '(' . strtoupper($data['pit_data']['swerve_type']) . ')' : '' ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted">Boyut:</span>
                                        <strong class="text-capitalize"><?= $data['pit_data']['robot_dimensions'] ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-1">
                                        <span class="text-muted">Mekanizma:</span>
                                        <strong class="text-capitalize"><?= $data['pit_data']['mechanism_type'] ?></strong>
                                    </li>
                                </ul>

                                <?php if (!empty($data['pit_data']['scout_comments'])): ?>
                                    <div class="p-3 bg-light rounded-3 border border-warning border-start-0 border-end-0 shadow-sm">
                                        <small class="text-muted fw-bold d-block mb-1"><i class="fas fa-comment-dots text-warning me-1"></i> Scout Notu:</small>
                                        <span class="fst-italic text-dark">"<?= htmlspecialchars($data['pit_data']['scout_comments']) ?>"</span>
                                    </div>
                                <?php endif; ?>

                            <?php else: ?>
                                <div class="alert alert-warning m-0 shadow-sm rounded-3"><i class="fas fa-exclamation-triangle me-2"></i> Bu takım için henüz Pit verisi girilmemiş.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-primary text-white border-0">
                            <h5 class="m-0 fw-bold"><i class="fas fa-chart-line text-info me-2"></i> Yakıt Performansı</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <?php if (empty($data['scout_matches'])): ?>
                                <div class="text-center text-muted p-4">
                                    <i class="fas fa-chart-bar fa-3x mb-3 opacity-50"></i><br>
                                    Yeterli maç verisi yok.
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
                    <h5 class="m-0 fw-bold"><i class="fas fa-clipboard-list text-warning me-2"></i> Maç Gözlemleri</h5>
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
                                    <div class="card match-card h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column">

                                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                                <h4 class="text-primary fw-bold m-0"><i class="fas fa-gamepad text-secondary me-2"></i> <?= $prettyMatchName ?></h4>

                                                <?php if ($videoKey): ?>
                                                    <button class="btn btn-sm btn-danger fw-bold shadow-sm rounded-pill px-3"
                                                            data-bs-toggle="modal" data-bs-target="#videoModal"
                                                            onclick="loadVideo('<?= $videoKey ?>')">
                                                        <i class="fab fa-youtube"></i> Maçı İzle
                                                    </button>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary rounded-pill px-3 py-2 opacity-75"><i class="fas fa-video-slash me-1"></i> Video Yok</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="row text-center g-2 flex-grow-1">
                                                <div class="col-6 col-md-3">
                                                    <div class="p-2 bg-info rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-white stat-box-label d-block mb-1">Auto Yakıt</span>
                                                        <span class="fs-3 text-white stat-box-value"><?= $match['auto_fuel'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="p-2 bg-success rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-white stat-box-label d-block mb-1">Teleop Yakıt</span>
                                                        <span class="fs-3 text-white stat-box-value"><?= $match['teleop_fuel'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="p-2 bg-warning rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-dark stat-box-label d-block mb-1" style="text-shadow: none;">Tırmanma</span>
                                                        <span class="fs-5 text-dark stat-box-value text-capitalize lh-1 mt-1" style="text-shadow: none;"><?= $match['teleop_climb'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="p-2 bg-danger rounded h-100 d-flex flex-column justify-content-center shadow-sm">
                                                        <span class="text-white stat-box-label d-block mb-1">Feed Kalitesi</span>
                                                        <span class="fs-5 text-white stat-box-value text-capitalize lh-1 mt-1"><?= $match['teleop_shooting'] ?? 'Bilinmiyor' ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($match['auto_path'])): ?>
                                                <div class="mt-3 text-center border-top pt-3">
                                                    <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-route text-primary me-1"></i> Çizilen Otonom Rotası:</small>
                                                    <div class="rounded shadow-sm mx-auto overflow-hidden border border-secondary"
                                                         style="max-width: 300px; max-height: 150px; background-image: url('/dist/img/saha.png'); background-size: cover; background-position: center;">

                                                        <img src="<?= $match['auto_path'] ?>"
                                                             class="img-fluid w-100 h-100"
                                                             style="object-fit: contain;"
                                                             alt="Otonom Rotası">
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
                                    <h5 class="text-dark fw-bold">Veri Yok</h5>
                                    Henüz bu takım için hiç saha gözlem verisi girilmemiş.
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
                <h4 class="modal-title text-white fw-bold"><i class="fab fa-youtube text-danger me-2"></i> TBA Maç Kaydı</h4>
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
                <small class="text-secondary"><i class="fas fa-robot me-1"></i> FRC <?= htmlspecialchars($data['team_info']['team_number']) ?> Analiz Paneli</small>
                <button type="button" class="btn btn-sm btn-outline-secondary text-white" data-bs-dismiss="modal" onclick="stopVideo()">Kapat</button>
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
                    <img src="<?= htmlspecialchars($data['pit_data']['photo_path']) ?>" class="img-fluid rounded shadow-lg" alt="Büyük Robot Fotoğrafı" style="max-height: 85vh; object-fit: contain; background: rgba(0,0,0,0.5);">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
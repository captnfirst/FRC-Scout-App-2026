<style>
    /* Üst Başlık Gradient Tasarımı */
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Başlık İçi Şık Geri Butonu */
    .back-btn-custom {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        padding: 10px 18px;
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

    /* Deneme Maçları Butonu */
    .practice-btn-custom {
        background-color: rgba(123, 45, 139, 0.25);
        color: white;
        border: 1px solid rgba(200, 130, 220, 0.5);
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
        transition: all 0.2s ease;
        backdrop-filter: blur(5px);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .practice-btn-custom:hover {
        background-color: #7b2d8b;
        color: white;
        box-shadow: 0 4px 10px rgba(123,45,139,0.3);
    }

    /* Modern Maç Kartı Tasarımı */
    .match-card {
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
    }

    .match-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Verisi Girilmiş (Tamamlanan) Maçlar İçin */
    .match-scouted {
        background-color: #f8f9fa !important;
        border-left-color: #198754 !important; /* Yeşil Accent */
        opacity: 0.85; /* Dikkat dağıtmaması için biraz soluk */
    }

    /* Veri Girilmeye Hazır (Aktif) Maçlar İçin */
    .match-active {
        background-color: #ffffff !important;
        border-left-color: #0d6efd !important; /* Mavi Accent */
    }

    /* Henüz Zamanı Gelmemiş Maçlar İçin */
    .match-locked {
        background-color: #fcfcfc !important;
        border-left-color: #adb5bd !important; /* Gri Accent */
    }

    /* Maç İsmi/Numarası Stili */
    .match-title {
        font-weight: 800;
        font-size: 1.3rem;
        letter-spacing: 0.5px;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-calendar-check text-warning me-2"></i> Maç Fikstürü
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-primary fs-6 me-2 shadow-sm d-inline-block mb-1 mb-md-0">
                            <i class="fas fa-robot me-1"></i> FRC <?= htmlspecialchars(str_replace('frc', '', $data['secilen_takim'])) ?>
                        </span>
                        <span class="badge bg-info text-dark fs-6 shadow-sm d-inline-block">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= strtoupper(htmlspecialchars($data['secilen_turnuva'])) ?>
                        </span>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-shrink-0 flex-wrap justify-content-end">
                    <a href="/default/practice_matches/<?= htmlspecialchars($data['secilen_takim']) ?>/<?= htmlspecialchars($data['secilen_turnuva']) ?>" class="practice-btn-custom">
                        <i class="fas fa-flask me-md-2"></i> <span class="d-none d-md-inline">Deneme Maçları</span>
                    </a>
                    <a href="/default/teams/<?= htmlspecialchars($data['secilen_turnuva']) ?>" class="back-btn-custom">
                        <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline">Takımlara Dön</span>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <?php if (isset($data['maclar']['error'])): ?>
                        <div class="alert alert-danger shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> API Bağlantı Hatası</h5>
                            <p class="mb-0"><?= $data['maclar']['message'] ?></p>
                        </div>

                    <?php elseif (!empty($data['maclar'])):

                        // Maçları numarasına göre sırala
                        usort($data['maclar'], fn($a, $b) => $a['match_number'] <=> $b['match_number']);
                        $current_time = time();

                        foreach ($data['maclar'] as $match):

                            // comp_level (qm) ve match_number birleşimi (Örn: QM 12)
                            $matchName = strtoupper($match['comp_level']) . ' ' . $match['match_number'];
                            $matchTime = $match['predicted_time'] ?? $match['time'] ?? 0;

                            // Maçın aktif olma zamanı (Planlanandan 15 dk önce açılır)
                            $timeThreshold = $matchTime - 900;
                            $isActive = ($matchTime === 0) || ($current_time >= $timeThreshold);

                            // Bu maç scout_data tablosunda var mı?
                            $isScouted = in_array($match['key'], $data['scouted_matches']);

                            // Duruma göre CSS sınıfı ve İkon belirleme
                            if ($isScouted) {
                                $cardClass = 'match-scouted';
                                $titleColor = 'text-success';
                                $icon = 'fa-check-double';
                            } elseif ($isActive) {
                                $cardClass = 'match-active';
                                $titleColor = 'text-primary';
                                $icon = 'fa-gamepad';
                            } else {
                                $cardClass = 'match-locked';
                                $titleColor = 'text-secondary';
                                $icon = 'fa-lock';
                            }
                            ?>

                            <div class="card match-card mb-3 shadow-sm border-0 border-start border-5 <?= $cardClass ?>">
                                <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">

                                    <div class="d-flex align-items-center mb-3 mb-md-0">

                                        <div class="me-3 fs-3 <?= $titleColor ?> opacity-75 d-none d-sm-block">
                                            <i class="fas <?= $icon ?>"></i>
                                        </div>

                                        <div>
                                            <div class="match-title <?= $titleColor ?>">
                                                <?= $matchName ?>
                                            </div>

                                            <?php if ($matchTime > 0): ?>
                                                <div class="text-muted small mt-1 fw-semibold">
                                                    <i class="far fa-clock me-1"></i> Planlanan Başlangıç:
                                                    <span class="text-dark"><?= date('d.m.Y - H:i', $matchTime) ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-muted small mt-1">
                                                    <i class="fas fa-hourglass-half me-1"></i> Zaman bilgisi bekleniyor...
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="w-100 w-md-auto mt-2 mt-md-0 text-md-end">
                                        <?php if ($isScouted): ?>
                                            <button class="btn btn-outline-success bg-opacity-10 text-success border-success fw-bold w-100 w-md-auto" disabled style="cursor: default;">
                                                <i class="fas fa-check-circle me-1"></i> Veri Girildi
                                            </button>

                                        <?php elseif ($isActive): ?>
                                            <a href="/default/scout/<?= $match['key'] ?>/<?= $data['secilen_takim'] ?>/<?= $data['secilen_turnuva'] ?>"
                                               class="btn btn-primary fw-bold shadow-sm w-100 w-md-auto px-4 rounded-pill">
                                                Scout Formunu Aç <i class="fas fa-arrow-right ms-2"></i>
                                            </a>

                                        <?php else: ?>
                                            <button class="btn btn-light text-secondary border fw-bold w-100 w-md-auto" disabled title="Zaman yaklaştığında aktifleşecek">
                                                <i class="fas fa-lock me-1"></i> Yakında Açılacak
                                            </button>
                                        <?php endif; ?>

                                        <a href="/default/simulator/<?= $match['key'] ?>/<?= $data['secilen_turnuva'] ?>"
                                           class="btn btn-outline-info fw-bold shadow-sm w-100 w-md-auto px-3 rounded-pill ms-0 ms-md-2 mt-2 mt-md-0">
                                            <i class="fas fa-magic"></i> Simüle Et
                                        </a>
                                    </div>

                                </div>
                            </div>

                        <?php endforeach;

                    else: ?>
                        <div class="alert alert-warning shadow-sm rounded-3 p-4 text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-muted"></i>
                            <h5 class="fw-bold text-dark">Fikstür Bulunamadı</h5>
                            <p class="text-muted mb-0">Bu takımın oynayacağı maçlar henüz TBA tarafından sisteme girilmemiş.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </section>
</div>
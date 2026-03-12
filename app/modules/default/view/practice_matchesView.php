<style>
    .deep-dive-header {
        background: linear-gradient(135deg, #4a1942 0%, #7b2d8b 100%);
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
        color: #4a1942;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .match-card {
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
    }

    .match-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .match-scouted {
        background-color: #f8f9fa !important;
        border-left-color: #198754 !important;
        opacity: 0.85;
    }

    .match-active {
        background-color: #ffffff !important;
        border-left-color: #7b2d8b !important;
    }

    .match-title {
        font-weight: 800;
        font-size: 1.3rem;
        letter-spacing: 0.5px;
    }

    .add-match-card {
        border: 2px dashed #7b2d8b;
        border-radius: 12px;
        background: linear-gradient(135deg, #fdf6ff 0%, #f5e6fa 100%);
        transition: all 0.2s;
    }

    .add-match-card:hover {
        border-color: #4a1942;
        box-shadow: 0 6px 16px rgba(123, 45, 139, 0.15);
    }

    .practice-badge {
        background: linear-gradient(135deg, #7b2d8b, #4a1942);
        color: white;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 text-truncate">
                        <i class="fas fa-flask text-warning me-2"></i> Deneme Maçları
                        <span class="practice-badge ms-2">PRACTICE</span>
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-dark fs-6 me-2 shadow-sm d-inline-block mb-1 mb-md-0">
                            <i class="fas fa-robot me-1 text-primary"></i> FRC <?= htmlspecialchars(str_replace('frc', '', $data['secilen_takim'])) ?>
                        </span>
                        <span class="badge bg-info text-dark fs-6 shadow-sm d-inline-block">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= strtoupper(htmlspecialchars($data['secilen_turnuva'])) ?>
                        </span>
                    </div>
                </div>

                <a href="/default/matches/<?= htmlspecialchars($data['secilen_takim']) ?>/<?= htmlspecialchars($data['secilen_turnuva']) ?>" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline">Maç Fikstürüne Dön</span>
                </a>
            </div>

            <!-- Yeni Deneme Maçı Ekle Kartı -->
            <div class="add-match-card p-4 mb-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-plus-circle fa-2x text-purple" style="color:#7b2d8b;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Yeni Deneme Maçı Ekle</h5>
                <p class="text-muted small mb-3">TBA'da olmayan deneme maçı verilerini buradan sisteme girebilirsiniz.</p>

                <form action="/default/add_practice_match" method="POST" class="d-flex justify-content-center gap-2 flex-wrap align-items-center">
                    <input type="hidden" name="team_key" value="<?= htmlspecialchars($data['secilen_takim']) ?>">
                    <input type="hidden" name="event_key" value="<?= htmlspecialchars($data['secilen_turnuva']) ?>">

                    <div class="input-group" style="max-width: 220px;">
                        <span class="input-group-text bg-white fw-bold text-secondary">PM</span>
                        <input type="number" name="match_number" class="form-control fw-bold text-center"
                               placeholder="Maç No" min="1" max="999" required
                               style="border-radius: 0 8px 8px 0; font-size: 1.1rem;">
                    </div>

                    <button type="submit" class="btn btn-lg fw-bold shadow-sm px-4"
                            style="background: linear-gradient(135deg, #7b2d8b, #4a1942); color: white; border-radius: 10px;">
                        <i class="fas fa-play me-2"></i> Scout Formunu Aç
                    </button>
                </form>
            </div>

            <!-- Mevcut Deneme Maçları Listesi -->
            <div class="row">
                <div class="col-12">

                    <?php if (!empty($data['practice_matches'])): ?>

                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="fas fa-list me-2"></i> Girilen Deneme Maçları
                            <span class="badge bg-secondary ms-2"><?= count($data['practice_matches']) ?></span>
                        </h6>

                        <?php foreach ($data['practice_matches'] as $pm):
                            $isScouted = in_array($pm['match_key'], $data['scouted_matches']);
                            $cardClass = $isScouted ? 'match-scouted' : 'match-active';
                            $titleColor = $isScouted ? 'text-success' : 'text-purple';
                            $icon = $isScouted ? 'fa-check-double' : 'fa-flask';
                        ?>

                            <div class="card match-card mb-3 shadow-sm border-0 border-start border-5 <?= $cardClass ?>">
                                <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">

                                    <div class="d-flex align-items-center mb-3 mb-md-0">
                                        <div class="me-3 fs-3 opacity-75 d-none d-sm-block" style="color: <?= $isScouted ? '#198754' : '#7b2d8b' ?>;">
                                            <i class="fas <?= $icon ?>"></i>
                                        </div>
                                        <div>
                                            <div class="match-title" style="color: <?= $isScouted ? '#198754' : '#7b2d8b' ?>;">
                                                PM <?= htmlspecialchars($pm['match_number']) ?>
                                                <span class="practice-badge ms-2" style="font-size:0.65rem;">PRACTICE</span>
                                            </div>
                                            <div class="text-muted small mt-1 fw-semibold">
                                                <i class="far fa-clock me-1"></i> Eklendi:
                                                <span class="text-dark"><?= date('d.m.Y - H:i', strtotime($pm['created_at'])) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-100 w-md-auto mt-2 mt-md-0 text-md-end">
                                        <?php if ($isScouted): ?>
                                            <button class="btn btn-outline-success bg-opacity-10 text-success border-success fw-bold w-100 w-md-auto" disabled style="cursor: default;">
                                                <i class="fas fa-check-circle me-1"></i> Veri Girildi
                                            </button>
                                        <?php else: ?>
                                            <a href="/default/scout/<?= htmlspecialchars($pm['match_key']) ?>/<?= htmlspecialchars($data['secilen_takim']) ?>/<?= htmlspecialchars($data['secilen_turnuva']) ?>"
                                               class="btn fw-bold shadow-sm w-100 w-md-auto px-4 rounded-pill"
                                               style="background: linear-gradient(135deg, #7b2d8b, #4a1942); color: white;">
                                                Scout Formunu Aç <i class="fas fa-arrow-right ms-2"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="alert shadow-sm rounded-3 p-4 text-center" style="background: linear-gradient(135deg, #fdf6ff, #f5e6fa); border: 1px solid #d6a8e8;">
                            <i class="fas fa-flask fa-3x mb-3" style="color: #7b2d8b; opacity: 0.5;"></i>
                            <h5 class="fw-bold text-dark">Henüz Deneme Maçı Girilmedi</h5>
                            <p class="text-muted mb-0">Yukarıdaki formu kullanarak bu takım için deneme maçı verisi ekleyebilirsiniz.</p>
                        </div>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </section>
</div>

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

    /* Takım Kartı Özelleştirmeleri */
    .team-card {
        border-radius: 15px;
        border: none;
        border-bottom: 4px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        background-color: #ffffff;
    }

    .team-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Verisi Girilmiş (İncelenmiş) Takımlar */
    .pit-scouted {
        background-color: #f8f9fa !important;
        border-bottom-color: #198754 !important; /* Yeşil Alt Çizgi */
    }

    /* İşlem Bekleyen (Aktif) Takımlar */
    .pit-pending {
        border-bottom-color: #ffc107 !important; /* Sarı Alt Çizgi */
    }
    .pit-pending:hover {
        border-bottom-color: #fd7e14 !important; /* Üzerine gelince turuncu/sarı parlar */
    }

    /* Takım Avatarı (Robot İkonu Çemberi) */
    .team-avatar {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        font-size: 1.8rem;
        transition: all 0.3s;
    }

    /* İncelenmemiş takım avatarı (Sarımtırak) */
    .avatar-pending {
        background: rgba(255, 193, 7, 0.15);
        color: #d39e00;
    }
    .pit-pending:hover .avatar-pending {
        background: #ffc107;
        color: #212529;
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
    }

    /* İncelenmiş takım avatarı (Yeşilimsi ve Soluk) */
    .avatar-scouted {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        opacity: 0.8;
    }

    /* Takım İsmi ve Numara Stilleri */
    .team-name {
        font-size: 0.9rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #6c757d;
    }
    .team-number {
        font-size: 1.5rem;
        font-weight: 900;
        color: #212529;
        letter-spacing: -0.5px;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-tools text-warning me-2"></i> Pit Scouting
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-warning text-dark fs-6 shadow-sm d-inline-block">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= strtoupper(htmlspecialchars($data['secilen_turnuva'])) ?>
                        </span>
                    </div>
                </div>

                <a href="/default/pit_tournaments" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline">Turnuvalara Dön</span>
                </a>
            </div>

            <div class="row g-3">
                <?php
                if (!empty($data['takimlar'])):

                    // Takımları küçükten büyüğe sırala
                    usort($data['takimlar'], fn($a, $b) => $a['team_number'] <=> $b['team_number']);

                    foreach ($data['takimlar'] as $team):
                        // Bu takıma daha önce pit verisi girilmiş mi?
                        $isScouted = in_array($team['key'], $data['pit_scouted_teams']);

                        // Duruma göre CSS sınıflarını belirliyoruz
                        $cardClass = $isScouted ? 'pit-scouted' : 'pit-pending';
                        $avatarClass = $isScouted ? 'avatar-scouted' : 'avatar-pending';
                        ?>

                        <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                            <div class="card h-100 team-card shadow-sm <?= $cardClass ?>">
                                <div class="card-body text-center p-3 d-flex flex-column">

                                    <div class="team-avatar <?= $avatarClass ?>">
                                        <i class="fas <?= $isScouted ? 'fa-check-circle' : 'fa-robot' ?>"></i>
                                    </div>

                                    <div class="team-number mb-1 text-dark">
                                        <?= $team['team_number'] ?>
                                    </div>
                                    <p class="team-name mb-3" title="<?= htmlspecialchars($team['nickname']) ?>">
                                        <?= htmlspecialchars($team['nickname']) ?>
                                    </p>

                                    <div class="mt-auto">
                                        <?php if ($isScouted): ?>
                                            <button class="btn btn-sm btn-outline-success w-100 fw-bold rounded-pill" disabled style="opacity: 0.85;">
                                                <i class="fas fa-check-double"></i> İncelendi
                                            </button>
                                        <?php else: ?>
                                            <a href="/default/pit_scout/<?= $team['key'] ?>/<?= $data['secilen_turnuva'] ?>"
                                               class="btn btn-sm btn-warning w-100 fw-bold rounded-pill shadow-sm" style="color: #212529;">
                                                Pite Git <i class="fas fa-wrench ms-1" style="font-size: 0.75rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php endforeach;
                else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning shadow-sm rounded-3 p-4 text-center">
                            <i class="fas fa-users-slash fa-3x mb-3 text-muted"></i>
                            <h5 class="fw-bold text-dark">Takım Listesi Boş</h5>
                            <p class="text-muted mb-0">Bu turnuvada incelenecek takım bulunamadı veya API'den veri çekilemedi.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>
</div>
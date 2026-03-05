<style>
    /* Üst Başlık Gradient Tasarımı */
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Takım Kartı Özelleştirmeleri */
    .team-card {
        border-radius: 15px;
        border: none;
        border-bottom: 4px solid #e2e8f0; /* Pasif Durum Alt Çizgisi */
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        background-color: #ffffff;
    }

    /* Kartın Üzerine Gelince Zıplama ve Renk Değişimi */
    .team-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1) !important;
        border-bottom-color: #0d6efd; /* Vurgu Rengi */
    }

    /* Takım Avatarı (Robot İkonu Çemberi) */
    .team-avatar {
        width: 65px;
        height: 65px;
        background: rgba(13, 110, 253, 0.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        font-size: 1.8rem;
        color: #0d6efd;
        transition: all 0.3s;
    }

    /* Karta yaklaşınca avatarın içi dolsun */
    .team-card:hover .team-avatar {
        background: #0d6efd;
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
    }

    /* Takım İsmi Kısaltma ve Stil */
    .team-name {
        font-size: 0.9rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #6c757d;
    }

    /* Numara Stili */
    .team-number {
        font-size: 1.5rem;
        font-weight: 900;
        color: #212529;
        letter-spacing: -0.5px;
    }
    /* Header İçi Şık Geri Butonu */
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
        color: #1e3c72; /* Üzerine gelince lacivert yazıya döner */
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">

                <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate pe-3">
                    <i class="fas fa-users-cog text-info me-2"></i> Takımlar
                </h2>

                <a href="/default/tournaments" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline">Geri Dön</span>
                </a>

            </div>

            <div class="row g-3">
                <?php
                // API Hatası Kontrolü
                if (isset($data['takimlar']['error'])): ?>
                    <div class="col-12">
                        <div class="alert alert-danger shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold"><i class="fas fa-exclamation-circle me-2"></i> Veri Çekilemedi</h5>
                            <p class="mb-0"><?= $data['takimlar']['message'] ?></p>
                        </div>
                    </div>

                <?php
                // Takımlar Listesi
                elseif (!empty($data['takimlar'])):
                    // Takım numarasına göre küçükten büyüğe sıralama
                    usort($data['takimlar'], fn($a, $b) => $a['team_number'] <=> $b['team_number']);

                    foreach ($data['takimlar'] as $team): ?>

                        <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                            <div class="card h-100 team-card shadow-sm">
                                <div class="card-body text-center p-3 d-flex flex-column">

                                    <div class="team-avatar">
                                        <i class="fas fa-robot"></i>
                                    </div>

                                    <div class="team-number mb-1">
                                        <?= $team['team_number'] ?>
                                    </div>

                                    <p class="team-name mb-3" title="<?= htmlspecialchars($team['nickname']) ?>">
                                        <?= htmlspecialchars($team['nickname']) ?>
                                    </p>

                                    <div class="mt-auto">
                                        <a href="/default/matches/<?= $team['key'] ?>/<?= $data['secilen_turnuva'] ?>"
                                           class="btn btn-sm btn-outline-primary w-100 fw-bold rounded-pill" style="letter-spacing: 0.5px;">
                                            Maçları Gör <i class="fas fa-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php endforeach;

                // Takım Yoksa
                else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning shadow-sm rounded-3 p-4 text-center">
                            <i class="fas fa-users-slash fa-3x mb-3 text-muted"></i>
                            <h5 class="fw-bold text-dark">Takım Listesi Boş</h5>
                            <p class="text-muted mb-0">Bu turnuvaya henüz TBA üzerinden takım ataması yapılmamış gibi görünüyor.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>
</div>
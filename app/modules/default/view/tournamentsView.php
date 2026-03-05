<style>
    /* Üst Başlık Gradient Tasarımı */
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Turnuva Kartı Özelleştirmeleri */
    .tournament-card {
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* Kartın Üzerine Gelince Zıplama ve Parlama Efekti */
    .tournament-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        border-color: rgba(13, 110, 253, 0.5) !important;
    }

    /* Üzerine gelince alttaki okun sağa doğru kayması */
    .tournament-card:hover .arrow-icon {
        transform: translateX(6px);
    }
    .arrow-icon {
        transition: transform 0.3s ease;
    }

    .font-monospace {
        letter-spacing: 1px;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="deep-dive-header flex-grow-1">
                    <h2 class="m-0 fw-bold">
                        <i class="fas fa-trophy text-warning me-2"></i> Turnuvalar
                    </h2>
                </div>
            </div>

            <?php if (isset($data['tournaments']['error'])): ?>
                <div class="alert alert-danger shadow-sm rounded-3 p-4">
                    <h5 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> API Bağlantı Hatası</h5>
                    <p class="mb-0"><?= $data['tournaments']['message'] ?></p>
                </div>

            <?php elseif (!empty($data['tournaments'])): ?>
                <?php
                // Turnuvaları Başlangıç Tarihine Göre Sıralıyoruz
                usort($data['tournaments'], function ($a, $b) {
                    $dateA = isset($a['start_date']) ? $a['start_date'] : '9999-12-31';
                    $dateB = isset($b['start_date']) ? $b['start_date'] : '9999-12-31';
                    return $dateA <=> $dateB;
                });
                ?>
                <div class="row g-4">
                    <?php foreach ($data['tournaments'] as $event): ?>
                        <div class="col-12 col-md-6 col-xl-4 mb-3">
                            <a href="/default/teams/<?= $event['key'] ?>" class="text-decoration-none text-dark d-block h-100">
                                <div class="card h-100 tournament-card shadow-sm bg-white">

                                    <div class="card-header bg-dark text-white border-0 py-2">
                                        <small class="fw-bold text-uppercase" style="letter-spacing: 1px;">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            <?= isset($event['city']) ? $event['city'] : 'Bilinmeyen Konum' ?>
                                            <?= isset($event['country']) ? ' / ' . $event['country'] : '' ?>
                                        </small>
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold text-primary mb-3 lh-base">
                                            <?= $event['name'] ?>
                                        </h5>

                                        <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary font-monospace fs-6 px-3 py-2">
                                                <i class="fas fa-hashtag me-1 text-muted"></i><?= strtoupper($event['key']) ?>
                                            </span>

                                            <span class="text-secondary fw-bold small">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?= isset($event['start_date']) ? date('d.m.Y', strtotime($event['start_date'])) : 'Tarih Belirsiz' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-light border-0 text-center py-3">
                                        <span class="fw-bold text-dark fs-6">
                                            Takımları Gör <i class="fas fa-arrow-right ms-2 text-primary arrow-icon"></i>
                                        </span>
                                    </div>

                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="alert alert-warning shadow-sm rounded-3 p-4 text-center">
                    <i class="fas fa-calendar-times fa-3x mb-3 text-muted"></i>
                    <h5 class="fw-bold text-dark">Aktif Turnuva Bulunamadı</h5>
                    <p class="text-muted mb-0">Takımınızın kayıtlı olduğu herhangi bir 2026 turnuvası tespit edilemedi.</p>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>
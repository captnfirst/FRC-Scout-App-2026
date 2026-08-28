<style>
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .tournament-card {
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .tournament-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        border-color: rgba(13, 110, 253, 0.5) !important;
    }
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
                <div class="deep-dive-header flex-grow-1 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="m-0 fw-bold">
                            <i class="fas fa-trophy text-warning me-2"></i> <?= __('match_tournaments_title') ?>
                        </h2>
                        <div class="mt-2">
                            <span class="badge bg-white text-primary fs-6 shadow-sm me-2">
                                <i class="fas fa-robot me-1"></i> <?= htmlspecialchars(strtoupper($data['active_team'] ?? 'FRC6459')) ?>
                            </span>
                            <span class="badge bg-white text-dark fs-6 shadow-sm">
                                <i class="far fa-calendar-alt me-1"></i> <?= __('active_season') ?> <?= htmlspecialchars($data['active_year'] ?? date('Y')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($data['tournaments']['error']) && $data['tournaments']['error']): ?>
                <div class="alert alert-danger shadow-sm rounded-3 p-4">
                    <h5 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> TBA API</h5>
                    <p class="mb-2"><?= htmlspecialchars($data['tournaments']['message']) ?></p>
                    <a href="/default/settings" class="btn btn-danger font-weight-bold mt-2 shadow-sm">
                        <i class="fas fa-key mr-1"></i> <?= __('open_settings') ?>
                    </a>
                </div>

            <?php elseif (!empty($data['tournaments']) && is_array($data['tournaments'])): ?>
                <?php
                usort($data['tournaments'], function ($a, $b) {
                    $dateA = isset($a['start_date']) ? $a['start_date'] : '9999-12-31';
                    $dateB = isset($b['start_date']) ? $b['start_date'] : '9999-12-31';
                    return $dateA <=> $dateB;
                });
                ?>
                <div class="row g-4">
                    <?php foreach ($data['tournaments'] as $event): ?>
                        <div class="col-12 col-md-6 col-xl-4 mb-3">
                            <a href="/default/teams/<?= htmlspecialchars($event['key']) ?>" class="text-decoration-none text-dark d-block h-100">
                                <div class="card h-100 tournament-card shadow-sm bg-white">

                                    <div class="card-header bg-dark text-white border-0 py-2">
                                        <small class="fw-bold text-uppercase" style="letter-spacing: 1px;">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            <?= htmlspecialchars(isset($event['city']) ? $event['city'] : (Lang::isTr() ? 'Bilinmeyen Konum' : 'Unknown Location')) ?>
                                            <?= isset($event['country']) ? ' / ' . htmlspecialchars($event['country']) : '' ?>
                                        </small>
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold text-primary mb-3 lh-base">
                                            <?= htmlspecialchars($event['name']) ?>
                                        </h5>

                                        <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary font-monospace fs-6 px-3 py-2">
                                                <i class="fas fa-hashtag me-1 text-muted"></i><?= htmlspecialchars(strtoupper($event['key'])) ?>
                                            </span>

                                            <span class="text-secondary fw-bold small">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?= isset($event['start_date']) ? date('d.m.Y', strtotime($event['start_date'])) : 'TBD' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-light border-0 text-center py-3">
                                        <span class="fw-bold text-dark fs-6">
                                            <?= __('teams_list_title') ?> <i class="fas fa-arrow-right ms-2 text-primary arrow-icon"></i>
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
                    <h5 class="fw-bold text-dark"><?= Lang::isTr() ? 'Aktif Turnuva Bulunamadı' : 'No Events Found' ?></h5>
                    <p class="text-muted mb-3"><?= Lang::isTr() ? 'Takımınızın kayıtlı olduğu herhangi bir aktif turnuva tespit edilemedi.' : 'No registered tournaments were found for your team on The Blue Alliance.' ?></p>
                    <a href="/default/settings" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-sliders-h mr-1"></i> <?= __('open_settings') ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>
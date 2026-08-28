<style>
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
    .settings-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
    }
    .weight-input-group .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
        font-weight: bold;
        color: #495057;
    }
    .weight-input-group .form-control {
        border-left: none;
        font-size: 1.25rem;
        font-weight: 900;
        color: #212529;
        text-align: right;
    }
    .weight-input-group .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
    .weight-input-group {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .weight-input-group:focus-within {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    }
    .total-score-badge {
        font-size: 2rem;
        font-weight: 900;
        padding: 10px 25px;
        border-radius: 15px;
        transition: all 0.3s;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5" style="max-width: 900px; margin: 0 auto;">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-sliders-h text-warning me-2"></i> <?= __('algorithm_weights_title') ?>
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-danger text-white fs-6 shadow-sm d-inline-block">
                            <i class="fas fa-lock me-1"></i> <?= __('admin_badge') ?>
                        </span>
                    </div>
                </div>

                <a href="/default/index" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline"><?= __('nav_dashboard') ?></span>
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-header bg-dark text-white border-0 p-3">
                            <h5 class="m-0 fw-bold"><i class="fas fa-balance-scale text-warning me-2"></i> <?= __('multiplier_title') ?></h5>
                        </div>
                        <div class="card-body p-4">

                            <?php
                            $epaW = isset($data['weights']['epa']) ? $data['weights']['epa'] : 30;
                            $autoW = isset($data['weights']['auto']) ? $data['weights']['auto'] : 20;
                            $teleopW = isset($data['weights']['teleop']) ? $data['weights']['teleop'] : 40;
                            $climbW = isset($data['weights']['climb']) ? $data['weights']['climb'] : 10;
                            ?>

                            <form action="/default/save_weights" method="POST" id="weightsForm">

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-secondary mb-2"><i class="fas fa-robot text-primary me-1"></i> <?= __('epa_weight') ?></label>
                                    <div class="input-group weight-input-group input-group-lg">
                                        <span class="input-group-text w-50"><?= __('max_weight') ?></span>
                                        <input type="number" class="form-control weight-calc" name="epa_weight" value="<?= $epaW ?>" min="0" max="100" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-secondary mb-2"><i class="fas fa-route text-info me-1"></i> <?= __('auto_weight') ?></label>
                                    <div class="input-group weight-input-group input-group-lg">
                                        <span class="input-group-text w-50"><?= __('max_weight') ?></span>
                                        <input type="number" class="form-control weight-calc" name="auto_weight" value="<?= $autoW ?>" min="0" max="100" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-secondary mb-2"><i class="fas fa-gamepad text-success me-1"></i> <?= __('teleop_weight') ?></label>
                                    <div class="input-group weight-input-group input-group-lg">
                                        <span class="input-group-text w-50"><?= __('max_weight') ?></span>
                                        <input type="number" class="form-control weight-calc" name="teleop_weight" value="<?= $teleopW ?>" min="0" max="100" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-secondary mb-2"><i class="fas fa-level-up-alt text-danger me-1"></i> <?= __('climb_weight') ?></label>
                                    <div class="input-group weight-input-group input-group-lg">
                                        <span class="input-group-text w-50"><?= __('max_weight') ?></span>
                                        <input type="number" class="form-control weight-calc" name="climb_weight" value="<?= $climbW ?>" min="0" max="100" required>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted">

                                <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm rounded-pill text-dark" style="font-size: 1.2rem;">
                                    <i class="fas fa-save me-2"></i> <?= __('update_weights_btn') ?>
                                </button>

                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card settings-card bg-light border-0 sticky-top" style="top: 20px;">
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold text-muted mb-4 text-uppercase" style="letter-spacing: 1px;"><?= __('total_distribution') ?></h5>

                            <div id="totalScoreContainer" class="total-score-badge bg-success text-white shadow">
                                <span id="totalValue">100</span>
                            </div>

                            <p id="totalMessage" class="mt-3 fw-bold text-success">
                                <i class="fas fa-check-circle"></i> <?= __('ideal_ratio') ?>
                            </p>

                            <div class="alert alert-info text-start mt-4 mb-0" style="font-size: 0.9rem;">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Tip:</strong> <?= Lang::isTr() ? 'Turnuva Analizindeki 1. Seçim Puanı bu çarpanlara göre dinamik olarak hesaplanır.' : 'The 1st Pick Score in Tournament Analytics computes overall ratings according to these weights.' ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
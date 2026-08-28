<style>
    .profile-header {
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        backdrop-filter: blur(5px);
        transition: all 0.2s ease;
    }
    .back-btn-custom:hover {
        background-color: white;
        color: #1e3c72;
    }
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="profile-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-user-circle text-warning me-2"></i> <?= __('profile_title') ?>
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-primary fs-6 shadow-sm">
                            <i class="fas fa-robot me-1"></i> <?= htmlspecialchars(strtoupper($_SESSION['admin']['team_number'] ?? 'FRC')) ?>
                        </span>
                        <span class="badge <?= ($_SESSION['admin']['administrator'] ?? 0) == 1 ? 'bg-warning text-dark' : 'bg-info text-white' ?> fs-6 shadow-sm ml-1">
                            <?= ($_SESSION['admin']['administrator'] ?? 0) == 1 ? ('👑 ' . __('admin_role')) : ('⚡ ' . __('scout_role')) ?>
                        </span>
                    </div>
                </div>
                <a href="/default/index" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline"><?= __('nav_dashboard') ?></span>
                </a>
            </div>

            <?php if(isset($_SESSION['profile_flash'])): $f = $_SESSION['profile_flash']; unset($_SESSION['profile_flash']); ?>
                <div class="alert alert-<?= $f['success'] ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-<?= $f['success'] ? 'check-circle' : 'exclamation-circle' ?> me-2"></i> <?= htmlspecialchars($f['message']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['password_flash'])): $f = $_SESSION['password_flash']; unset($_SESSION['password_flash']); ?>
                <div class="alert alert-<?= $f['success'] ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-<?= $f['success'] ? 'check-circle' : 'exclamation-circle' ?> me-2"></i> <?= htmlspecialchars($f['message']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['transfer_flash'])): $f = $_SESSION['transfer_flash']; unset($_SESSION['transfer_flash']); ?>
                <div class="alert alert-<?= $f['success'] ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-<?= $f['success'] ? 'check-circle' : 'exclamation-circle' ?> me-2"></i> <?= htmlspecialchars($f['message']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <div class="row">

                <!-- Personal Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card profile-card h-100">
                        <div class="card-header bg-dark text-white p-3">
                            <h5 class="m-0 fw-bold"><i class="fas fa-id-card text-warning me-2"></i> <?= __('profile_info') ?></h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="/default/update_profile" method="POST">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark"><?= __('full_name') ?></label>
                                    <input type="text" class="form-control font-weight-bold" name="name" 
                                           value="<?= htmlspecialchars($_SESSION['admin']['name'] ?? '') ?>" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark"><?= __('email_address') ?></label>
                                    <input type="email" class="form-control font-weight-bold" name="eposta" 
                                           value="<?= htmlspecialchars($_SESSION['admin']['eposta'] ?? '') ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                    <i class="fas fa-save mr-1"></i> <?= __('save_profile') ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="col-lg-6 mb-4">
                    <div class="card profile-card h-100">
                        <div class="card-header bg-dark text-white p-3">
                            <h5 class="m-0 fw-bold"><i class="fas fa-key text-warning me-2"></i> <?= __('change_password') ?></h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="/default/change_password" method="POST">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark"><?= __('current_password') ?></label>
                                    <input type="password" class="form-control" name="current_password" placeholder="<?= __('current_password') ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark"><?= __('new_password') ?></label>
                                    <input type="password" class="form-control" name="new_password" placeholder="<?= Lang::isTr() ? 'En az 6 karakter' : 'At least 6 characters' ?>" minlength="6" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark"><?= __('confirm_password') ?></label>
                                    <input type="password" class="form-control" name="confirm_password" placeholder="<?= Lang::isTr() ? 'Şifreyi tekrar girin' : 'Re-enter password' ?>" minlength="6" required>
                                </div>
                                <button type="submit" class="btn btn-warning font-weight-bold text-dark shadow-sm">
                                    <i class="fas fa-shield-alt mr-1"></i> <?= __('change_password') ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Team Transfer Request -->
                <div class="col-12 mb-4">
                    <div class="card profile-card">
                        <div class="card-header bg-dark text-white p-3">
                            <h5 class="m-0 fw-bold"><i class="fas fa-exchange-alt text-warning me-2"></i> <?= __('team_transfer') ?></h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-lg-5 mb-4 mb-lg-0 border-right">
                                    <p class="text-muted small">
                                        <?= __('team_transfer_desc') ?>
                                    </p>
                                    <form action="/default/request_transfer" method="POST">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark"><?= __('target_team') ?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light font-weight-bold">FRC</span>
                                                </div>
                                                <input type="text" class="form-control font-weight-bold" name="target_team" placeholder="<?= Lang::isTr() ? 'Örn: 9483' : 'e.g. 9483' ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark"><?= __('transfer_note') ?></label>
                                            <textarea class="form-control" name="request_note" rows="2" placeholder="..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-info font-weight-bold shadow-sm">
                                            <i class="fas fa-paper-plane mr-1"></i> <?= __('send_transfer_request') ?>
                                        </button>
                                    </form>
                                </div>

                                <div class="col-lg-7">
                                    <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-history text-secondary mr-1"></i> <?= __('transfer_history') ?></h6>
                                    <?php if(empty($data['user_requests'])): ?>
                                        <div class="p-3 text-center text-muted bg-light rounded">
                                            <i class="fas fa-inbox mr-1"></i> <?= Lang::isTr() ? 'Bekleyen veya geçmiş bir transfer talebiniz yok.' : 'You have no pending or past transfer requests.' ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover align-middle mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th><?= __('target_team') ?></th>
                                                        <th><?= __('date') ?></th>
                                                        <th><?= __('transfer_note') ?></th>
                                                        <th><?= __('status') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($data['user_requests'] as $req): ?>
                                                        <tr>
                                                            <td class="font-weight-bold text-primary">
                                                                <?= htmlspecialchars(strtoupper($req['target_team'])) ?>
                                                            </td>
                                                            <td class="small text-muted">
                                                                <?= date('d.m.Y H:i', strtotime($req['created_at'])) ?>
                                                            </td>
                                                            <td class="small text-muted">
                                                                <?= !empty($req['request_note']) ? htmlspecialchars($req['request_note']) : '-' ?>
                                                            </td>
                                                            <td>
                                                                <?php if($req['status'] === 'pending'): ?>
                                                                    <span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i> <?= __('status_pending') ?></span>
                                                                <?php elseif($req['status'] === 'approved'): ?>
                                                                    <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> <?= __('status_approved') ?></span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> <?= __('status_rejected') ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</div>

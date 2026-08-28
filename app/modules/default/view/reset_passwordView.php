<style>
    body.login-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    .reset-box {
        width: 440px;
        margin: 40px auto;
    }
    .login-card-custom {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }
    .login-header-custom {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 30px 20px 25px 20px;
        text-align: center;
    }
    .login-header-custom h3 {
        font-weight: 800;
        letter-spacing: 1.5px;
        margin: 0;
        font-size: 1.6rem;
    }
    .login-header-custom p {
        color: rgba(255, 255, 255, 0.85);
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }
    .login-body-custom {
        padding: 35px 30px;
        background-color: #ffffff;
    }
    .input-group-custom .form-control {
        border-radius: 8px 0 0 8px;
        padding: 12px 15px;
        height: auto;
        border-color: #e2e8f0;
        background-color: #f8f9fa;
    }
    .input-group-custom .form-control:focus {
        border-color: #2a5298;
        background-color: #ffffff;
        box-shadow: none;
    }
    .input-group-custom .input-group-text {
        border-radius: 0 8px 8px 0;
        border-color: #e2e8f0;
        background-color: #f8f9fa;
        color: #a0aec0;
    }
    .btn-action-custom {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: bold;
        letter-spacing: 1px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-action-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(42, 82, 152, 0.3);
        color: white;
    }
</style>

<div class="reset-box">
    <div class="card login-card-custom">

        <div class="login-header-custom">
            <img src="/dist/img/frc_logo.svg" alt="FRC" style="width: 65px; height: 65px; margin-bottom: 10px;">
            <h3><?= __('app_name') ?></h3>
            <p><?= __('reset_password_title') ?></p>
        </div>

        <div class="card-body login-body-custom">

            <?php if(isset($data['error'])): ?>
                <div class="alert alert-danger p-3 mb-4 shadow-sm" style="border-radius: 8px;">
                    <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> <?= Lang::isTr() ? 'Geçersiz Bağlantı' : 'Invalid Link' ?></h6>
                    <p class="small mb-3"><?= htmlspecialchars($data['error']) ?></p>
                    <a href="/default/forgot_password" class="btn btn-sm btn-outline-danger font-weight-bold">
                        <i class="fas fa-redo mr-1"></i> <?= Lang::isTr() ? 'Yeni Sıfırlama Talebi Oluştur' : 'Request New Reset Link' ?>
                    </a>
                </div>
            <?php else: ?>

                <?php if(isset($data['msg_error'])): ?>
                    <div class="alert alert-danger p-2 mb-3 small text-center" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-circle mr-1"></i> <?= htmlspecialchars($data['msg_error']) ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info p-2 mb-3 small" style="border-radius: 8px;">
                    <i class="fas fa-user-check mr-1"></i> <?= Lang::isTr() ? 'Şifresi yenilenecek hesap:' : 'Account to reset:' ?> <strong><?= htmlspecialchars($data['eposta']) ?></strong>
                </div>

                <form action="/default/reset_password" method="post">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($data['token']) ?>">

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1"><?= __('new_password') ?></label>
                        <div class="input-group input-group-custom shadow-sm">
                            <input type="password" class="form-control" name="password" placeholder="<?= Lang::isTr() ? 'En az 6 karakter' : 'At least 6 characters' ?>" minlength="6" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark small mb-1"><?= __('confirm_password') ?></label>
                        <div class="input-group input-group-custom shadow-sm">
                            <input type="password" class="form-control" name="password_confirm" placeholder="<?= Lang::isTr() ? 'Şifreyi tekrar girin' : 'Re-enter password' ?>" minlength="6" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-shield-alt"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-action-custom text-white w-100 fs-5 mb-3 shadow">
                        <?= __('update_password_btn') ?> <i class="fas fa-check-circle ms-2"></i>
                    </button>

                </form>
            <?php endif; ?>

            <div class="text-center mt-3 border-top pt-3">
                <a href="/default/login" class="font-weight-bold text-secondary small">
                    <i class="fas fa-arrow-left mr-1"></i> <?= __('back_to_login') ?>
                </a>
            </div>

        </div>
    </div>
</div>

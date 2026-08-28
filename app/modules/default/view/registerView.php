<style>
    body.login-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    .register-box-custom {
        width: 460px;
        margin: 30px auto;
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
        padding: 30px 30px;
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
    .btn-register {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: bold;
        letter-spacing: 1px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(42, 82, 152, 0.3);
        color: white;
    }
</style>

<div class="register-box-custom">
    <div class="card login-card-custom">

        <div class="login-header-custom">
            <img src="/dist/img/frc_logo.svg" alt="FRC" style="width: 65px; height: 65px; margin-bottom: 10px;">
            <h3><?= __('app_name') ?></h3>
            <p><?= __('register_title') ?></p>
        </div>

        <div class="card-body login-body-custom">

            <?php if(isset($data['msg'])): ?>
                <div class="alert alert-danger text-center p-2 mb-3" style="border-radius: 8px; font-size: 0.9rem;">
                    <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($data['msg']) ?>
                </div>
            <?php endif; ?>

            <form action="/default/register" method="post">

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark small mb-1"><?= __('team_number') ?></label>
                    <div class="input-group input-group-custom shadow-sm">
                        <input type="text" class="form-control" name="team_number" placeholder="<?= __('team_number_placeholder') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-hashtag"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark small mb-1"><?= __('full_name') ?></label>
                    <div class="input-group input-group-custom shadow-sm">
                        <input type="text" class="form-control" name="name" placeholder="<?= __('full_name_placeholder') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark small mb-1"><?= __('email_address') ?></label>
                    <div class="input-group input-group-custom shadow-sm">
                        <input type="email" class="form-control" name="eposta" placeholder="user@team.com" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark small mb-1"><?= __('password') ?></label>
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

                <button type="submit" class="btn btn-primary btn-block btn-register text-white w-100 fs-5 mb-3 shadow">
                    <?= __('register_btn') ?> <i class="fas fa-arrow-right ms-2"></i>
                </button>

                <div class="text-center mt-3">
                    <a href="/default/login" class="font-weight-bold text-primary small">
                        <?= __('already_have_account') ?>
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    body.login-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .login-box {
        width: 420px;
        margin-top: -50px;
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
        padding: 40px 20px 30px 20px;
        text-align: center;
        position: relative;
    }
    .login-header-custom h3 {
        font-weight: 800;
        letter-spacing: 1.5px;
        margin: 0;
        font-size: 1.8rem;
    }
    .login-header-custom p {
        color: rgba(255, 255, 255, 0.8);
        margin: 5px 0 0 0;
        font-size: 1rem;
    }

    .login-body-custom {
        padding: 40px 35px;
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
    .input-group-custom .form-control:focus + .input-group-append .input-group-text {
        border-color: #2a5298;
        color: #2a5298;
        background-color: #ffffff;
    }

    .btn-login {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: bold;
        letter-spacing: 1px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(42, 82, 152, 0.3);
        color: white;
    }
</style>

<div class="login-box">
    <div class="card login-card-custom">

        <div class="login-header-custom">
            <img src="/dist/img/frc_logo.svg" alt="FRC" style="width: 70px; height: 70px; margin-bottom: 12px;">
            <h3><?= __('app_name') ?></h3>
            <p><?= __('app_slogan') ?></p>
        </div>

        <div class="card-body login-body-custom">

            <?php if(isset($_GET['reset_success'])): ?>
                <div class="alert alert-success text-center p-2 mb-4" style="border-radius: 8px; font-size: 0.9rem;">
                    <i class="fas fa-check-circle me-1"></i> <?= Lang::isTr() ? 'Şifreniz başarıyla sıfırlandı! Yeni şifrenizle giriş yapabilirsiniz.' : 'Password reset successfully! You can now sign in with your new password.' ?>
                </div>
            <?php elseif(isset($data['msg'])): ?>
                <div class="alert alert-danger text-center p-2 mb-4" style="border-radius: 8px; font-size: 0.9rem;">
                    <i class="fas fa-exclamation-circle me-1"></i> <?= $data['msg'] ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted mb-4 fw-bold"><?= __('login_welcome') ?></p>
            <?php endif; ?>

            <form action="/default/login" method="post">

                <div class="input-group mb-3 input-group-custom shadow-sm">
                    <input type="email" class="form-control" name="eposta" placeholder="<?= __('email_address') ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-2 input-group-custom shadow-sm">
                    <input type="password" class="form-control" name="password" placeholder="<?= __('password') ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <a href="/default/forgot_password" class="text-muted small text-decoration-none">
                        <i class="fas fa-question-circle mr-1"></i> <?= __('forgot_password_link') ?>
                    </a>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block btn-login text-white w-100 fs-5 mb-3 shadow">
                            <?= __('sign_in') ?> <i class="fas fa-sign-in-alt ms-2"></i>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-3 border-top pt-3">
                    <a href="/default/register" class="font-weight-bold text-primary small">
                        <?= __('register_team_link') ?> <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
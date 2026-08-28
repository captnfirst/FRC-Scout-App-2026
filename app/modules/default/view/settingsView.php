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
    .settings-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        overflow: hidden;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-sliders-h text-warning me-2"></i> <?= __('team_settings_title') ?>
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-primary fs-6 shadow-sm">
                            <i class="fas fa-users-cog me-1"></i> <?= __('multi_tenant_badge') ?>
                        </span>
                    </div>
                </div>
                <a href="/default/index" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline"><?= __('nav_dashboard') ?></span>
                </a>
            </div>

            <?php if (isset($data['msg'])): ?>
                <div class="alert alert-<?= htmlspecialchars($data['msg']['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2"></i> <?= htmlspecialchars($data['msg']['text']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card settings-card">
                        <div class="card-header bg-dark text-white p-3">
                            <h5 class="m-0 fw-bold"><i class="fas fa-key text-warning me-2"></i> <?= __('system_config') ?></h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="/default/save_settings" method="POST" id="settingsForm">

                                <div class="form-group mb-4">
                                    <label for="team_key" class="font-weight-bold text-dark d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-robot text-primary mr-1"></i> <?= __('team_number') ?></span>
                                        <span class="badge badge-secondary font-weight-normal"><i class="fas fa-lock mr-1"></i> <?= Lang::isTr() ? 'Çalışma Alanı Kilitli' : 'Workspace Locked' ?></span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light font-weight-bold"><?= Lang::isTr() ? 'Takım' : 'Team' ?></span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg font-weight-bold bg-light" 
                                               id="team_key" name="team_key" 
                                               value="<?= htmlspecialchars(str_replace('frc', '', $data['settings']['team_key'] ?? '6459')) ?>" 
                                               readonly>
                                    </div>
                                    <small class="form-text text-muted"><?= Lang::isTr() ? 'Bu çalışma alanı <strong>' . htmlspecialchars(strtoupper($data['settings']['team_key'] ?? 'FRC6459')) . '</strong> takımına aittir. Tüm gözlem kayıtları, strateji ağırlıkları ve veriler takımınıza özel olarak izole tutulur.' : 'This workspace belongs to team <strong>' . htmlspecialchars(strtoupper($data['settings']['team_key'] ?? 'FRC6459')) . '</strong>. All scout records, weights, and strategy data remain isolated to your team.' ?></small>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="tba_api_key" class="font-weight-bold text-dark d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-satellite-dish text-danger mr-1"></i> <?= __('tba_read_key') ?></span>
                                        <a href="https://www.thebluealliance.com/account" target="_blank" class="small text-primary font-weight-normal">
                                            <i class="fas fa-external-link-alt"></i> <?= __('get_key_from_tba') ?>
                                        </a>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control font-weight-bold" 
                                               id="tba_api_key" name="tba_api_key" 
                                               placeholder="X-TBA-Auth-Key..." 
                                               value="<?= htmlspecialchars($data['settings']['tba_api_key'] ?? '') ?>" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleApiKeyBtn" title="Show / Hide">
                                                <i class="fas fa-eye" id="toggleIcon"></i>
                                            </button>
                                            <button class="btn btn-outline-info font-weight-bold" type="button" id="testTbaBtn">
                                                <i class="fas fa-bolt"></i> <?= __('test_connection') ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="testResult" class="mt-2" style="display:none;"></div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-success btn-lg font-weight-bold px-5 shadow">
                                        <i class="fas fa-save mr-2"></i> <?= __('save_settings_btn') ?>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleApiKeyBtn');
    const apiInput = document.getElementById('tba_api_key');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleBtn.addEventListener('click', function() {
        if (apiInput.type === 'password') {
            apiInput.type = 'text';
            toggleIcon.className = 'fas fa-eye-slash';
        } else {
            apiInput.type = 'password';
            toggleIcon.className = 'fas fa-eye';
        }
    });

    const testBtn = document.getElementById('testTbaBtn');
    const testResult = document.getElementById('testResult');

    testBtn.addEventListener('click', function() {
        const apiKey = apiInput.value.trim();
        const teamNum = document.getElementById('team_key').value.trim();

        if (!apiKey) {
            testResult.style.display = 'block';
            testResult.innerHTML = '<div class="alert alert-warning p-2 small m-0"><i class="fas fa-exclamation-triangle mr-1"></i> ' + (isTurkish ? 'Lütfen önce TBA API anahtarını girin.' : 'Please enter a TBA API key first.') + '</div>';
            return;
        }

        testBtn.disabled = true;
        testBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (isTurkish ? 'Test Ediliyor...' : 'Testing...');
        testResult.style.display = 'none';

        const formData = new FormData();
        formData.append('tba_api_key', apiKey);
        formData.append('team_key', teamNum);

        fetch('/default/test_tba', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            testResult.style.display = 'block';
            if (data.success) {
                testResult.innerHTML = '<div class="alert alert-success p-2 small m-0"><i class="fas fa-check-circle mr-1"></i> <strong>' + (isTurkish ? 'Bağlantı Başarılı!' : 'Connection Successful!') + '</strong> ' + (isTurkish ? 'Takım:' : 'Team:') + ' ' + (data.team_name || teamNum) + '</div>';
            } else {
                testResult.innerHTML = '<div class="alert alert-danger p-2 small m-0"><i class="fas fa-times-circle mr-1"></i> <strong>' + (isTurkish ? 'Hata:' : 'Error:') + '</strong> ' + (data.message || 'Invalid API Key') + '</div>';
            }
        })
        .catch(err => {
            testResult.style.display = 'block';
            testResult.innerHTML = '<div class="alert alert-danger p-2 small m-0"><i class="fas fa-times-circle mr-1"></i> ' + (isTurkish ? 'Sunucu bağlantı hatası!' : 'Server error or no network connectivity.') + '</div>';
        })
        .finally(() => {
            testBtn.disabled = false;
            testBtn.innerHTML = '<i class="fas fa-bolt"></i> ' + (isTurkish ? 'Bağlantıyı Test Et' : 'Test Connection');
        });
    });
});
</script>

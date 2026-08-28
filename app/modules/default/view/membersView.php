<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="font-weight-bold"><i class="fas fa-users-cog text-danger mr-2"></i> <?= __('team_roster_title') ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php if(isset($_SESSION['admin_flash'])): $f = $_SESSION['admin_flash']; unset($_SESSION['admin_flash']); ?>
                <div class="alert alert-<?= $f['success'] ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="fas fa-<?= $f['success'] ? 'check-circle' : 'exclamation-circle' ?> me-2"></i> <?= htmlspecialchars($f['message']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <!-- Incoming Transfer Requests -->
            <?php if (!empty($data['pending_requests'])): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-warning card-outline shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h3 class="card-title font-weight-bold">
                                    <i class="fas fa-user-clock mr-2"></i> <?= __('incoming_transfers') ?> 
                                    <span class="badge badge-dark ml-2"><?= count($data['pending_requests']) ?> <?= __('status_pending') ?></span>
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-bordered table-hover m-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th><?= __('full_name') ?></th>
                                            <th><?= __('email_address') ?></th>
                                            <th><?= Lang::isTr() ? 'Mevcut Takımı' : 'Previous Team' ?></th>
                                            <th><?= __('transfer_note') ?></th>
                                            <th><?= __('date') ?></th>
                                            <th style="width: 180px;"><?= __('actions') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['pending_requests'] as $req): ?>
                                            <tr>
                                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($req['user_name']) ?></td>
                                                <td><?= htmlspecialchars($req['user_email']) ?></td>
                                                <td><span class="badge badge-secondary"><?= htmlspecialchars(strtoupper($req['current_team'])) ?></span></td>
                                                <td class="small text-muted"><?= !empty($req['request_note']) ? htmlspecialchars($req['request_note']) : '-' ?></td>
                                                <td class="small text-muted"><?= date('d.m.Y H:i', strtotime($req['created_at'])) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="/default/approve_transfer/<?= intval($req['id']) ?>" 
                                                           class="btn btn-success font-weight-bold"
                                                           onclick="return confirm('<?= Lang::isTr() ? 'Bu üyeyi takımınıza dahil etmek istediğinizden emin misiniz?' : 'Are you sure you want to approve this member into your team?' ?>');">
                                                            <i class="fas fa-check mr-1"></i> <?= __('approve') ?>
                                                        </a>
                                                        <a href="/default/reject_transfer/<?= intval($req['id']) ?>" 
                                                           class="btn btn-danger font-weight-bold"
                                                           onclick="return confirm('<?= Lang::isTr() ? 'Bu talebi reddetmek istediğinizden emin misiniz?' : 'Are you sure you want to reject this request?' ?>');">
                                                            <i class="fas fa-times mr-1"></i> <?= __('reject') ?>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                
                <div class="col-md-12">
                    
                    <div class="card card-danger card-outline shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i> <?= __('add_new_user') ?> (<?= htmlspecialchars(strtoupper($data['current_team'] ?? '')) ?>)</h3>
                        </div>
                        
                        <form class="form-horizontal" action="/default/add_member" method="post">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="isim" class="col-sm-2 col-form-label font-weight-bold"><?= __('full_name') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="isim" name="name" placeholder="<?= __('full_name_placeholder') ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="eposta" class="col-sm-2 col-form-label font-weight-bold"><?= __('email_address') ?></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="eposta" name="eposta" placeholder="user@team.com" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sifre" class="col-sm-2 col-form-label font-weight-bold"><?= __('password') ?></label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="sifre" name="password" placeholder="<?= Lang::isTr() ? 'En az 6 karakter' : 'At least 6 characters' ?>" minlength="6" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="administrator" class="col-sm-2 col-form-label font-weight-bold"><?= __('admin_privileges') ?></label>
                                    <div class="col-sm-10 d-flex align-items-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="administrator" name="administrator" value="1">
                                            <label class="custom-control-label" for="administrator"><?= __('grant_admin') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-light d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger font-weight-bold px-4 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> <?= __('save_user') ?>
                                </button>
                            </div>
                            
                        </form>
                    </div>
                    
                </div>
                
            </div>
            
            <?php if(isset($_SESSION['admin'])): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <h3 class="card-title font-weight-bold"><i class="fas fa-list mr-2"></i> <?= __('team_members') ?> (<?= htmlspecialchars(strtoupper($data['current_team'] ?? '')) ?>)</h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-bordered table-striped table-hover m-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th><?= __('full_name') ?></th>
                                        <th><?= __('email_address') ?></th>
                                        <th><?= __('role') ?></th>
                                        <th><?= Lang::isTr() ? 'Güvenlik' : 'Security' ?></th>
                                        <th style="width: 100px;"><?= __('actions') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($data['admin'])): ?>
                                        <?php foreach ($data['admin'] as $admin): ?>
                                            <tr>
                                                <td class="font-weight-bold"><?= htmlspecialchars($admin['name']) ?></td>
                                                <td><?= htmlspecialchars($admin['eposta']) ?></td>
                                                <td>
                                                    <?php if ($admin['administrator'] == 1): ?>
                                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-shield-alt mr-1"></i> <?= __('admin_role') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-clipboard mr-1"></i> <?= __('scout_role') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="badge badge-success"><i class="fas fa-lock mr-1"></i> bcrypt <?= Lang::isTr() ? 'Korumalı' : 'Secured' ?></span></td>
                                                <td>
                                                    <?php if ($admin['admin_id'] != $_SESSION['admin']['admin_id']): ?>
                                                        <a href="/default/delete_member/<?= intval($admin['admin_id']); ?>" 
                                                           class="btn btn-sm btn-outline-danger" 
                                                           onclick="return confirm('<?= Lang::isTr() ? 'Bu kullanıcıyı takımınızdan silmek istediğinizden emin misiniz?' : 'Are you sure you want to remove this user from your team?' ?>');">
                                                            <i class="fa fa-trash-alt mr-1"></i> <?= __('delete') ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge badge-light text-muted"><?= Lang::isTr() ? 'Aktif Oturum' : 'Active Session' ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted p-3"><?= Lang::isTr() ? 'Henüz kayıtlı üye bulunmuyor.' : 'No team members registered yet.' ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            <?php endif; ?>
        </div>
    </section>
    
</div>

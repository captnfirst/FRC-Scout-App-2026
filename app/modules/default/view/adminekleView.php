<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Admin Ekle</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Admin Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-horizontal" action="/default/addadmin" method="post">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="isim" class="col-sm-2 col-form-label">Ad Soyad</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="isim" name="name" placeholder="İsim">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="eposta" class="col-sm-2 col-form-label">Eposta</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="eposta" name="eposta" placeholder="Eposta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sifre" class="col-sm-2 col-form-label">Şifre</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="sifre" name="password" placeholder="Şifre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="administrator" class="col-sm-2 col-form-label">Yönetici</label>
                                    <div class="col-sm-10">
                                        <input value="1" name="administrator" type="checkbox" id="checkboxPrimary1">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-danger btn-block">Kaydet</button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <?php if(isset($_SESSION['admin'])): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Admin Adı</th>
                                        <th>Eposta</th>
                                        <th>Şifre</th>
                                        <th>İşlemler</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data['admin'] as $admin):?>
                                        <tr>
                                            <td><?=$admin['name']?></td>
                                            <td><?=base64_decode($admin['eposta'])?></td>
                                            <td><?=base64_decode($admin['password'])?></td>
                                            <td>
                                                <a href="/default/deleteadmin/<?=$admin['admin_id'];?>" class="btn btn-xm btn-danger"><i class="fa fa-times"></i>Sil</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            <?php endif; ?>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
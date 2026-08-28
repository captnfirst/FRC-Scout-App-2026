<!DOCTYPE html>
<html lang="<?= Lang::current() ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= __('auth_portal') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="shortcut icon" href="/dist/img/frc_logo.svg" type="image/svg+xml">
    <style>
        .auth-lang-switch {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body class="hold-transition login-page">

<div class="auth-lang-switch">
    <div class="btn-group shadow-sm" role="group">
        <a href="/default/set_language/tr" class="btn btn-sm <?= Lang::isTr() ? 'btn-primary font-weight-bold' : 'btn-light border' ?>">
            🇹🇷 TR
        </a>
        <a href="/default/set_language/en" class="btn btn-sm <?= Lang::isEn() ? 'btn-primary font-weight-bold' : 'btn-light border' ?>">
            🇬🇧 EN
        </a>
    </div>
</div>

<?=$data['VIEW']?>

<script src="/plugins/jquery/jquery.min.js"></script>
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/dist/js/adminlte.min.js"></script>
</body>
</html>

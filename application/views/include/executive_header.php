<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('./assets/style.css'); ?>">
    <link rel="icon" href="<?= base_url('./assets/pictures/c8.jpg'); ?>" type = "image/x-icon">
    <title><?= $title ?></title>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-light">

<div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="<?= base_url('./assets/pictures/calibr8logo.png'); ?>" height="30px" alt=""></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="<?= site_url('Executive/dashboard_view') ?>">Dashboard</a>
        </li>

        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="<?= site_url('Executive/generate_reports') ?>">Generate Reports</a>
        </li>

        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="<?= site_url('Executive') ?>">Reservation</a>
        </li>

        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            View
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= site_url('Executive/dev_masterlist_view') ?>">Device Masterlist</a></li>
            <li><a class="dropdown-item" href="<?= site_url('Executive/emp_masterlist_view') ?>">Employee Masterlist</a></li>
        </ul>
        </li>
      
    </ul>

    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Profile</a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= site_url('Executive/profile_view') ?>"><i class="fas fa-user"></i> View my profile</a></li>
            <li><a class="dropdown-item" href="<?= site_url('Login/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        
        </ul>

    </ul>
    
    </div>
</div>
</nav>

<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('./assets/style.css'); ?>">
    <link rel="icon" href="<?= base_url('./assets/pictures/c8.jpg'); ?>" type = "image/x-icon">
    <title><?= $title ?></title>
</head>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><a class="navbar-brand" href="#"><div class="logo"><img src="<?= base_url('./assets/pictures/calibr8logo.png'); ?>" alt="Calibr8 Logo" height="30px"></div></a></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?= site_url('Admin') ?>">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    View
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="<?= site_url('Admin/dev_masterlist_view') ?>">Device Masterlist</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('Admin/emp_masterlist_view') ?>">Employee Masterlist</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('Admin/devApproval_view') ?>">Device Approval List</a></li>
                </ul>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Logs
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="<?= site_url('Admin/transaction_logs') ?>">Transaction Logs</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('Admin/system_logs') ?>">System Logs</a></li>
                </ul>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="<?= site_url('Admin/generate_reports') ?>">Generate Reports</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Registration
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="<?= site_url('Admin/devReg_view') ?>">Device Registration</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('Admin/empReg_view') ?>">Employee Registration</a></li>
                </ul>
                </li>

                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                My Profile
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" id="prof-nav">
                    <li><a class="dropdown-item" href="<?= site_url('Admin/profile_view') ?>">View My Profile</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('Login/logout') ?>">Logout</a></li>
                </ul>
            </ul>
            </div>

            <!-- <ul class="navbar-nav">
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Hi, Name
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" id="prof-nav">
                    <li><a class="dropdown-item" href="<?= site_url('Admin/profile_view') ?>">View My Profile</a></li>
                    <li><a class="dropdown-item" href="">Logout</a></li>
                </ul>
            </ul> -->
        
        </div>
    </nav>
    
<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>

<body>
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
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><a class="navbar-brand" href="#"><div class="logo"><img src="<?= base_url('./assets/pictures/calibr8logo.png'); ?>" alt="Calibr8 Logo" height="30px"></div></a></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?= site_url('Employee') ?>">Reservation</a>
            </li>
            
            <li class="nav-item">
            <a class="nav-link" href="<?= site_url('Employee/dev_masterlist_view') ?>">Device Masterlist</a>
            </li>

            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            My Profile
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" id="prof-nav">
                <li><a class="dropdown-item" href="<?= site_url('Employee/profile_view') ?>">View My Profile</a></li>
                <li><a class="dropdown-item" href="<?= site_url('Login/logout') ?>">Logout</a></li>
            </ul>
        </ul>
        </div>
    
    </div>
</nav>
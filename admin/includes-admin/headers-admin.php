<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <!-- <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous"> -->
    <link rel="stylesheet" type="text/css" href="../static-admin/css-admin/datatables-1.10.25.min.css" />
    <link rel="stylesheet" type="text/css" href="../static-admin/css-admin/bootstrap5.0.1.min.css" />
    <link rel="stylesheet" type="text/css" href="../static-admin/css-admin/navbar-admin.css" />
    <link rel="stylesheet" type="text/css" href="../static-admin/css-admin/index-admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    
    <title>Admin Panel</title>
    
    
</head>
<style>
    .fa,.far,.fas {
    font-family: "Font Awesome 5 Free" !important;
}
</style>
<body>
<?php
session_start();

if (($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header('Location:'.SITE_URL.'login.html');
    exit();
}
?>
<?php include ('../includes-admin/navbar-admin.php') ?>

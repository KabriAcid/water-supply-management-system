<?php
// Sets the page title, fallback to default if not provided
function set_title(string $title = 'Water Supply Management System')
{
    return htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically. Pass $page_title before including this file to override. -->
    <title><?= isset($page_title) ? set_title($page_title) : set_title() ?></title>
    <!-- Favicon: adjust path as needed based on your directory structure -->
    <link rel="icon" href="/water-supply-system/assets/img/logo.png" type="image/x-icon">

    <!-- Bootstrap CSS (Grid and Utilities) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Icon Fonts -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Google Fonts: Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

    <!-- Local CSS (adjust paths if needed) -->
    <link rel="stylesheet" href="/water-supply-system/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/water-supply-system/assets/css/style.css">

    <style>
        body {
            font-family: 'Sora', sans-serif;
            background-color: #f8f9fa; /* Light background for better contrast */
        }
    </style>
</head>
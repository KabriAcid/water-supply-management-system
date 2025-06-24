<?php
function set_title(string $title = 'Water Management System')
{
    if (isset($title)) {
        return $title;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically -->
    <title><?= set_title('Water Management System' ?? null) ?></title>
    <link rel="icon" href="/water-supply-system/assets/img/logo.png" type="image/x-icon">
    <!-- Font Awesome for icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap">


    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            font-family: 'Sora', sans-serif;
        }
    </style>

</head>

<body>

</body>

</html>
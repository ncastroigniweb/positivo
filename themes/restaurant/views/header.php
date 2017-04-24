<?php
/**
 * Created by PhpStorm.
 * User: Igniweb038
 * Date: 04/08/16
 * Time: 10:51
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= site_url() ?>"/>
    <title><?= $Settings->site_name ?> - <?= $title ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= $assets ?>images/icon.ico" />
        
    <?= put_headers() ?>

    <?php if ($this->sma->is_waiter()) { ?>
        <script type="text/javascript">

            $(document).ready(function () {
                // init notifications
                notifications();

                // recurrent notifications
                setInterval( notifications, 3000);
            });

        </script>
    <?php } ?>
    
</head>
<body>
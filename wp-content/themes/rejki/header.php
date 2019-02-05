<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="ru"> <!--<![endif]-->

<head>
    <html <?php language_attributes(); ?>>
    <meta charset="<?php bloginfo('charset'); ?>">
    <link rel="shortcut icon" href="<?= get_template_directory_uri() ?>/img/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= get_template_directory_uri() ?>/img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= get_template_directory_uri() ?>/img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= get_template_directory_uri() ?>/img/favicon/apple-touch-icon-114x114.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/libs/bootstrap/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/libs/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/libs/slick/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/libs/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/libs/Magnific-Popup-master/dist/magnific-popup.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/fonts.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/main.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/media.css">
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/css/new_styles.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/new_media.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/tabs.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/style.css">
    <script src="<?= get_template_directory_uri() ?>/libs/modernizr/modernizr.js"></script>
    <script src="https://vk.com/js/api/openapi.js?159" type="text/javascript"></script>
    <!-- <script src="https://vk.com/js/api/openapi.js" type="text/javascript"></script> -->
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="scroller"></div>
<?php include_once "inc/header.php"; ?>
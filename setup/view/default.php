<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2019 TZ Portfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

# Family website: http://www.templaza.com

# Family Support: Forum - https://www.templaza.com/Forums.html

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

?>
<!DOCTYPE html>
<html class="demo-mobile-horizontal" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo COM_TZ_PORTFOLIO_PLUS_SETUP_URL;?>/assets/images/logo.png" rel="shortcut icon" type="image/vnd.microsoft.icon"/>

    <?php if (JVERSION < 4.0 ) { ?>
        <link type="text/css" href="<?php echo JURI::root(true);?>/media/jui/css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo JURI::root(true);?>/media/jui/css/icomoon.css" rel="stylesheet" />
    <?php } else { ?>
        <link type="text/css" href="<?php echo JURI::root(true);?>/media/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo JURI::root(true);?>/media/vendor/fontawesome-free/css/fontawesome.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo JURI::base(true);?>/templates/atum/css/fontawesome.css" rel="stylesheet" />
    <?php } ?>

    <?php if($active == 'complete'){ ?>
    <link type="text/css" href="<?php echo JUri::root(); ?>/components/com_tz_portfolio_plus/css/all.min.css?<?php echo COM_TZ_PORTFOLIO_PLUS_SETUP_HASH; ?>" rel="stylesheet" />
    <?php } ?>
    <link type="text/css" href="<?php echo COM_TZ_PORTFOLIO_PLUS_SETUP_URL;?>/assets/css/style.min.css?<?php echo COM_TZ_PORTFOLIO_PLUS_SETUP_HASH; ?>" rel="stylesheet" />

    <?php if (JVERSION < 4.0 ) { ?>
        <script src="<?php echo JURI::root(true);?>/media/jui/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo JURI::root(true);?>/media/jui/js/bootstrap.min.js" type="text/javascript"></script>
    <?php } else { ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
        <script src="<?php echo JURI::root(true);?>/media/vendor/jquery/js/jquery.min.js"></script>
        <script src="<?php echo JURI::root(true);?>/media/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo JURI::root(true);?>/media/system/js/toolbar.min.js"></script>
    <?php } ?>

    <script type="text/javascript">
        <?php require(COM_TZ_PORTFOLIO_PLUS_SETUP_PATH.'/assets/js/script.js'); ?>
    </script>
</head>

<body class="step<?php echo $active;?>">

    <div class="tpp-installation">
        <div class="head text-center">
            <div class="container-fluid">
                <div class="top-bar d-flex">
                    <div>
                    <img src="<?php echo COM_TZ_PORTFOLIO_PLUS_SETUP_URL;?>/assets/images/logo.png" height="48" width="48" />
                    </div>
                    <div class="text-left">
                        <h3><?php echo JText::_('COM_TZ_PORTFOLIO_PLUS');?></h3>
                        <span><?php echo JText::_('COM_TZ_PORTFOLIO_PLUS_DESCRIPTION_3');?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <ul class="step-indicator" data-installation-steps>
                    <?php include(__DIR__ . '/default_steps.php'); ?>
                </ul>
                <div class="installation-methods">
                    <?php include(__DIR__ . '/steps/' . $activeStep->template . '.php'); ?>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="container-fluid">
                <?php include(__DIR__ . '/default_footer.php'); ?>
            </div>
        </div>
    </div>
</body>
</html>
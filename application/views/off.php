<!DOCTYPE html>
<html lang="en-us" class="no-js">

<head>
    <meta charset="utf-8">
    <title>Working On Updates | <?= get_setting()['name']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Kava Developers">
    <!-- ================= Favicons ================== -->
    <link rel="icon" href="<?= base_url() ?>asset/images/favicon.png" type="image/x-icon">
    <!-- ============== Resources style ============== -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('asset/404/') ?>css/style.css">
</head>

<body class="bubble">
    <canvas id="canvasbg"></canvas>
    <canvas id="canvas"></canvas>
    <!-- Your logo on the top left -->
    <a href="#" class="logo-link" title="back home">
        <img src="<?= base_url() ?>asset/assets/images/logo.png" class="logo" alt="Company's logo">
    </a>
    <div class="content">
        <div class="content-box">
            <div class="big-content">
                <!-- Main squares for the content logo in the background -->
                <div class="list-square">
                    <span class="square"></span>
                    <span class="square"></span>
                    <span class="square"></span>
                </div>
                <!-- Main lines for the content logo in the background -->
                <div class="list-line">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
                <!-- The animated searching tool -->
                <i class="fa fa-search color" aria-hidden="true"></i>
                <!-- div clearing the float -->
                <div class="clear"></div>
            </div>
            <!-- Your text -->
            <h1>Working On Updates</h1>
        </div>
    </div>
    <script src="<?= base_url('asset/404/') ?>js/jquery.min.js"></script>
    <script src="<?= base_url('asset/404/') ?>js/bootstrap.min.js"></script>
    <!-- Bubble plugin -->
    <script src="<?= base_url('asset/404/') ?>js/bubble.js"></script>
</body>

</html>

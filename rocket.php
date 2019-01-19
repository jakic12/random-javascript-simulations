<!DOCTYPE html>
<html style="height:100%">
    <head>
            <?php include("template/config.php") ?>
            <title>physics</title>
            <meta name="title" content ="space exploration simulator">
            <meta name="description" content="A demo that simulates forces between a rocket and multiple planets">
            <meta property="og:title" content="space exploration simulator" />
            <meta property="og:description" content="A demo that simulates forces between a rocket and multiple planets" />
            <meta property="og:image" content="<?= $domain ?>/projects/images/2DEC.png" />
    </head>
<body>
        <?php include("template/HaS.php");?>
        <div id="content">
            <canvas style="height:calc(100% - 70px); width:100%"></canvas>
        </div>
        <script src="javascript/functionality.js"></script>
    </body>

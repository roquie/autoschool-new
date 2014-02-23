<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title><?=$title?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?=$title?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=HTML::style('global/css/bootstrap.min.css')?>
    <?=HTML::style('main/css/main.css')?>
    <?=HTML::style('http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=latin,cyrillic')?>
    <?=HTML::style('global/css/font-awesome.min.css')?>
    <?=HTML::style('main/css/datepicker.css')?>
    <?=HTML::style('main/css/validation.css')?>
    <?=HTML::style('global/css/pageslide.css')?>
    <?=HTML::style('global/css/twitter.css')?>


    <?=HTML::script('global/js/jquery.min.js')?>
    <?=HTML::script('global/js/jquery-ui.min.js')?>
    <?=HTML::script('global/js/bootstrap.min.js')?>

    <!--[if IE]>
        <script src="/global/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>

<?=$navbar.PHP_EOL?>
<div id="wrap">
    <?=$content.PHP_EOL?>
    <div id="push"></div>
</div>
<?=$footer.PHP_EOL?>

</body>
</html>
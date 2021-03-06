<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saral Framework</title>
    <?php
    echo <<<CSS
        <link href="{$this->getPublicURL()}css/bootstrap/bootstrap.min.css" rel="stylesheet"> \n
CSS;
    // you can pass css files that can be used for particular page
    if (isset($data['css'])) {
        foreach ($data['css'] as $css) {
            echo "<link href='" . $this->getPublicURL() . "css/$css' rel='stylesheet' /> \n";
        }
    }
    ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php
    echo <<<JS
    <script>
        var SITE_URL = '{$this->getSiteURL()}';
    </script>
JS;
    ?>
  </head>
  <body>
  <!--  actual body content goes here, which usually differ from page to page -->
    <?php
    $this->loadView($data['view'], $data, true);
    ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php
    echo <<<JS
    <script src="{$this->getPublicURL()}scripts/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{$this->getPublicURL()}scripts/bootstrap/bootstrap.min.js"></script> \n
JS;
    // you can pass javascript files that can be used for particular page
    if (isset($data['js'])) {
        foreach ($data['js'] as $js) {
            echo "<script src='" . $this->getPublicURL() . "scripts/$js'></script> \n";
        }
    }
    ?>
  </body>
</html>
<?php
    
    use Particle\Validator\Validator;
    
    require_once '../vendor/autoload.php';

    echo "Hello Motherfunction!";

    $file = '../storage/database.db';
    if (is_writable('../storage/database.local.db')) {
        $file = '../storage/database.local.db';
    }

    $database = new medoo([
        'database_type' => 'sqlite',
        'database_file' => $file
    ]);

    $comment = new AdamApp\Comment($database);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $v = new Validator();
        $v->required('name')->lengthBetween(1, 100)->alnum(TRUE);
        $v->required('email')->email()->lengthBetween(5, 255);
        $v->required('comment')->lengthBetween(10, NULL);

        $result = $v->validate($_POST);
        if ($result->isValid()) {
            try {
                $comment
                  ->setName($_POST['name'])
                  ->setEmail($_POST['email'])
                  ->setComment($_POST['comment'])
                  ->save();
                header('Location: /');
                return;
            }
            catch (\Exception $e) {
                die($e->getMessage());
            }

        }
        else {
            dump($result->getMessages());
        }
    }
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <!-- Latest compiled and minified bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <!-- Latest compiled Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    </head>
    <body>
    <div class="container">
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->

        <!-- List comments here-->
        <?php foreach ($comment->findAll() as $c) : ?>
          <div class="panel panel-primary">
              <div class="panel-heading"><h4>On <?= $c->getSubmissionDate() ?>, <strong><?= $c->getName() ?></strong> wrote: </h4></div>
              <div class="panel-body"><?= $c->getComment(); ?></div>
          </div>
        <?php endforeach; ?>
        <!-- put comment form here -->
        <form method="post">
            <div class="form-group">
                <label for="name">Name: </label>
                <input class="form-control" type="text" name="name" placeholder="Your name">
            </div>
            <div class="form-group">
                <label for="email">Email: </label>
                <input class="form-control" type="email" name="email" placeholder="Your email">
            </div>
            <div class="form-group">
                <label for="comment">Comment: </label>
                <textarea class="form-control" name="comment" cols="30" rows="10"></textarea>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" value="Save">
        </form>

        <!-- Boilerplate Scripts -->
        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </div>
    </body>
</html>

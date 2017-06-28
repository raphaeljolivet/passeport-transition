<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= $description ?>">
  <meta name="author" content="">

  <meta property="og:type"               content="article" />
  <meta property="og:title"              content="<?= $title ?>" />
  <meta property="og:description"        content="<?= $description ?>" />
  <meta property="og:image"              content="<?= $image ?: "http://passeport-transition.fr/img/sample-passeport.png"?>" />

  <title><?= $title ?></title>

  <!-- Bootstrap Core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="css/awesome-bootstrap-checkbox.css" rel="stylesheet">
  <link href="css/landing-page.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

      </head>


      <body data-spy="scroll" data-target="#nav-root" data-offset="50">

        <!-- Header -->
        <a name="about"></a>
        <div class="intro-header">
          <div class="container">

            <div class="row">
              <div class="col-lg-12">
                <div class="intro-message">
                  <h1><a href="/">Mon Passeport Transition</a></h1>
                  <h3>par <a href="http://alternatiba.eu/alternatiba06/">Alternatiba 06</a></h3>
                </div>
              </div>
            </div>

          </div>
          <!-- /.container -->

        </div>
        <!-- /.intro-header -->

        <!-- Page Content -->

      <?= $this->section('content') ?>






  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <ul class="list-inline">
            <li>
              <a href="mentions.php">Mentions légales</a>
            </li>
            <li class="footer-menu-divider">&sdot;</li>
            <li>
              <a href="mailto:contact@passeport-transition.fr">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- jQuery -->
  <script src="js/jquery.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>



</body>

</html>
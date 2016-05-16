<!-- Page comportant le tableau des keys -->


<!DOCTYPE html>
<html lang="fr">
    <?php
        
        session_start();
    ?>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MSDN LLB Keys">
    <meta name="author" content="LLB">
    <link rel="shortcut icon" href="favicon.ico">
    <title>MSKeys LLB</title>
    
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    
    <!--Bootstrap core JavaScript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php 
    
    if ($_GET['double'] != 0){
        $doblos = $_GET['double'];
        echo '<script>alert("'.$doblos.' doublons non insérés !");</script>';
    }
    
    if (($_SESSION['login']) and ($_SESSION['password'])){
       
        include "fonctionDB.php";
        
        $connexion = connect();
        
        if(isset($_POST['submit_import'])){
            parsingXML();// Fonction de parsage XML, importations automatique des clées
        }     
        
        if(isset($_GET['action']) || isset($_POST['submit_ajout']) || isset($_POST['submit_modif'])){
            action($connexion);
        }
    ?>
    
    
    <!-- MENU -->
    
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">MSKeys LLB</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Accueil</a></li>
                <li class="active" ><a href="importations.php">Importations</a></li>
                <li><a href="prefCompte.php">Paramètres</a></li>
            </ul>
            <form class="navbar-form navbar-right" role="form" action="index.php" method="post">
                <input class="btn btn-warning" name="logout" type="submit" value="Déconnexion"></input>
            </form>
        </div><!-- /.navbar-collapse -->
    </nav>
    
    
    <!-- IMPORTATIONS XML -->

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="container"><br><br><br><br>
        <div class="panel panel-default">
        <!-- Default panel contents -->
            <div class="panel-heading">Importations XML</div>
            <div class="panel-body">
            <form class="form-inline" role="form" enctype="multipart/form-data" method="post">
                <div class="input-group">
                    <div class="input-group-btn">
                        <select class="btn btn-primary dropdown-toggle" name="OS1"> 
                            <option value="Microsoft Windows 7 Professionnel" selected="selected">Windows 7 pro</option>
                            <option value="Microsoft Windows XP Professional">Windows XP pro</option>
                        <select>
                    </div>
                    <input type="file" name="file" class="form-control">
                    <span class="input-group-btn"><input name="submit_import" class="btn btn-primary" type="submit" value="Envoyer"></input></span>
                </div>
            </form>
            </div>
            <?php
            if($_GET[success] == "1"){
                ?><div class="alert alert-success">Importation réussi</div><?php
            } elseif ($_GET[danger] == "1") { ?><div class="alert alert-danger">Importation echoué</div><?php }
              elseif ($_GET[danger] == "2") { ?><div class="alert alert-danger">Impossible d'ouvrir le fichier XML</div><?php }
            ?>
        </div>
        
        
        <!-- GERER LES CLEES -->
        
        <div class="panel panel-default">
        <!-- Default panel contents -->
            <div class="panel-heading">Tableau de clefs</div>
            <div class="panel-body">
                <form class="form-inline" role="form" method="post">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-primary dropdown-toggle" name="OS4"> 
                                <option value="Microsoft Windows 7 Professionnel" selected="selected">Windows 7 pro</option>
                                <option value="Microsoft Windows XP Professional">Windows XP pro</option>
                            <select>
                            <input name="submit_tab" class="btn btn-primary" type="submit" value="Valider" style="margin-left:15px;"></input>
                        </div>
                    </div><!-- /input-group -->
                </form>
            </div>
            <?php if(isset($_POST['submit_tab'])){
                tab_key();// Fonction liste toutes les clées
            } else {
                tab_key();
            }?>
        </div>
        <?php if($_GET[action] == "suppr"){ ?><div class="alert alert-success"><center>Suppression Réussi</center></div>
            <script>
                setTimeout(function(){location.href="importations.php"} , 800);
            </script>
        <?php }
        if($_POST["submit_modif"]){ ?><div class="alert alert-success"><center>Modification Réussi</center></div><?php }
        if($_POST["submit_ajout"]){ ?><div class="alert alert-success"><center>Insertion Réussi</center></div><?php }
    }
    else {
        header("Location:index.php");
    } ?>
  </body>
</html>

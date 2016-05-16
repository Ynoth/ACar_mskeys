<!DOCTYPE html>
<html lang="fr">
    <?php
        session_start();
        include "fonctionDB.php";

        if ($_POST['logout']) {
            session_destroy();
            header("Location:index.php");
        }
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
        
        <!--Bootstrap core JavaScript-->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
    <?php

        $connexion = connect();
        sessionConnexion($connexion);

    ?>
    <div class="container">
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
                <?
                    if (($_SESSION['login']) and ($_SESSION['password'])){
                ?>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Accueil</a></li>
                        <li><a href="importations.php">Importations</a></li>
                        <li><a href="prefCompte.php">Paramètres</a></li>
                    </ul>
		    <form class="navbar-form navbar-right" role="form" action="index.php" method="post">
                        <input class="btn btn-warning" name="logout" type="submit" value="Déconnexion"></input>
                    </form><?
                } else {?>
                <form class="navbar-form navbar-right" role="form" action="index.php" method="post">
                    <div class="form-group">
                    <input name="login" type="text" placeholder="Login" class="form-control">
                    </div>
                    <div class="form-group">
                    <input name="password" type="password" placeholder="Password" class="form-control">
                    </div>
                    <input name="submit_session" type="submit" class="btn btn-success" value="Connexion"></input>
                </form>
                <?}?>
            </div><!--/.navbar-collapse -->
        </nav>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <?php if (($_SESSION['login']) AND ($_SESSION['password'])){ ?>
                <center><br><h3>Afin d'obtenir une clef d'activation Windows, veuillez choisir votre Système d'Exploitation ainsi que les clefs de license proposées ci-dessous puis cliquez sur "Obtenir" et suivez les indications</h3>
                <form class="form-inline" role="form" method="post">
                    <select class="btn btn-info dropdown-toggle" name="OS3"> 
                        <option value="Microsoft Windows 7 Professionnel" selected="selected">Windows 7 pro</option>
                        <option value="Microsoft Windows XP Professional">Windows XP pro</option>
                    </select>
                    <input class="btn btn-info" type="submit" name="submit_option" value="Valider"></input>
                </form></center><br>
            <?php 
            
            
            if ($_POST['submit_option']){

                $os = $_POST['OS3'];// valeurs du select option pour l'affichage des clefs
                $result_key = select_key_product($connexion, $os);
                $nbclef = select_key_limit($connexion, $os);
                $nb = $nbclef->rowCount();

    
                    if ($nb != 0){
                        ?>
                
                        <form class="form-inline" role="form" method="get">
                            <table class="table">
                                <div class="panel panel-primary">
                                <div class="panel-heading"><center><?php echo $os; ?></center></div><?php

                                while ($row = $result_key->fetch(PDO::FETCH_OBJ)){ ?>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                           
                                            <input type="text" name="key" value="<?php echo $row->cle; ?>" class="form-control" readonly>
                                            <span class="input-group-btn">
                                                <a class="btn btn-info" href="STAkey.php?idkey=<?echo $row->idKey?>&key=<?echo $row->cle?>&OSS=<?echo $os?>">Obtenir</a> 
                                                <!--onClick="email = prompt('Veulliez rentrer votre email pour recevoir la clef');                                                     
                                                            if (confirm('Est ce que votre email est bon ?\n\n' + email)){
                                                                location.href='index.php?idKey=<?echo $row->idKey?>&key=<?echo $row->cle?>&action=clef&success=1&email='+ email +'&os=<?echo $os?>';
                                                            }else{
                                                                alert('Votre email est mauvais');
                                                            }"-->
                                            </span>
                                        </div><!-- /input-group -->
                                    </div><!-- /.col-lg-6 --><?php
                                }?>
                                </div>
                            </table>
                        </form>
                        <center><h4 class="text-warning">Choisissez une des clés ci-dessus puis cliquez sur le bouton obtenir</h4><?php
                        echo "$nb clef(s) $os non utilisée(s)";?></center><?php
                    }
                    else {
                        ?><center><h4 class="text-warning">Aucune clef trouvée</h4></center><?php
                    }
                }

                if(isset($_GET['action'])){
                    if($_GET['action']=="clef"){
    
                       /*$email = $_GET['email'];
                       $clay = $_GET['key'];
                       $osrow = $_GET['os'];
                        $trololol = envoyeMail($email, $clay, $osrow);*/
                        $ok = $_GET['ok'];
                       
                         if($ok != "ok"){
                            echo "Une erreur s'est produite.";
                        }
                        else{
                            echo "<center>Votre clé a été envoyée.</center>";
                            //update($connexion, $_GET['idKey']);
                            if($_GET[success] == "1"){?><div class="alert alert-success"><center>Clé envoyer et maintenant indisponible</center></div>
                            <script>setTimeout(function(){location.href="index.php"} , 800);</script> <?php }
                            
                        }
                                    
                    } else if($_GET['action']=="changemdp"){
                            echo "<center>Votre mot de passe a été modifié.</center>";
                            if($_GET[success] == "1"){?><div class="alert alert-success"><center>Mot de passe modifié</center></div>
                            <script>setTimeout(function(){location.href="index.php"} , 800);</script> <?php }
                    }
                }
                
                
            }
            else {
                if($_GET[danger] == "1"){
                    ?><center><div class="alert alert-danger">Erreur d'authentification</div></center>
        </div><?php
            }
            else {?>
                <div class="container">
                    <h2>Bienvenue</h2>
                    <h4>Merci de vous authentifier pour acc&eacute;der &agrave; l'application de gestion des licences Microsoft du lyc&eacute;e Laetitia Bonaparte.</h4>
<br />
<br />
<br />
<br />
<br />
                </div><?php
            }
        }?>
    </div>
    <footer id="footer">
        
        <h5>© LLB 2014</h5>
    </footer></div>
    
    <div class="container">

<!--        <center><h3>Grâce à l'application MSKeys LLB, vous pourrez envoyer des clés de licences sous les différents systèmes d'exploitations Windows afin de vous les transmettre par mail.</h3></center>
        <br><br><br><br><br><br><br><br>
        <table style="width:300%">
<tr>
<th><img src="images/logoWS08.jpg" width="220" height="60"></th>
<th><img src="images/logoW7.png" width="230" height="60"></th>
<th><img src="images/logoW8.jpg" width="220" height="60"></th>
</tr>
</table>
-->
    </div>
        

    </body>
  <!--  <body style="background:url(images/windows.jpg) no-repeat center fixed ; background-size: 2560px 1440px ; background-repeat: no-repeat;"></body>-->
</html>

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
    
    if ($_GET['danger'] == 1){
        echo '<script>alert("La clé existe déjà ! Réessayer !");</script>';
    }
    
    if($_GET['mess'] == 1){
        echo "<script>window.alert('Cette clé existe déjà !');</script>";
    }
  
    if (($_SESSION['login']) and ($_SESSION['password'])){
       
        include "fonctionDB.php";
        $connexion = connect();
        sessionConnexion($connexion);
 ;
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
        
    <!-- GERER LES CLEES -->

    <div class="panel panel-default">
  
        
    <!-- Default panel contents -->
        <div class="panel-heading">Tableau de clefs</div>
        <div class="panel-body">
           
            <form class="form-inline" role="form" action="importations.php" method="post">
                <center><div class="input-group">
                    <input class="form-control" type="hidden" name="old_key" value="<?php if($_GET['action']=="modif") {$extractKey = $_GET[idKey];            
                    $result_select = select_key_formulaire($extractKey);  echo $result_select->idKey;} ?>">
            <h3>Clé</h3><input class="form-control" type="text" name="key" value="<?php if($_GET['action']=="modif") {$extractKey = $_GET[idKey];            
                    $result_select = select_key_formulaire($extractKey);   echo $result_select->cle;} ?>"required><br>
                    <?php if($_GET['action']=="modif") { ?> <h3>Utilisée</h3>
                    <table style="border-collapse: collapse; width: initial;">
                        <tr><td>
                                oui
                            </td> 
                            <td>
                                <?php if ($result_select->cle==0){ ?> 
                                <input type="radio" name="utilisee" value="1" onclick="document.getElementById('submit')" >
                                <?php } else { ?>
                                  <input type="radio" name="utilisee" value="1" onclick="document.getElementById('submit')" checked>
                               <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                non
                            </td>
                            <td>
                                <?php if ($result_select->cle==0){ ?> 
                                <input type="radio" name="utilisee" value="0" onclick="document.getElementById('submit')"checked >
                                <?php } else { ?>
                                  <input type="radio" name="utilisee" value="0" onclick="document.getElementById('submit')">
                               <?php } ?>
                            </td>
                        </tr>
                    </table><br><?php } ?>
                    <h3>Nom Produit</h3>
                    <select class="btn btn-primary dropdown-toggle" name="name">
                        <?php 
                            $product = $connexion->query("SELECT `name` FROM  `Product`");
                            while ($tab = $product->fetch(PDO::FETCH_OBJ)){
                        ?>
                            <option name="option" value="<?php echo $tab->name?>"><?php echo $tab->name ?></option>
                        <?php } ?>
                    </select><br>
                    <br>
                    <h3>MSDN</h3>
                    
                    <table style="border-collapse: collapse; width: initial;">
                        <tr><td>
                                oui
                            </td> 
                            <td>
                                
                                <input type="radio" name="msdn" value="1" onclick="document.getElementById('submit').disabled=false">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                non
                            </td>
                            <td>
                                <input type="radio" name="msdn" value="0" onclick="document.getElementById('submit').disabled=false">
                            </td>
                        </tr>
                    </table>
           <br><br>
                    <input class="btn btn-primary" id="submit" name="<?php if ($_GET['action']=="modif") {echo "submit_modif";} else {echo "submit_ajout";} ?>" type="submit" disabled="true" value="Valider">
                </div></center><!-- /input-group -->
            </form>
        </div>
    </div>
    <?php } else {
        header("Location:index.php");
    } ?>
  </body>
</html>

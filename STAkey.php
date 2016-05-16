<!DOCTYPE html>
<html lang="fr">
    <?php     
        session_start();
        include "fonctionDB.php";
        
        $connexion = connect();
        sessionConnexion($connexion);
        
        if(isset($_POST["btnSTAkey"])){
            $key = $_POST['idkey'];
            $clay = $_POST['key'];
            $osw = $_POST['OS'];
            $STA = $_POST['STA'];
            $MAC = $_POST['MAC'];
            $email = $_POST['Email'];
            $ok ="";
            $trololol = envoyeMail($email, $clay, $osw);
            
            if($trololol == "ok"){
                            $ok ="ok";
                        }
                        
            update($connexion, $key, $STA, $MAC, $email);
            header('Location: index.php?action=clef&success=1&ok='.$ok.'');
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
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    
    <!--Bootstrap core JavaScript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
  <body>
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
                <li><a href="importations.php">Importations</a></li>
                <li><a href="prefCompte.php">Paramètres</a></li>           
            </ul>
            <form class="navbar-form navbar-right" role="form" action="index.php" method="post">
                <input class="btn btn-warning" name="logout" type="submit" value="Déconnexion"></input>
            </form>
        </div><!-- /.navbar-collapse -->
    </nav>
      <div class="container"><br><br><br>
          <form class="form-inline" method="POST" action="STAkey.php">
                <center><div class="input-group"><br>   
                <p>Clé:
                    <input class="form-control" type="text" name="key" value="<?php echo $_GET['key'];?>" readonly/></p><br>
                <p>OS:
                    <input class="form-control" type="text" name="OS" value="<?php echo $_GET['OSS'];?>"  readonly/></p><br>
                <p>Nom STA:
                    <input class="form-control" type="text" name="STA" required/></p><br>
                <p>adresse MAC:
                    <input class="form-control" type="text" name="MAC" required/></p><br>
                <p>Email:
                <input class="form-control" type="text" name="Email" required/></p><br>
                <input class="form-control" type="hidden" name="idkey" value="<?php echo $_GET['idkey'];?>"/>
                <input class="btn btn-info" type="submit" name="btnSTAkey" value='Valider'/>
                    </div>
                </center>
          </form>
          
              


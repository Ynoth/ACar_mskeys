<?php
ini_set('error_reporting', E_ALL);
// Connect to the database
function connect(){ 
    $PARAM_hote='localhost';
    $PARAM_port='3306';
    $PARAM_nom_bd='bdd_mskeys';
    $PARAM_utilisateur='';
    $PARAM_mot_passe='';
    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);  
    return $connexion;  
}


/* Tableau des clées
 * 
 * 
 */
function tab_key(){       
            
    $connexion = connect();
    $os = $_POST['OS4'];// valeurs du select option pour l'affichage des clées dans le tableau
    $select_tab_key = "SELECT `idKey`, `cle`, `utilisee`, `name`, `msdn` FROM `cles` INNER JOIN Product ON cles.idProduct = Product.idProduct WHERE Product.name LIKE '$os%'";
    $result_tab_key = $connexion->query($select_tab_key) or die("Erreur SQL Select_Tableau");
    
    echo "<div class='panel panel-default'>
          <!-- Default panel contents -->
          <div class='panel-heading'>Base de données</div>
            <!-- Table -->
            <div class='panel-salut' style='height:500px;overflow:scroll'>
            <table class='table' style='height:500px;overflow:scroll'>
                <th>idKey</th> <th>Key</th> <th>Utilisee</th> <th>Name</th> <th>Msdn</th> <th></th> <td><a class='btn btn-default btn-lg' href='formulaire.php?action=ajout'><span class='glyphicon glyphicon-plus'></span></a></td>";

                while ($row = $result_tab_key->fetch(PDO::FETCH_OBJ))
                    {
                        echo "<tr>";
                                echo"<td>$row->idKey</td>";
                                echo"<td>$row->cle</td>";
                    echo"<td>";
                    if ($row->utilisee == 1) 
                        { 
                            echo "<img src='images/button-green.png' width='15' height='15'>";
                        }
                            else { 
                                    echo "<img src='images/button-red.png' width='15' height='15'>";
                                    
                            }echo"</td>";
                            echo"<td>$row->name</td>";
                            echo"<td>";
                            if ($row->msdn == 1) 
                                { 
                                    echo "<img src='images/button-green.png' width='15' height='15'>";
                                    
                                }
                                    else { 
                                            echo "<img src='images/button-red.png' width='15' height='15'>";

                                    }
                                echo"</td>";
                                echo"<td><a class='btn btn-default btn-lg' href='importations.php?idKey=$row->idKey&action=suppr'><span class='glyphicon glyphicon-trash'></span></a></td>";
                                echo"<td><a class='btn btn-default btn-lg' href='formulaire.php?idKey=$row->idKey&action=modif'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                            echo"</tr>"; 
                    }
            "</table>
             </div>
        </div>";
       
}


/* Execution des actions los du clic sur le bouton associer
 * 
 * 
 */
function action($connexion){
        
    if($_POST['submit_ajout']){ // Action ajouter
        extract($_POST);
        add_key($connexion,$name,$key, $msdn);
    }

    elseif($_POST['submit_modif']){ // Action modifier
        extract($_POST);
        update_key($connexion, $name,$key,$utilisee,$old_key,$msdn);
        }

    elseif($_GET['action']=="suppr"){ // Action supprimer
        delete_key($connexion);
    }
}

function select_key($connexion){
    
    $os = $_POST['OS3'];// valeurs du select option pour l'affichage des clées
    $select_key = "SELECT `key` FROM `cles` INNER JOIN Product ON cles.idProduct = Product.idProduct WHERE Product.name LIKE '$os%' AND utilisee = 0  LIMIT 0,6";
    foreach ($connexion->query($select_key) as $row) 
    {
        print $row['key']. "\t";
    }
    return $select_key;
}

function select_key_product($connexion, $os){
    $result_key = $connexion->query("SELECT * FROM `cles` INNER JOIN Product ON cles.idProduct = Product.idProduct WHERE Product.name LIKE '$os%' AND utilisee = '0'  LIMIT 0,6");
    return $result_key;
}

function select_key_limit($connexion, $os){
    $nbclef = $connexion->query("SELECT * FROM `cles` INNER JOIN Product ON cles.idProduct = Product.idProduct WHERE Product.name LIKE '$os%' AND utilisee = '0'");
    return $nbclef;
}

function select_key_formulaire($extractKey){
    $connexion = connect();
    $result = $connexion->query("SELECT `idKey`, `cle`, `utilisee`, `name` FROM `cles` INNER JOIN Product ON cles.idProduct = Product.idProduct WHERE idKey=$extractKey");
    $select = $result->fetch(PDO::FETCH_OBJ);
    return $select;
}

function add_key($connexion, $name,$key, $msdn){
    
        $select_session = "SELECT * FROM `cles` WHERE `cle` = '$key' AND `idProduct` IN (SELECT `idProduct` FROM `Product` WHERE `name` = '$name')";
        
        $result = $connexion->query($select_session);
        
         if ($result->rowCount() == "0"){ 
             $laconex = connect();
             $add_key = $laconex->prepare("INSERT INTO `cles` (`cle`, `utilisee`, `idProduct`, `msdn`) VALUES ('$key', 0, (SELECT `idProduct` FROM `Product` WHERE `name` = '$name'), '$msdn')");
             $add_key->execute();
             
         }  
         else {
             $i = 1;
             header("Location: formulaire.php?action=ajout?mess=$i");
             
         }
}

function update_key($connexion, $name, $key, $modif, $old_key,$msdn){
    $update_key = $connexion->prepare("UPDATE `cles` SET `cle` = '$key', `utilisee` = $modif, `idProduct` = (SELECT `idProduct` FROM `Product` WHERE `name` = '$name'), `msdn` = $msdn WHERE `cles`.`idKey` = $old_key") or die("Erreur SQL Update");
    // Met à jour la base de donnée si le bouton est cliquer, la clé est utilisee et ne sera plus jamais afficher
    $update_key->execute();
}

// Update de la clée lorsque qu'on clique sur le bouton obtenir
function update($connexion, $key,$STA,$MAC,$email){
    $update_key = $connexion->prepare(
            "UPDATE `cles` SET `utilisee` = 1 WHERE `cles`.`idKey` = $key;
            INSERT INTO `UsedProduct` (`nomSTA`, `idKey`, `MAC`, `email`) VALUES ('$STA', $key,'$MAC','$email');"    
    ) or die("Erreur SQL Update");
    // Met à jour la base de donnée si le bouton est cliquer, la clé est utilisee et ne sera plus jamais afficher
    $update_key->execute();
}

function delete_key($connexion){
    
        $idKey = $_GET['idKey'];
        
        $delete_key = $connexion->prepare("DELETE FROM `cles` WHERE `idKey` = $idKey");
        $delete_key->execute() or die(print_r($delete_key->errorInfo()));
}

function baliseOuvrante($parseur, $nomBalise){
    global $derniereBalise;
    $derniereBalise = $nomBalise;
}

function baliseFermante($parseur, $nomBalise){
    global $derniereBalise;
    $derniereBalise = "";
}

function affichageTexte($parseur, $texte){
    global $derniereBalise;
    $i = 0;
    $os = $_POST['OS1'];// valeurs du select option de l'importation d'un fichier XML

    // Insertion dans la base de données
    switch ($derniereBalise) {
        case "KEY":// Indique le texte à prendre dans la balise "ex : <Key>texte à prendre</key>"
            $connexion = connect();
            $select_session = "SELECT * FROM `cles` WHERE `cle` = '$texte' AND `idProduct` IN (SELECT `idProduct` FROM `Product` WHERE `name` = '$os')";
            $result = $connexion->query($select_session);

             if ($result->rowCount() == "0"){ 
                $loconnex = connect();
                $sql = $loconnex->prepare("INSERT INTO `cles` (`cle`, `idProduct`, `utilisee`) VALUES ('$texte', (SELECT `idProduct` FROM `Product` WHERE name LIKE '$os%'), 0)");
                $sql->execute() or die(print_r($sql->errorInfo())); 
                break;
             }else
             {
                 $i++;
             }
    }
    
    return $i;
}

function parsingXML($connexion){
    $fichier = $_FILES['file']['tmp_name'];
            
    baliseOuvrante($parseur, $nomBalise);
    baliseFermante($parseur, $nomBalise);
    $doblo = affichageTexte($parseur, $texte);

    $parseurXML = xml_parser_create();// Création du parseur XML
    xml_set_element_handler($parseurXML, "baliseOuvrante", "baliseFermante");// Indique la balise de début et de fin du fichier XML
    xml_set_character_data_handler($parseurXML, "affichageTexte");// Indique le texte à récupérer entre les balises

    $open = fopen($fichier, "r");// Ouverture du fichier en lecture
    if (!$open){ header('Location: importations.php?danger=1'); }
    
    else {

        while ( $ligneXML = fgets($open, 1024)){
            xml_parse($parseurXML, $ligneXML) or die(header('Location: importations.php?danger=2'));// Analyse le document XML ligne par ligne
        }

        xml_parser_free($parseurXML);// Met fin à l'analyse
        fclose($open);// Fermeture du fichier
        header("Location: importations.php?success=1?double=$doblo");
    }
}

function sessionConnexion($connexion){
    
    $login = $_POST['login'];// input texte du login de connexion
    $mdp = $_POST['password'];// input texte du mot de passe de connexion
    
    $mdp = md5($mdp);
    
    if ($_POST['submit_session']){
        
        $select_session = "SELECT `id` FROM Admin WHERE login = '$login' AND password = '$mdp'";
        $result = $connexion->query($select_session);
        
         if ($result->rowCount() == "0"){ 
  
            header("Location:index.php?danger=1");
        }
        else {
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $mdp;
   
            header("Location:index.php");
        }
        
    } 
    
}

function envoyeMail($email, $clay, $osw){
    include "PHPMailer_5.2.4/class.phpmailer.php"; // include the class name
                        $mail = new PHPMailer(); // create a new object
                        $mail->IsSMTP(); // enable SMTP
                        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
                        $mail->SMTPAuth = true; // authentication enabled
                        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
                        $mail->Host = "smtp.gmail.com";
                        $mail->Port = 465; // or 587
                        $mail->IsHTML(true);
                        $mail->Username = "mskeysllb@gmail.com";
                        $mail->Password = "MSKeysMDP";
                        $mail->SetFrom("mskeysllb@gmail.com");
                        $mail->AddAddress($email , "destinataire");
                        $mail->Subject = "Votre clé d'activation";
                        $mail->Body = "Bonjour,<br> voici votre cl&eacute; d'activation  {$osw} : {$clay}";

                         if(!$mail->Send()){
                            echo "Mailer Error: " . $mail->ErrorInfo;
                         }else{
                             $jij="ok";
                             return $jij;
                         }
                         
}

function changeMDP($connexion,$newmdp,$login,$mdp){
    
    $mdpreq=$connexion->prepare("UPDATE Admin SET password=MD5({$newmdp}) WHERE login = '{$login}' AND password = '{$mdp}'");
    $mdpreq->execute();
    header("Location:index.php");
}

?>
   
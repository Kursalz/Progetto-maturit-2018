<link rel="stylesheet" type="text/css" href="stile.css" >
<link href='https://fonts.googleapis.com/css?family=Kavoon' rel='stylesheet'>
<?php

function getIdIndirizzo($p_db, $p_citta, $p_provincia, $p_cap, $p_numeroCivico, $p_via, $p_localita) {
    $statement = $p_db->prepare("SELECT id FROM INDIRIZZO I WHERE :i=citta AND :p=provincia AND :a=cap AND :n=numeroCivico AND :v=via AND :l=localita");
    $statement->bindValue(":i", $p_citta);
    $statement->bindValue(":p", $p_provincia);
    $statement->bindValue(":a", $p_cap);
    $statement->bindValue(":n", $p_numeroCivico);
    $statement->bindValue(":v", $p_via);
    $statement->bindValue(":l", $p_localita);
    $result = $statement->execute();
    $numRigheRestituite = $statement->rowCount();
    if ($numRigheRestituite == 0) {
        $statement = $p_db->prepare("INSERT INTO INDIRIZZO (citta, provincia, cap, numeroCivico, via, localita) VALUES (:i, :p, :a, :n, :v, :l)");
        $statement->bindValue(":i", $p_citta);
        $statement->bindValue(":p", $p_provincia);
        $statement->bindValue(":a", $p_cap);
        $statement->bindValue(":n", $p_numeroCivico);
        $statement->bindValue(":v", $p_via);
        $statement->bindValue(":l", $p_localita);
        $res = $statement->execute();
        $statement = $p_db->prepare("SELECT id FROM INDIRIZZO I WHERE :i=citta AND :p=provincia AND :a=cap AND :n=numeroCivico AND :v=via AND :l=localita");
        $statement->bindValue(":i", $p_citta);
        $statement->bindValue(":p", $p_provincia);
        $statement->bindValue(":a", $p_cap);
        $statement->bindValue(":n", $p_numeroCivico);
        $statement->bindValue(":v", $p_via);
        $statement->bindValue(":l", $p_localita);
        $result = $statement->execute();
    }
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    return $row["id"];
}

session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] == false) {
    $db = new PDO("sqlite:ECOMMERCE.sqlite");
    if (!$db) {
        die("Errore nell'apertura del database");
    }
    $username = strtoupper(trim($_POST["username"]));
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $nome = strtoupper(trim($_POST["nome"]));
    $cognome = strtoupper(trim($_POST["cognome"]));
    $telefono = $_POST["telefono"];
    $citta = strtoupper(trim($_POST["citta"]));
    $provincia = strtoupper(trim($_POST["provincia"]));
    $cap = $_POST["cap"];
    $localita = strtoupper(trim($_POST["localita"]));
    $via = strtoupper(trim($_POST["via"]));
    $numeroCivico = strtoupper(trim($_POST["civico"]));
    if ($password == $password2) {
        $statement = $db->prepare("SELECT id FROM CLIENTE WHERE :u=username");
        $statement->bindParam(":u", $username, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch();
        //echo $result[0];
        if (isset($result[0])) {
            $_SESSION["messaggioErrore"] = "SCEGLIERE UN ALTRO USERNAME: $username già presente";
            //$message = "SCEGLIERE UN ALTRO USERNAME: $username già presente"; //.$username;
            //echo "<script type='text/javascript'>alert('$message');</script>";
        } else {
            $idIndirizzo = getIdIndirizzo($db, $citta, $provincia, $cap, $numeroCivico, $via, $localita);
            //echo $idIndirizzo;
            $statement = $db->prepare("INSERT INTO CLIENTE (username, password, nome, cognome, numeroTelefono,idIndirizzoAttuale) VALUES (:u, :p, :n, :c, :t, :i)");
            $statement->bindValue(":u", $username);
            $statement->bindValue(":p", $password);
            $statement->bindValue(":n", $nome);
            $statement->bindValue(":c", $cognome);
            $statement->bindValue(":t", $telefono);
            $statement->bindValue(":i", $idIndirizzo);
            $result = $statement->execute();
            $message = "È STATO INSERITO L'UTENTE "; //.$username;
            echo "<script type='text/javascript'>alert('$message');</script>";
            echo "l'utente " . $username . " è stato inserito correttamente";
            $_SESSION["messaggioInfo"] = "l'utente " . $username . " è stato inserito correttamente";
            $_SESSION["messaggioErrore"] = "";
        }
    } else {
        $_SESSION["messaggioInfo"] = "";
        $_SESSION["messaggioErrore"] = "Le password inserite non corrispondono. Riprovare.";
    }
    echo "<script type='text/javascript'>window.location.replace(\"index.php\")</script>";
    //header("location: index.php");
}
?>


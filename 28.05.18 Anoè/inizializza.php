<?php

session_start();
$_SESSION["paginaChiamante"] = "inizializza.php";
$_SESSION["inizializzato"] = true;
$_SESSION["username"] = "";
$_SESSION["login"] = false;
$_SESSION["idCliente"] = "";
$_SESSION["idProdotto"] = "";
$_SESSION["idCategoria"] = "";
$_SESSION["subtotal"] = null;
$_SESSION["total"] = null;
echo "<script type='text/javascript'>window.location.replace(\"cancellaMessaggi.php\")</script>";
?>
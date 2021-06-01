<?php

if(!isset($_GET['erroStatusCode'])) header('Location: index.php');
echo file_get_contents('html/error500.xhtml');
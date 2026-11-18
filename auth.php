<?php
session_start();

if (empty($_SESSION['uzytkownik'])) {
    header('Location: login.php');
    exit;
}

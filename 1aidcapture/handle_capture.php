<?php
session_start();
error_reporting(-1);
// Include
require_once('../config/config.php');
require_once('validate.php');

try {
    // Initialize DB
    $dbh = new PDO($config['db.dsn'], $config['db.user'], $config['db.pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    exit("Connection to database failed. Please contact admin");
}

$error = array();

try{
    // Initialize validator
    $validate = new Validate($dbh);

    // Validate postid
    if (isset($_POST['postid']) && ($poststatus = $validate->validatePostid($_POST['postid'])) == VALID) {
        $postid  = $_POST['postid'];
        // Save post id in session
        $_SESSION['postid'] = $_POST['postid'];
    } else {
        $error[] = "Post nummer er ikke korrekt - {$_POST['postid']}";
    }

    // Validate 1aidcode
    if (isset($_POST['1aidcode']) && ($aidcodestatus = $validate->validate1Aidcode($_POST['1aidcode'])) == VALID) {
        $aidcode  = $_POST['1aidcode'];
    } else {
        switch ($aidcodestatus) {
            case USED:
                $error[] = "Nødkuvert er allerede registreret - Registering afbrudt - {$_POST['1aidcode']}";
                break;
            case NOTVALID:
            default:
                $error[] = "Nødkuvert nummer er ikke korrekt - Registering afbrudt - {$_POST['1aidcode']}";
        }
    }
} catch (Exception $e) {
    $error[] = $e->getMessage();
}

// Errors have occured
if (!empty($error)) {
    include "error.tpl.php";
    exit();
}

// Register captures
try {
    $dbh->beginTransaction();

    // Register 1aid
    $stmt = $dbh->prepare("UPDATE `1aid` SET `post` = ?, `used` = 1, `time` = now(), `useragent` = ? WHERE `code` = ?;");
    $stmt->execute(array($postid, $_SERVER['HTTP_USER_AGENT'], $aidcode));

    // Commit updates
    $dbh->commit();
} catch (Exception $e) {
    $dbh->rollback();
    $error[] = $e->getMessage();
}

// Errors have occured
if (!empty($error)) {
    include "error.tpl.php";
    exit();
}

try {
    // Grab 1aid record
    $stmt = $dbh->prepare("SELECT * FROM `1aid` WHERE `code` = ?");
    $stmt->execute(array($aidcode));
    $aid = $stmt->fetch();

    // Grab scout info
    $stmt = $dbh->prepare("SELECT * FROM `scout` WHERE `id` = ?;");
    $stmt->execute(array($aid['scoutid']));
    $scout = $stmt->fetch();
} catch (Exception $e) {
    $error[] = $e->getMessage();
}

// Errors have occured
if (!empty($error)) {
    include "error.tpl.php";
    exit();
}

// Show receiot
include('receipt.tpl.php');

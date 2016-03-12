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
    $sjakShowId = $_POST['ssi'];
    // Initialize validator
    $validate = new Validate($dbh);

    // Validate sjakid
    if (isset($_POST['sjakid']) && ($sjakstatus = $validate->validateSjakid($_POST['sjakid'])) == VALID) {
        $sjakid  = $_POST['sjakid'];
        // Save sjak id in session
        $_SESSION['sjakid'] = $_POST['sjakid'];
    } else {
        $error[] = "Sjak ID er ikke korrekt - {$_POST['sjakid']}";
    }

    // Validate scout code
    if (isset($_POST['scoutcode']) && ($scoutcodestatus = $validate->validateScoutcode($_POST['scoutcode'])) == VALID) {
        $scoutcode  = $_POST['scoutcode'];
    } else {
        switch ($scoutcodestatus) {
            case USED:
                $error[] = "Spejderkode er allerede brugt - Registering afbrudt - {$_POST['scoutcode']}";
                break;
            case NOTVALID:
            default:
                $error[] = "Spejderkode er ikke korrekt - Registering afbrudt - {$_POST['scoutcode']}";
        }
    }

    // Validate object codes
    $objects = array();
    if (isset($_POST['objectcode'])) {
        foreach ($_POST['objectcode'] AS $object) {
            if (!empty($object) && ($objectstatus = $validate->validateObjectcode($object)) == VALID) {
                $objects[] = array(
                        'code' => $object,
                        'id' => substr($object, 0, 3),
                        );
            } else if (!empty($object)) {
                switch ($objectstatus) {
                    case USED:
                        $error[] = "Tingkode er allerede brugt - Registering afbrudt - {$object}";
                        break;
                    case NOTVALID:
                    default:
                        $error[] = "Tingkode er forkert - Registering afbrudt - {$object}";
                }
            }
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

    $scoutid = substr($scoutcode, 0, 4);

    // Register captured scout patrole
    $stmt = $dbh->prepare("UPDATE `capturelog` SET `sjakid` = ?, `scoutid` = ?, `used` = 1, `time` = now(), `useragent` = ? WHERE `code` = ?;");
    $stmt->execute(array($sjakid, $scoutid, $_SERVER['HTTP_USER_AGENT'], $scoutcode));

    // Register objects found
    foreach ($objects AS $object) {
        $stmt = $dbh->prepare("UPDATE `objectlog` SET `sjakid` = ?, `scoutid` = ?, `used` = 1, `time` = now(), `useragent` = ? WHERE `code` = ?;");
        $stmt->execute(array($sjakid, $scoutid, $_SERVER['HTTP_USER_AGENT'], $object['code']));
    }
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
    // Grab scout info
    $stmt = $dbh->prepare("SELECT * FROM `scout` WHERE `id` = ?;");
    $stmt->execute(array($scoutid));
    $scout = $stmt->fetch();

    // Grab sjak info
    $stmt = $dbh->prepare("SELECT * FROM `sjak` WHERE `id` = ?;");
    $stmt->execute(array($sjakid));
    $sjak = $stmt->fetch();

    // Grab object info
    foreach ($objects AS $object) {
        $stmt = $dbh->prepare("SELECT * FROM `object` WHERE id = ?;");
        $stmt->execute(array($object['id']));
        $foundobject[] = $stmt->fetch();
    }
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

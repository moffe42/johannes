<?php
define('VALID', 1);
define('NOTVALID', 2);
define('USED', 3);

// Validation functions
class Validate
{
    public $dbh = null;

    public function __construct (PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    public function validatePostid($postId)
    {
        return VALID;
    }

    public function validate1Aidcode($aidcode)
    {
        // Validate syntax
        if (preg_match('/^4\d{2}\d{5}$/', $aidcode) !== 1 ) {
            return NOTVALID;
        }

        // Check if code exists
        $stmt = $this->dbh->prepare('SELECT COUNT(code) AS `exists` FROM `1aid` WHERE code = ?');
        $stmt->execute(array($aidcode));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['exists'] !== '1') {
            return NOTVALID;
        }

        // Check if code exists
        $stmt = $this->dbh->prepare('SELECT COUNT(code) AS `exists` FROM `1aid` WHERE code = ? AND used = 0;');
        $stmt->execute(array($aidcode));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['exists'] !== '1') {
            return USED;
        }
        return VALID;
    }

    public function validateSjakid($sjakid) {
        // Validate syntax
        if (preg_match('/^2\d{2}$/', $sjakid) !== 1 ) {
            return NOTVALID;
        }

        // Check ig code exists
        $stmt = $this->dbh->prepare('SELECT COUNT(id) AS `exists` FROM sjak WHERE id = ?;');
        $stmt->execute(array($sjakid));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['exists'] !== '1') {
            return NOTVALID;
        }
        return VALID;
    }

    public function validateScoutcode($scoutcode)
    {
        // Validate syntax
        if (preg_match('/^1\d{2}\d{5}$/', $scoutcode) !== 1 ) {
            return NOTVALID;
        }

        // Check ig code exists
        $stmt = $this->dbh->prepare('SELECT COUNT(code) AS `exists` FROM capturelog WHERE code = ?;');
        $stmt->execute(array($scoutcode));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['exists'] !== '1') {
            return NOTVALID;
        }

        // Check ig code exists
        $stmt = $this->dbh->prepare('SELECT COUNT(code) AS `exists` FROM capturelog WHERE code = ? AND used = 0;');
        $stmt->execute(array($scoutcode));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['exists'] !== '1') {
            return USED;
        }
        return VALID;
    }

    public function validateObjectcode($objectcode)
    {
        // Validate syntax
        if (preg_match('/^3\d{2}\d{5}$/', $objectcode) !== 1 ) {
            return NOTVALID;
        }

        // Check if code exists
        $stmt = $this->dbh->prepare('SELECT COUNT(code) AS `exists` FROM objectlog WHERE code = ?;');
        $stmt->execute(array($objectcode));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['exists'] !== '1') {
            return NOTVALID;
        }

        // Check if code exists
        $stmt = $this->dbh->prepare('SELECT COUNT(code) AS `exists` FROM objectlog WHERE code = ? AND used = 0;');
        $stmt->execute(array($objectcode));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['exists'] !== '1') {
            return USED;
        }
        return VALID;
    }
}

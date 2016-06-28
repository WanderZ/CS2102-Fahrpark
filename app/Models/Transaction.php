<?php
  # Transactions
  namespace App\Models;
  use App\Models\User;

  class Transaction extends CS2102Model {

    private $strInvoiceNo;
    private $intFrom;
    private $intTo;
    private $dblAmount;
    private $strType;
    private $dtCreatedAt;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public static $tableName = 'Transactions';

    /**
     * Variable to indicate if the primary keys need to be incremented
     *
     * @var array
     */
    public $incrementing = false;

    public static function createTableSQL() {
      $createTableSql = '
      CREATE TABLE IF NOT EXISTS ' . static::$tableName . ' (
        `invoiceNo`VARCHAR(255) PRIMARY KEY,
        `from` INT NOT NULL,
        `to` INT NOT NULL,
        `amount` DECIMAL(10, 2) NOT NULL,
        `type` CHAR(7) NOT NULL,
        `createdAt` TIMESTAMP NOT NULL DEFAULT NOW(),
        CONSTRAINT `fk_from`
        FOREIGN KEY(`from`) REFERENCES `' . User::$tableName . '` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `fk_to`
        FOREIGN KEY(`to`) REFERENCES `' . User::$tableName . '` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
      );
      ';
      return $createTableSql;
    }

    public static function createProcedures() {

      $stmt = "DROP PROCEDURE IF EXISTS t_getInvoiceCount;";
      \DB::unprepared($stmt);

      $createStoredProc = 'CREATE PROCEDURE t_getInvoiceCount(OUT invoices INT)
      BEGIN SELECT COUNT( * ) INTO invoices FROM ' . static::$tableName . ';
      END;';

      \DB::unprepared($createStoredProc);
    }

    public static function validateAmount($amount) {
      return ($amount >= 0);
    }

    public static function getTransactionCount() {
      $stmt = "CALL t_getInvoiceCount(@count);";
      \DB::unprepared($stmt);
      $stmt = "SELECT @count;";
      return \DB::select($stmt);
    }

    public static function createTransactionRecord($approver_id, $journey_id, $booking_id) {

      $stmReqUser = "SELECT passenger_id FROM Bookings WHERE id = " . $booking_id;
      $rsReqUser = \DB::select($stmReqUser);

      $stmCost = "SELECT cost FROM Journeys WHERE id = " . $journey_id;
      $rsCost = \DB::select($stmCost);
      return
      Transaction::doInsert([
        'invoiceNo' => Transaction::generateNextInvoiceNumber(),
        'from' => $rsReqUser[0]->passenger_id,
        'to' => $approver_id,
        'amount' => $rsCost[0]->cost,
        'type' => "BOOKING"
      ]);

    }

    public static function admCreateTransactionRecord($amount, $journey_id, $booking_id) {

      $stmReqUser = "SELECT passenger_id FROM Bookings WHERE id = $booking_id";
      $rsReqUser = \DB::select($stmReqUser);
      $frmUser = $rsReqUser->passenger_id;

      $stmCostVeh = "SELECT cost, vehicle FROM Journeys WHERE id = $journey_id";
      $rsCostVeh = \DB::select($frmUser);

      $stmOwner = "SELECT driver FROM Vehicles WHERE vehicle = $rsCostVeh->vehicle";
      $rsToUser = \DB::select($stmOwner);
      $toUser = $rsToUser->id;

      Transaction::doInsert([
        'invoiceNo' => generateNextInvoiceNumber(),
        'from' => $frmUser,
        'to' => $toUser,
        'amount' => $rsCost->cost,
        'type' => 'BOOKING',
        'createdAt' => date("Y-m-d H:i:s"),
      ]);
    }

    public static function generateNextInvoiceNumber() {
      $rand = substr(md5(microtime()),rand(0,26),5);
      return 'INV-FPK-'.date_timestamp_get(date_create()).'-'.$rand.'-'.\Auth::user()->id.'A';
    }

    public static function updateTransactionRecord() {
      return null;
    }

    public static function deleteTransactionRecord() {
      return null;
    }

    public static function retrieveAllTransactionRecords() {
      $strSql = "SELECT t.invoiceNo, payer.username AS 'payer', payee.username AS 'payee', t.amount, t.type, t.createdAt FROM " .
        static::$tableName . " t INNER JOIN " . User::$tableName . " payer ON payer.id = t.from " .
          "INNER JOIN " . User::$tableName . " payee ON payee.id = t.to";
      return \DB::select($strSql);
    }

    public static function retrieveUserIncomingTransactionRecords($user_id) {
      $strSql = "SELECT t.invoiceNo, payer.username AS 'person', t.amount, t.type, t.createdAt FROM ".static::$tableName." t INNER JOIN Users payer ON payer.id = t.from INNER JOIN Users payee ON payee.id = t.to WHERE t.to = ".$user_id;;
      return \DB::select($strSql);
    }

    public static function retrieveUserOutgoingTransactionRecords($user_id) {
      $strSql = "SELECT t.invoiceNo, payee.username AS 'person', t.amount, t.type, t.createdAt FROM ".static::$tableName." t INNER JOIN Users payer ON payer.id = t.from INNER JOIN Users payee ON payee.id = t.to WHERE t.from =".$user_id; ;
      return \DB::select($strSql);
    }

    public static function retrieveAllEarnings($user_id) {
      $strSql = "SELECT SUM(amount) AS 'tlEarnings' FROM " . static::$tableName . " WHERE " . static::$tableName . ".to = " . $user_id;
      return \DB::select($strSql);
    }

    public static function retrieveAllExpenses($user_id) {
      $strSql = "SELECT SUM(amount) AS 'tlExpenses' FROM " . static::$tableName . " WHERE " . static::$tableName . ".from = " . $user_id;
      return \DB::select($strSql);
    }

    public static function retrieveLargestEarnings($user_id) {
      $strSql = "SELECT MAX(amount) AS 'mxEarnings' FROM " . static::$tableName . " WHERE " . static::$tableName . ".to = " . $user_id;
      return \DB::select($strSql);
    }

    public static function retrieveLargestExpenses($user_id) {
      $strSql = "SELECT MAX(amount) AS 'mxExpenses' FROM " . static::$tableName . " WHERE " . static::$tableName . ".from = " . $user_id;
      return \DB::select($strSql);
    }

    public static function retrieveSmallestEarnings($user_id) {
      $strSql = "SELECT MIN(amount) AS 'mnEarnings' FROM " . static::$tableName . " WHERE " . static::$tableName . ".to = " . $user_id;
      return \DB::select($strSql);
    }

    public static function retrieveSmallestExpenses($user_id) {
      $strSql = "SELECT MIN(amount) AS 'mnExpenses' FROM " . static::$tableName . " WHERE " . static::$tableName . ".from = " . $user_id;
      return \DB::select($strSql);
    }

    public static function retrieveAverageEarnings($user_id) {
      $strSql = "SELECT AVG(amount) AS 'avEarnings' FROM " . static::$tableName . " WHERE " . static::$tableName . ".to = " . $user_id;
      return \DB::select($strSql);
    }

    public static function retrieveAverageExpenses($user_id) {
      $strSql = "SELECT AVG(amount) AS 'avExpenses' FROM " . static::$tableName . " WHERE " . static::$tableName . ".from = " . $user_id;
      return \DB::select($strSql);
    }

    public static function getTransactionMade($user_id) {
      $strSql = "SELECT COUNT(invoiceNo) AS 'tlTransactions' FROM " . static::$tableName . " WHERE " . static::$tableName . ".from = " . $user_id . " OR " . static::$tableName . ".to = ". $user_id;
      return \DB::select($strSql);
    }
  }

?>

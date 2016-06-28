<?php

    namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends CS2102Model implements AuthenticatableContract,
                                          AuthorizableContract,
                                          CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public static $tableName = 'Users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];


    public $incrementing = true;

    /**
     * Overrides Authenticatable trait to set the remember_token field.
     */
    public function getRememberTokenName()
    {
      return 'rememberToken';
    }

    public function isAdmin() {
      return $this->isAdmin;
    }

    public function changePassword($newPassword) {
      return static::doUpdate(['id' => $this->id], ['password' => bcrypt($newPassword)]);
    }

    public static function getAllUsers()
    {
      $results = \DB::select('SELECT * FROM `' . static::$tableName .'`');
      return static::rawResultToModels($results);
    }

    public static function getAllUsersPaginated($page = 0, $limit = 25)
    {
      $results = \DB::select('SELECT * FROM `' . static::$tableName .'` LIMIT ? OFFSET ?', [$limit, $limit * $page]);
      \DB::setFetchMode(\PDO::FETCH_ASSOC);
      $count = \DB::select('SELECT COUNT(*) FROM `' . static::$tableName .'`');
      \DB::setFetchMode(\PDO::FETCH_CLASS);

      return [static::rawResultToModels($results), $count[0]['COUNT(*)']];
    }

    public static function searchUsers($keywords)
    {
      $keywords = explode(" ", $keywords);
      $values = [];
      $where = '';
      foreach ($keywords as $k => $v) {
        if ($where) {
          $where = $where . ' OR ';
        }
        $where = $where . 'fullname LIKE CONCAT(\'%\', ?, \'%\') OR username LIKE CONCAT(\'%\', ?, \'%\') OR phone LIKE CONCAT(\'%\', ?, \'%\')';
        $values[] = $v;
        $values[] = $v;
        $values[] = $v;
      }
      $results = \DB::select('SELECT * FROM `' . static::$tableName . '` WHERE ' . $where, $values);
      return static::rawResultToModels($results);
    }

    public static function searchUsersPaginated($keywords, $page = 0, $limit = 25)
    {
      $keywords = explode(" ", $keywords);
      $values = [];
      $where = '';
      foreach ($keywords as $k => $v) {
        if ($where) {
          $where = $where . ' OR ';
        }
        $where = $where . 'fullname LIKE CONCAT(\'%\', ?, \'%\') OR username LIKE CONCAT(\'%\', ?, \'%\') OR phone LIKE CONCAT(\'%\', ?, \'%\')';
        $values[] = $v;
        $values[] = $v;
        $values[] = $v;
      }
      $values[] = $limit;
      $values[] = $limit * $page;
      $results = \DB::select('SELECT * FROM `' . static::$tableName . '` WHERE ' . $where . ' LIMIT ? OFFSET ?', $values);
      \DB::setFetchMode(\PDO::FETCH_ASSOC);
      unset($values[count($values)-1]);
      unset($values[count($values)-1]);
      $count = \DB::select('SELECT COUNT(*) FROM `' . static::$tableName . '` WHERE ' . $where, $values);
      \DB::setFetchMode(\PDO::FETCH_CLASS);
      return [static::rawResultToModels($results), $count[0]['COUNT(*)']];
    }

    public static function deleteUser($username)
    {
      try {
        $users = \DB::select('SELECT * FROM `' . static::$tableName . '` WHERE username = ?', [$username]);
        if (count($users) == 0) return false;
        $user_id = $users[0]->id;

        $vehicles = \DB::select('SELECT * FROM `' . \App\Models\Vehicle::$tableName . '` WHERE driver = ?', [$user_id]);
        $vehiclesArray = [];
        $vehiclesWhere = '';
        foreach ($vehicles as $k => $vehicle) {
          $vehiclesArray[] = $vehicle->plateNo;
          if ($vehiclesWhere != '') $vehiclesWhere = $vehiclesWhere . ', ';
          $vehiclesWhere = $vehiclesWhere . '?';
        }

        $journeys = \DB::select('SELECT * FROM `' . \App\Models\Journey::$tableName . '` WHERE vehicle IN (' . $vehiclesWhere . ')', $vehiclesArray);
        $journeyArray = [];
        $journeyWhere = '';
        foreach ($journeys as $k => $journey) {
          $journeyArray[] = $journey->id;
          if ($journeyWhere != '') $journeyWhere = $journeyWhere . ', ';
          $journeyWhere = $journeyWhere . '?';
        }

        \DB::delete('DELETE FROM `' . \App\Models\Transaction::$tableName . '` WHERE `from` = ? OR `to` = ?', [$user_id, $user_id]);
        \DB::delete('DELETE FROM `' . \App\Models\Booking::$tableName . '` WHERE passenger_id = ?', [$user_id]);
        \DB::delete('DELETE FROM `' . \App\Models\Booking::$tableName . '` WHERE journey_id IN (SELECT id FROM `' . \App\Models\Journey::$tableName . '` WHERE vehicle IN (SELECT plateNo FROM `' . \App\Models\Vehicle::$tableName . '` WHERE driver = ' . $user_id . '))');
        \DB::delete('DELETE FROM `' . \App\Models\Journey::$tableName . '` WHERE vehicle IN (SELECT plateNo FROM `' . \App\Models\Vehicle::$tableName . '` WHERE driver = ' . $user_id . ')');
        \DB::delete('DELETE FROM `' . \App\Models\Vehicle::$tableName . '` WHERE driver = ?', [$user_id]);
        \DB::delete('DELETE FROM `' . static::$tableName . '` WHERE id = ?', [$user_id]);
      } catch (\Illuminate\Database\QueryException $e) {
        //throw $e;
        return false;
      }

      return true;
    }

    public static function getUserNameIdPair() {
      $strSql = "SELECT id, username FROM " . static::$tableName;
      return \DB::select($strSql);
    }

    public static function getUsername($userId) {
      $strSql = "SELECT username FROM " . static::$tableName . " WHERE id = " . $userId;
      return \DB::select($strSql);
    }

   public static function getUserId($username) {
      $strSql = "SELECT id FROM " . static::$tableName . " WHERE username = '" . $username . "'";
      return \DB::select($strSql);
    }

    public static function createTableSQL()
    {
      return
      'CREATE TABLE IF NOT EXISTS ' . static::$tableName . ' (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `fullname` VARCHAR(255) NOT NULL,
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `password` CHAR(60) NOT NULL,
        `rememberToken` CHAR(100) NOT NULL,
        `phone` VARCHAR(45) NOT NULL,
        `isAdmin` TINYINT(1)  NOT NULL DEFAULT 0,
        `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `lastLogin` TIMESTAMP NOT NULL
      )';
    }
}

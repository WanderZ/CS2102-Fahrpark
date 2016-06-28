<?php

namespace App\Models;

use DB;

class Vehicle extends CS2102Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public static $tableName = 'Vehicles';

    /**
     * Variable to indicate if the primary keys need to be incremented
     *
     * @var array
     */
    public $incrementing = false;

    public static function createTableSQL()
    {
      $createSql = '
          CREATE TABLE IF NOT EXISTS ' . static::$tableName . ' (
            `plateNo` VARCHAR(8) NOT NULL,
            `numSeat` SMALLINT(2) NOT NULL,
            `brand` VARCHAR(255) NOT NULL,
            `model` VARCHAR(255) NOT NULL,
            `color` VARCHAR(45) NOT NULL,
            `driver` INT NOT NULL,
            `deletedAt` TIMESTAMP NULL,
            `createdAt` TIMESTAMP NOT NULL DEFAULT NOW(),
            PRIMARY KEY (`plateNo`),
            INDEX `fk_driver_idx` (`driver` ASC),
            CONSTRAINT `fk_driver`
                FOREIGN KEY (`driver`)
                REFERENCES `Users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
          )';
      return $createSql;
    }

    /**
     * Retrieve Vehicles record by user.
     *
     * @param string userId
     * @return array of Vehicle
     */
    public static function retrieveAll() {
        $vehicle = DB::select('SELECT * FROM Vehicles v, Users u WHERE v.driver = u.id');

        return $vehicle;
    }

    /**
     * Retrieve Vehicle record.
     *
     * @param string plateNo
     * @return Vehicle
     */
    public static function retrieveByPlateNo($plateNo) {
        $vehicle = DB::select('SELECT * FROM Vehicles v, Users u WHERE plateNo = ? AND v.driver = u.id', array($plateNo));

        return $vehicle[0];
    }

    /**
     * Retrieve Vehicles record by user.
     *
     * @param string userId
     * @return array of Vehicle
     */
    public static function retrieveByUserId($userId) {
        $vehicle = DB::select('SELECT * FROM Vehicles v, Users u WHERE driver = ? AND v.driver = u.id AND v.deletedAt IS NULL', array($userId));

        return $vehicle;
    }

    /**
     * Retrieve Vehicles record with the specified term
     * filtered with user permission.
     *
     * @param string keyword
     * @param int isAdmin
     * @param string userId
     * @return array of Vehicle
     */
    public static function retrieveByKeyword($keyword, $isAdmin, $userId) {
        if ($isAdmin == 1) {
            $vehicle = DB::select('SELECT * FROM Vehicles v, Users u WHERE v.driver = u.id
                 AND (plateNo LIKE \'%'.$keyword.'%\'
                 OR numSeat LIKE \'%'.$keyword.'%\'
                 OR brand LIKE \'%'.$keyword.'%\'
                 OR model LIKE \'%'.$keyword.'%\'
                 OR color LIKE \'%'.$keyword.'%\')
                 ;');
        } else {
            $vehicle = DB::select('SELECT * FROM Vehicles v, Users u WHERE driver = '.$userId.'
                 AND v.driver = u.id
                 AND (plateNo LIKE \'%'.$keyword.'%\'
                 OR numSeat LIKE \'%'.$keyword.'%\'
                 OR brand LIKE \'%'.$keyword.'%\'
                 OR model LIKE \'%'.$keyword.'%\'
                 OR color LIKE \'%'.$keyword.'%\')
                 ;');
        }

        return $vehicle;
    }
}

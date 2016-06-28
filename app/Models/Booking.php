<?php

    namespace App\Models;

class Booking extends CS2102Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public static $tableName = 'Bookings';

    public static function createTableSQL()
    {
          return
            'CREATE  TABLE IF NOT EXISTS ' . static::$tableName . ' (
          `id` INT PRIMARY KEY AUTO_INCREMENT ,
          `journey_id` INT ,
          `passenger_id` INT ,
          `status` CHAR(9) NOT NULL ,
          `remarks` TEXT NOT NULL ,
          `createdAt` TIMESTAMP NOT NULL DEFAULT NOW(),
          FOREIGN KEY (`journey_id`)
          REFERENCES ' .Journey::$tableName.' (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
          FOREIGN KEY (`passenger_id`)
          REFERENCES ' .User::$tableName.' (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
          )';
    }

    public static function doCreate($journeyId, $passengerId, $status, $remarks){
        return Booking::doInsert([
            'journey_id' => $journeyId,
            'passenger_id' => $passengerId,
            'status' => $status,
            'remarks' => $remarks
        ]);
    }

    public static function getRequest($userId){
          $query = 'SELECT *
          FROM '.Journey::$tableName.' j, '.Booking::$tableName.' b
          WHERE j.id = b.journey_id
          AND b.passenger_id = '.$userId;

          $results = \DB::select($query);
          return $results;
    }

    public static function getBooking($bookingId){
          $query = 'SELECT *
          FROM '.User::$tableName.' u, '.Booking::$tableName.' b
          WHERE u.id = b.passenger_id
          AND b.id = '.$bookingId. '
          ORDER BY b.createdAt';

          $results = \DB::select($query);
          return $results;
     }


    public static function getOfferOfJourney($journeyId){
          $query = 'SELECT *
          FROM '.User::$tableName.' u, '.Booking::$tableName.' b
          WHERE u.id = b.passenger_id
          AND b.journey_id = '.$journeyId. '
          ORDER BY b.createdAt';

          $results = \DB::select($query);
          return $results;
     }

      public static function getStatusOfJourney($userId){
               $query = 'SELECT j.*,
            COUNT(CASE WHEN b.status = "ACCEPT" THEN 1 ELSE NULL END)
            AS accept,
            COUNT(CASE WHEN b.status = "REJECT" THEN 1 ELSE NULL END)
            AS reject,
            COUNT(CASE WHEN b.status = "PENDING" THEN 1 ELSE NULL END)
            AS pending
           FROM '.Booking::$tableName.' b, '.
            Journey::$tableName.' j, '.
            Vehicle::$tableName.' v, '.
            User::$tableName.' u
          WHERE b.journey_id = j.id
          AND u.id = v.driver
		      AND v.plateNo = j.vehicle
		      AND u.id = '.$userId.'
          GROUP BY b.journey_id';

          $results = \DB::select($query);
          return $results;
     }

     public static function getStatusOfAllJourney(){
               $query = 'SELECT j.*,
            COUNT(CASE WHEN b.status = "ACCEPT" THEN 1 ELSE NULL END)
            AS accept,
            COUNT(CASE WHEN b.status = "REJECT" THEN 1 ELSE NULL END)
            AS reject,
            COUNT(CASE WHEN b.status = "PENDING" THEN 1 ELSE NULL END)
            AS pending
           FROM '.Booking::$tableName.' b, '.
            Journey::$tableName.' j, '.
            Vehicle::$tableName.' v, '.
            User::$tableName.' u
          WHERE b.journey_id = j.id
          AND u.id = v.driver
		      AND v.plateNo = j.vehicle
          GROUP BY b.journey_id';

          $results = \DB::select($query);
          return $results;
     }

     public static function editBooking($journeyId, $userId, $status, $remarks, $bookingId){
          $query = 'UPDATE '.Booking::$tableName.'
          SET journey_id = "'.$journeyId.'",
          passenger_id = "'.$userId.'",
          status = "'.$status.'",
          remarks = "'.$remarks.'"
          WHERE id = "'.$bookingId.'"';

          $results = \DB::update($query);
          return $results;
     }

     public static function updateStatus($id, $status){
          $query = 'UPDATE '.Booking::$tableName.'
          SET status = "'.$status.'"
          WHERE id = '.$id;

          $results = \DB::update($query);
          return $results;
     }

    public static function deleteRequest($id){
          $query = 'DELETE FROM '.Booking::$tableName.'
          WHERE id = '.$id;

          $results = \DB::delete($query);
          return $results;
    }
}

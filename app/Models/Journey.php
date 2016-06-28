<?php

namespace App\Models;

class Journey extends CS2102Model
{
  /**
     * The database table used by the model.
     *
     * @var string
     */
  public static $tableName = 'Journeys';

  public $incrementing = true;

  /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
  protected $hidden = ['createdAt'];

  public static function createTableSQL()
  {
    $createSql = '
      CREATE TABLE IF NOT EXISTS ' . static::$tableName . ' (
      `id` INT PRIMARY KEY AUTO_INCREMENT,
  		`vehicle` VARCHAR(8) NOT NULL ,
  		`createdAt` TIMESTAMP NOT NULL DEFAULT NOW(),
      `startLat` DOUBLE NOT NULL ,
      `startLng` DOUBLE NOT NULL ,
  		`endLat` DOUBLE NOT NULL ,
  		`endLng` DOUBLE NOT NULL ,
  		`start` VARCHAR(255) NOT NULL ,
  		`end` VARCHAR(255) NOT NULL ,
	  	`cost` DECIMAL(10,2) NOT NULL CHECK (cost >= 0),
	  	`remarks` TEXT NOT NULL,
	  	`departureDatetime` DATETIME NOT NULL ,
	  	`arrivalDatetime` DATETIME NOT NULL ,
      `deletedAt`   TIMESTAMP NULL ,
	  	CONSTRAINT `chk_dt`
	  		CHECK (departureDatetime > arrivalDatetime),
	  	CONSTRAINT `fk_vehicle`
	    	FOREIGN KEY (`vehicle` )
	    	REFERENCES ' .Vehicle::$tableName.' (`plateNo` )
	    	ON DELETE CASCADE
	    	ON UPDATE CASCADE
      )
      ';
    return $createSql;
  }

  public static function createTriggers()
  {
    $stmt = "DROP TRIGGER IF EXISTS j_check_cost_dt_ins;";
    \DB::unprepared($stmt);
    // FOR EACH EVENT FOR EACH TABLE CAN ONLY HAVE 1 !! TRIGGER. SO MERGE INTO ONE METHOD
    $stmt = "CREATE TRIGGER j_check_cost_dt_ins BEFORE INSERT ON ".static::$tableName.' FOR EACH ROW BEGIN ';

    $stmt .= "DECLARE dummy DECIMAL(10,2); IF NEW.cost < 0 THEN ";
    $stmt .= "SELECT CONCAT('Cannot Insert This Because cost ',NEW.cost,' is <0')";
    $stmt .= "INTO dummy FROM information_schema.tables; END IF;";

    $stmt .= "IF NEW.departureDatetime > NEW.arrivalDatetime THEN ";
    $stmt .= "SELECT CONCAT('Cannot Insert This Because departureDatetime ',NEW.departureDatetime,' is > ',NEW.arrivalDatetime,'')";
    $stmt .= "INTO dummy FROM information_schema.tables; END IF; END;";
    \DB::unprepared($stmt);

    $stmt = "DROP TRIGGER IF EXISTS j_check_cost_dt_upd;";
    \DB::unprepared($stmt);
    // FOR EACH EVENT FOR EACH TABLE CAN ONLY HAVE 1 !! TRIGGER. SO MERGE INTO ONE METHOD
    $stmt = "CREATE TRIGGER j_check_cost_dt_upd BEFORE UPDATE ON ".static::$tableName.' FOR EACH ROW BEGIN ';

    $stmt .= "DECLARE dummy DECIMAL(10,2); IF NEW.cost < 0 THEN ";
    $stmt .= "SELECT CONCAT('Cannot Insert This Because cost ',NEW.cost,' is <0')";
    $stmt .= "INTO dummy FROM information_schema.tables; END IF; ";

    $stmt .= "IF NEW.departureDatetime > NEW.arrivalDatetime THEN ";
    $stmt .= "SELECT CONCAT('Cannot Insert This Because departureDatetime ',NEW.departureDatetime,' is > ',NEW.arrivalDatetime,'')";
    $stmt .= "INTO dummy FROM information_schema.tables; END IF; END;";
    \DB::unprepared($stmt);
  }

  public static function createStoredProcedures()
  {
    $stmt = "DROP PROCEDURE IF EXISTS j_getUserId;";
    \DB::unprepared($stmt);

    $stmt = "CREATE PROCEDURE j_getUserId (IN ve_pl VARCHAR(8), OUT u_id INT(11)) ";
    $stmt .= "BEGIN SELECT v.driver INTO u_id FROM Vehicles v WHERE v.plateNo=ve_pl; END;";
    \DB::unprepared($stmt);

    //Sample Call (Suppose Car Plate is SG12345X)
    $stmt = "CALL j_getUserId('SG12345X', @a); SELECT @a;";
    //return \DB::statement($stmt);
  }

  public static function getAll()
  {
    $query =
      'SELECT j.*
		FROM '.Journey::$tableName.' j
		WHERE j.departureDatetime > NOW()
    AND j.deletedAt IS NULL ORDER BY j.departureDatetime';

    $results = \DB::select($query);

    return $results;
  }

  public static function getJourneyByKeys($id) {
    $query = 'SELECT * FROM '.Journey::$tableName.' WHERE deletedAt IS NULL AND `id` = ' .$id .'';
    $results = \DB::select($query);
    foreach ($results as $journey) {
      $journey->departureDatetime = strftime('%Y-%m-%d %H:%M', strtotime($journey->departureDatetime));
      $journey->arrivalDatetime = strftime('%Y-%m-%d %H:%M', strtotime($journey->arrivalDatetime));
    }
    return $results;
  }

  public static function getAllUserJourney($userId)
  {
    $query =
      'SELECT j.*, u.username
		FROM '.Journey::$tableName.' j, '.Vehicle::$tableName.' v, '.User::$tableName.' u
		WHERE u.id = v.driver
		AND v.plateNo = j.vehicle
    AND j.deletedAt IS NULL
		AND u.id = '.$userId.' ORDER BY j.departureDatetime';
    $results = \DB::select($query);

    return $results;
  }

  public static function getJourneysLikeTerm($term)
  {
    $query =
      'SELECT j.* FROM '.
      Journey::$tableName.' j, '.
        User::$tableName.' u
				WHERE u.username LIKE \'%'.$term.'%\'
				OR j.start LIKE \'%'.$term.'%\'
				OR j.end LIKE \'%'.$term.'%\'
				OR j.remarks LIKE \'%'.$term.'%\'
				OR j.vehicle LIKE \'%'.$term.'%\'
        GROUP BY j.vehicle, j.id
        ORDER BY j.departureDatetime
				;'  ;

    $results = \DB::select($query);
    return $results;
  }

  public static function getSearchedJourney($filter)
  {
    $searchCond = "";
    if($filter['vehicle'] != '')
    {
      $searchCond .= "AND vehicle LIKE '%".$filter['vehicle']."%' ";
    }

    if($filter['start'] != '')
    {
      $searchCond .= "AND start LIKE '%".$filter['start']."%' ";
    }

    if($filter['end'] != '')
    {
      $searchCond .= "AND end LIKE '%".$filter['end']."%' ";
    }

    if($filter['departureDatetime'] != '')
    {
      $filter['departureDatetime'] = strftime('%Y-%m-%d %H:%M:%S', strtotime($filter['departureDatetime']));
      if ($filter['departureDatetimeOption'] == 'lessthan')
      {
        $searchCond .= "AND departureDatetime < '".$filter['departureDatetime']."' ";

      } else if ($filter['departureDatetimeOption'] == 'morethan')
      {
        $searchCond .= "AND departureDatetime > '".$filter['departureDatetime']."' ";
      } else if ($filter['costOption'] == 'between') {
        if ($filter['departureDatetime2'] != '')
        {
          $filter['departureDatetime2'] = strftime('%Y-%m-%d %H:%M:%S', strtotime($filter['departureDatetime2']));
          $searchCond .= "AND departureDatetime > '".$filter['departureDatetime']."' ";
          $searchCond .= "AND departureDatetime < '".$filter['departureDatetime2']."' ";
        }
      }

    }

    if($filter['arrivalDatetime'] != '')
    {
      $filter['arrivalDatetime'] = strftime('%Y-%m-%d %H:%M:%S', strtotime($filter['arrivalDatetime']));
      if ($filter['arrivalDatetimeOption'] == 'lessthan')
      {
        $searchCond .= "AND arrivalDatetime < '".$filter['arrivalDatetime']."' ";

      } else if ($filter['arrivalDatetimeOption'] == 'morethan')
      {
        $searchCond .= "AND arrivalDatetime > '".$filter['arrivalDatetime']."' ";
      } else if ($filter['costOption'] == 'between') {
        if ($filter['arrivalDatetime2'] != '')
        {
          $filter['arrivalDatetime2'] = strftime('%Y-%m-%d %H:%M:%S', strtotime($filter['arrivalDatetime2']));
          $searchCond .= "AND arrivalDatetime > '".$filter['arrivalDatetime']."' ";
          $searchCond .= "AND arrivalDatetime < '".$filter['arrivalDatetime2']."' ";
        }
      }
    }

    if($filter['cost'] != '')
    {
      if ($filter['costOption'] == 'lessthan')
      {
        $searchCond .= "AND cost < ".$filter['cost']." ";

      } else if ($filter['costOption'] == 'morethan')
      {
        $searchCond .= "AND cost > ".$filter['cost']." ";
      } else if ($filter['costOption'] == 'between')
      {
        if ($filter['costOption'] != '')
        {
          $searchCond .= "AND cost > ".$filter['cost']." ";
          $searchCond .= "AND cost < ".$filter['cost2']." ";
        }
      }
    }

    if($filter['remarks'] != '')
    {
      $searchCond .= "AND remarks LIKE '%".$filter['remarks']."%' ";
    }

    if ($searchCond != ''){
      $searchCond = substr($searchCond, 4);
      $searchCond .= ' AND deletedAt IS NULL;';
      $query = "SELECT * FROM Journeys WHERE ".$searchCond;
      $results = \DB::select($query);
      return $results;
    }

    return false;
  }

  public static function retrieveJourneysForGuests() {
    $strSql = "SELECT j.id, j.start, j.end, v.brand, v.model FROM Journeys j INNER JOIN Vehicles v ON j.vehicle = v.plateNo WHERE j.deletedAt IS NULL AND v.deletedAt IS NULL ORDER BY j.createdAt ASC";
    return \DB::select($strSql);
  }

  protected static function softDelete($id) {
    $stmt = "UPDATE ".static::$tableName." ";
    $stmt .= "SET deletedAt=NOW() ";
    $stmt .= "WHERE `id`=".$id;

    $results = \DB::UPDATE($stmt);
    return $results;
  }
}

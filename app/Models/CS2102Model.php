<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class CS2102Model extends Model
{
  /**
   * Table name for use by our model.
   */
  public static $tableName = '';

  public $timestamps = false;

  /**
   * Flag to indicate if the primary key is incrementing
   *
   * @var bool
   */
  public $incrementing = false;

  public function __construct(array $attributes = [])
  {
    $this->table = static::$tableName;

    parent::__construct($attributes);
  }

  /**
   * Creates models from the raw results.
   *
   * @param array $rawResult
   * @return Collection
   */
  public static function rawResultToModels($rawResult = [])
  {
    $objects = [];

    foreach($rawResult as $result)
    {
        $object = new static();
        $object->setRawAttributes((array) $result, true);
        $objects[] = $object;
    }

    return new Collection($objects);
  }

  /**
   * Returns SQL Query to create the table.
   *
   * @var string
   */
  public static function createTableSQL()
  {
    return 'CREATE TABLE IF NOT EXISTS ' . static::$tableName . ' ()';
  }

  /**
   * Create table. Throws error if unsuccessful.
   */
  public static function createTable()
  {
    \DB::statement(static::createTableSQL());
  }

  /*
   * Do an SQL insert into the table with the key/values $attributes. Should be used for almost all 'insert'-ing needs.
   *
   * @return \App\Models\CS2102Model
   */
  public static function doInsert(array $attributes)
  {
    // Map the attributes to values which we'll use to construct the SQL statement
    $fields = '';
    $valuesName = '';

    // Form our fields and values strings
    //   field1, field2, field3 etc.
    //   :value1, :value2, :value3 etc.
    // So that our query would look like:
    // INSERT INTO TABLE_NAME_HERE (field1, field2, field3) VALUES (:value1, :value2, :value3)
    // The : are named values passed as second argument to the function
    foreach ($attributes as $k => $v) {
      if ($fields != '') {
        $fields = $fields . ', ';
        $valuesName = $valuesName . ', ';
      }
      $fields = $fields . '`' . $k . '`';
      $valuesName = $valuesName . ':' . $k;
    }

    // Create our SQL statement and perform the insert
    if (\DB::insert('INSERT INTO `' . static::$tableName . '` (' . $fields . ') VALUES (' . $valuesName . ')', $attributes)) {
      // Return the model object if successful
      $model = new static();
      if ($model->incrementing) {
        $results = \DB::select('SELECT * FROM `' . static::$tableName . '` WHERE `' . $model->primaryKey . '` = ' . \DB::getPdo()->lastInsertId());
        $results = static::rawResultToModels($results);
        return $results[0];
      }

      $model->setRawAttributes($attributes);
      return $model;
    }

    return null;
  }

  /*
   * Do an SQL update query. Should be used for simple update queries where the WHERE clause only consist of 'AND' operators.
   *
   * @return Number of rows affected
   */
  public static function doUpdate(array $whereQuery, array $attributes)
	{
    // Form our fields and values strings
    //   field1, field2, field3 etc.
    //   :value1, :value2, :value3 etc.
    // So that our query would look like:
    // UPDATE TABLE_NAME_HERE SET field1 = :value1 WHERE field2 = :value2 AND field3 = :value3
    // The : are named values passed as second argument to the function
    $values = [];
    $set = '';
    foreach ($attributes as $k => $v) {
      if ($set) {
        $set = $set . ', ';
      }
      $set = $set . $k . ' = ?';
      $values[] = $v;
    }

    $where = '';
    foreach ($whereQuery as $k => $v) {
      if ($where) {
        $where = $where . ' AND ';
      }
      $where = $where . $k . ' = ?';
      $values[] = $v;
    }

		return \DB::update('UPDATE '. static::$tableName . ' SET ' . $set . ' WHERE ' . $where, $values);
	}

  /*
   * Do an SQL delete query. Should be used for simple delete queries where the WHERE clause only consist of 'AND' operators.
   *
   * @return Number of rows affected
   */
	public static function doDelete(array $whereQuery)
	{
    // Form our fields and values strings
    //   field1, field2, field3 etc.
    //   :value1, :value2, :value3 etc.
    // So that our query would look like:
    // DELETE FROM TABLE_NAME_HERE WHERE field1 = :value1 AND field2 = :value2 AND field3 = :value3
    // The : are named values passed as second argument to the function
    $values = [];
    $where = '';
    foreach ($whereQuery as $k => $v) {
      if ($where) {
        $where = $where . ' AND ';
      }
      $where = $where . $k . ' = ?';
      $values[] = $v;
    }

		return \DB::delete('DELETE FROM '. static::$tableName . ' WHERE ' . $where, $values);
	}
}

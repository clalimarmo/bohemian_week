<?php
/*
**	A general model manager, which includes an abstract model class,
**	and a generic database interface class.
**
**	Database access settings must be specified in settings/database.php
**
**	It is NOT recommended that you modify these classes. Their intended purpose
**	is to provide functionality for subclasses. See README on usage.
**
**	Enjoy!
**
**	Original Version: Fall 2011, Carlos Lalimarmo
*/

/*
** The abstract model class
*/
class ObjectModel {
	protected static $field_names = array();
	protected static $primary_keys = array();
	protected static $field_defaults = array();

	public static final function get_field_names() {
		return static::$field_names;
	}
	public static final function pk() {
		return static::$primary_keys;
	}
	public static final function get_field_defaults() {
		return static::$field_defaults;
	}
	public static function not_primary_key($field_name) {
		return !in_array($field_name, static::$primary_keys);
	}
	public static final function get_non_pk_field_names() {
		return array_filter(static::$field_names,
			"ObjectModel::not_primary_key");
	}

	protected $fields = array();
	protected static $manager_class_name;

	/*
	** If provided with an array, will use values from that array as the
	** field values, for any values whose key matches a field name.
	**
	** Any uninitialized field values are initialized to NULL if no other
	** default is specified in ObjectModel::$field_defaults.
	*/
	public function __construct($field_values = NULL) {
		$this->set_fields($field_values);
	}

	/*
	** Returns the value of the specified field name. If the field name doesn't
	** exist, throws a field doesn't exist exception
	*/
	public function get_field($field_name) {
		if(array_key_exists($field_name, $this->fields))
			return $this->fields[$field_name];
		else
			throw new Exception('field ' . $field_name . ' doesn\'t exist');
	}

	/*
	** Sets fields, as per the __construct function
	*/
	public function set_fields($field_values = NULL) {
		if(is_array($field_values)) {
			foreach(static::get_non_pk_field_names() as $field_name) {
				if(array_key_exists($field_name, $field_values)) {
					$this->fields[$field_name] = $field_values[$field_name];
				}
			}
		}
		foreach(static::get_non_pk_field_names() as $field_name) {
			if(!array_key_exists($field_name, $this->fields)
				|| $this->fields[$field_name] === NULL
				|| $this->fields[$field_name] === ""
			) {
				if(array_key_exists($field_name, static::$field_defaults))
					$this->fields[$field_name] =
						static::$field_defaults[$field_name];
				else
					$this->fields[$field_name] = NULL;
			}
		}
	}

	/*
	** Saves this instance to the database. If this instance
	** does not have an ID (it's a new instance not retrieved from the
	** database) then a new row is created. Otherwise, the row in the database
	** that has the same ID as this instance is updated with the non-NULL
	** values of this instance.
	**
	** In the case that ID is set, but a corresponding database entry does
	** not exist in the database, ID is ignored and a new database is created
	** with a new ID (which is not necessarily the ID specified in this
	** instance).
	**
	** Returns the return value of the msyql_query() with the mysql
	** create/update statement used to affect the changes in the database.
	*/
	public function save() {
		$m = static::get_object_manager();

		$filters = array(array("id=" => $this->fields['id']));
		$is_new = !count($m->retrieve($filters));

		$rval = NULL;

		if($is_new){
			$rval = $m->create($this);
			}
		else{
			$rval = $m->update($this, $filters);
			}
		
		return $rval;
	}

	/*
	** Removes the entry associated with this ObjectModel instance from the
	** database
	*/
	public function delete() {
		$m = static::get_object_manager();
		$filters = array(array("id=" => $this->fields['id']));
		return $m->delete($filters);
	}

	public static function get_object_manager() {
		static $m;
		if($m === NULL) {
			$reflection_obj = new ReflectionClass(static::$manager_class_name);
			$m = $reflection_obj->newInstanceArgs();
		}
			
		return $m;
	}
}

/*
** the database interface class
*/
class ObjectManager {
	protected static $table_name;

	/* the name of the object model class we're managing */
		protected static $model_class_name;

	protected $table_prefix;
	protected $db_connection;
	

	public function __construct() {
		include("settings/database.php");

		$this->db_connection = mysql_connect($db_server, $db_user, $db_passwd)
			or die("Could not connect: " . mysql_error() . "; check settings/database.php");
		$db_selected = mysql_select_db($db_name);
		if(!$db_selected) {
			die("Can't use database " . $db_name . " " . mysql_error()
				. "; check settings/database.php");
		}
		$this->table_prefix = $db_table_prefix;
	}

	public function __destruct() {
		mysql_close($this->db_connection);
	}

	/* add a instance to the database */
	public function create($instance) {
		$field_names = static::get_non_pk_field_names();
		$create = "INSERT INTO " . $this->full_table_name();
		$create = $create . " ("
			. implode(
				' , ',
				$field_names
			)
			. ")";

		$value_string = "VALUES (";
		foreach($field_names as $field_name) {
			$field_value = $instance->get_field($field_name);
			if($value_string != "VALUES (")
				$value_string = $value_string . ' , ';
			if(is_string($field_value))
				$value_string = $value_string
					. "'" . mysql_real_escape_string($field_value) . "'";
			elseif($field_value === NULL)
				$value_string = $value_string . "NULL";
			else
				$value_string = $value_string . $field_value;
		}
		$value_string = $value_string . ")";
		$create = $create . " " . $value_string;

		return mysql_query($create);
	}

	/*
	**	Get instances from the database, using a set of filters specified as
	**	a mysql WHERE clause
	**
	**	$filters will be specified as an array of $key => $value arrays, each
	**	of which will satisfy a set of possible necessary conditions to be
	**	filtered in. That is: conditions in the innermost arrays will be joined
	**	by ANDs, and each set of those in the outermost array will be joined
	**	by ORs.
	**	
	**	Each key in the innermost array of $filters is treated as the left side
	**	of a mysql WHERE clause, and the value is treated as the right side.
	**
	**	For example: the following $filters argument
	**		$filters = array(
	**		array("id = " => 2, "publication = " => "NY Times"),
	**			array("id < " => 5)
	**		);
	**	corresponds with
	**		"WHERE (id = 2 AND publication = 'NY Times') OR (id < 5)"
	**
	**	All string values in $filters are mysql escaped, and wrapped in
	**	single quotes.
	**
	**	Specifying NULL for $filters (or omitting) retrieves all instances
	**
	**	Returns all objects in the database which satisfy all specified
	**	filters, offset = $offset, and up to the amount specified by $limit.
	*/
	public function retrieve($filters = NULL, $offset = 0, $limit = 0,
		$sortby = NULL)
	{
		$instances = array();
		$field_names = static::get_field_names();

		if($sortby == NULL)
			$sortby = $field_names[0];

		/* build the mysql select statement */

		$fields = implode(' , ', $field_names);

		$select = "SELECT " . $fields . " FROM " . $this->full_table_name()
			. " " . static::get_where_clause($filters);
		$select = $select . " ORDER BY " . $sortby;
		if($limit > 0)
			$select = $select . " LIMIT " . $limit;
		if($offset > 0)
			$select = $select . " OFFSET " . $offset;

		/* get the result into an array of model instances */

		//echo $select;
		$result = mysql_query($select);
		while($row = mysql_fetch_array($result)) {
			$field_vals = array();
			foreach($field_names as $field_name) {
				$field_vals[$field_name] = $row[$field_name];
			}

			$reflection_obj = new ReflectionClass(static::$model_class_name);
			$instance = $reflection_obj->newInstanceArgs();
			$instance->set_fields($field_vals);

			$instances[] = $instance;
		}

		return $instances;
	}

	/*
	**	Updates model instances retrieved using $filters with the non-null
	**	properties of $instance. $limit specifies the maximum number of rows
	**	to be affected by the update. $limit = 0 allows any amount of
	**	instances to be updated. By default, will only update 1 instance.
	**
	**	Recommended to only filter on the primary key when using this
	**	function.
	*/
	public function update($instance, $filters, $limit = 1) {
		$field_names = call_user_func(
			static::$model_class_name . "::get_non_pk_field_names"
		);
		$where_clause = static::get_where_clause($filters);
		$set_string = "SET ";
		foreach($field_names as $field_name) {
			if($set_string != "SET ")
				$set_string = $set_string . ",";
			$new_value = $instance->get_field($field_name);
			if($new_value === NULL)
				$new_value = "NULL";
			elseif(is_string($new_value))
				$new_value = "'" . mysql_real_escape_string($new_value) . "'";
			$set_string = $set_string . $field_name . "="
				. $new_value;
		}

		$update = "UPDATE " . $this->full_table_name() . " " . $set_string
			. " " . $where_clause;
		
		if($limit > 0)
			$update = $update . " LIMIT " . $limit;

		return mysql_query($update);
	}

	/*
	**	Deletes up to $limit instances, specified by $filters. $limit = 0
	**	allows any amount of instances to be deleted, the default is 1
	*/
	public function delete($filters, $limit = 1) {
		$delete = "DELETE FROM " . $this->full_table_name() . " " .
			static::get_where_clause($filters);
		if($limit > 0)
			$delete = $delete . " LIMIT " . $limit;

		return mysql_query($delete);
	}

	protected function full_table_name() {
		$class = get_class($this);
		return $this->table_prefix . $class::$table_name;
	}

	protected static function get_where_clause($filters) {
		$where_clause = "";
		foreach($filters as $and_set) {
			$and_clause_group = "(";
			if($where_clause == "") {
				$where_clause = "WHERE ";
			}
			else {
				$where_clause = $where_clause . " OR ";
			}
			foreach($and_set as $key => $value) {
				if($and_clause_group != "(") {
					$and_clause_group = $and_clause_group . " AND ";
				}
				$value_is_numeric_list = preg_match(
					'#^\((\d+,)*\d+\)$#',
					$value
				);
				$should_escape = (is_string($value)
					&& !$value_is_numeric_list);
				if($should_escape) {
					$value = "'" . mysql_real_escape_string($value) . "'";
				}
				$and_clause_group = $and_clause_group
					. mysql_real_escape_string($key) . $value;
			}
			$and_clause_group = $and_clause_group . ")";
			$where_clause = $where_clause . $and_clause_group;
		}
		return $where_clause;
	}

	protected static final function get_non_pk_field_names() {
		$mcn = static::$model_class_name;
		$callback = array($mcn, 'get_non_pk_field_names');
		$field_names = call_user_func($callback);
		return $field_names;
	}

	protected static final function get_field_names() {
		$mcn = static::$model_class_name;
		$callback = array($mcn, 'get_field_names');
		$field_names = call_user_func($callback);
		return $field_names;
	}
}
?>

<?php
require_once(MODEL_ROOT.'model.php');

class Contributor extends ObjectModel {
	protected static $field_names = array(
		'id'
		,'name'
		,'email'
	);
	protected static $field_defaults = array(
		'name' => 'Anonymous'
		,'email' => ''
	);
	protected static $primary_keys = array('id');
	protected static $manager_class_name = 'ContributorManager';

	public function save() {
		$success = parent::save();
		if($this->get_field('id') == NULL) {
			$this->set_fields(array(
				'id' => static::get_object_manager()->last_insert_id()
			));
		}
		return $success;
	}
}
class ContributorManager extends ObjectManager {
	protected static $table_name = 'contributor';
	protected static $model_class_name = 'Contributor';

	public function last_insert_id() {
		$liid = mysql_insert_id($this->db_connection);
		echo '<br>liid:'.$liid;
		return $liid;
	}
}

?>

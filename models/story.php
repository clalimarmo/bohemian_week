<?php
require_once(MODEL_ROOT.'model.php');
require_once(MODEL_ROOT.'contributor.php');

class Story extends ObjectModel {
	protected static $field_names = array(
		'id'
		,'contributor'
		,'title'
		,'story'
		,'location'
		,'season'
	);
	protected static $primary_keys = array('id');
	protected static $field_defaults = array(
		'location' => ''
	);
	protected static $manager_class_name = 'StoryManager';

	public function contributor() {
		$contributors = Contributor::get_object_manager()->retrieve(array(array(
			'id=' => $this->get_field('id')
		)));
		return $contributors[0];
	}
}
class StoryManager extends ObjectManager {
	protected static $table_name = 'story';
	protected static $model_class_name = 'Story';
}

?>


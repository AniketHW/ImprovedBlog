<?php
/**
* This is the template for generating the model class of a specified table.
* - $this: the ModelCode object
* - $tableName: the table name for this class (prefix is already removed if necessary)
* - $modelClass: the model class name
* - $columns: list of table columns (name=>CDbColumnSchema)
* - $labels: list of attribute labels (name=>label)
* - $rules: list of validation rules
* - $relations: list of relations (name=>relation declaration)
*/

?>
<?php echo "<?php\n"; ?>

<?php foreach($columns as $column): ?>
 /* @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?> {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
<?php if($connectionId!='db'):?>

	public function getDbConnection() {
		return Yii::app()-><?php echo $connectionId ?>;
	}
<?php endif?>

	public function tableName() {
		return '<?php echo $tableName; ?>';
	}

	public function rules() {
		return array(
			<?php foreach($rules as $rule) { ?>
				<?php echo $rule.",\n"; ?>
			<?php } ?>
		);
	}

	public function relations() {
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	public function beforeSave() {
		if($this->isNewRecord) { 
			$this->created_at = time();
		}
		$this->updated_at = time();
		return parent::beforeSave();
	}

	public function updateColumns($column_value_array) {
		$column_value_array['updated_at'] = time();
		foreach($column_value_array as $column_name => $column_value)
			$this->$column_name = $column_value;
		$this->update(array_keys($column_value_array));
	}

	public static function create($attributes) {
		$model = new <?php echo $modelClass; ?>;
		$model->attributes = $attributes;
		$model->save();
		return $model;
	}
}
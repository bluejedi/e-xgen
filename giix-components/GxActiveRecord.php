<?php

/**
 * GxActiveRecord class file.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @link http://rodrigocoelho.com.br/giix/
 * @copyright Copyright &copy; 2010 Rodrigo Coelho
 * @license http://rodrigocoelho.com.br/giix/license/ New BSD License
 */

/**
 * GxActiveRecord is the base class for the generated AR (base) models.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @since 1.0
 */
abstract class GxActiveRecord extends CActiveRecord {

	/**
	 * @var string the separator used to separate the primary keys values in a
	 * composite pk table. Usually a character.
	 */
	public static $pkSeparator = '-';

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * This method should be overridden to declare related pivot models for each MANY_MANY relationship.
	 * The pivot model is used by {@link saveWithRelated}.
	 * @return array List of pivot models for each MANY_MANY relationship. Defaults to empty array.
	 */
	public function pivotModels() {
		return array();
	}

	/**
	 * The specified column(s) is(are) the responsible for the
	 * string representation of the model instance.
	 * The column is used in the {@link __toString} default implementation.
	 * Every model must specify the attributes used to build their
	 * string representation by overriding this method.
	 * This method must be overriden in each model class
	 * that extends this class.
	 * @return string|array the name of the representing column for the table (string) or
	 * the names of the representing columns (array).
	 * @see {@link __toString}.
	 */
	public static function representingColumn() {
		return null;
	}

	/**
	 * Returns a string representation of the model instance, based on
	 * {@link representingColumn}.
	 * If the representing column is not set, the primary key will be used.
	 * If there is no primary key, the first field will be used.
	 * When you overwrite this method, all model attributes used to build
	 * the string representation of the model must be specified in
	 * {@link representingColumn}.
	 * @return string the string representation for the model instance.
	 */
	public function __toString() {
		$representingColumn = $this->representingColumn();

		if ($representingColumn === null)
			if ($this->getTableSchema()->primaryKey !== null)
				$representingColumn = $this->getTableSchema()->primaryKey;
			else
				$representingColumn=$this->getTableSchema()->columnNames[0];

		if (is_array($representingColumn)) {
			$part = '';
			foreach ($representingColumn as $representingColumn_item) {
				$part .= ( $this->$representingColumn_item === null ? '' : $this->$representingColumn_item) . '-';
			}
			return substr($part, 0, -1);
		} else {
			return $this->$representingColumn === null ? '' : (string) $this->$representingColumn;
		}
	}

	/**
	 * Finds all active records satisfying the specified condition, selecting only the requested
	 * attributes and, if specified, the primary keys.
	 * See {@link CActiveRecord::find} for detailed explanation about $condition and $params.
	 * @param string|array $attributes the names of the attributes to be selected.
	 * Optional. If not specified, the {@link representingColumn} will be used.
	 * @param boolean $withPk specifies if the primary keys will be selected.
	 * @param mixed $condition query condition or criteria.
	 * @param array $params parameters to be bound to an SQL statement.
	 * @return array list of active records satisfying the specified condition. An empty array is returned if none is found.
	 */
	public function findAllAttributes($attributes = null, $withPk = false, $condition='', $params=array()) {
		$criteria = $this->getCommandBuilder()->createCriteria($condition, $params);
		if ($attributes === null)
			$attributes = $this->representingColumn();
		if ($withPk) {
			$pks = self::model(get_class($this))->tableSchema->primaryKey;
			if (!is_array($pks))
				$pks = array($pks);
			if (!is_array($attributes))
				$attributes = array($attributes);
			$attributes = array_merge($pks, $attributes);
		}
		$criteria->select = $attributes;
		return parent::findAll($criteria);
	}

	/**
	 * Extracts and returns only the primary keys values from each model.
	 * @param GxActiveRecord|array $model a model or an array of models.
	 * @param boolean $forceString whether pk values on composite pk tables
	 * should be compressed into a string. The values on the string will by
	 * separated by {@link $pkSeparator}.
	 * @return string|array the pk value as a string (for single pk tables) or
	 * array (for composite pk tables) if one model was specified or
	 * an array of strings or arrays if multiple models were specified.
	 */
	public static function extractPkValue($model, $forceString = false) {
		if (!is_array($model)) {
			$pk = $model->primaryKey;
			if ($forceString && is_array($pk))
				$pk = implode(self::$pkSeparator, $pk);
			return $pk;
		} else {
			$pks = array();
			foreach ($model as $model_item) {
				$pks[] = self::extractPkValue($model_item, $forceString);
			}
			return $pks;
		}
	}

	/**
	 * Saves the current record and its relations.
	 * @param array $relatedData The relation data in the format returned by {@link GxController::getRelatedData}.
	 * @param boolean $runValidation Whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database. This applies to all (including related) models.
	 * This does not apply when in batch mode. This does not apply for deletes. If you want to validate deletes, disable
	 * batch mode and use the {@link CActiveRecord::onBeforeDelete} event.
	 * @param array $attributes List of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved. This applies only to the main model.
	 * @param array $options Additional options. Valid options are:
	 * <ul>
	 * <li>'withTransaction', boolean: Whether to use a transaction.
	 * Note: if there are no changes in the relations, no transaction will be used.</li>
	 * <li>'batch', boolean: Whether to try to do the deletes and inserts in batch.
	 * While batches may be faster, using active record instances provides better control, validation, event support etc.
	 * Batch is only supported for deletes.</li>
	 * </ul>
	 * @return boolean Whether the saving succeeds.
	 */
	public function saveWithRelated($relatedData, $runValidation = true, $attributes = null, $options = array()) {
		// The default options.
		$defaultOptions = array(
			'withTransaction' => true,
			'batch' => true,
		);
		// Merge the specified options with the default options.
		$options = array_merge($defaultOptions, $options);
		// Pass the options to variables.
		$withTransaction = $options['withTransaction'];
		$batch = $options['batch'];

		if (empty($relatedData)) {
			// There is no related data. We simply save the main model.
			return parent::save($runValidation, $attributes);
		} else {
			// Save each related data.
			foreach ($relatedData as $relationName => $relationData) {
				// Get the current related models of this relation and map the current related primary keys.
				$currentRelation = $this->$relationName;
				$currentMap = array();
				foreach ($currentRelation as $currentRelModel) {
					$currentMap[] = $currentRelModel->primaryKey;
				}
				// Compare the current map to the new data and identify what is to be kept, deleted or inserted.
				$newMap = $relationData;
				$deleteMap = array();
				$insertMap = array();
				if (!is_null($newMap)) {
					// Identify the relations to be deleted.
					foreach ($currentMap as $currentItem) {
						if (!in_array($currentItem, $newMap))
							$deleteMap[] = $currentItem;
					}
					// Identify the relations to be inserted.
					foreach ($newMap as $newItem) {
						if (!in_array($newItem, $currentMap))
							$insertMap[] = $newItem;
					}
				} else // If the new data is empty, everything must be deleted.
					$deleteMap = $currentMap;
				// If nothing changed, we simply save the main model.
				if (empty($deleteMap) && empty($insertMap))
					return parent::save($runValidation, $attributes);
				// Now act inserting and deleting the related data: first prepare the data.
				// Get the foreign key names for the models.
				$activeRelation = $this->getActiveRelation($relationName);
				$relatedClassName = $activeRelation->className;

				if (preg_match('/(.+)\((.+),\s*(.+)\)/', $activeRelation->foreignKey, $matches)) {
					// By convention, the first fk is for this model, the second is for the related model.
					//$pivotTableName = $matches[1];
					$thisFkName = $matches[2];
					$relatedFkName = $matches[3];
				}
				// The pivot model class name.
				$pivotClassNames = $this->pivotModels();
				$pivotClassName = $pivotClassNames[$relationName];
				$pivotModelStatic = GxActiveRecord::model($pivotClassName);
				// Get the primary key value of the main model.
				$thisPkValue = $this->primaryKey;
				if (is_array($thisPkValue))
					throw new Exception(Yii::t('giix', 'Composite primary keys are not supported.'), 500);
				// Inject the foreign key names of both models and the primary key value of the main model in the maps.
				foreach ($deleteMap as &$pkValue)
					$pkValue = array_merge(array($relatedFkName => $pkValue), array($thisFkName => $thisPkValue));
				unset($pkValue); // Clear reference;
				foreach ($insertMap as &$pkValue)
					$pkValue = array_merge(array($relatedFkName => $pkValue), array($thisFkName => $thisPkValue));
				unset($pkValue); // Clear reference;
				// Start the transaction if required.
				if ($withTransaction && is_null($this->dbConnection->currentTransaction)) {
					$transacted = true;
					$transaction = $this->dbConnection->beginTransaction();
				} else
					$transacted = false;
				try {
					// Save the main model.
					if (!parent::save($runValidation, $attributes)) {
						if ($transacted)
							$transaction->rollback();
						return false;
					}
					// Now act inserting and deleting the related data: then execute the changes.
					// Delete the data.
					if (!empty($deleteMap)) {
						if ($batch) {
							// Delete in batch mode.
							if ($pivotModelStatic->deleteByPk($deleteMap) !== count($deleteMap)) {
								if ($transacted)
									$transaction->rollback();
								return false;
							}
						} else {
							// Delete one active record at a time.
							foreach ($deleteMap as $value) {
								$pivotModel = GxActiveRecord::model($pivotClassName)->findByPk($value);
								if (!$pivotModel->delete()) {
									if ($transacted)
										$transaction->rollback();
									return false;
								}
							}
						}
					}
					// Insert the new data.
					foreach ($insertMap as $value) {
						$pivotModel = new $pivotClassName();
						$pivotModel->attributes = $value;
						if (!$pivotModel->save($runValidation)) {
							if ($transacted)
								$transaction->rollback();
							return false;
						}
					}
					// If transacted, commit the transaction.
					if ($transacted)
						$transaction->commit();
				} catch (Exception $ex) {
					// If there is an exception, roll back the transaction, if transacted. If not transacted, rethrow the exception.
					if ($transacted) {
						$transaction->rollback();
						return false;
					} else
						throw $ex;
				}
			}
			return true;
		}
	}

}
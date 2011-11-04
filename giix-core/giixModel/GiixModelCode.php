<?php

/**
 * GiixModelCode class file.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @link http://rodrigocoelho.com.br/giix/
 * @copyright Copyright &copy; 2010 Rodrigo Coelho
 * @license http://rodrigocoelho.com.br/giix/license/ New BSD License
 */
Yii::import('system.gii.generators.model.ModelCode');
Yii::import('ext.giix-core.helpers.*');

/**
 * GiixModelCode is the model for giix model generator.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @since 1.0
 */
class GiixModelCode extends ModelCode {

	/**
	 * @var string The (base) model base class name.
	 */
	public $baseClass = 'GxActiveRecord';
	/**
	 * @var string The path of the base model.
	 */
	public $baseModelPath;
	/**
	 * @var string The base model class name.
	 */
	public $baseModelClass;

	/**
	 * Prepares the code files to be generated.
	 * Overrides and based on ModelCode::prepare.
	 * Changes: generates the base model,
	 * provides the representing column for the table and
	 * provides the pivot class names for MANY_MANY relations.
	 */
	public function prepare() {
		$this->files = array();

		$templatePath = $this->templatePath;

		if (($pos = strrpos($this->tableName, '.')) !== false) {
			$schema = substr($this->tableName, 0, $pos);
			$tableName = substr($this->tableName, $pos + 1);
		} else {
			$schema = '';
			$tableName = $this->tableName;
		}
		if ($tableName[strlen($tableName) - 1] === '*') {
			$tables = Yii::app()->db->schema->getTables($schema);
			if ($this->tablePrefix != '') {
				foreach ($tables as $i => $table) {
					if (strpos($table->name, $this->tablePrefix) !== 0)
						unset($tables[$i]);
				}
			}
		}
		else
			$tables=array($this->getTableSchema($this->tableName));

		$this->relations = $this->generateRelations();

		foreach ($tables as $table) {
			$tableName = $this->removePrefix($table->name);
			$className = $this->generateClassName($table->name);

			// Generate the pivot model data.
			$pivotModels = array();
			if (isset($this->relations[$className])) {
				foreach ($this->relations[$className] as $relationName => $relationData) {
					if (preg_match('/^array\(self::MANY_MANY,.*?,\s*\'(.+?)\(/', $relationData, $matches)) {
						$pivotTableName = $matches[1];
						$pivotModels[$relationName] = $this->generateClassName($pivotTableName);
					}
				}
			}

			$params = array(
				'tableName' => $schema === '' ? $tableName : $schema . '.' . $tableName,
				'modelClass' => $className,
				'columns' => $table->columns,
				'labels' => $this->generateLabels($table),
				'rules' => $this->generateRules($table),
				'relations' => isset($this->relations[$className]) ? $this->relations[$className] : array(),
				'representingColumn' => $this->getRepresentingColumn($table), // The representing column for the table.
				'pivotModels' => $pivotModels, // The pivot models.
			);
			// Setup base model information.
			$this->baseModelPath = $this->modelPath . '._base';
			$this->baseModelClass = 'Base' . $className;
			// Generate the model.
			$this->files[] = new CCodeFile(
							Yii::getPathOfAlias($this->modelPath . '.' . $className) . '.php',
							$this->render($templatePath . DIRECTORY_SEPARATOR . 'model.php', $params)
			);
			// Generate the base model.
			$this->files[] = new CCodeFile(
							Yii::getPathOfAlias($this->baseModelPath . '.' . $this->baseModelClass) . '.php',
							$this->render($templatePath . DIRECTORY_SEPARATOR . '_base' . DIRECTORY_SEPARATOR . 'basemodel.php', $params)
			);
		}
	}

	/**
	 * Lists the template files.
	 * Overrides and based on ModelCode::requiredTemplates.
	 * Changes: includes the base model.
	 * @return array A list of required template filenames.
	 */
	public function requiredTemplates() {
		return array(
			'model.php',
			'_base' . DIRECTORY_SEPARATOR . 'basemodel.php',
		);
	}

	/**
	 * Generates the rules for table fields.
	 * Overrides ModelCode::generateRules.
	 * @param CDbTableSchema $table The table definition.
	 * @return array The rules for the table.
	 */
	public function generateRules($table) {
		$rules = array();
		$null = array();
		foreach ($table->columns as $column) {
			if ($column->isPrimaryKey && $table->sequenceName !== null)
				continue;
			if (!(!$column->allowNull && $column->defaultValue === null))
				$null[] = $column->name;
		}
		if ($null !== array())
			$rules[] = "array('" . implode(', ', $null) . "', 'default', 'setOnEmpty' => true, 'value' => null)";

		return array_merge(parent::generateRules($table), $rules);
	}

	/**
	 * Selects the representing column of the table.
	 * This field will be the responsible for the string representation of
	 * the model instance.
	 * @param CDbTableSchema $table a table definition.
	 * @return string|array the name of the column as a string or the names of the columns as an array.
	 */
	protected function getRepresentingColumn($table) {
		$columns = $table->columns;
		// If this is not a MANY_MANY pivot table
		if (!$this->isRelationTable($table)) {
			// First we look for a string, not null, not pk, not fk column, not original number on db.
			foreach ($columns as $name => $column) {
				if ($column->type === 'string' && !$column->allowNull && !$column->isPrimaryKey && !$column->isForeignKey && stripos($column->dbType, 'int') === false)
					return $name;
			}
			// Then a string, not null, not fk column, not original number on db.
			foreach ($columns as $name => $column) {
				if ($column->type === 'string' && !$column->allowNull && !$column->isForeignKey && stripos($column->dbType, 'int') === false)
					return $name;
			}
			// Then the first string column, not original number on db.
			foreach ($columns as $name => $column) {
				if ($column->type === 'string' && stripos($column->dbType, 'int') === false)
					return $name;
			}
		} // If the appropriate column was not found or if this is a MANY_MANY pivot table.
		// Then the pk column(s).
		$pk = $table->primaryKey;
		if ($pk !== null) {
			if (is_array($pk))
				return $pk;
			else
				return (string) $pk;
		}
		// Then the first column.
		return $columns[0]->name;
	}

}
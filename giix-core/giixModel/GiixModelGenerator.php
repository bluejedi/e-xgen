<?php

/**
 * GiixModelGenerator class file.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @link http://rodrigocoelho.com.br/giix/
 * @copyright Copyright &copy; 2010 Rodrigo Coelho
 * @license http://rodrigocoelho.com.br/giix/license/ New BSD License
 */

/**
 * GiixModelGenerator is the controller for giix model generator.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @since 1.0
 */
class GiixModelGenerator extends CCodeGenerator {

	public $codeModel = 'ext.giix-core.giixModel.GiixModelCode';

	/**
	 * Returns the table names in an array.
	 * The array is used to build the autocomplete field.
	 * An '*' is appended to the end of the list to allow the generation
	 * of models for all tables.
	 * @return array The names of all tables in the schema, plus an '*'
	 */
	protected function getTables() {
		$tables = Yii::app()->db->schema->tableNames;
		$tables[] = '*';
		return $tables;
	}

}
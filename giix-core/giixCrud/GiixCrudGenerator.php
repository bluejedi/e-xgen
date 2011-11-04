<?php

/**
 * GiixCrudGenerator class file.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @link http://rodrigocoelho.com.br/giix/
 * @copyright Copyright &copy; 2010 Rodrigo Coelho
 * @license http://rodrigocoelho.com.br/giix/license/ New BSD License
 */

/**
 * GiixCrudGenerator is the controller for giix crud generator..
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @since 1.0
 */
class GiixCrudGenerator extends CCodeGenerator {

	public $codeModel = 'ext.giix-core.giixCrud.GiixCrudCode';

	/**
	 * Returns the model names in an array.
	 * Only non abstract and subclasses of GxActiveRecord models are returned.
	 * The array is used to build the autocomplete field.
	 * @return array the names of the models
	 */
	protected function getModels() {
		$models = array();
		$files = scandir(Yii::getPathOfAlias('application.models'));
		foreach ($files as $file) {
			if ($file[0] !== '.' && CFileHelper::getExtension($file) === 'php') {
				$fileClassName = substr($file, 0, strpos($file, '.'));
				if (class_exists($fileClassName) && is_subclass_of($fileClassName, 'GxActiveRecord')) {
					$fileClass = new ReflectionClass($fileClassName);
					if (!$fileClass->isAbstract())
						$models[] = $fileClassName;
				}
			}
		}
		return $models;
	}

}
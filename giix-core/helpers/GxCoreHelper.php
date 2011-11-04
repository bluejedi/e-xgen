<?php

/**
 * GxCoreHelper class file.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @link http://rodrigocoelho.com.br/giix/
 * @copyright Copyright &copy; 2010 Rodrigo Coelho
 * @license http://rodrigocoelho.com.br/giix/license/ New BSD License
 */

/**
 * GxCoreHelper is a static class that provides a collection of helper methods for code generation.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @since 1.0
 */
class GxCoreHelper {

	/**
	 * Transforms an array instance in PHP source code to generate this array.
	 * If any key or value must be valid PHP code instead of a string, use "php:"
	 * on the beggining of the key or value string. Example:
	 * <pre>
	 * $array = array(
	 * 	'class' => 'CMyClass',
	 * 	'title' => 'php:Yii::t(\'app\', \'Any data\')',
	 * )
	 * </pre>
	 * Object serialization is not supported.
	 * @param array $array the array.
	 * @param string $empty the value to be returned if the passed array is empty.
	 * @param integer $indent the base indentation (as number of tabstops) for the generated source in each new line.
	 * Note that the first line will not receive indentation.
	 * @return string the PHP source code representation of the array.
	 */
	public static function ArrayToPhpSource($array, $indent = 1, $empty = 'array()') {
		if (empty($array))
			return $empty;

		// Start of array.
		$result = "array(\n";
		foreach ($array as $key => $value) {
			// Indentation.
			$result .= str_repeat("\t", $indent);

			// The key.
			if (is_int($key))
				$result .= $key;
			else if (is_string($key))
				if (strpos($key, 'php:') === 0)
					$result .= substr($key, 4);
				else
					$result .= "'{$key}'";

			// The assignment.
			$result .= ' => ';

			// The value.
			if (is_null($value))
				$result .= 'null';
			else if (is_array($value))
				$result .= self::ArrayToPhpSource($value, $indent + 1, $empty);
			else if (is_bool($value))
				$result .= $value ? 'true' : 'false';
			else if (is_int($value) || is_float($value))
				$result .= $value;
			else if (is_string($value))
				if (strpos($value, 'php:') === 0)
					$result .= substr($value, 4);
				else
					$result .= "'{$value}'";
			else if (is_object($value))
				throw new InvalidArgumentException(Yii::t('giix', 'Object serialization is not supported (on key "{key}").', array('{key}' => $key)));
			else
				throw new InvalidArgumentException(Yii::t('giix', 'Array element type not supported (on key "{key}").', array('{key}' => $key)));

			// End of line
			$result .= ",\n";
		}
		// Indentation.
		$result .= str_repeat("\t", $indent);
		// End of array.
		$result .= ')';

		return $result;
	}

}

?>

<?php
/**
 * @author     Carlos Ramos <carlos@ktaris.com>
 * @copyright  2015 Carlos Ramos.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    0.1.0
 * @link       https://github.com/ktaris/yii-mexvalidators
 */

/**
 * Valida que la cadena sea un RFC válido según las leyes mexicanas.
 *
 * Validates that the string is a valid RFC according to Mexican law.
 */
class RfcValidator extends CValidator
{
	/**
	 * Determina si se convierte el campo a mayúsculas automáticamente.
	 * @var boolean determines if the field is automatically converted to uppercase.
	 */
	public $toUpper = true;

	/**
     * @inheritdoc
     */
	public function init()
	{
		if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} must be a string.');
        }
	}

	/**
     * @inheritdoc
     */
	protected function validateAttribute($model, $attribute)
	{
		//Uppercase the value (all RFC must be uppercase).
		if ($this->toUpper === true) {
			$model->{$attribute} = strtoupper($model->{$attribute});
		}
		//Proceed with value validation.
		$value = $model->{$attribute};
		$errorMessage = $this->validateValue($value);
		if ($errorMessage){
			$this->addError($model, $attribute, $this->message);
		}
	}

	/**
     * @inheritdoc
     */
	protected function validateValue($value)
	{
		//Check the attribute is a string.
		if (!is_string($value)) {
            return $this->message;
        }
        //Check against the pattern.
        $pattern = '/^[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/';
		$result = preg_match($pattern, $value);
		if ($result !== 1){ //there was no match.
			$this->message = Yii::t('yii', 'The format of {attribute} is invalid.');
			return $this->message;
		}
		//Return false (meaning no error occurred).
		return false;
	}
}
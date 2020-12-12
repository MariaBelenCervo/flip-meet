<?php
namespace Validation;

use Exception;

/**
 * Defino una clase Validator, que ejecutará las validaciones requeridas utilizando los datos recibidos
 * de la interacción con el usuario, las reglas previstas para cada campo de ingreso y
 * un array de errores que permitirá mostrar al usuario los errores lo más personalizados posible
 */
class Validator
{
	/**
	 * @var array $data - Los datos a validar
	 * @var array $rules - Las reglas que los datos deben respetar
	 * @var array $errors - Los mensajes de error en caso de no haber pasado la validación
	 */
	private $data;
	private $rules;
	private $errors = [];

	/**
	 * Constructor
	 * @param array $data - Los datos a validar
	 * @param array $rules - Las reglas que los datos deben respetar
 	 * @throws Exception - Si alguno de los métodos de validación no existe
	 */
	public function __construct(array $data, array $rules)
	{
		$this->data = $data;
		$this->rules = $rules;
		$this->validate();
	}

	/**
	 * Informa si la validación tuvo éxito
	 *
	 * @return bool
	 */
	public function passes() : bool
	{
		return count($this->errors) == 0;
	}

	/**
	 * Verifica si un método existe o no
	 *
	 * @param string $methodName
	 * @param string $ruleName
 	 * @throws Exception - Si alguno de los métodos de validación no existe
	 */
	private function isMethod(string $methodName, string $ruleName)
	{
		if (!method_exists($this, $methodName)) {
			throw new Exception("La regla de validación $ruleName no existe");
		}
	}

	/**
	 * Obtiene el valor del campo pasado como parámetro
	 * 
	 * @param string $fieldName
	 * @return string El valor del campo sin caracteres en blanco a los costados 
	 */
	private function getFieldvalue(string $fieldName) : string
	{
		return trim($this->data[$fieldName]);
	}

	////////////////////////////// MÉTODOS DE EJECUCIÓN //////////////////////////////
	/**
	 * Ejecuta las validaciones requeridas
	 * (la validación correspondiente de acuerdo al campo y la regla requerida)
	 *
	 * @return void
	 * @throws Exception - Si alguno de los métodos de validación no existe
	 */
	public function validate()
	{
		foreach ($this->rules as $fieldName => $fieldRules) {
			foreach ($fieldRules as $ruleName) {
				$this->executeRule($fieldName, $ruleName);
			}
		}
	}

	/**
	 * Método que ejecuta (llama) una determinada regla de validación sobre un cierto campo
	 *
	 * @param string $fieldName - El nombre del campo a validar
	 * @param string $ruleName - La regla de validación
	 * @return void
	 * @throws Exception - Si alguno de los métodos de validación no existe
	 */
	public function executeRule(string $fieldName, string $ruleName)
	{
		// Caso para las reglas de máximo y mínimo de caracteres
		if (strpos($ruleName, ':') !== false) {
			$ruleData = explode(':', $ruleName);
			$methodName = $ruleData[0];

			// Segundo parámetro: cantidad de caracteres previstos
			$param = $ruleData[1];

		// Caso que sea una expresión regular
		} else if(substr($ruleName, 0) == '/' && substr($ruleName, -1) == '/') {
			// El método es el de regexp
			$methodName = 'regexp';
			
			// Segundo parámetro: la expresión regular
			$param = $ruleName;
		} else {
			//Para las reglas que se llamen igual que los métodos...
			$methodName = $ruleName;
			$param = null;
		}

		// Verifico que el método en efecto exista
		$this->isMethod($methodName, $ruleName);

		// Ejecuto el método, pasándole el nombre del campo y un segundo parámetro según corresponda
		$this->{$methodName}($fieldName, $param);
	}

	////////////////////////////// MÉTODOS DE VALIDACIÓN //////////////////////////////
	/**
	 * Verifica que el campo tenga una cantidad mínima de caracteres
	 *
	 * @param string $fieldName
	 * @param int $minLength
	 */
	public function min(string $fieldName, int $minLength)
	{
		if (strlen($this->getFieldvalue($fieldName)) < $minLength) {
			$this->addError($fieldName, "El campo $fieldName debe tener al menos $minLength caracteres.");
		}
	}

	/**
	 * Verifica que el campo tenga una cantidad máxima de caracteres
	 *
	 * @param string $fieldName
	 * @param int $maxLength
	 */
	public function max(string $fieldName, int $maxLength)
	{
		if (strlen($this->getFieldvalue($fieldName)) > $maxLength) {
			$this->addError($fieldName, "El campo $fieldName debe tener un máximo de $maxLength caracteres.");
		}
	}

	/**
	 * Verifica que el campo cumpla con una dada expresión regular
	 *
	 * @param string $fieldName
	 * @param string $regexp
	 */
	public function regexp(string $fieldName, string $regexp)
	{
		if (!preg_match($regexp, $this->getFieldvalue($fieldName))) {
			$this->addError($fieldName, "El campo $fieldName es inválido.");
		}
	}

	/**
	 * Verifica que el campo no esté vacío
	 * 
	 * @param string $fieldName
	 */
	public function required(string $fieldName)
	{
		if (empty($this->getFieldvalue($fieldName))) {
			$this->addError($fieldName, "El campo $fieldName es obligatorio.");
		}
	}

	/**
	 * Verifica que el campo sea numérico
	 * @param string $fieldName
	 */
	public function numeric(string $fieldName)
	{
		if (!is_numeric($this->getFieldvalue($fieldName))) {
			$this->addError($fieldName, "El campo $fieldName sólo acepta números.");
		}
	}

	/**
	 * Valida un campo email (su validación es muy particular)
	 * 
	 * @param string $fieldName
	 */
	public function email(string $fieldName)
	{
		/* Validación de email: la validación de la dirección de email se divide en dos grupos principales:
		la parte local, y la parte del dominio.
		
		1) PARTE LOCAL:
		-permite caracteres alfanuméricos y los caracteres !#$%&'*+-/=?^_`{}|~
		-el punto no debe ser ni el primer ni el último caracter, ni aparecer consecutivamente
		
		2) PARTE DEL DOMINIO:
		-tiene que haber al menos un dns, de al menos 1 caracter alfanumérico
		-si hay más de un dns, tiene que estar separado por punto
		-además de caracteres alfanuméricos se puede usar guión medio (-), pero no puede ser ni el primero
		ni el último caracter del dns (por lo que no puede quedar junto a un punto)

		Para representar los caracteres alfanuméricos uso [a-z0-9] en vez de \w ya que el \w incluye
		el guión bajo (_) */
		$email_re = "/^([\w!#$%&'*+\-\/=?\^`{}|~]+(?:\.[\w!#$%&'*+\-\/=?\^`{}|~]+)*)@([a-z0-9](?:[a-z0-9]|\-(?=[a-z0-9])|\-(?=\-))*(?:\.[a-z0-9](?:[a-z0-9]|\-(?=[a-z0-9])|\-(?=\-))*)*)$/";

		if (preg_match($email_re, $this->getFieldvalue($fieldName), $matches)) {
			// La parte local puede tener hasta 64 caracteres en total
			if (strlen($matches[1]) > 64) {
				$this->addError($fieldName, "La primera parte de la dirección de email debe tener menos de 64 caracteres.");
			}

			// La parte del dominio puede tener hasta 190 caracteres en total
			if (strlen($matches[2]) > 190) {
				$this->addError($fieldName, "La segunda parte de la dirección de email debe tener menos de 190 caracteres.");
			} else {
				//Cada dns puede tener hasta 63 caracteres
				$domain = explode('.', $matches[2]);
				foreach ($domain as $dns) {
					if (strlen($dns) > 63) {
						$this->addError($fieldName, "Los dns deben tener menos de 63 caracteres.");
					}
				}
			}
		} else {
			$this->addError($fieldName, "Formato de email inválido.");
		}
	}

	////////////////////////////// MÉTODOS DE MANEJO DE ERRORES //////////////////////////////
	/**
	 * Devuelve los errores acumulados en $errors
	 * 
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Agrega un mensaje de error de validación al array $errors
	 * 
	 * @param string $fieldName
	 * @param string $msg
	 */
	public function addError(string $fieldName, string $msg)
	{
		// Verifico que ya no haya habido errores para ese campo
		if (!isset($this->errors[$fieldName])) {
			$this->errors[$fieldName] = [];
		}

		$this->errors[$fieldName][] = $msg;
	}

}
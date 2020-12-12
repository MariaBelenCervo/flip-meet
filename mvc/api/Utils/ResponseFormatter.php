<?php
namespace Utils;

use JsonSerializable;
use Exception;

/**
 * Clase que arma el mensaje de respuesta para enviar al client
 * Provee la funcionalidad necesaria para armar la estructura de datos de la respuesta y formatearla para su envío por ajax.
 * Estructuta: [status (int), message (string), validationErrors (array), oldInputs (array), data (object)]
 */
class ResponseFormatter implements JsonSerializable
{
    // Constantes pertenecientes a la propiedad $status
	/** 
    * @var int ERROR - Indica que la respuesta contiene errores 
    * @var int OK - La respuesta no tiene errores y contiene, posiblemente, los datos esperados 
    * @var int REDIRECT - La respuesta contiene una directiva de que hay que redirigir 
    */
	const ERROR = 0;
    const OK = 1;
    const REDIRECT = 2;

    /**
     * @var int - Propiedad que contendrá el tipo de mensaje (OK o ERROR)
     */
    private $status;

    /**
     * @var string - Propiedad que contendrá el mensaje de la respuesta (Puede ser de error o OK)
     */
    private $message;

    /**
     * @var array - Array asociativo que contendrá todos los errores de validación
     * Ejemplo: validationErrors['nombre_del_campo'] = 'Mensaje de error correspondiente';
     */
    private $validationErrors = [];

    /**
     * @var array - Array asociativo que contendrá los valores de los inputs que quiero
     * reenviar junto con la respuesta, para que el cliente los recupere
     */
    private $oldInputs = [];

    /**
     * @var mixed - Variable genérica donde se almacenan los datos a enviar
     */
    private $data;

    /**
     * @var string - URL donde debe redirigirse el cliente en caso de un redireccionamiento
     */
    private $url;


    /** 
     * Constructor
     * @param int $status - OK por default 
     * @param mixed $data
     */    
    public function __construct($status = self::OK, $data = null) 
    {
        $this->setStatus($status);
        $this->setData($data);
    }

    /**
     * Agrega uno o más errores de validación al array actual
     * @param array $validationErrors
     * @return void
     * @throws Exception - Si $validationErrors no es array
     */
    public function addValidationErrors(array $validationErrors)
    {
        $this->validationErrors = array_merge($this->validationErrors, $validationErrors);
    }
   
    /**
     * Agrega uno o más valores de campos al array actual
     * @param array $oldInputs
     * @return void
     */
    public function addOldInputs(array $oldInputs)
    {
        $this->oldInputs = array_merge($this->oldInputs, $oldInputs);
    }

    /**
     * Elimina el error de validación de un campo en específico
     * @param string $field
     * @return void
     */
    public function removeValidationError(string $field)
    {
        if (isset($this->validationErrors[$field])) {
            unset($this->validationErrors[$field]);
        }
    }

    /**
     * Elimina el valor de un campo viejo en específico
     * @param string $field
     * @return void
     */
    public function removeOldInput(string $field)
    {
        if (isset($this->oldInputs[$field])) {
            unset($this->oldInputs[$field]);
        }
    }

    //////////////////////////////GETTERS//////////////////////////////

    /**
     * Devuelve el status del mensaje
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Devuelve el mensaje de error principal
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * Devuelve el array que contiene los errores de validación
     * @return array
     */
    public function getValidationErrors() : array
    {
        return $this->validationErrors;
    }

    /**
     * Devuelve el array que contiene los old inputs
     * @return array
     */
    public function getOldInputs() : array
    {
        return $this->oldInputs;
    }

    /**
     * Devuelve los datos que contiene el mensaje 
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Devuelve la url donde se debe redirigir el cliente
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }


    //////////////////////////////SETTERS//////////////////////////////

    /**
     * Setea el status del mensaje
     * @param int $status
     * @return void
     * @throws Exception - Si se quiere setear un estado desconocido
     */
    public function setStatus(int $status)
    {
        if ($status >= self::ERROR || $status <= self::REDIRECT ) {
            $this->status = $status;
        } else {
            throw new Exception("Error al setear valor $status al estado: estado desconocido en " . __CLASS__);
        }
    }

    /**
     * Setea el mensaje de error principal
     * @param string $message
     * @return void
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * Setea el array que contiene los errores de validación
     * @param array $validationErrors
     * @return void
     */
    public function setValidationErrors(array $validationErrors)
    {
        $this->validationErrors = $validationErrors;
    }

    /**
     * Setea el array que contiene los old inputs
     * @param array $oldInputs
     * @return void
     */
    public function setOldInputs(array $oldInputs)
    {
        $this->oldInputs = $oldInputs;
    }

    /**
     * Setea los datos que contiene el mensaje 
     * @param mixed $data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Setea la url a donde debe redirigise el cliente
     * @param string $url
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }


    /////////////////////////FUNCIONES MÁGICAS/////////////////////////

    /* Utilizo el método __get y __set para llamar a los getters/setters de esta instancia desde
    afuera como si se estuviera accediendo directamente (sin encapsulamiento) a dicha propiedad */ 
    /**
     * __get()
     * @param string $name - Nombre de la propiedad
     * @return mixed - Valor de la propiedad
     * @throws Exception - Si no existe el método
     */
    public function __get(string $name) 
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        } else {
            throw new Exception("Propiedad $name inexistente en " . __CLASS__);
        }
    } 

    /**
     * __set()
     * @param string $name - Nombre de la propiedad
     * @param mixed $value - Valor de la propiedad
     * @return void
     * @throws Exception - Si no existe el método
     */
    public function __set(string $name, $value) 
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
        } else {
            throw new Exception("Propiedad $name inexistente en " . __CLASS__);
        }
    } 

    ///////////////////SERIALIZACIÓN A FORMATO JSON////////////////////
    /**
     * Método de serialización a JSON.
     * @return array
     */
    public function JsonSerialize()
    {
        $json['status'] = $this->status;

        if (isset($this->message)) {
            $json['message'] = $this->message;
        }

        if (count($this->validationErrors) > 0) {
            $json['validationErrors'] = $this->validationErrors;
        }

        if (count($this->oldInputs) > 0) {
            $json['oldInputs'] = $this->oldInputs;
        }

        if (isset($this->data)) {
            $json['data'] = $this->data;
        }

        if (isset($this->url)) {
            $json['url'] = $this->url;
        }        

        return $json;
    }
}
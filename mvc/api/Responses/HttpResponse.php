<?php
namespace Responses;

use JsonSerializable;
use Exception;

/**
 * Clase que arma el mensaje de respuesta para enviar al client
 * Provee la funcionalidad necesaria para armar la estructura de datos de la respuesta y formatearla para su envío por ajax.
 * Estructuta: [statusCode (int), statusText (string), title (string), message (string), validationErrors (array), data (object)]
 */
class HttpResponse implements JsonSerializable
{
    /**
     * @var int - Propiedad que contendrá status code (200, 404, etc)
     */
    protected $statusCode;

    /**
     * @var string - Propiedad que contendrá el status text (OK, Not Found, etc)
     */
    protected $statusText;

    /**
     * @var string - Propiedad que contendrá título descriptivo del mensaje
     */
    protected $title;

    /**
     * @var string - Propiedad que contendrá el mensaje de la respuesta (puede ser de error o OK)
     */
    protected $message;

    /**
     * @var array - Array asociativo que contendrá todos los errores de validación
     * Ejemplo: validationErrors['nombre_del_campo'] = 'Mensaje de error correspondiente';
     */
    protected $validationErrors = [];

    /**
     * @var object - Variable genérica donde se almacenan los datos a enviar en el caso de un OK 200
     */
    protected $data;

    /** 
     * Constructor
     * @param int $statusCode  
     * @param string $statusText  
     * @param string|null $title
     * @param string|null $message
     * @param object|null $data
     */    
    public function __construct(int $statusCode, string $statusText, string $message = null, string $title = null, object $data = null) 
    {
        $this->setStatusCode($statusCode);
        $this->setStatusText($statusText);
        $this->setTitle($title);
        $this->setMessage($message);
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


    //////////////////////////////GETTERS//////////////////////////////

    /**
     * Devuelve el status code del mensaje
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * Devuelve el status text del mensaje
     * @return string
     */
    public function getStatusText() : string
    {
        return $this->statusText;
    }

    /**
     * Devuelve el título del mensaje
     * @return string|null
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * Devuelve el mensaje
     * @return string|null
     */
    public function getMessage() : ?string
    {
        return $this->message;
    }

    /**
     * Devuelve el array que contiene los errores de validación
     * @return array|null
     */
    public function getValidationErrors() : ?array
    {
        return $this->validationErrors;
    }

    /**
     * Devuelve los datos que contiene el mensaje 
     * @return object|null
     */
    public function getData() : ?object
    {
        return $this->data;
    }

    //////////////////////////////SETTERS//////////////////////////////

    /**
     * Setea el status code del mensaje
     * @param int $statusCode
     * @return void
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Setea el status text del mensaje
     * @param string $statusText
     * @return void
     */
    public function setStatusText(string $statusText)
    {
        $this->statusText = $statusText;
    }

    /**
     * Setea el titulo del mensaje
     * @param string $title
     * @return void
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    /**
     * Setea el mensaje
     * @param string $message
     * @return void
     */
    public function setMessage(?string $message)
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
     * Setea los datos que contiene el mensaje 
     * @param object $data
     * @return void
     */
    public function setData(?object $data)
    {
        $this->data = $data;
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
        $json['statusCode'] = $this->statusCode;
        $json['statusText'] = $this->statusText;

        if (isset($this->title)) {
            $json['title'] = $this->title;
        }

        if (isset($this->message)) {
            $json['message'] = $this->message;
        }

        if (count($this->validationErrors) > 0) {
            $json['validationErrors'] = $this->validationErrors;
        }

        if (isset($this->data)) {
            $json['data'] = $this->data;
        }

        return $json;
    }

    ////////////////// ENVIO DE RESPUESTA ////////////////////
    /**
     * Envío la respuesta seteando header en json y el status code correspondiente.
     */
    public function send()
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this);
    }

}
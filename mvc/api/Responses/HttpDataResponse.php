<?php
namespace Responses;

use Exceptions\HttpException;

/**
 * Clase que arma el mensaje de respuesta de 200 OK con el campo data en el body. Extiende funcionalidad de la clase base HttpResponse.
 * Estructuta: [statusCode (int), statusText (string), data (object)]
 */
class HttpDataResponse extends HttpResponse
{
    /** 
     * Constructor que armar la respuesta de OK a partir de $data
     * @param object|null $data
     */
    public function __construct(?object $data) 
    {
        parent::__construct(200, "OK", null, null, $data);
    }
}
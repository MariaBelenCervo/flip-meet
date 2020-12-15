<?php
namespace Responses;

use Exceptions\HttpException;

/**
 * Clase que arma el mensaje de respuesta de error para enviar al cliente. Extiende funcionalidad de la clase base HttpResponse.
 * Provee la funcionalidad necesaria para armar la estructura de datos de la respuesta y formatearla para su envío por ajax.
 * Estructuta: [statusCode (int), statusText (string), title (string), message (string)]
 */
class HttpErrorResponse extends HttpResponse
{
    /** 
     * Constructor que armar la respuesta de error http a partir de una HttpException
     * @param HttpException $e
     */    
    public function __construct(HttpException $e) 
    {
        parent::__construct($e->getCode(), $e->getStatusPhrase(), $e->getMessage(), $e->getTitle());
    }
}
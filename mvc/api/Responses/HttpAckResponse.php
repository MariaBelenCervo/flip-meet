<?php
namespace Responses;

use Exceptions\HttpException;

/**
 * Clase que arma el mensaje de respuesta de 200 OK para ackownledge (solo titulo y message, sin data en el body). 
 * Extiende funcionalidad de la clase base HttpResponse.
 * Estructuta: [statusCode (int), statusText (string), title (string), message (string)]
 */
class HttpAckResponse extends HttpResponse
{
    /** 
     * Constructor que armar la respuesta de OK a partir de $data
     * @param string|null $message
     * @param string|null $title
     */    
    public function __construct(?string $message = null, ?string $title = null) 
    {
        parent::__construct(200, "OK", $message, $title);
    }
}
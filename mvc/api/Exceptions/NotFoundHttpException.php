<?php
namespace Exceptions;

class NotFoundHttpException extends HttpException 
{
    /**
     * @param string $message     Descripción del error (opcional)
     * @param string $title       Título de la descripción (opcional)
     */
    public function __construct(string $message = null, string $title = null)
    {
        parent::__construct(404, $message, $title);
    }
}
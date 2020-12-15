<?php
namespace Exceptions;

class UnprocessableEntityHttpException extends HttpException 
{
    /**
     * @var array - Array asociativo que contendrá todos los errores de validación
     */
    protected $validationErrors = [];

    /**
     * @param array $validationErrors
     * @param string|null $message     Descripción del error (opcional)
     * @param string|null $title       Título de la descripción (opcional)
     */
    public function __construct(array $validationErrors, ?string $message = null, ?string $title = null)
    {
        $this->setValidationErrors($validationErrors);
        parent::__construct(422, $message, $title);
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
     * Setea el array que contiene los errores de validación
     * @param array $validationErrors
     * @return void
     */
    public function setValidationErrors(array $validationErrors)
    {
        $this->validationErrors = $validationErrors;
    }

}
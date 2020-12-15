<?php
namespace Exceptions;

use Exception;

class HttpException extends Exception
{
    /**
     * Lista de HTTP status codes
     *
     * http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     *
     * @var array
     */
    private $status = array(
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        419 => 'Authentication Timeout',
        420 => 'Enhance Your Calm',
        422 => 'Unprocessable Entity',
        423 => 'Locked', // WebDAV; RFC 4918
        424 => 'Failed Dependency', // WebDAV; RFC 4918
        425 => 'Unordered Collection', // Internet draft
        426 => 'Upgrade Required', // RFC 2817
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
        444 => 'No Response', // Nginx
        449 => 'Retry With', // Microsoft
        450 => 'Blocked by Windows Parental Controls', // Microsoft
        494 => 'Request Header Too Large',
        495 => 'Cert Error', 
        496 => 'No Cert', 
        497 => 'HTTP to HTTPS', 
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        598 => 'Network read timeout error',
        599 => 'Network connect timeout error',
    );

    /**
     * Nombre del código de error (ej: "Not Found" para statusCode 404)
     *
     * @var string
     */
    protected $statusPhrase;

    /**
     * Título descriptivo del error
     *
     * @var string
     */
    protected $title;

    /**
     * @param int    $statusCode   500 por default
     * @param string $message      Descripción del error (opcional)
     * @param string $title        Título del error (opcional)
     * @param string $statusPhrase Internal Server Error por default
     */
    public function __construct(int $statusCode = 500, string $message = null, string $title = null, string $statusPhrase = null)
    {
        if ($statusPhrase === null && isset($this->status[$statusCode])) {
            $statusPhrase = $this->status[$statusCode];
        }

        parent::__construct($message, $statusCode);
        $this->statusPhrase = $statusPhrase;
        $this->title = $title;
    }

    /**
     * Devuelve el nombre del código de error 
     * 
     * @return string
     */
    public function getStatusPhrase() : string
    {
        return $this->statusPhrase;
    }

    /**
     * Setea el nombre del código de error 
     * 
     * @param string $statusPhrase
     */
    public function setStatusPhrase(string $statusPhrase)
    {
        $this->statusPhrase = $statusPhrase;
    }

    /**
     * Devuelve el título del error 
     * 
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * Setea el título del error 
     * 
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

}
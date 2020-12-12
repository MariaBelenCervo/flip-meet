<?php
namespace Core;

/**
 * Clase que se encargará de renderizar los datos obtenidos de los controladores.
 */
class View
{
    /**
     * Retorna los datos recibidos en formato JSON
     * @param mixed $data
     */
    public static function renderJson($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    
}
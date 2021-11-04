<?php

namespace Src\listener;

/**
 * Clase que scucha todas las peticiones.
 */
class listener {

    public function __construct() {
       define('ERROR', 10);
       define('DONE', 11);
    }

    public function actionListener(array $data) : string {
        $request = [];

        if (isset($data)) {
            switch ($data['case']) {
                case 'value':
                    // code...
                    break;
                
                default:
                    // code...
                    break;
            }
        }else {
            $request = ['status' => ERROR, 'request' => 'Petición no procesada.'];
        }

        return json_encode($request, JSON_UNESCAPED_UNICODE);
    }

}
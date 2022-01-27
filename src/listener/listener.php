<?php

namespace Src\Listener;

// Se usan los traits.
use Src\controllers\traits\Responses; // Trait para gestionar las respuestas.

// Se usan los controladores.
use Src\controllers\UsersController;

/**
 * Clase que escucha todas las peticiones.
 */
class Listener {

    use Responses;

    private UsersController $users;

    public function __construct() {
        $this->users = new UsersController();
    }

    public function actionListener(string $type, array $data) : string {
        $request = [];

        if (isset($type) && $type !== 'GET') {
            switch ($type) {
                case 'PUT':
                    $request = $this->create($data);
                    break;

                case 'POST':
                    $request = $this->error400();
                    //$request = $this->update($data)[0] === 'read' ? $this->read($data) : $this->error200('La acción se ha realizado con éxito.', 'done');
                    break;

                case 'DELETE':
                    $request = $this->delete($data);
                    break;
                
                default:
                    $request = $this->error405();
                    break;
            }
        }else {
            $request = $this->error405();
        }

        return json_encode($request, JSON_UNESCAPED_UNICODE);
    }

    private function create(array $data) : array {
        switch ($data['type']) {
            case 'createUser':
                return $this->users->createUser((int) $data["document_user"], (string) $data["name_user"], (string) $data["lastName_user"], (string) $data['email_user']);
                break;
            
            default:
                return ['read'];
                break;
        }
    }

    private function read(?array $data = NULL) : array {
        // code...
    }

    private function update(array $data) : array {
        // code...
    }

    private function delete(array $data) : array {
        // code...
    }

}
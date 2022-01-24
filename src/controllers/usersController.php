<?php

namespace Src\Controllers;

// Se usan otros controladores.
use Src\Controllers\Controller;
use Src\Controllers\Functions\EmailController;

// Se usan las cápsulas.
use Src\Models\Capsules\UsersEntity;
use Src\Models\Capsules\EmailEntity;

// Se usan los traits.
use Src\Controllers\Traits\Security;
use Src\Controllers\Traits\Validation;
use Src\Controllers\Traits\Responses;

// Se usa el modelo.
use Src\Models\UsersModel;

// Se usan las librerías.
use Respect\Validation\Validator as v;
use Egulias\EmailValidator\EmailValidator;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Clase que cotrola y gestiona los datos de los usuarios.
 */
class UsersController extends Controller {

	use Security;
	use Validation;
	use Responses;
	
	private $model;
	private $logger;

	public function __construct(?string $rol = NULL) {
		$this->model = new UsersModel($rol);
		$this->logger = new Logger('logs_user');
		$this->logger->pushHandler(new StreamHandler('Src/Controllers/Logs/' . date('d-m-Y') . '_users_error.log', Logger::INFO));
		$this->logger->pushHandler(new FirePHPHandler());
	}

	// Se registra el usuario y si devuelve true, envía el correo electrónico; de lo contrario, devuelve false.
	public function createUser(int $document, string $name, string $lastName, string $email, ?int $phone = NULL, string $password) : array {
		// Se valida que el número de documento sea un número entero y que tenga entre 7 a 10 dígitos.
		if (v::intType()->length(7, 10)->validate($document)) {
			// Se valida si el usuario se encuentra registrado en la tabla users.
			if ((int) $this->model->readUserExistDB(new UsersEntity(NULL, $document))['exist'] === 0) {
				// Se valida que el nombre sea sólo una cadena de texto.
				if ($this->validateJustString($name)) {
					// Se valida que el apellido sea sólo una cadena de texto.
					if ($this->validateJustString($lastName)) {
						// Se valida que el email ingresado, sea válido también con @misena y @sena.
						if ($this->validateEmail($email)) {
							// Se valida que de existir el número de teléfono, sea un número entero y que esté entre 8 a 10 dígitos.
							if (v::intType()->length(8, 10)->validate($phone) || $phone === NULL) {
								// Se valida que la contraseña sea mínimo de 8 caracteres.
								if (v::stringType()->length(8, NULL)->validate($password)) {
									// Se genera el token, basándose en el número de documento.
									$token = $this->setMayus($this->encryptToken($this->encryptPassword($document)));
									if ($this->model->createUserDB(new UsersEntity(NULL, $this->resetSpecialStrings($document), $this->resetSpecialStrings($name), $this->resetSpecialStrings($lastName), $this->resetSpecialStrings($email), $this->resetSpecialStrings($phone), $this->encryptRSA($this->encryptHash($this->resetSpecialStrings($password))), $token))) {
										if ($this->sendEmailConfirm($name, $lastName, $email, $token)) {
											$this->logger->info('Se registró el usuario ' . $document . ' - ' . $name . ' ' . $lastName . '.', ['UsersController->createUser']);
											return $this->doneMessage('Se ha registrado tu cuenta. Por favor, revisa la bandeja de entrada del correo electrónico que registraste, o la bandeja de SPAM.');
										}else {
											$this->logger->warning('Se registró el usuario ' . $document . ' - ' . $name . ' ' . $lastName . ', pero no se envió el correo (' . $email . ') de confirmación.', ['UsersController->createUser']);
											return $this->doneMessage('Se ha registrado tu cuenta, pero ha ocurrido un error al intentar enviar el correo de confirmación. Por favor, contacta con soporte.');
										}
									}else {
										$this->logger->warning('No se registró el usuario ' . $document . ' - ' . $name . ' ' . $lastName . '.', ['UsersController->createUser']);
										return $this->genericError('Error al registrar esta cuenta.');
									}
								}else {
									$this->logger->info('Contraseña muy insegura.', ['UsersController->createUser']);
									return $this->genericError('Contraseña muy corta.');
								}
							}else {
								$this->logger->info('Número de contacto inválido: ' . $phone . '.', ['UsersController->createUser']);
								return $this->error406();
							}
						} else {
							$this->logger->info('Correo electrónico inválido: ' . $email . '.', ['UsersController->createUser']);
							return $this->error406();
						}
					}else {
						$this->logger->info('Apellido(s) inválido(s): ' . $lastName . '.', ['UsersController->createUser']);
						return $this->error406();
					}
				}else {
					$this->logger->info('Nombre(s) inválido(s): ' . $name . '.', ['UsersController->createUser']);
					return $this->error406();
				}
			}else {
				$this->logger->info('El usuario ya existe: ' . $document . '.', ['UsersController->createUser']);
				return $this->genericError('Este usuario ya se encuentra registrado.');
			}
		}else {
			$this->logger->info('Número de documento inválido: ' . $document . '.', ['UsersController->createUser']);
			return $this->error406();
		}
	}

	// Se confirma el correo electrónico del usuario.
	public function confirmUser(string $token) : bool {
		return $this->model->confirmUserDB(new UsersEntity(NULL, NULL, NULL, NULL, NULL, NULL, NULL, $token));
	}

	// Se envía el correo de confirmación.
	private function sendEmailConfirm(string $name, string $lastName, string $email, string $token) : bool {
		$send = new EmailController();
		return $send->send(new EmailEntity($email, 'scorrea@sena.edu.co', NULL, NULL, NULL, 'CORREO DE CONFIRMACIÓN PARA ' . $this->setMayus($name) . ' ' . $this->setMayus($lastName), '<a href="http://localhost:8080/idi/prueba/' . $token . '">Click aquí (' . $token . ')</a>'));
	}

	// Se envía correo para recuperar la contraseña.
	public function sendEmailPassword(int $document, string $name, string $lastName, string $email, string $token) : bool {
		if ($this->model->setTokenDB(new UsersEntity(NULL, $document, NULL, NULL, NULL, NULL, NULL, $token))) {
			$email = new emailController($name . ' ' . $lastName, $email, NULL, 'CORREO PARA RECUPERACIÓN DE CONTRASEÑA', NULL, $token);
			return $email->sendRecoverPasswordEmail();
		}else {
			return false;
		}
	}

	// Se verifican y se devuelven los datos del usuario.
	public function loginUser() {
		$_POST = $this->fileGetContents();
		if ($this->validHash(getallheaders()['Origin'] . $_ENV['TOKEN_API'], getallheaders()['Authorization'])) {
			extract($_POST);
			print_r(json_encode($this->validHash($this->encryptPassword($password), $this->decryptRSA($this->model->readUsersPasswordDB(new UsersEntity(NULL, $document))['password'])) ? $this->model->readUserDataDB(new UsersEntity(NULL, $document)) : [false], JSON_UNESCAPED_UNICODE));
		}
	}

	// Leer todos los usuarios registrados.
	public function readUsers() : array {
		return $this->model->readUsersDB();
	}

	// Se actualiza la contraseña. Ya sea como recuperación, o desde dentro de la plataforma.
	public function updatePasswordUser(int $document, string $password) {
		$user = new UsersEntity(NULL, $document, NULL, NULL, NULL, NULL, $password);
		return $this->model->updatePasswordUserDB($user);
	}

	// Se actualizan los datos del usuario.
	public function updateUser(string $documentType, int $document, string $name, string $lastName, ?int $phone = NULL, string $email) : bool {
		$user = new UsersEntity($documentType, $document, $name, $lastName, $phone, $email);
		return $this->model->updateUserDB($user);
	}

	// Se elimina el usuario.
	public function deleteUser(int $document) : bool {
		return $this->model->deleteUserDB(new UsersEntity(NULL, $document));
	}

}
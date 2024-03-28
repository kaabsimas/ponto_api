<?php

namespace Ponto;

use Ponto\Routing\Router;
use Ponto\Database\Connection;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Application
{
    const PASSWORD = 'secret66046a5832e3b';

    protected Router $router;

    public function __construct()
    {
        $this->router = new Router;

        $this->router->addRoute('GET', '/', function(){
            http_response_code(418);
        });

        $this->router->addRoute('POST', '/login', $this->login(...));
    }

    protected function login()
    {
        if(! isset($_POST['email']) || !isset($_POST['password'])) {
            $entityBody = file_get_contents('php://input');
            $body = json_decode($entityBody, true);
            if(\json_last_error() !== JSON_ERROR_NONE) {
                $body = [];
            }
        } else {
            $body = $_POST;
        }

        $pdo = (new Connection)->connect();

        $prepare = $pdo->prepare("select * from user where email = :email");
        $prepare->execute(['email' => $body['email']]);
        $userFound = $prepare->fetch();

        if(isset($userFound)) {
            if(! password_verify($body['password'], $userFound->password)) {
                return "Invalid password";
            }
            
            $payload = [
                'iat' => time(),
                'exp' => time() + 7200,
                'email' => $userFound->email
            ];
    
            $encode = JWT::encode($payload, self::PASSWORD, 'HS256');

            return json_encode($encode);
        }
        return "User not found";
    }

    public function run()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Content-Type: application/json');
        echo $this->router->matchRoute();
    }
}
<?php

namespace Ponto;

use Ponto\Routing\Router;

class Application
{
    const USERNAME = 'admin';
    const PASSWORD = 'secret';

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
        } else {
            $body = $_POST;
        }

        if(isset($body['email']) && $body['email'] === self::USERNAME && isset($body['password'])) {
            if($body['password'] !== self::PASSWORD) {
                return "Invalid password";
            }
            
            return md5(self::PASSWORD);
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
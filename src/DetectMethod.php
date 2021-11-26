<?php
declare(strict_types=1);

namespace Devcompru\Users;

class DetectMethod
{
    const AUTH_TOKEN_NAME = 'authorization',
          ACCESS_TOKEN_NAME = 'access_token';
    const AUTH_PAGE_URL = '/v1/user/auth';

    public string $username_email_type = 'username';
    private string $current_url = '';
    public string $method;
    public string|bool $authorization = false;
    public string|bool $access_token = false;
    public string|bool $username = false;
    private string|bool $password = false;


    public function __construct()
    {
        $this->method();
        $this->getParams();
        $this->current_url = $_SERVER['REQUEST_URI'];
    }

    private function method()
    {
        if($_SERVER['REQUEST_METHOD'])
        {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }

    }

    private function getParams()
    {
        $this->isHeader();
        $this->isGet();
        $this->isPost();
        $this->isPut();



    }

    private function isHeader(): void
    {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);

        if(isset($headers[self::AUTH_TOKEN_NAME]))
            $this->authorization = trim(str_replace('Bearer', '',$headers[self::AUTH_TOKEN_NAME]));

    }

    private function isGet(): void
    {
        if($this->method === 'GET' && isset($_GET[self::ACCESS_TOKEN_NAME]))
            $this->access_token = filter_input(INPUT_GET, self::ACCESS_TOKEN_NAME, FILTER_SANITIZE_STRING);
    }
    private function isPut(): void
    {
        if($this->method == 'PUT')
            parse_str(file_get_contents('php://input'), $_PUT);
        if($this->method == 'PUT' && isset($_PUT['username']) && isset($_PUT['password']))
        {
            $this->username = filter_var($_PUT['username'], FILTER_SANITIZE_EMAIL);
            $this->password = filter_var($_PUT['password'], FILTER_SANITIZE_EMAIL);
        }
    }
    private function isPost(): void
    {
        if($this->method == 'POST' && isset($_POST['username']) && isset($_POST['password']))
        {
            $this->username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
            $this->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_EMAIL);
        }
    }


    private function priority()
    {


    }

    public function validatePassword($hash)
    {

        return password_verify($this->password, $hash);
    }


}
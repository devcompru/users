<?php
declare(strict_types=1);


namespace Devcompru\Users;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class UserModel
{
    public int $id=0;
    public string $GUID;
    public string $access_key;
    public string $username='';
    public string $pass_hash='';


    public string $reg_user_ip='';
    public string $oauth_token='';
    public string $oauth_id='';
    public string $oauth_type='';
    public int    $ban=0;

    public string $password_reset='';
    public int    $created_at;
    public int    $updated_at;


    public function createUser(array $user_fields)
    {

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        
        echo "<pre>";
        print_r($this);
    }


    public function validate()
    {

    }

}
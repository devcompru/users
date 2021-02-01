<?php
declare(strict_types=1);


namespace Devcompru\Users;


class ErrorHandler
{

    public function __construct()
    {
        $this->registration();
    }

    public function registration()
    {

        set_exception_handler([$this, 'exception']);
    }

    public function exception(\Exception $e)
    {
    
        echo "Server error ". $e->getCode();

        return true;
    }


}
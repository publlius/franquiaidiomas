<?php

class Usuario extends TRecord
{
    const TABLENAME  = 'usuario';
    const PRIMARYKEY = 'id_user';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name_user');
        parent::addAttribute('login');
        parent::addAttribute('email');
        parent::addAttribute('unit');
        parent::addAttribute('active');
            
    }

    
}


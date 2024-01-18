<?php

class Aniversario extends TRecord
{
    const TABLENAME  = 'aniversario';
    const PRIMARYKEY = 'aluno_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('aluno');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('dia');
        parent::addAttribute('mes');
        parent::addAttribute('situacao_id');
        parent::addAttribute('situacao');
        parent::addAttribute('unidade_id');
        parent::addAttribute('unidade');
            
    }

    
}


<?php

class AulaDada extends TRecord
{
    const TABLENAME  = 'aula_dada';
    const PRIMARYKEY = 'aula_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('agenda_id');
        parent::addAttribute('curriculo_id');
        parent::addAttribute('tipo_aula');
        parent::addAttribute('data_aula');
        parent::addAttribute('professor_id');
        parent::addAttribute('aula_realizada');
        parent::addAttribute('unidade_id');
        parent::addAttribute('unidade');
        parent::addAttribute('remuneracao');
        parent::addAttribute('professor');
        parent::addAttribute('turma');
            
    }

    
}


<?php

class FaturamentoMensal extends TRecord
{
    const TABLENAME  = 'faturamento_mensal';
    const PRIMARYKEY = 'aluno_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('plano_id');
        parent::addAttribute('plano_descricao');
        parent::addAttribute('plano_valor');
        parent::addAttribute('aluno_nome');
        parent::addAttribute('aluno_situacao_id');
        parent::addAttribute('aluno_convenio_id');
        parent::addAttribute('aluno_unidade_id');
        parent::addAttribute('convenio_descricao');
        parent::addAttribute('convenio_desconto');
        parent::addAttribute('situacao_status');
        parent::addAttribute('unidade');
            
    }

    
}


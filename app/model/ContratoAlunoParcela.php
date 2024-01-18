<?php

class ContratoAlunoParcela extends TRecord
{
    const TABLENAME  = 'contrato_aluno_parcela';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $contrato_aluno;
    private $forma_pagamento;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('contrato_aluno_id');
        parent::addAttribute('valor');
        parent::addAttribute('data_vencimento');
        parent::addAttribute('parcela');
        parent::addAttribute('valor_real');
        parent::addAttribute('forma_pagamento_id');
        parent::addAttribute('data_recebimento');
        parent::addAttribute('valor_recebido');
        parent::addAttribute('observacao');
    
    }

    /**
     * Method set_contrato_aluno
     * Sample of usage: $var->contrato_aluno = $object;
     * @param $object Instance of ContratoAluno
     */
    public function set_contrato_aluno(ContratoAluno $object)
    {
        $this->contrato_aluno = $object;
        $this->contrato_aluno_id = $object->id;
    }

    /**
     * Method get_contrato_aluno
     * Sample of usage: $var->contrato_aluno->attribute;
     * @returns ContratoAluno instance
     */
    public function get_contrato_aluno()
    {
    
        // loads the associated object
        if (empty($this->contrato_aluno))
            $this->contrato_aluno = new ContratoAluno($this->contrato_aluno_id);
    
        // returns the associated object
        return $this->contrato_aluno;
    }
    /**
     * Method set_forma_pagamento
     * Sample of usage: $var->forma_pagamento = $object;
     * @param $object Instance of FormaPagamento
     */
    public function set_forma_pagamento(FormaPagamento $object)
    {
        $this->forma_pagamento = $object;
        $this->forma_pagamento_id = $object->id;
    }

    /**
     * Method get_forma_pagamento
     * Sample of usage: $var->forma_pagamento->attribute;
     * @returns FormaPagamento instance
     */
    public function get_forma_pagamento()
    {
    
        // loads the associated object
        if (empty($this->forma_pagamento))
            $this->forma_pagamento = new FormaPagamento($this->forma_pagamento_id);
    
        // returns the associated object
        return $this->forma_pagamento;
    }

  /*public function get_status_pagamento()
    {
        return $this->status_pagamento == 0?'Inad.':'Ok';
    }
 */

}


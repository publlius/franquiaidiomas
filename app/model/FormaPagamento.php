<?php

class FormaPagamento extends TRecord
{
    const TABLENAME  = 'forma_pagamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('ativo');
            
    }

    /**
     * Method getContratoAlunoParcelas
     */
    public function getContratoAlunoParcelas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('forma_pagamento_id', '=', $this->id));
        return ContratoAlunoParcela::getObjects( $criteria );
    }

    public function set_contrato_aluno_parcela_contrato_aluno_to_string($contrato_aluno_parcela_contrato_aluno_to_string)
    {
        if(is_array($contrato_aluno_parcela_contrato_aluno_to_string))
        {
            $values = ContratoAluno::where('id', 'in', $contrato_aluno_parcela_contrato_aluno_to_string)->getIndexedArray('id', 'id');
            $this->contrato_aluno_parcela_contrato_aluno_to_string = implode(', ', $values);
        }
        else
        {
            $this->contrato_aluno_parcela_contrato_aluno_to_string = $contrato_aluno_parcela_contrato_aluno_to_string;
        }

        $this->vdata['contrato_aluno_parcela_contrato_aluno_to_string'] = $this->contrato_aluno_parcela_contrato_aluno_to_string;
    }

    public function get_contrato_aluno_parcela_contrato_aluno_to_string()
    {
        if(!empty($this->contrato_aluno_parcela_contrato_aluno_to_string))
        {
            return $this->contrato_aluno_parcela_contrato_aluno_to_string;
        }
    
        $values = ContratoAlunoParcela::where('forma_pagamento_id', '=', $this->id)->getIndexedArray('contrato_aluno_id','{contrato_aluno->id}');
        return implode(', ', $values);
    }

    public function set_contrato_aluno_parcela_forma_pagamento_to_string($contrato_aluno_parcela_forma_pagamento_to_string)
    {
        if(is_array($contrato_aluno_parcela_forma_pagamento_to_string))
        {
            $values = FormaPagamento::where('id', 'in', $contrato_aluno_parcela_forma_pagamento_to_string)->getIndexedArray('id', 'id');
            $this->contrato_aluno_parcela_forma_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->contrato_aluno_parcela_forma_pagamento_to_string = $contrato_aluno_parcela_forma_pagamento_to_string;
        }

        $this->vdata['contrato_aluno_parcela_forma_pagamento_to_string'] = $this->contrato_aluno_parcela_forma_pagamento_to_string;
    }

    public function get_contrato_aluno_parcela_forma_pagamento_to_string()
    {
        if(!empty($this->contrato_aluno_parcela_forma_pagamento_to_string))
        {
            return $this->contrato_aluno_parcela_forma_pagamento_to_string;
        }
    
        $values = ContratoAlunoParcela::where('forma_pagamento_id', '=', $this->id)->getIndexedArray('forma_pagamento_id','{forma_pagamento->id}');
        return implode(', ', $values);
    }

    
}


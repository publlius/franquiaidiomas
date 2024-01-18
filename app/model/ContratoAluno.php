<?php

class ContratoAluno extends TRecord
{
    const TABLENAME  = 'contrato_aluno';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $curriculo_aluno;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('curriculo_aluno_id');
        parent::addAttribute('qtd_parcela');
        parent::addAttribute('valor_parcela');
        parent::addAttribute('qtd_hora');
        parent::addAttribute('valor_real');
        parent::addAttribute('vigente');
            
    }

    /**
     * Method set_curriculo_aluno
     * Sample of usage: $var->curriculo_aluno = $object;
     * @param $object Instance of CurriculoAluno
     */
    public function set_curriculo_aluno(CurriculoAluno $object)
    {
        $this->curriculo_aluno = $object;
        $this->curriculo_aluno_id = $object->id;
    }

    /**
     * Method get_curriculo_aluno
     * Sample of usage: $var->curriculo_aluno->attribute;
     * @returns CurriculoAluno instance
     */
    public function get_curriculo_aluno()
    {
    
        // loads the associated object
        if (empty($this->curriculo_aluno))
            $this->curriculo_aluno = new CurriculoAluno($this->curriculo_aluno_id);
    
        // returns the associated object
        return $this->curriculo_aluno;
    }

    /**
     * Method getContratoAlunoParcelas
     */
    public function getContratoAlunoParcelas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('contrato_aluno_id', '=', $this->id));
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
    
        $values = ContratoAlunoParcela::where('contrato_aluno_id', '=', $this->id)->getIndexedArray('contrato_aluno_id','{contrato_aluno->id}');
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
    
        $values = ContratoAlunoParcela::where('contrato_aluno_id', '=', $this->id)->getIndexedArray('forma_pagamento_id','{forma_pagamento->id}');
        return implode(', ', $values);
    }

    
}


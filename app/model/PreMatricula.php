<?php

class PreMatricula extends TRecord
{
    const TABLENAME  = 'pre_matricula';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $estado;
    private $cidade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('cpf');
        parent::addAttribute('rg');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('cep');
        parent::addAttribute('endereco');
        parent::addAttribute('bairro');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('estado_id');
        parent::addAttribute('cidade_id');
        parent::addAttribute('fone_fixo');
        parent::addAttribute('celular_1');
        parent::addAttribute('celular_2');
        parent::addAttribute('email');
        parent::addAttribute('responsavel_1');
        parent::addAttribute('responsavel_1_cpf');
        parent::addAttribute('responsavel_1_rg');
        parent::addAttribute('responsavel_1_fone');
        parent::addAttribute('responsavel_1_lt');
        parent::addAttribute('responsavel_1_lt_fone');
        parent::addAttribute('responsavel_2');
        parent::addAttribute('responsavel_2_cpf');
        parent::addAttribute('responsavel_2_rg');
        parent::addAttribute('responsavel_2_fone');
        parent::addAttribute('responsavel_2_lt');
        parent::addAttribute('responsavel_2_lt_fone');
        parent::addAttribute('column_29');
            
    }

    /**
     * Method set_estado
     * Sample of usage: $var->estado = $object;
     * @param $object Instance of Estado
     */
    public function set_estado(Estado $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }

    /**
     * Method get_estado
     * Sample of usage: $var->estado->attribute;
     * @returns Estado instance
     */
    public function get_estado()
    {
    
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new Estado($this->estado_id);
    
        // returns the associated object
        return $this->estado;
    }
    /**
     * Method set_cidade
     * Sample of usage: $var->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }

    /**
     * Method get_cidade
     * Sample of usage: $var->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
    
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }

    
}


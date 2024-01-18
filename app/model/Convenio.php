<?php

class Convenio extends TRecord
{
    const TABLENAME  = 'convenio';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $unidade;
    private $criado_por_user;
    private $alterado_por_user;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('desconto');
        parent::addAttribute('observacao');
        parent::addAttribute('criado_em');
        parent::addAttribute('alterado_em');
        parent::addAttribute('criado_por_user_id');
        parent::addAttribute('alterado_por_user_id');
        parent::addAttribute('unidade_id');
            
    }

    /**
     * Method set_system_unit
     * Sample of usage: $var->system_unit = $object;
     * @param $object Instance of SystemUnit
     */
    public function set_unidade(SystemUnit $object)
    {
        $this->unidade = $object;
        $this->unidade_id = $object->id;
    }

    /**
     * Method get_unidade
     * Sample of usage: $var->unidade->attribute;
     * @returns SystemUnit instance
     */
    public function get_unidade()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->unidade))
            $this->unidade = new SystemUnit($this->unidade_id);
        TTransaction::close();
        // returns the associated object
        return $this->unidade;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_criado_por_user(SystemUsers $object)
    {
        $this->criado_por_user = $object;
        $this->criado_por_user_id = $object->id;
    }

    /**
     * Method get_criado_por_user
     * Sample of usage: $var->criado_por_user->attribute;
     * @returns SystemUsers instance
     */
    public function get_criado_por_user()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->criado_por_user))
            $this->criado_por_user = new SystemUsers($this->criado_por_user_id);
        TTransaction::close();
        // returns the associated object
        return $this->criado_por_user;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_alterado_por_user(SystemUsers $object)
    {
        $this->alterado_por_user = $object;
        $this->alterado_por_user_id = $object->id;
    }

    /**
     * Method get_alterado_por_user
     * Sample of usage: $var->alterado_por_user->attribute;
     * @returns SystemUsers instance
     */
    public function get_alterado_por_user()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->alterado_por_user))
            $this->alterado_por_user = new SystemUsers($this->alterado_por_user_id);
        TTransaction::close();
        // returns the associated object
        return $this->alterado_por_user;
    }

    /**
     * Method getAlunos
     */
    public function getAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('convenio_id', '=', $this->id));
        return Aluno::getObjects( $criteria );
    }

    public function set_aluno_situacao_to_string($aluno_situacao_to_string)
    {
        if(is_array($aluno_situacao_to_string))
        {
            $values = Situacao::where('id', 'in', $aluno_situacao_to_string)->getIndexedArray('id', 'id');
            $this->aluno_situacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->aluno_situacao_to_string = $aluno_situacao_to_string;
        }

        $this->vdata['aluno_situacao_to_string'] = $this->aluno_situacao_to_string;
    }

    public function get_aluno_situacao_to_string()
    {
        if(!empty($this->aluno_situacao_to_string))
        {
            return $this->aluno_situacao_to_string;
        }
    
        $values = Aluno::where('convenio_id', '=', $this->id)->getIndexedArray('situacao_id','{situacao->id}');
        return implode(', ', $values);
    }

    public function set_aluno_convenio_to_string($aluno_convenio_to_string)
    {
        if(is_array($aluno_convenio_to_string))
        {
            $values = Convenio::where('id', 'in', $aluno_convenio_to_string)->getIndexedArray('id', 'id');
            $this->aluno_convenio_to_string = implode(', ', $values);
        }
        else
        {
            $this->aluno_convenio_to_string = $aluno_convenio_to_string;
        }

        $this->vdata['aluno_convenio_to_string'] = $this->aluno_convenio_to_string;
    }

    public function get_aluno_convenio_to_string()
    {
        if(!empty($this->aluno_convenio_to_string))
        {
            return $this->aluno_convenio_to_string;
        }
    
        $values = Aluno::where('convenio_id', '=', $this->id)->getIndexedArray('convenio_id','{convenio->id}');
        return implode(', ', $values);
    }

    public function set_aluno_estado_to_string($aluno_estado_to_string)
    {
        if(is_array($aluno_estado_to_string))
        {
            $values = Estado::where('id', 'in', $aluno_estado_to_string)->getIndexedArray('id', 'id');
            $this->aluno_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->aluno_estado_to_string = $aluno_estado_to_string;
        }

        $this->vdata['aluno_estado_to_string'] = $this->aluno_estado_to_string;
    }

    public function get_aluno_estado_to_string()
    {
        if(!empty($this->aluno_estado_to_string))
        {
            return $this->aluno_estado_to_string;
        }
    
        $values = Aluno::where('convenio_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    public function set_aluno_cidade_to_string($aluno_cidade_to_string)
    {
        if(is_array($aluno_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $aluno_cidade_to_string)->getIndexedArray('id', 'id');
            $this->aluno_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->aluno_cidade_to_string = $aluno_cidade_to_string;
        }

        $this->vdata['aluno_cidade_to_string'] = $this->aluno_cidade_to_string;
    }

    public function get_aluno_cidade_to_string()
    {
        if(!empty($this->aluno_cidade_to_string))
        {
            return $this->aluno_cidade_to_string;
        }
    
        $values = Aluno::where('convenio_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
        return implode(', ', $values);
    }

    
}


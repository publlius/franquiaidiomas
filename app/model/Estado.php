<?php

class Estado extends TRecord
{
    const TABLENAME  = 'estado';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $pais;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('uf');
        parent::addAttribute('pais_id');
            
    }

    /**
     * Method set_pais
     * Sample of usage: $var->pais = $object;
     * @param $object Instance of Pais
     */
    public function set_pais(Pais $object)
    {
        $this->pais = $object;
        $this->pais_id = $object->id;
    }

    /**
     * Method get_pais
     * Sample of usage: $var->pais->attribute;
     * @returns Pais instance
     */
    public function get_pais()
    {
    
        // loads the associated object
        if (empty($this->pais))
            $this->pais = new Pais($this->pais_id);
    
        // returns the associated object
        return $this->pais;
    }

    /**
     * Method getCidades
     */
    public function getCidades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_id', '=', $this->id));
        return Cidade::getObjects( $criteria );
    }
    /**
     * Method getAlunos
     */
    public function getAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_id', '=', $this->id));
        return Aluno::getObjects( $criteria );
    }
    /**
     * Method getPreMatriculas
     */
    public function getPreMatriculas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_id', '=', $this->id));
        return PreMatricula::getObjects( $criteria );
    }

    public function set_cidade_estado_to_string($cidade_estado_to_string)
    {
        if(is_array($cidade_estado_to_string))
        {
            $values = Estado::where('id', 'in', $cidade_estado_to_string)->getIndexedArray('id', 'id');
            $this->cidade_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->cidade_estado_to_string = $cidade_estado_to_string;
        }

        $this->vdata['cidade_estado_to_string'] = $this->cidade_estado_to_string;
    }

    public function get_cidade_estado_to_string()
    {
        if(!empty($this->cidade_estado_to_string))
        {
            return $this->cidade_estado_to_string;
        }
    
        $values = Cidade::where('estado_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
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
    
        $values = Aluno::where('estado_id', '=', $this->id)->getIndexedArray('situacao_id','{situacao->id}');
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
    
        $values = Aluno::where('estado_id', '=', $this->id)->getIndexedArray('convenio_id','{convenio->id}');
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
    
        $values = Aluno::where('estado_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
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
    
        $values = Aluno::where('estado_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
        return implode(', ', $values);
    }

    public function set_pre_matricula_estado_to_string($pre_matricula_estado_to_string)
    {
        if(is_array($pre_matricula_estado_to_string))
        {
            $values = Estado::where('id', 'in', $pre_matricula_estado_to_string)->getIndexedArray('id', 'id');
            $this->pre_matricula_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->pre_matricula_estado_to_string = $pre_matricula_estado_to_string;
        }

        $this->vdata['pre_matricula_estado_to_string'] = $this->pre_matricula_estado_to_string;
    }

    public function get_pre_matricula_estado_to_string()
    {
        if(!empty($this->pre_matricula_estado_to_string))
        {
            return $this->pre_matricula_estado_to_string;
        }
    
        $values = PreMatricula::where('estado_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
        return implode(', ', $values);
    }

    public function set_pre_matricula_cidade_to_string($pre_matricula_cidade_to_string)
    {
        if(is_array($pre_matricula_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $pre_matricula_cidade_to_string)->getIndexedArray('id', 'id');
            $this->pre_matricula_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->pre_matricula_cidade_to_string = $pre_matricula_cidade_to_string;
        }

        $this->vdata['pre_matricula_cidade_to_string'] = $this->pre_matricula_cidade_to_string;
    }

    public function get_pre_matricula_cidade_to_string()
    {
        if(!empty($this->pre_matricula_cidade_to_string))
        {
            return $this->pre_matricula_cidade_to_string;
        }
    
        $values = PreMatricula::where('estado_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
        return implode(', ', $values);
    }

    
}


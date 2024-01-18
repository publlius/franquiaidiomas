<?php

class Cidade extends TRecord
{
    const TABLENAME  = 'cidade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $estado;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('estado_id');
            
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
     * Method getAlunos
     */
    public function getAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return Aluno::getObjects( $criteria );
    }
    /**
     * Method getPreMatriculas
     */
    public function getPreMatriculas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return PreMatricula::getObjects( $criteria );
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
    
        $values = Aluno::where('cidade_id', '=', $this->id)->getIndexedArray('situacao_id','{situacao->id}');
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
    
        $values = Aluno::where('cidade_id', '=', $this->id)->getIndexedArray('convenio_id','{convenio->id}');
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
    
        $values = Aluno::where('cidade_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
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
    
        $values = Aluno::where('cidade_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
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
    
        $values = PreMatricula::where('cidade_id', '=', $this->id)->getIndexedArray('estado_id','{estado->id}');
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
    
        $values = PreMatricula::where('cidade_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->id}');
        return implode(', ', $values);
    }

    
}


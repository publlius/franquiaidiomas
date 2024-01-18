<?php

class Pasta extends TRecord
{
    const TABLENAME  = 'pasta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $criado_por_user;
    private $alterado_por_user;
    private $unidade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('unidade_id');
        parent::addAttribute('descricao');
        parent::addAttribute('maximo');
        parent::addAttribute('criado_por_user_id');
        parent::addAttribute('criado_em');
        parent::addAttribute('alterado_por_user_id');
        parent::addAttribute('alterado_em');
            
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
     * Method getTurmas
     */
    public function getTurmas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pasta_id', '=', $this->id));
        return Turma::getObjects( $criteria );
    }

    public function set_turma_pasta_to_string($turma_pasta_to_string)
    {
        if(is_array($turma_pasta_to_string))
        {
            $values = Pasta::where('id', 'in', $turma_pasta_to_string)->getIndexedArray('id', 'id');
            $this->turma_pasta_to_string = implode(', ', $values);
        }
        else
        {
            $this->turma_pasta_to_string = $turma_pasta_to_string;
        }

        $this->vdata['turma_pasta_to_string'] = $this->turma_pasta_to_string;
    }

    public function get_turma_pasta_to_string()
    {
        if(!empty($this->turma_pasta_to_string))
        {
            return $this->turma_pasta_to_string;
        }
    
        $values = Turma::where('pasta_id', '=', $this->id)->getIndexedArray('pasta_id','{pasta->id}');
        return implode(', ', $values);
    }

    public function set_turma_idioma_to_string($turma_idioma_to_string)
    {
        if(is_array($turma_idioma_to_string))
        {
            $values = Idioma::where('id', 'in', $turma_idioma_to_string)->getIndexedArray('id', 'id');
            $this->turma_idioma_to_string = implode(', ', $values);
        }
        else
        {
            $this->turma_idioma_to_string = $turma_idioma_to_string;
        }

        $this->vdata['turma_idioma_to_string'] = $this->turma_idioma_to_string;
    }

    public function get_turma_idioma_to_string()
    {
        if(!empty($this->turma_idioma_to_string))
        {
            return $this->turma_idioma_to_string;
        }
    
        $values = Turma::where('pasta_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
        return implode(', ', $values);
    }

    public function set_turma_book_to_string($turma_book_to_string)
    {
        if(is_array($turma_book_to_string))
        {
            $values = Book::where('id', 'in', $turma_book_to_string)->getIndexedArray('id', 'id');
            $this->turma_book_to_string = implode(', ', $values);
        }
        else
        {
            $this->turma_book_to_string = $turma_book_to_string;
        }

        $this->vdata['turma_book_to_string'] = $this->turma_book_to_string;
    }

    public function get_turma_book_to_string()
    {
        if(!empty($this->turma_book_to_string))
        {
            return $this->turma_book_to_string;
        }
    
        $values = Turma::where('pasta_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
        return implode(', ', $values);
    }

    public function set_turma_stage_to_string($turma_stage_to_string)
    {
        if(is_array($turma_stage_to_string))
        {
            $values = Stage::where('id', 'in', $turma_stage_to_string)->getIndexedArray('id', 'id');
            $this->turma_stage_to_string = implode(', ', $values);
        }
        else
        {
            $this->turma_stage_to_string = $turma_stage_to_string;
        }

        $this->vdata['turma_stage_to_string'] = $this->turma_stage_to_string;
    }

    public function get_turma_stage_to_string()
    {
        if(!empty($this->turma_stage_to_string))
        {
            return $this->turma_stage_to_string;
        }
    
        $values = Turma::where('pasta_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
        return implode(', ', $values);
    }

    
}


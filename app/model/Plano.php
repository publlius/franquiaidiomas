<?php

class Plano extends TRecord
{
    const TABLENAME  = 'plano';
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
        parent::addAttribute('quantidade');
        parent::addAttribute('quantidade_idioma');
        parent::addAttribute('valor');
        parent::addAttribute('duracao_aula');
        parent::addAttribute('quantidade_aula');
        parent::addAttribute('unidade_id');
        parent::addAttribute('status');
        parent::addAttribute('criado_em');
        parent::addAttribute('alterado_em');
        parent::addAttribute('criado_por_user_id');
        parent::addAttribute('alterado_por_user_id');
    
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
     * Method getCurriculoAlunos
     */
    public function getCurriculoAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('plano_id', '=', $this->id));
        return CurriculoAluno::getObjects( $criteria );
    }

    public function set_curriculo_aluno_aluno_to_string($curriculo_aluno_aluno_to_string)
    {
        if(is_array($curriculo_aluno_aluno_to_string))
        {
            $values = Aluno::where('id', 'in', $curriculo_aluno_aluno_to_string)->getIndexedArray('nome', 'nome');
            $this->curriculo_aluno_aluno_to_string = implode(', ', $values);
        }
        else
        {
            $this->curriculo_aluno_aluno_to_string = $curriculo_aluno_aluno_to_string;
        }

        $this->vdata['curriculo_aluno_aluno_to_string'] = $this->curriculo_aluno_aluno_to_string;
    }

    public function get_curriculo_aluno_aluno_to_string()
    {
        if(!empty($this->curriculo_aluno_aluno_to_string))
        {
            return $this->curriculo_aluno_aluno_to_string;
        }
    
        $values = CurriculoAluno::where('plano_id', '=', $this->id)->getIndexedArray('aluno_id','{aluno->nome}');
        return implode(', ', $values);
    }

    public function set_curriculo_aluno_idioma_to_string($curriculo_aluno_idioma_to_string)
    {
        if(is_array($curriculo_aluno_idioma_to_string))
        {
            $values = Idioma::where('id', 'in', $curriculo_aluno_idioma_to_string)->getIndexedArray('id', 'id');
            $this->curriculo_aluno_idioma_to_string = implode(', ', $values);
        }
        else
        {
            $this->curriculo_aluno_idioma_to_string = $curriculo_aluno_idioma_to_string;
        }

        $this->vdata['curriculo_aluno_idioma_to_string'] = $this->curriculo_aluno_idioma_to_string;
    }

    public function get_curriculo_aluno_idioma_to_string()
    {
        if(!empty($this->curriculo_aluno_idioma_to_string))
        {
            return $this->curriculo_aluno_idioma_to_string;
        }
    
        $values = CurriculoAluno::where('plano_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
        return implode(', ', $values);
    }

    public function set_curriculo_aluno_book_to_string($curriculo_aluno_book_to_string)
    {
        if(is_array($curriculo_aluno_book_to_string))
        {
            $values = Book::where('id', 'in', $curriculo_aluno_book_to_string)->getIndexedArray('id', 'id');
            $this->curriculo_aluno_book_to_string = implode(', ', $values);
        }
        else
        {
            $this->curriculo_aluno_book_to_string = $curriculo_aluno_book_to_string;
        }

        $this->vdata['curriculo_aluno_book_to_string'] = $this->curriculo_aluno_book_to_string;
    }

    public function get_curriculo_aluno_book_to_string()
    {
        if(!empty($this->curriculo_aluno_book_to_string))
        {
            return $this->curriculo_aluno_book_to_string;
        }
    
        $values = CurriculoAluno::where('plano_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
        return implode(', ', $values);
    }

    public function set_curriculo_aluno_stage_to_string($curriculo_aluno_stage_to_string)
    {
        if(is_array($curriculo_aluno_stage_to_string))
        {
            $values = Stage::where('id', 'in', $curriculo_aluno_stage_to_string)->getIndexedArray('id', 'id');
            $this->curriculo_aluno_stage_to_string = implode(', ', $values);
        }
        else
        {
            $this->curriculo_aluno_stage_to_string = $curriculo_aluno_stage_to_string;
        }

        $this->vdata['curriculo_aluno_stage_to_string'] = $this->curriculo_aluno_stage_to_string;
    }

    public function get_curriculo_aluno_stage_to_string()
    {
        if(!empty($this->curriculo_aluno_stage_to_string))
        {
            return $this->curriculo_aluno_stage_to_string;
        }
    
        $values = CurriculoAluno::where('plano_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
        return implode(', ', $values);
    }

    public function set_curriculo_aluno_plano_to_string($curriculo_aluno_plano_to_string)
    {
        if(is_array($curriculo_aluno_plano_to_string))
        {
            $values = Plano::where('id', 'in', $curriculo_aluno_plano_to_string)->getIndexedArray('id', 'id');
            $this->curriculo_aluno_plano_to_string = implode(', ', $values);
        }
        else
        {
            $this->curriculo_aluno_plano_to_string = $curriculo_aluno_plano_to_string;
        }

        $this->vdata['curriculo_aluno_plano_to_string'] = $this->curriculo_aluno_plano_to_string;
    }

    public function get_curriculo_aluno_plano_to_string()
    {
        if(!empty($this->curriculo_aluno_plano_to_string))
        {
            return $this->curriculo_aluno_plano_to_string;
        }
    
        $values = CurriculoAluno::where('plano_id', '=', $this->id)->getIndexedArray('plano_id','{plano->id}');
        return implode(', ', $values);
    }

    public function set_curriculo_aluno_turma_to_string($curriculo_aluno_turma_to_string)
    {
        if(is_array($curriculo_aluno_turma_to_string))
        {
            $values = Turma::where('id', 'in', $curriculo_aluno_turma_to_string)->getIndexedArray('id', 'id');
            $this->curriculo_aluno_turma_to_string = implode(', ', $values);
        }
        else
        {
            $this->curriculo_aluno_turma_to_string = $curriculo_aluno_turma_to_string;
        }

        $this->vdata['curriculo_aluno_turma_to_string'] = $this->curriculo_aluno_turma_to_string;
    }

    public function get_curriculo_aluno_turma_to_string()
    {
        if(!empty($this->curriculo_aluno_turma_to_string))
        {
            return $this->curriculo_aluno_turma_to_string;
        }
    
        $values = CurriculoAluno::where('plano_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
        return implode(', ', $values);
    }

}


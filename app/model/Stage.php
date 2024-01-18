<?php

class Stage extends TRecord
{
    const TABLENAME  = 'stage';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $criado_por_user;
    private $alterado_por_user;
    private $book;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('criado_em');
        parent::addAttribute('alterado_em');
        parent::addAttribute('criado_por_user_id');
        parent::addAttribute('alterado_por_user_id');
        parent::addAttribute('book_id');
    
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
     * Method set_book
     * Sample of usage: $var->book = $object;
     * @param $object Instance of Book
     */
    public function set_book(Book $object)
    {
        $this->book = $object;
        $this->book_id = $object->id;
    }

    /**
     * Method get_book
     * Sample of usage: $var->book->attribute;
     * @returns Book instance
     */
    public function get_book()
    {
    
        // loads the associated object
        if (empty($this->book))
            $this->book = new Book($this->book_id);
    
        // returns the associated object
        return $this->book;
    }

    /**
     * Method getCurriculoAlunos
     */
    public function getCurriculoAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('stage_id', '=', $this->id));
        return CurriculoAluno::getObjects( $criteria );
    }
    /**
     * Method getTurmas
     */
    public function getTurmas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('stage_id', '=', $this->id));
        return Turma::getObjects( $criteria );
    }
    /**
     * Method getProfessorHabilitacaos
     */
    public function getProfessorHabilitacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('stage_id', '=', $this->id));
        return ProfessorHabilitacao::getObjects( $criteria );
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
    
        $values = CurriculoAluno::where('stage_id', '=', $this->id)->getIndexedArray('aluno_id','{aluno->nome}');
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
    
        $values = CurriculoAluno::where('stage_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
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
    
        $values = CurriculoAluno::where('stage_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
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
    
        $values = CurriculoAluno::where('stage_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
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
    
        $values = CurriculoAluno::where('stage_id', '=', $this->id)->getIndexedArray('plano_id','{plano->id}');
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
    
        $values = CurriculoAluno::where('stage_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
        return implode(', ', $values);
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
    
        $values = Turma::where('stage_id', '=', $this->id)->getIndexedArray('pasta_id','{pasta->id}');
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
    
        $values = Turma::where('stage_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
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
    
        $values = Turma::where('stage_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
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
    
        $values = Turma::where('stage_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
        return implode(', ', $values);
    }

    public function set_professor_habilitacao_professor_to_string($professor_habilitacao_professor_to_string)
    {
        if(is_array($professor_habilitacao_professor_to_string))
        {
            $values = Professor::where('id', 'in', $professor_habilitacao_professor_to_string)->getIndexedArray('id', 'id');
            $this->professor_habilitacao_professor_to_string = implode(', ', $values);
        }
        else
        {
            $this->professor_habilitacao_professor_to_string = $professor_habilitacao_professor_to_string;
        }

        $this->vdata['professor_habilitacao_professor_to_string'] = $this->professor_habilitacao_professor_to_string;
    }

    public function get_professor_habilitacao_professor_to_string()
    {
        if(!empty($this->professor_habilitacao_professor_to_string))
        {
            return $this->professor_habilitacao_professor_to_string;
        }
    
        $values = ProfessorHabilitacao::where('stage_id', '=', $this->id)->getIndexedArray('professor_id','{professor->id}');
        return implode(', ', $values);
    }

    public function set_professor_habilitacao_idioma_to_string($professor_habilitacao_idioma_to_string)
    {
        if(is_array($professor_habilitacao_idioma_to_string))
        {
            $values = Idioma::where('id', 'in', $professor_habilitacao_idioma_to_string)->getIndexedArray('id', 'id');
            $this->professor_habilitacao_idioma_to_string = implode(', ', $values);
        }
        else
        {
            $this->professor_habilitacao_idioma_to_string = $professor_habilitacao_idioma_to_string;
        }

        $this->vdata['professor_habilitacao_idioma_to_string'] = $this->professor_habilitacao_idioma_to_string;
    }

    public function get_professor_habilitacao_idioma_to_string()
    {
        if(!empty($this->professor_habilitacao_idioma_to_string))
        {
            return $this->professor_habilitacao_idioma_to_string;
        }
    
        $values = ProfessorHabilitacao::where('stage_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
        return implode(', ', $values);
    }

    public function set_professor_habilitacao_book_to_string($professor_habilitacao_book_to_string)
    {
        if(is_array($professor_habilitacao_book_to_string))
        {
            $values = Book::where('id', 'in', $professor_habilitacao_book_to_string)->getIndexedArray('id', 'id');
            $this->professor_habilitacao_book_to_string = implode(', ', $values);
        }
        else
        {
            $this->professor_habilitacao_book_to_string = $professor_habilitacao_book_to_string;
        }

        $this->vdata['professor_habilitacao_book_to_string'] = $this->professor_habilitacao_book_to_string;
    }

    public function get_professor_habilitacao_book_to_string()
    {
        if(!empty($this->professor_habilitacao_book_to_string))
        {
            return $this->professor_habilitacao_book_to_string;
        }
    
        $values = ProfessorHabilitacao::where('stage_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
        return implode(', ', $values);
    }

    public function set_professor_habilitacao_stage_to_string($professor_habilitacao_stage_to_string)
    {
        if(is_array($professor_habilitacao_stage_to_string))
        {
            $values = Stage::where('id', 'in', $professor_habilitacao_stage_to_string)->getIndexedArray('id', 'id');
            $this->professor_habilitacao_stage_to_string = implode(', ', $values);
        }
        else
        {
            $this->professor_habilitacao_stage_to_string = $professor_habilitacao_stage_to_string;
        }

        $this->vdata['professor_habilitacao_stage_to_string'] = $this->professor_habilitacao_stage_to_string;
    }

    public function get_professor_habilitacao_stage_to_string()
    {
        if(!empty($this->professor_habilitacao_stage_to_string))
        {
            return $this->professor_habilitacao_stage_to_string;
        }
    
        $values = ProfessorHabilitacao::where('stage_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
        return implode(', ', $values);
    }

}


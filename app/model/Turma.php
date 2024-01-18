<?php

class Turma extends TRecord
{
    const TABLENAME  = 'turma';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $unidade;
    private $criado_por_user;
    private $alterado_por_user;
    private $idioma;
    private $book;
    private $stage;
    private $pasta;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pasta_id');
        parent::addAttribute('descricao');
        parent::addAttribute('unidade_id');
        parent::addAttribute('tamanho_turma');
        parent::addAttribute('idioma_id');
        parent::addAttribute('book_id');
        parent::addAttribute('stage_id');
        parent::addAttribute('situacao');
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
     * Method set_idioma
     * Sample of usage: $var->idioma = $object;
     * @param $object Instance of Idioma
     */
    public function set_idioma(Idioma $object)
    {
        $this->idioma = $object;
        $this->idioma_id = $object->id;
    }

    /**
     * Method get_idioma
     * Sample of usage: $var->idioma->attribute;
     * @returns Idioma instance
     */
    public function get_idioma()
    {
    
        // loads the associated object
        if (empty($this->idioma))
            $this->idioma = new Idioma($this->idioma_id);
    
        // returns the associated object
        return $this->idioma;
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
     * Method set_stage
     * Sample of usage: $var->stage = $object;
     * @param $object Instance of Stage
     */
    public function set_stage(Stage $object)
    {
        $this->stage = $object;
        $this->stage_id = $object->id;
    }

    /**
     * Method get_stage
     * Sample of usage: $var->stage->attribute;
     * @returns Stage instance
     */
    public function get_stage()
    {
    
        // loads the associated object
        if (empty($this->stage))
            $this->stage = new Stage($this->stage_id);
    
        // returns the associated object
        return $this->stage;
    }
    /**
     * Method set_pasta
     * Sample of usage: $var->pasta = $object;
     * @param $object Instance of Pasta
     */
    public function set_pasta(Pasta $object)
    {
        $this->pasta = $object;
        $this->pasta_id = $object->id;
    }

    /**
     * Method get_pasta
     * Sample of usage: $var->pasta->attribute;
     * @returns Pasta instance
     */
    public function get_pasta()
    {
    
        // loads the associated object
        if (empty($this->pasta))
            $this->pasta = new Pasta($this->pasta_id);
    
        // returns the associated object
        return $this->pasta;
    }

    /**
     * Method getCurriculoAlunos
     */
    public function getCurriculoAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('turma_id', '=', $this->id));
        return CurriculoAluno::getObjects( $criteria );
    }
    /**
     * Method getTurmaAlunoss
     */
    public function getTurmaAlunoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('turma_id', '=', $this->id));
        return TurmaAlunos::getObjects( $criteria );
    }
    /**
     * Method getAgendas
     */
    public function getAgendas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('turma_id', '=', $this->id));
        return Agenda::getObjects( $criteria );
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
    
        $values = CurriculoAluno::where('turma_id', '=', $this->id)->getIndexedArray('aluno_id','{aluno->nome}');
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
    
        $values = CurriculoAluno::where('turma_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
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
    
        $values = CurriculoAluno::where('turma_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
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
    
        $values = CurriculoAluno::where('turma_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
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
    
        $values = CurriculoAluno::where('turma_id', '=', $this->id)->getIndexedArray('plano_id','{plano->id}');
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
    
        $values = CurriculoAluno::where('turma_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
        return implode(', ', $values);
    }

    public function set_turma_alunos_turma_to_string($turma_alunos_turma_to_string)
    {
        if(is_array($turma_alunos_turma_to_string))
        {
            $values = Turma::where('id', 'in', $turma_alunos_turma_to_string)->getIndexedArray('id', 'id');
            $this->turma_alunos_turma_to_string = implode(', ', $values);
        }
        else
        {
            $this->turma_alunos_turma_to_string = $turma_alunos_turma_to_string;
        }

        $this->vdata['turma_alunos_turma_to_string'] = $this->turma_alunos_turma_to_string;
    }

    public function get_turma_alunos_turma_to_string()
    {
        if(!empty($this->turma_alunos_turma_to_string))
        {
            return $this->turma_alunos_turma_to_string;
        }
    
        $values = TurmaAlunos::where('turma_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
        return implode(', ', $values);
    }

    public function set_turma_alunos_aluno_to_string($turma_alunos_aluno_to_string)
    {
        if(is_array($turma_alunos_aluno_to_string))
        {
            $values = Aluno::where('id', 'in', $turma_alunos_aluno_to_string)->getIndexedArray('nome', 'nome');
            $this->turma_alunos_aluno_to_string = implode(', ', $values);
        }
        else
        {
            $this->turma_alunos_aluno_to_string = $turma_alunos_aluno_to_string;
        }

        $this->vdata['turma_alunos_aluno_to_string'] = $this->turma_alunos_aluno_to_string;
    }

    public function get_turma_alunos_aluno_to_string()
    {
        if(!empty($this->turma_alunos_aluno_to_string))
        {
            return $this->turma_alunos_aluno_to_string;
        }
    
        $values = TurmaAlunos::where('turma_id', '=', $this->id)->getIndexedArray('aluno_id','{aluno->nome}');
        return implode(', ', $values);
    }

    public function set_agenda_sala_to_string($agenda_sala_to_string)
    {
        if(is_array($agenda_sala_to_string))
        {
            $values = Sala::where('id', 'in', $agenda_sala_to_string)->getIndexedArray('id', 'id');
            $this->agenda_sala_to_string = implode(', ', $values);
        }
        else
        {
            $this->agenda_sala_to_string = $agenda_sala_to_string;
        }

        $this->vdata['agenda_sala_to_string'] = $this->agenda_sala_to_string;
    }

    public function get_agenda_sala_to_string()
    {
        if(!empty($this->agenda_sala_to_string))
        {
            return $this->agenda_sala_to_string;
        }
    
        $values = Agenda::where('turma_id', '=', $this->id)->getIndexedArray('sala_id','{sala->id}');
        return implode(', ', $values);
    }

    public function set_agenda_turma_to_string($agenda_turma_to_string)
    {
        if(is_array($agenda_turma_to_string))
        {
            $values = Turma::where('id', 'in', $agenda_turma_to_string)->getIndexedArray('id', 'id');
            $this->agenda_turma_to_string = implode(', ', $values);
        }
        else
        {
            $this->agenda_turma_to_string = $agenda_turma_to_string;
        }

        $this->vdata['agenda_turma_to_string'] = $this->agenda_turma_to_string;
    }

    public function get_agenda_turma_to_string()
    {
        if(!empty($this->agenda_turma_to_string))
        {
            return $this->agenda_turma_to_string;
        }
    
        $values = Agenda::where('turma_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
        return implode(', ', $values);
    }

    public function set_agenda_status_cor_to_string($agenda_status_cor_to_string)
    {
        if(is_array($agenda_status_cor_to_string))
        {
            $values = StatusCor::where('id', 'in', $agenda_status_cor_to_string)->getIndexedArray('id', 'id');
            $this->agenda_status_cor_to_string = implode(', ', $values);
        }
        else
        {
            $this->agenda_status_cor_to_string = $agenda_status_cor_to_string;
        }

        $this->vdata['agenda_status_cor_to_string'] = $this->agenda_status_cor_to_string;
    }

    public function get_agenda_status_cor_to_string()
    {
        if(!empty($this->agenda_status_cor_to_string))
        {
            return $this->agenda_status_cor_to_string;
        }
    
        $values = Agenda::where('turma_id', '=', $this->id)->getIndexedArray('status_cor_id','{status_cor->id}');
        return implode(', ', $values);
    }

}


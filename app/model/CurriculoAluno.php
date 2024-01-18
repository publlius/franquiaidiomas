<?php

class CurriculoAluno extends TRecord
{
    const TABLENAME  = 'curriculo_aluno';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $book;
    private $stage;
    private $plano;
    private $turma;
    private $unidade;
    private $aluno;
    private $criado_por_user;
    private $alterado_por_user;
    private $idioma;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('aluno_id');
        parent::addAttribute('data_matricula');
        parent::addAttribute('vencimento_mensalidade');
        parent::addAttribute('matricula');
        parent::addAttribute('idioma_id');
        parent::addAttribute('book_id');
        parent::addAttribute('stage_id');
        parent::addAttribute('plano_id');
        parent::addAttribute('qtd_hora');
        parent::addAttribute('valor_parcela');
        parent::addAttribute('qtd_parcela');
        parent::addAttribute('turma_id');
        parent::addAttribute('unidade_id');
        parent::addAttribute('status');
        parent::addAttribute('observacao');
        parent::addAttribute('criado_em');
        parent::addAttribute('alterado_em');
        parent::addAttribute('criado_por_user_id');
        parent::addAttribute('alterado_por_user_id');
    
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
     * Method set_plano
     * Sample of usage: $var->plano = $object;
     * @param $object Instance of Plano
     */
    public function set_plano(Plano $object)
    {
        $this->plano = $object;
        $this->plano_id = $object->id;
    }

    /**
     * Method get_plano
     * Sample of usage: $var->plano->attribute;
     * @returns Plano instance
     */
    public function get_plano()
    {
    
        // loads the associated object
        if (empty($this->plano))
            $this->plano = new Plano($this->plano_id);
    
        // returns the associated object
        return $this->plano;
    }
    /**
     * Method set_turma
     * Sample of usage: $var->turma = $object;
     * @param $object Instance of Turma
     */
    public function set_turma(Turma $object)
    {
        $this->turma = $object;
        $this->turma_id = $object->id;
    }

    /**
     * Method get_turma
     * Sample of usage: $var->turma->attribute;
     * @returns Turma instance
     */
    public function get_turma()
    {
    
        // loads the associated object
        if (empty($this->turma))
            $this->turma = new Turma($this->turma_id);
    
        // returns the associated object
        return $this->turma;
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
     * Method set_aluno
     * Sample of usage: $var->aluno = $object;
     * @param $object Instance of Aluno
     */
    public function set_aluno(Aluno $object)
    {
        $this->aluno = $object;
        $this->aluno_id = $object->id;
    }

    /**
     * Method get_aluno
     * Sample of usage: $var->aluno->attribute;
     * @returns Aluno instance
     */
    public function get_aluno()
    {
    
        // loads the associated object
        if (empty($this->aluno))
            $this->aluno = new Aluno($this->aluno_id);
    
        // returns the associated object
        return $this->aluno;
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
     * Method getAulas
     */
    public function getAulas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('curriculo_aluno_id', '=', $this->id));
        return Aula::getObjects( $criteria );
    }
    /**
     * Method getContratoAlunos
     */
    public function getContratoAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('curriculo_aluno_id', '=', $this->id));
        return ContratoAluno::getObjects( $criteria );
    }

    public function set_aula_agenda_to_string($aula_agenda_to_string)
    {
        if(is_array($aula_agenda_to_string))
        {
            $values = Agenda::where('id', 'in', $aula_agenda_to_string)->getIndexedArray('id', 'id');
            $this->aula_agenda_to_string = implode(', ', $values);
        }
        else
        {
            $this->aula_agenda_to_string = $aula_agenda_to_string;
        }

        $this->vdata['aula_agenda_to_string'] = $this->aula_agenda_to_string;
    }

    public function get_aula_agenda_to_string()
    {
        if(!empty($this->aula_agenda_to_string))
        {
            return $this->aula_agenda_to_string;
        }
    
        $values = Aula::where('curriculo_aluno_id', '=', $this->id)->getIndexedArray('agenda_id','{agenda->id}');
        return implode(', ', $values);
    }

    public function set_aula_curriculo_aluno_to_string($aula_curriculo_aluno_to_string)
    {
        if(is_array($aula_curriculo_aluno_to_string))
        {
            $values = CurriculoAluno::where('id', 'in', $aula_curriculo_aluno_to_string)->getIndexedArray('id', 'id');
            $this->aula_curriculo_aluno_to_string = implode(', ', $values);
        }
        else
        {
            $this->aula_curriculo_aluno_to_string = $aula_curriculo_aluno_to_string;
        }

        $this->vdata['aula_curriculo_aluno_to_string'] = $this->aula_curriculo_aluno_to_string;
    }

    public function get_aula_curriculo_aluno_to_string()
    {
        if(!empty($this->aula_curriculo_aluno_to_string))
        {
            return $this->aula_curriculo_aluno_to_string;
        }
    
        $values = Aula::where('curriculo_aluno_id', '=', $this->id)->getIndexedArray('curriculo_aluno_id','{curriculo_aluno->id}');
        return implode(', ', $values);
    }

    public function set_contrato_aluno_curriculo_aluno_to_string($contrato_aluno_curriculo_aluno_to_string)
    {
        if(is_array($contrato_aluno_curriculo_aluno_to_string))
        {
            $values = CurriculoAluno::where('id', 'in', $contrato_aluno_curriculo_aluno_to_string)->getIndexedArray('id', 'id');
            $this->contrato_aluno_curriculo_aluno_to_string = implode(', ', $values);
        }
        else
        {
            $this->contrato_aluno_curriculo_aluno_to_string = $contrato_aluno_curriculo_aluno_to_string;
        }

        $this->vdata['contrato_aluno_curriculo_aluno_to_string'] = $this->contrato_aluno_curriculo_aluno_to_string;
    }

    public function get_contrato_aluno_curriculo_aluno_to_string()
    {
        if(!empty($this->contrato_aluno_curriculo_aluno_to_string))
        {
            return $this->contrato_aluno_curriculo_aluno_to_string;
        }
    
        $values = ContratoAluno::where('curriculo_aluno_id', '=', $this->id)->getIndexedArray('curriculo_aluno_id','{curriculo_aluno->id}');
        return implode(', ', $values);
    }

}


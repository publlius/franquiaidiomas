<?php

class Aula extends TRecord
{
    const TABLENAME  = 'aula';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'criado_em';
    const UPDATEDAT  = 'alterado_em';

    private $curriculo_aluno;
    private $agenda;
    private $criado_por;
    private $alterado_por;
    private $professor;

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('agenda_id');
        parent::addAttribute('curriculo_aluno_id');
        parent::addAttribute('tipo_aula');
        parent::addAttribute('competencia');
        parent::addAttribute('data_aula');
        parent::addAttribute('professor_id');
        parent::addAttribute('duracao');
        parent::addAttribute('ultima_palavra');
        parent::addAttribute('ultima_pagina');
        parent::addAttribute('presente');
        parent::addAttribute('observacao');
        parent::addAttribute('criado_em');
        parent::addAttribute('criado_por_id');
        parent::addAttribute('alterado_em');
        parent::addAttribute('alterado_por_id');
    
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
     * Method set_agenda
     * Sample of usage: $var->agenda = $object;
     * @param $object Instance of Agenda
     */
    public function set_agenda(Agenda $object)
    {
        $this->agenda = $object;
        $this->agenda_id = $object->id;
    }

    /**
     * Method get_agenda
     * Sample of usage: $var->agenda->attribute;
     * @returns Agenda instance
     */
    public function get_agenda()
    {
    
        // loads the associated object
        if (empty($this->agenda))
            $this->agenda = new Agenda($this->agenda_id);
    
        // returns the associated object
        return $this->agenda;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_criado_por(SystemUsers $object)
    {
        $this->criado_por = $object;
        $this->criado_por_id = $object->id;
    }

    /**
     * Method get_criado_por
     * Sample of usage: $var->criado_por->attribute;
     * @returns SystemUsers instance
     */
    public function get_criado_por()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->criado_por))
            $this->criado_por = new SystemUsers($this->criado_por_id);
        TTransaction::close();
        // returns the associated object
        return $this->criado_por;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_alterado_por(SystemUsers $object)
    {
        $this->alterado_por = $object;
        $this->alterado_por_id = $object->id;
    }

    /**
     * Method get_alterado_por
     * Sample of usage: $var->alterado_por->attribute;
     * @returns SystemUsers instance
     */
    public function get_alterado_por()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->alterado_por))
            $this->alterado_por = new SystemUsers($this->alterado_por_id);
        TTransaction::close();
        // returns the associated object
        return $this->alterado_por;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_professor(SystemUsers $object)
    {
        $this->professor = $object;
        $this->professor_id = $object->id;
    }

    /**
     * Method get_professor
     * Sample of usage: $var->professor->attribute;
     * @returns SystemUsers instance
     */
    public function get_professor()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->professor))
            $this->professor = new SystemUsers($this->professor_id);
        TTransaction::close();
        // returns the associated object
        return $this->professor;
    }

}


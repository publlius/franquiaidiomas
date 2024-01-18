<?php

class TurmaAlunos extends TRecord
{
    const TABLENAME  = 'turma_alunos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $aluno;
    private $turma;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('turma_id');
        parent::addAttribute('aluno_id');
            
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

    
}


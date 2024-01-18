<?php

class ResponsavelAluno extends TRecord
{
    const TABLENAME  = 'responsavel_aluno';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $aluno;
    private $criado_por_user;
    private $alterado_por_user;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('aluno_id');
        parent::addAttribute('nome');
        parent::addAttribute('parentesco');
        parent::addAttribute('rg');
        parent::addAttribute('cpf');
        parent::addAttribute('fone_1');
        parent::addAttribute('fone_2');
        parent::addAttribute('criado_em');
        parent::addAttribute('alterado_em');
        parent::addAttribute('criado_por_user_id');
        parent::addAttribute('alterado_por_user_id');
    
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

}


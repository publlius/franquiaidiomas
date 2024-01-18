<?php

class Aluno extends TRecord
{
    const TABLENAME  = 'aluno';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $situacao;
    private $criado_por_user;
    private $alterado_por_user;
    private $convenio;
    private $unidade;
    private $estado;
    private $cidade;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('situacao_id');
        parent::addAttribute('rg');
        parent::addAttribute('cpf');
        parent::addAttribute('convenio_id');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('endereco');
        parent::addAttribute('bairro');
        parent::addAttribute('cep');
        parent::addAttribute('numero');
        parent::addAttribute('estado_id');
        parent::addAttribute('cidade_id');
        parent::addAttribute('unidade_id');
        parent::addAttribute('email');
        parent::addAttribute('fone_2');
        parent::addAttribute('fone_1');
        parent::addAttribute('criado_por_user_id');
        parent::addAttribute('alterado_por_user_id');
        parent::addAttribute('criado_em');
        parent::addAttribute('alterado_em');
    
    }

    /**
     * Method set_situacao
     * Sample of usage: $var->situacao = $object;
     * @param $object Instance of Situacao
     */
    public function set_situacao(Situacao $object)
    {
        $this->situacao = $object;
        $this->situacao_id = $object->id;
    }

    /**
     * Method get_situacao
     * Sample of usage: $var->situacao->attribute;
     * @returns Situacao instance
     */
    public function get_situacao()
    {
    
        // loads the associated object
        if (empty($this->situacao))
            $this->situacao = new Situacao($this->situacao_id);
    
        // returns the associated object
        return $this->situacao;
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
     * Method set_convenio
     * Sample of usage: $var->convenio = $object;
     * @param $object Instance of Convenio
     */
    public function set_convenio(Convenio $object)
    {
        $this->convenio = $object;
        $this->convenio_id = $object->id;
    }

    /**
     * Method get_convenio
     * Sample of usage: $var->convenio->attribute;
     * @returns Convenio instance
     */
    public function get_convenio()
    {
    
        // loads the associated object
        if (empty($this->convenio))
            $this->convenio = new Convenio($this->convenio_id);
    
        // returns the associated object
        return $this->convenio;
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
     * Method set_cidade
     * Sample of usage: $var->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }

    /**
     * Method get_cidade
     * Sample of usage: $var->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
    
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }

    /**
     * Method getResponsavelAlunos
     */
    public function getResponsavelAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('aluno_id', '=', $this->id));
        return ResponsavelAluno::getObjects( $criteria );
    }
    /**
     * Method getCurriculoAlunos
     */
    public function getCurriculoAlunos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('aluno_id', '=', $this->id));
        return CurriculoAluno::getObjects( $criteria );
    }
    /**
     * Method getTurmaAlunoss
     */
    public function getTurmaAlunoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('aluno_id', '=', $this->id));
        return TurmaAlunos::getObjects( $criteria );
    }

    public function set_responsavel_aluno_aluno_to_string($responsavel_aluno_aluno_to_string)
    {
        if(is_array($responsavel_aluno_aluno_to_string))
        {
            $values = Aluno::where('id', 'in', $responsavel_aluno_aluno_to_string)->getIndexedArray('nome', 'nome');
            $this->responsavel_aluno_aluno_to_string = implode(', ', $values);
        }
        else
        {
            $this->responsavel_aluno_aluno_to_string = $responsavel_aluno_aluno_to_string;
        }

        $this->vdata['responsavel_aluno_aluno_to_string'] = $this->responsavel_aluno_aluno_to_string;
    }

    public function get_responsavel_aluno_aluno_to_string()
    {
        if(!empty($this->responsavel_aluno_aluno_to_string))
        {
            return $this->responsavel_aluno_aluno_to_string;
        }
    
        $values = ResponsavelAluno::where('aluno_id', '=', $this->id)->getIndexedArray('aluno_id','{aluno->nome}');
        return implode(', ', $values);
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
    
        $values = CurriculoAluno::where('aluno_id', '=', $this->id)->getIndexedArray('aluno_id','{aluno->nome}');
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
    
        $values = CurriculoAluno::where('aluno_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
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
    
        $values = CurriculoAluno::where('aluno_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
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
    
        $values = CurriculoAluno::where('aluno_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
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
    
        $values = CurriculoAluno::where('aluno_id', '=', $this->id)->getIndexedArray('plano_id','{plano->id}');
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
    
        $values = CurriculoAluno::where('aluno_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
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
    
        $values = TurmaAlunos::where('aluno_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
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
    
        $values = TurmaAlunos::where('aluno_id', '=', $this->id)->getIndexedArray('aluno_id','{aluno->nome}');
        return implode(', ', $values);
    }

}


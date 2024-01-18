<?php

class Agenda extends TRecord
{
    const TABLENAME  = 'agenda';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}
    const CACHECONTROL  = 'TAPCache';

    const DELETEDAT  = 'deletado_em';
    const CREATEDAT  = 'criado_em';
    const UPDATEDAT  = 'alterado_em';

    private $sala;
    private $professor;
    private $turma;
    private $unidade;
    private $status_cor;

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('unidade_id');
        parent::addAttribute('sala_id');
        parent::addAttribute('professor_id');
        parent::addAttribute('turma_id');
        parent::addAttribute('data_inicial');
        parent::addAttribute('data_final');
        parent::addAttribute('horario_inicial');
        parent::addAttribute('horario_final');
        parent::addAttribute('status_cor_id');
        parent::addAttribute('cor');
        parent::addAttribute('aula_realizada');
        parent::addAttribute('link_sala');
        parent::addAttribute('observacao');
        parent::addAttribute('criado_em');
        parent::addAttribute('criado_por_id');
        parent::addAttribute('alterado_em');
        parent::addAttribute('alterado_por_id');
        parent::addAttribute('deletado_em');
    
    }

    /**
     * Method set_sala
     * Sample of usage: $var->sala = $object;
     * @param $object Instance of Sala
     */
    public function set_sala(Sala $object)
    {
        $this->sala = $object;
        $this->sala_id = $object->id;
    }

    /**
     * Method get_sala
     * Sample of usage: $var->sala->attribute;
     * @returns Sala instance
     */
    public function get_sala()
    {
    
        // loads the associated object
        if (empty($this->sala))
            $this->sala = new Sala($this->sala_id);
    
        // returns the associated object
        return $this->sala;
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
     * Method set_status_cor
     * Sample of usage: $var->status_cor = $object;
     * @param $object Instance of StatusCor
     */
    public function set_status_cor(StatusCor $object)
    {
        $this->status_cor = $object;
        $this->status_cor_id = $object->id;
    }

    /**
     * Method get_status_cor
     * Sample of usage: $var->status_cor->attribute;
     * @returns StatusCor instance
     */
    public function get_status_cor()
    {
    
        // loads the associated object
        if (empty($this->status_cor))
            $this->status_cor = new StatusCor($this->status_cor_id);
    
        // returns the associated object
        return $this->status_cor;
    }

    /**
     * Method getAulas
     */
    public function getAulas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('agenda_id', '=', $this->id));
        return Aula::getObjects( $criteria );
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
    
        $values = Aula::where('agenda_id', '=', $this->id)->getIndexedArray('agenda_id','{agenda->id}');
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
    
        $values = Aula::where('agenda_id', '=', $this->id)->getIndexedArray('curriculo_aluno_id','{curriculo_aluno->id}');
        return implode(', ', $values);
    }

    public function validate()
    {
       if (TSession::getValue("userunitids") && ! in_array($this->unidade_id, TSession::getValue("userunitids")))
       {
            throw new Exception('Você não pode editar agendas da unidade: ' .  $this->get_unidade()->name);
       }
   
       $criteriaProfessor = new TCriteria;
       $criteriaProfessor->add(new TFilter('id', "<>", $this->id));
       $criteriaProfessor->add(new TFilter('professor_id', "=", $this->professor_id));
   
       $agendas = self::getObjects($criteriaProfessor);
   
   
       //         | HORARIO AGENDADO |
       //    |  NOVO   |         |    NOVO    | 
       //       |         NOVO         |
       //
   
       // Verifica disponibilidade do professor.
       if ($agendas)
       {
           $dtInicial = $this->horario_inicial; 
           $dtFinal = $this->horario_final; 
       
           foreach($agendas as $agenda)
           {
                $agendaInicio = date('Y-m-d H:i', strtotime($agenda->horario_inicial)); 
                $agendaFim =  date('Y-m-d H:i', strtotime($agenda->horario_final));
            
                if ($dtFinal > $agendaInicio && $dtInicial < $agendaFim) {
                    throw new Exception('Professor em aula: ' . $agenda->get_turma()->descricao . ' | ' . $agenda->get_unidade()->name );
                }
           }
       }
   
       // Verifica disponibilidade de sala.
       $criteriaSala = new TCriteria;
       $criteriaSala->add(new TFilter('id', "<>", $this->id));
       $criteriaSala->add(new TFilter('sala_id', "=", $this->sala_id));
   
       $agendas = self::getObjects($criteriaSala);
   
       if ($agendas)
       {
           $dtInicial = $this->horario_inicial; 
           $dtFinal = $this->horario_final; 
       
           foreach($agendas as $agenda)
           {
                $agendaInicio = date('Y-m-d H:i', strtotime($agenda->horario_inicial)); 
                $agendaFim =  date('Y-m-d H:i', strtotime($agenda->horario_final));
            
                if ($dtFinal > $agendaInicio && $dtInicial < $agendaFim) {
                    throw new Exception('Sala ocupada: ' . $agenda->get_professor()->name . ' | ' . $agenda->get_turma()->descricao . ' | ' . $agenda->get_unidade()->name );
                }
           }
       }
    }
 
 public function get_aula_ok()
    {
        return $this->aula_realizada == 1?'SIM':'NÃO';
    }
 
                            
}


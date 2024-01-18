<?php

class Sala extends TRecord
{
    const TABLENAME  = 'sala';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $unidade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('unidade_id');
        parent::addAttribute('descricao');
            
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
     * Method getAgendas
     */
    public function getAgendas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('sala_id', '=', $this->id));
        return Agenda::getObjects( $criteria );
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
    
        $values = Agenda::where('sala_id', '=', $this->id)->getIndexedArray('sala_id','{sala->id}');
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
    
        $values = Agenda::where('sala_id', '=', $this->id)->getIndexedArray('turma_id','{turma->id}');
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
    
        $values = Agenda::where('sala_id', '=', $this->id)->getIndexedArray('status_cor_id','{status_cor->id}');
        return implode(', ', $values);
    }

    
}


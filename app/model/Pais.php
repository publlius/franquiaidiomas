<?php

class Pais extends TRecord
{
    const TABLENAME  = 'pais';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('sigla');
            
    }

    /**
     * Method getEstados
     */
    public function getEstados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pais_id', '=', $this->id));
        return Estado::getObjects( $criteria );
    }

    public function set_estado_pais_to_string($estado_pais_to_string)
    {
        if(is_array($estado_pais_to_string))
        {
            $values = Pais::where('id', 'in', $estado_pais_to_string)->getIndexedArray('id', 'id');
            $this->estado_pais_to_string = implode(', ', $values);
        }
        else
        {
            $this->estado_pais_to_string = $estado_pais_to_string;
        }

        $this->vdata['estado_pais_to_string'] = $this->estado_pais_to_string;
    }

    public function get_estado_pais_to_string()
    {
        if(!empty($this->estado_pais_to_string))
        {
            return $this->estado_pais_to_string;
        }
    
        $values = Estado::where('pais_id', '=', $this->id)->getIndexedArray('pais_id','{pais->id}');
        return implode(', ', $values);
    }

    
}


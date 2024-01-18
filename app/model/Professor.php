<?php

class Professor extends TRecord
{
    const TABLENAME  = 'professor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $usuario;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('usuario_id');
        parent::addAttribute('remuneracao');
            
    }

    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_usuario(SystemUsers $object)
    {
        $this->usuario = $object;
        $this->usuario_id = $object->id;
    }

    /**
     * Method get_usuario
     * Sample of usage: $var->usuario->attribute;
     * @returns SystemUsers instance
     */
    public function get_usuario()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->usuario))
            $this->usuario = new SystemUsers($this->usuario_id);
        TTransaction::close();
        // returns the associated object
        return $this->usuario;
    }

    /**
     * Method getProfessorHabilitacaos
     */
    public function getProfessorHabilitacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('professor_id', '=', $this->id));
        return ProfessorHabilitacao::getObjects( $criteria );
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
    
        $values = ProfessorHabilitacao::where('professor_id', '=', $this->id)->getIndexedArray('professor_id','{professor->id}');
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
    
        $values = ProfessorHabilitacao::where('professor_id', '=', $this->id)->getIndexedArray('idioma_id','{idioma->id}');
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
    
        $values = ProfessorHabilitacao::where('professor_id', '=', $this->id)->getIndexedArray('book_id','{book->id}');
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
    
        $values = ProfessorHabilitacao::where('professor_id', '=', $this->id)->getIndexedArray('stage_id','{stage->id}');
        return implode(', ', $values);
    }

    
}


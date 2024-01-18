<?php

class ProfessorHabilitacao extends TRecord
{
    const TABLENAME  = 'professor_habilitacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $idioma;
    private $book;
    private $stage;
    private $professor;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('professor_id');
        parent::addAttribute('idioma_id');
        parent::addAttribute('book_id');
        parent::addAttribute('stage_id');
            
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
     * Method set_professor
     * Sample of usage: $var->professor = $object;
     * @param $object Instance of Professor
     */
    public function set_professor(Professor $object)
    {
        $this->professor = $object;
        $this->professor_id = $object->id;
    }

    /**
     * Method get_professor
     * Sample of usage: $var->professor->attribute;
     * @returns Professor instance
     */
    public function get_professor()
    {
    
        // loads the associated object
        if (empty($this->professor))
            $this->professor = new Professor($this->professor_id);
    
        // returns the associated object
        return $this->professor;
    }

    
}


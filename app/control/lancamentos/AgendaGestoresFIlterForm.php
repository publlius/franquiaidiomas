<?php

class AgendaGestoresFIlterForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_AgendaGestoresFIlterForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Agenda Gestores");

        $criteria_professor = new TCriteria();
        $criteria_turma = new TCriteria();

        $filterVar = "Y";
        $criteria_professor->add(new TFilter('active', '=', $filterVar)); 
        $filterVar = "1";
        $criteria_turma->add(new TFilter('situacao', '=', $filterVar)); 

        $unidade = new TDBCombo('unidade', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $professor = new TDBCombo('professor', 'permission', 'SystemUsers', 'id', '{name}','name asc' , $criteria_professor );
        $idioma = new TDBCombo('idioma', 'cdi', 'Idioma', 'id', '{descricao}','descricao asc'  );
        $turma = new TDBCombo('turma', 'cdi', 'Turma', 'id', '{turma_alunos_aluno_to_string}','id asc' , $criteria_turma );
        $button_filtrar = new TButton('button_filtrar');
        $calendario = new BPageContainer();


        $unidade->setValue(TSession::getValue('userunitid'));
        $turma->enableSearch();
        $button_filtrar->addStyleClass('btn-default');
        $button_filtrar->setImage('fas:search #000000');
        $calendario->setId('b6154d8331b0d3');

        $button_filtrar->setAction(new TAction([$this, 'onFilter']), "Filtrar");
        $calendario->setAction(new TAction(['AgendaFormView', 'onShow'], $param));

        $turma->setSize('75%');
        $idioma->setSize('100%');
        $unidade->setSize('100%');
        $professor->setSize('100%');
        $calendario->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $calendario->add($loadingContainer);

        $this->calendario = $calendario;

        $row1 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null),$unidade],[new TLabel("Professor:", null, '14px', null),$professor],[new TLabel("Idioma:", null, '14px', null),$idioma],[new TLabel("Aluno:", null, '14px', null, '100%'),$turma,$button_filtrar]);
        $row1->layout = [' col-sm-2',' col-sm-3',' col-sm-3',' col-sm-4'];

        $row2 = $this->form->addFields([$calendario]);
        $row2->layout = [' col-sm-12'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Lançamentos","Agenda Gestores"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onFilter($param = null) 
    {
        try 
        {
            //code here

            $this->form->setData($this->form->getData());

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

    } 

}


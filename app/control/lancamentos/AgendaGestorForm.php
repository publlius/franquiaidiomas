<?php

class AgendaGestorForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Agenda';
    private static $primaryKey = 'id';
    private static $formName = 'form_Agenda';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Lançamento de agenda");


        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $sala_id = new TDBCombo('sala_id', 'cdi', 'Sala', 'id', '{id}','id asc'  );
        $professor_id = new TDBCombo('professor_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $turma_id = new TDBCombo('turma_id', 'cdi', 'Turma', 'id', '{id}','id asc'  );
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');
        $horario_inicial = new TDateTime('horario_inicial');
        $horario_final = new TDateTime('horario_final');
        $cor = new TColor('cor');
        $observacao = new TEntry('observacao');
        $criado_em = new TDateTime('criado_em');
        $criado_por_id = new TEntry('criado_por_id');
        $alterado_em = new TDateTime('alterado_em');
        $alterado_por_id = new TEntry('alterado_por_id');

        $turma_id->addValidation("Turma id", new TRequiredValidator()); 
        $horario_inicial->addValidation("Horário inicial", new TRequiredValidator()); 
        $horario_final->addValidation("Horário final", new TRequiredValidator()); 

        $id->setEditable(false);

        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');
        $criado_em->setMask('dd/mm/yyyy hh:ii');
        $alterado_em->setMask('dd/mm/yyyy hh:ii');
        $horario_final->setMask('dd/mm/yyyy hh:ii');
        $horario_inicial->setMask('dd/mm/yyyy hh:ii');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');
        $criado_em->setDatabaseMask('yyyy-mm-dd hh:ii');
        $alterado_em->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $cor->setSize(100);
        $criado_em->setSize(150);
        $sala_id->setSize('100%');
        $data_final->setSize(110);
        $turma_id->setSize('100%');
        $alterado_em->setSize(150);
        $data_inicial->setSize(110);
        $unidade_id->setSize('100%');
        $horario_final->setSize(150);
        $observacao->setSize('100%');
        $professor_id->setSize('100%');
        $horario_inicial->setSize(150);
        $criado_por_id->setSize('100%');
        $alterado_por_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Unidade id:", null, '14px', null)],[$unidade_id]);
        $row3 = $this->form->addFields([new TLabel("Sala id:", null, '14px', null)],[$sala_id]);
        $row4 = $this->form->addFields([new TLabel("Professor id:", null, '14px', null)],[$professor_id]);
        $row5 = $this->form->addFields([new TLabel("Turma id:", '#ff0000', '14px', null)],[$turma_id]);
        $row6 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null)],[$data_inicial]);
        $row7 = $this->form->addFields([new TLabel("Data final:", null, '14px', null)],[$data_final]);
        $row8 = $this->form->addFields([new TLabel("Horário inicial:", '#ff0000', '14px', null)],[$horario_inicial]);
        $row9 = $this->form->addFields([new TLabel("Horário final:", '#ff0000', '14px', null)],[$horario_final]);
        $row10 = $this->form->addFields([new TLabel("Cor:", null, '14px', null)],[$cor]);
        $row11 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$observacao]);
        $row12 = $this->form->addFields([new TLabel("Criado em:", null, '14px', null)],[$criado_em]);
        $row13 = $this->form->addFields([new TLabel("Criado por id:", null, '14px', null)],[$criado_por_id]);
        $row14 = $this->form->addFields([new TLabel("Alterado em:", null, '14px', null)],[$alterado_em]);
        $row15 = $this->form->addFields([new TLabel("Alterado por id:", null, '14px', null)],[$alterado_por_id]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Lançamentos","Lançamento de agenda"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Agenda(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', "Registro salvo", $messageAction); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Agenda($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}


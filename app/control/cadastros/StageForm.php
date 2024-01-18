<?php

class StageForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Stage';
    private static $primaryKey = 'id';
    private static $formName = 'form_Stage';

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
        $this->form->setFormTitle("Cadastro de stage");


        $id = new TEntry('id');
        $descricao = new TEntry('descricao');
        $criado_em = new TDateTime('criado_em');
        $alterado_em = new TDateTime('alterado_em');
        $criado_por_user_id = new TDBCombo('criado_por_user_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $alterado_por_user_id = new TDBCombo('alterado_por_user_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $book_id = new TDBCombo('book_id', 'cdi', 'Book', 'id', '{id}','id asc'  );

        $descricao->addValidation("Descricao", new TRequiredValidator()); 
        $book_id->addValidation("Book id", new TRequiredValidator()); 

        $id->setEditable(false);

        $criado_em->setMask('dd/mm/yyyy hh:ii');
        $alterado_em->setMask('dd/mm/yyyy hh:ii');

        $criado_em->setDatabaseMask('yyyy-mm-dd hh:ii');
        $alterado_em->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $criado_em->setSize(150);
        $book_id->setSize('70%');
        $descricao->setSize('70%');
        $alterado_em->setSize(150);
        $criado_por_user_id->setSize('70%');
        $alterado_por_user_id->setSize('70%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Descricao:", '#ff0000', '14px', null)],[$descricao]);
        $row3 = $this->form->addFields([new TLabel("Criado em:", null, '14px', null)],[$criado_em]);
        $row4 = $this->form->addFields([new TLabel("Alterado em:", null, '14px', null)],[$alterado_em]);
        $row5 = $this->form->addFields([new TLabel("Criado por user id:", null, '14px', null)],[$criado_por_user_id]);
        $row6 = $this->form->addFields([new TLabel("Alterado por user id:", null, '14px', null)],[$alterado_por_user_id]);
        $row7 = $this->form->addFields([new TLabel("Book id:", '#ff0000', '14px', null)],[$book_id]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
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
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de stage"]));
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

            $object = new Stage(); // create an empty object 

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

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);

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

                $object = new Stage($key); // instantiates the Active Record 

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


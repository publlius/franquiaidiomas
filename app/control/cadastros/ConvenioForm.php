<?php

class ConvenioForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Convenio';
    private static $primaryKey = 'id';
    private static $formName = 'form_Convenio';

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
        $this->form->setFormTitle("Cadastro de convenio");

        $criteria_unidade_id = new TCriteria();

        $filterVar = TSession::getValue("userunitids");
        $criteria_unidade_id->add(new TFilter('id', 'in', $filterVar)); 

        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc' , $criteria_unidade_id );
        $descricao = new TEntry('descricao');
        $desconto = new TNumeric('desconto', '2', ',', '.' );
        $observacao = new TText('observacao');

        $unidade_id->addValidation("Unidade:", new TRequiredValidator()); 
        $descricao->addValidation("Descricao", new TRequiredValidator()); 
        $desconto->addValidation("Desconto", new TRequiredValidator()); 

        $id->setEditable(false);
        $id->setSize(100);
        $desconto->setSize('40%');
        $descricao->setSize('70%');
        $unidade_id->setSize('70%');
        $observacao->setSize('70%', 70);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Unidade:", '#ff0000', '14px', null)],[$unidade_id]);
        $row3 = $this->form->addFields([new TLabel("Descrição:", '#ff0000', '14px', null)],[$descricao]);
        $row4 = $this->form->addFields([new TLabel("Desconto %:", '#ff0000', '14px', null)],[$desconto]);
        $row5 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$observacao]);

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
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de convenio"]));
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

            $object = new Convenio(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if (empty($object->id))
            {
                $object->criado_por_user_id = TSession::getValue('userid');
                $object->criado_em = date('Y-m-d H:i:s');
            } else {
                $object->alterado_por_user_id = TSession::getValue('userid');
                $object->alterado_em = date('Y-m-d H:i:s');
            }
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

                $object = new Convenio($key); // instantiates the Active Record 

                                $object->unidade_id = $object->unidade->id;

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

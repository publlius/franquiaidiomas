<?php

class AgendaFormList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'Agenda';
    private static $primaryKey = 'id';
    private static $formName = 'form_list_Agenda';
    private $limit = 20;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Agenda Gestor List");
        $this->limit = 20;


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
        $row2 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unidade_id]);
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

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_unidade_name = new TDataGridColumn('unidade->name', "Unidade id", 'left');
        $column_sala_id = new TDataGridColumn('sala_id', "Sala id", 'left');
        $column_professor_name = new TDataGridColumn('professor->name', "Professor id", 'left');
        $column_turma_id = new TDataGridColumn('turma_id', "Turma id", 'left');
        $column_data_inicial = new TDataGridColumn('data_inicial', "Data inicial", 'left');
        $column_data_final = new TDataGridColumn('data_final', "Data final", 'left');
        $column_horario_inicial = new TDataGridColumn('horario_inicial', "Horário inicial", 'left');
        $column_horario_final = new TDataGridColumn('horario_final', "Horário final", 'left');
        $column_cor = new TDataGridColumn('cor', "Cor", 'left');
        $column_observacao = new TDataGridColumn('observacao', "Observação", 'left');
        $column_criado_em = new TDataGridColumn('criado_em', "Criado em", 'left');
        $column_criado_por_id = new TDataGridColumn('criado_por_id', "Criado por id", 'left');
        $column_alterado_em = new TDataGridColumn('alterado_em', "Alterado em", 'left');
        $column_alterado_por_id = new TDataGridColumn('alterado_por_id', "Alterado por id", 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_unidade_name);
        $this->datagrid->addColumn($column_sala_id);
        $this->datagrid->addColumn($column_professor_name);
        $this->datagrid->addColumn($column_turma_id);
        $this->datagrid->addColumn($column_data_inicial);
        $this->datagrid->addColumn($column_data_final);
        $this->datagrid->addColumn($column_horario_inicial);
        $this->datagrid->addColumn($column_horario_final);
        $this->datagrid->addColumn($column_cor);
        $this->datagrid->addColumn($column_observacao);
        $this->datagrid->addColumn($column_criado_em);
        $this->datagrid->addColumn($column_criado_por_id);
        $this->datagrid->addColumn($column_alterado_em);
        $this->datagrid->addColumn($column_alterado_por_id);

        $action_onEdit = new TDataGridAction(array('AgendaFormList', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('AgendaFormList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Lançamentos","Agenda Gestor List"]));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onEdit($param = null) 
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

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Agenda($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
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
            $this->onReload();

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'cdi'
            TTransaction::open(self::$database);

            // creates a repository for Agenda
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }
            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }


}


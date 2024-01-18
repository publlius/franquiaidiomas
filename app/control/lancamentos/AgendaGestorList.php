<?php

class AgendaGestorList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'cdi';
    private static $activeRecord = 'Agenda';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Agenda';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters'];
    private $limit = 20;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Agenda Gestor (Listagem)");
        $this->limit = 20;

        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $sala_id = new TCombo('sala_id');
        $professor_id = new TDBCombo('professor_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $turma_id = new TDBCombo('turma_id', 'cdi', 'Turma', 'id', '{descricao}','descricao asc'  );
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');

        $unidade_id->setChangeAction(new TAction([$this,'onChangeunidade_id']));

        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');

        $sala_id->enableSearch();
        $unidade_id->enableSearch();
        $professor_id->enableSearch();

        $sala_id->setSize('100%');
        $data_final->setSize(110);
        $turma_id->setSize('100%');
        $data_inicial->setSize(110);
        $unidade_id->setSize('100%');
        $professor_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unidade_id]);
        $row2 = $this->form->addFields([new TLabel("Sala:", null, '14px', null)],[$sala_id]);
        $row3 = $this->form->addFields([new TLabel("Professor:", null, '14px', null)],[$professor_id]);
        $row4 = $this->form->addFields([new TLabel("Turma:", null, '14px', null)],[$turma_id]);
        $row5 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null)],[$data_inicial],[new TLabel("Data final:", null, '14px', null)],[$data_final]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #000000');
        $this->btn_onexportcsv = $btn_onexportcsv;

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['AgendaGestorForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_unidade_name_transformed = new TDataGridColumn('unidade->name', "Unidade", 'left');
        $column_sala_id_transformed = new TDataGridColumn('sala_id', "Sala", 'left');
        $column_professor_name_transformed = new TDataGridColumn('professor->name', "Professor", 'left');
        $column_turma_descricao_transformed = new TDataGridColumn('turma->descricao', "Turma", 'left');
        $column_data_inicial_transformed = new TDataGridColumn('data_inicial', "Data", 'left');
        $column_horario_inicial_transformed = new TDataGridColumn('horario_inicial', "Horário", 'left');

        $column_unidade_name_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_sala_id_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_professor_name_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_turma_descricao_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_data_inicial_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_horario_inicial_transformed->setTransformer(function($value, $object, $row)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });        

        $this->datagrid->addColumn($column_unidade_name_transformed);
        $this->datagrid->addColumn($column_sala_id_transformed);
        $this->datagrid->addColumn($column_professor_name_transformed);
        $this->datagrid->addColumn($column_turma_descricao_transformed);
        $this->datagrid->addColumn($column_data_inicial_transformed);
        $this->datagrid->addColumn($column_horario_inicial_transformed);

        $action_onEdit = new TDataGridAction(array('AgendaGestorForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('AgendaGestorList', 'onDelete'));
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

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Lançamentos","Agenda Gestor List"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public static function onChangeunidade_id($param)
    {
        try
        {

            if (isset($param['unidade_id']) && $param['unidade_id'])
            { 
                $criteria = TCriteria::create(['unidade_id' => $param['unidade_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'sala_id', 'cdi', 'Sala', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'sala_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
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

    public function onExportCsv($param = null) 
    {
        try
        {
            $this->onSearch();

            TTransaction::open(self::$database); // open a transaction
            $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
            $criteria = $this->filter_criteria;

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            $records = $repository->load($criteria); // load the objects according to criteria
            if ($records)
            {
                $file = 'tmp/'.uniqid().'.csv';
                $handle = fopen($file, 'w');
                $columns = $this->datagrid->getColumns();

                $csvColumns = [];
                foreach($columns as $column)
                {
                    $csvColumns[] = $column->getLabel();
                }
                fputcsv($handle, $csvColumns, ';');

                foreach ($records as $record)
                {
                    $csvColumns = [];
                    foreach($columns as $column)
                    {
                        $name = $column->getName();
                        $csvColumns[] = $record->{$name};
                    }
                    fputcsv($handle, $csvColumns, ';');
                }
                fclose($handle);

                TPage::openFile($file);
            }
            else
            {
                new TMessage('info', _t('No records found'));       
            }

            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->unidade_id))
            {
                $value = $object->unidade_id;

                $obj->unidade_id = $value;
            }
            if(isset($object->sala_id))
            {
                $value = $object->sala_id;

                $obj->sala_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->unidade_id))
            {
                $value = $object->unidade_id;

                $obj->unidade_id = $value;
            }
            if(isset($object->sala_id))
            {
                $value = $object->sala_id;

                $obj->sala_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->unidade_id) AND ( (is_scalar($data->unidade_id) AND $data->unidade_id !== '') OR (is_array($data->unidade_id) AND (!empty($data->unidade_id)) )) )
        {

            $filters[] = new TFilter('unidade_id', '=', $data->unidade_id);// create the filter 
        }

        if (isset($data->sala_id) AND ( (is_scalar($data->sala_id) AND $data->sala_id !== '') OR (is_array($data->sala_id) AND (!empty($data->sala_id)) )) )
        {

            $filters[] = new TFilter('sala_id', '=', $data->sala_id);// create the filter 
        }

        if (isset($data->professor_id) AND ( (is_scalar($data->professor_id) AND $data->professor_id !== '') OR (is_array($data->professor_id) AND (!empty($data->professor_id)) )) )
        {

            $filters[] = new TFilter('professor_id', '=', $data->professor_id);// create the filter 
        }

        if (isset($data->turma_id) AND ( (is_scalar($data->turma_id) AND $data->turma_id !== '') OR (is_array($data->turma_id) AND (!empty($data->turma_id)) )) )
        {

            $filters[] = new TFilter('turma_id', '=', $data->turma_id);// create the filter 
        }

        if (isset($data->data_inicial) AND ( (is_scalar($data->data_inicial) AND $data->data_inicial !== '') OR (is_array($data->data_inicial) AND (!empty($data->data_inicial)) )) )
        {

            $filters[] = new TFilter('data_inicial', '=', $data->data_inicial);// create the filter 
        }

        if (isset($data->data_final) AND ( (is_scalar($data->data_final) AND $data->data_final !== '') OR (is_array($data->data_final) AND (!empty($data->data_final)) )) )
        {

            $filters[] = new TFilter('data_final', '=', $data->data_final);// create the filter 
        }

        $this->fireEvents($data);

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
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

            $criteria = clone $this->filter_criteria;

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

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

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
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
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


<?php

class TurmaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'cdi';
    private static $activeRecord = 'Turma';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Turma';
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
        $this->form->setFormTitle("Listagem de turmas");
        $this->limit = 20;

        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $situacao = new TCombo('situacao');
        $descricao = new TEntry('descricao');
        $idioma_id = new TDBCombo('idioma_id', 'cdi', 'Idioma', 'id', '{descricao}','descricao asc'  );
        $book_id = new TCombo('book_id');
        $stage_id = new TCombo('stage_id');

        $idioma_id->setChangeAction(new TAction([$this,'onChangeidioma_id']));
        $book_id->setChangeAction(new TAction([$this,'onChangebook_id']));

        $situacao->addItems(["1"=>"Andamento","2"=>"Encerrada"]);
        $situacao->setValue('1');

        $book_id->enableSearch();
        $situacao->enableSearch();
        $stage_id->enableSearch();
        $idioma_id->enableSearch();
        $unidade_id->enableSearch();

        $id->setSize(100);
        $book_id->setSize('70%');
        $situacao->setSize('80%');
        $stage_id->setSize('80%');
        $descricao->setSize('70%');
        $idioma_id->setSize('80%');
        $unidade_id->setSize('70%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unidade_id],[new TLabel("Situação turma:", null, '14px', null)],[$situacao]);
        $row3 = $this->form->addFields([new TLabel("Pasta:", null, '14px', null)],[$descricao],[new TLabel("Idioma:", null, '14px', null)],[$idioma_id]);
        $row4 = $this->form->addFields([new TLabel("Book:", null, '14px', null)],[$book_id],[new TLabel("Stage:", null, '14px', null)],[$stage_id]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #000000');
        $this->btn_onexportcsv = $btn_onexportcsv;

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['TurmaForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_unidade_name_transformed = new TDataGridColumn('unidade->name', "Unidade", 'left');
        $column_pasta_descricao_transformed = new TDataGridColumn('pasta->descricao', "Pasta", 'left');
        $column_descricao_transformed = new TDataGridColumn('descricao', "Nome pasta", 'left');
        $column_idioma_descricao_transformed = new TDataGridColumn('idioma->descricao', "Idioma", 'left');
        $column_book_descricao_transformed = new TDataGridColumn('book->descricao', "Book", 'left');
        $column_stage_descricao_transformed = new TDataGridColumn('stage->descricao', "Stage", 'left');
        $column_situacao_transformed = new TDataGridColumn('situacao', "Em andamento?", 'center');

        $column_unidade_name_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_pasta_descricao_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_descricao_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_idioma_descricao_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_book_descricao_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_stage_descricao_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_situacao_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
            {
                return 'Sim';
            }
            elseif($value === false || $value == 'f' || $value === 0 || $value == '0' || $value == 'n' || $value == 'N' || $value == 'F')   
            {
                return 'Não';
            }

            return $value;

        });        

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_unidade_name_transformed);
        $this->datagrid->addColumn($column_pasta_descricao_transformed);
        $this->datagrid->addColumn($column_descricao_transformed);
        $this->datagrid->addColumn($column_idioma_descricao_transformed);
        $this->datagrid->addColumn($column_book_descricao_transformed);
        $this->datagrid->addColumn($column_stage_descricao_transformed);
        $this->datagrid->addColumn($column_situacao_transformed);

        $action_onEdit = new TDataGridAction(array('TurmaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('TurmaList', 'onDelete'));
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
            $container->add(TBreadCrumb::create(["Cadastros","Turmas"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public static function onChangeidioma_id($param)
    {
        try
        {

            if (isset($param['idioma_id']) && $param['idioma_id'])
            { 
                $criteria = TCriteria::create(['idioma_id' => $param['idioma_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'book_id', 'cdi', 'Book', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'book_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public static function onChangebook_id($param)
    {
        try
        {

            if (isset($param['book_id']) && $param['book_id'])
            { 
                $criteria = TCriteria::create(['book_id' => $param['book_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'stage_id', 'cdi', 'Stage', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'stage_id'); 
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
                $object = new Turma($key, FALSE); 

                $agendaPassadas = Agenda::where('turma_id', '=', $object->id)->where('horario_inicial', '<=', date('Y-m-d 23:59:59'))->first();

                if ($agendaPassadas)
                {

                    if (in_array(4, TSession::getValue('usergroupids'))) // GESTOR
                    {
                        if (! empty($param['gestor_aceitou']) AND $param['gestor_aceitou'] == 1)
                        {
                            Aula::where('agenda_id', 'in', "(SELECT id FROM agenda WHERE turma_id = {$object->id})")->delete();
                            Agenda::where('turma_id', '=', $object->id)->where('horario_inicial', '<=', date('Y-m-d 23:59:59'))->delete();
                        }
                        else
                        {
                            $param['gestor_aceitou'] = 1;
                            $action = new TAction(array($this, 'onDelete'));
                            $action->setParameters($param);
                            $action->setParameter('delete', 1);

                            new TQuestion('Essa turma que possue agendamentos passados, confirma a exclusão mesmo assim?', $action);       

                            TTransaction::close();
                            return;
                        }
                    }
                    else
                    {
                        throw new Exception('Você não pode apagar turmas que possuem agendamentos passados! Procure o seu GESTOR e informe o ID: ' .$object->id);
                    }
                }

                // Deleta agendamento futuros

                Agenda::where('turma_id', '=', $object->id)->where('horario_inicial', '>=', date('Y-m-d 00:00:00'))->delete();

                // Alunos turma
                TurmaAlunos::where('turma_id', '=', $object->id)->delete();

                // Deleta a turma
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
            if(isset($object->idioma_id))
            {
                $value = $object->idioma_id;

                $obj->idioma_id = $value;
            }
            if(isset($object->book_id))
            {
                $value = $object->book_id;

                $obj->book_id = $value;
            }
            if(isset($object->stage_id))
            {
                $value = $object->stage_id;

                $obj->stage_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->idioma_id))
            {
                $value = $object->idioma_id;

                $obj->idioma_id = $value;
            }
            if(isset($object->book_id))
            {
                $value = $object->book_id;

                $obj->book_id = $value;
            }
            if(isset($object->stage_id))
            {
                $value = $object->stage_id;

                $obj->stage_id = $value;
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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->unidade_id) AND ( (is_scalar($data->unidade_id) AND $data->unidade_id !== '') OR (is_array($data->unidade_id) AND (!empty($data->unidade_id)) )) )
        {

            $filters[] = new TFilter('unidade_id', '=', $data->unidade_id);// create the filter 
        }

        if (isset($data->situacao) AND ( (is_scalar($data->situacao) AND $data->situacao !== '') OR (is_array($data->situacao) AND (!empty($data->situacao)) )) )
        {

            $filters[] = new TFilter('situacao', '=', $data->situacao);// create the filter 
        }

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->idioma_id) AND ( (is_scalar($data->idioma_id) AND $data->idioma_id !== '') OR (is_array($data->idioma_id) AND (!empty($data->idioma_id)) )) )
        {

            $filters[] = new TFilter('idioma_id', '=', $data->idioma_id);// create the filter 
        }

        if (isset($data->book_id) AND ( (is_scalar($data->book_id) AND $data->book_id !== '') OR (is_array($data->book_id) AND (!empty($data->book_id)) )) )
        {

            $filters[] = new TFilter('book_id', '=', $data->book_id);// create the filter 
        }

        if (isset($data->stage_id) AND ( (is_scalar($data->stage_id) AND $data->stage_id !== '') OR (is_array($data->stage_id) AND (!empty($data->stage_id)) )) )
        {

            $filters[] = new TFilter('stage_id', '=', $data->stage_id);// create the filter 
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

            // creates a repository for Turma
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


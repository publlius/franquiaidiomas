<?php

class AulaSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'Aula';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Aula';
    private $limit = 20;

    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 10;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_tipo_aula_transformed = new TDataGridColumn('tipo_aula', "Tipo aula", 'left');
        $column_data_aula_transformed = new TDataGridColumn('data_aula', "Data", 'left');
        $column_ultima_palavra = new TDataGridColumn('ultima_palavra', "Ultima palavra", 'left');
        $column_ultima_pagina = new TDataGridColumn('ultima_pagina', "Ultima pagina", 'left');
        $column_observacao = new TDataGridColumn('observacao', "Observacao", 'left');
        $column_professor_name = new TDataGridColumn('professor->name', "Professor", 'left');

        $column_tipo_aula_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_data_aula_transformed->setTransformer(function($value, $object, $row) 
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

        $this->datagrid->addColumn($column_tipo_aula_transformed);
        $this->datagrid->addColumn($column_data_aula_transformed);
        $this->datagrid->addColumn($column_ultima_palavra);
        $this->datagrid->addColumn($column_ultima_pagina);
        $this->datagrid->addColumn($column_observacao);
        $this->datagrid->addColumn($column_professor_name);

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
        $panel->add($this->datagrid);
        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Lançamentos","Registros aula"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

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

            // creates a repository for Aula
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if (empty($param['order']))
            {
                $param['order'] = 'data_aula';    
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

            if(!empty($param['agenda_id']))
            {
                $sql = "(SELECT id FROM agenda WHERE CAST(horario_inicial as DATE) <  (SELECT CAST(b.horario_inicial as DATE) FROM agenda b WHERE b.id = {$param['agenda_id']}) AND turma_id = (SELECT a.turma_id FROM agenda a WHERE a.id = {$param['agenda_id']}))";
                TSession::setValue(__CLASS__.'load_filter_agenda_id', $sql);
            }
            else if (!empty($param['turma_id']) && !empty($param['data_aula']))
            {
                $sql = "(SELECT id FROM agenda WHERE CAST(horario_inicial as DATE) < CAST('{$param['data_aula']}' as DATE) AND turma_id = {$param['turma_id']})";
                TSession::setValue(__CLASS__.'load_filter_agenda_id', $sql);
            }
            else
            {
                TSession::setValue(__CLASS__.'load_filter_agenda_id', '(SELECT 0)');
            }

            $filterVar = TSession::getValue(__CLASS__.'load_filter_agenda_id');
            $criteria->add(new TFilter('agenda_id', 'IN', $filterVar));

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
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
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


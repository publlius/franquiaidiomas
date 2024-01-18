<?php

class FaturamentoChart extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'CurriculoAluno';
    private static $primaryKey = 'id';
    private static $formName = 'form_FaturamentoChart';

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Faturamento");

        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $plano_id = new TDBCombo('plano_id', 'cdi', 'Plano', 'id', '{id}','id asc'  );
        $idioma_id = new TDBCombo('idioma_id', 'cdi', 'Idioma', 'id', '{id}','id asc'  );
        $status = new TEntry('status');

        $status->setMaxLength(20);
        $status->setSize('100%');
        $plano_id->setSize('100%');
        $idioma_id->setSize('100%');
        $unidade_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unidade_id]);
        $row2 = $this->form->addFields([new TLabel("Plano:", null, '14px', null)],[$plano_id]);
        $row3 = $this->form->addFields([new TLabel("Idioma:", null, '14px', null)],[$idioma_id]);
        $row4 = $this->form->addFields([new TLabel("Status:", null, '14px', null)],[$status]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:search #ffffff');
        $this->btn_ongenerate = $btn_ongenerate;
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Gráficos","Faturamento"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->unidade_id) AND ( (is_scalar($data->unidade_id) AND $data->unidade_id !== '') OR (is_array($data->unidade_id) AND (!empty($data->unidade_id)) )) )
        {

            $filters[] = new TFilter('unidade_id', '=', $data->unidade_id);// create the filter 
        }
        if (isset($data->plano_id) AND ( (is_scalar($data->plano_id) AND $data->plano_id !== '') OR (is_array($data->plano_id) AND (!empty($data->plano_id)) )) )
        {

            $filters[] = new TFilter('plano_id', '=', $data->plano_id);// create the filter 
        }
        if (isset($data->idioma_id) AND ( (is_scalar($data->idioma_id) AND $data->idioma_id !== '') OR (is_array($data->idioma_id) AND (!empty($data->idioma_id)) )) )
        {

            $filters[] = new TFilter('idioma_id', '=', $data->idioma_id);// create the filter 
        }
        if (isset($data->status) AND ( (is_scalar($data->status) AND $data->status !== '') OR (is_array($data->status) AND (!empty($data->status)) )) )
        {

            $filters[] = new TFilter('status', '=', $data->status);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);
    }

    /**
     * Load the datagrid with data
     */
    public function onGenerate()
    {
        try
        {
            $this->onSearch();
            // open a transaction with database 'cdi'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for CurriculoAluno
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if ($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {

                $dataTotals = [];
                $groups = [];
                $data = [];
                foreach ($objects as $obj)
                {
                    $group1 = $obj->idioma->descricao;

                    $groups[$group1] = true;
                    $numericField = $obj->plano->valor;

                    $dataTotals[$group1]['count'] = isset($dataTotals[$group1]['count']) ? $dataTotals[$group1]['count'] + 1 : 1;
                    $dataTotals[$group1]['sum'] = isset($dataTotals[$group1]['sum']) ? $dataTotals[$group1]['sum'] + $numericField  : $numericField;

                }

                $groups = ['x'=>true]+$groups;

                foreach ($dataTotals as $group1 => $totals) 
                {    

                    array_push($data, [$group1, $totals['sum']]);

                }

                $chart = new THtmlRenderer('app/resources/c3_pizza_chart.html');
                $chart->enableSection('main', [
                    'data'=> json_encode($data),
                    'height' => 300,
                    'precision' => 2,
                    'decimalSeparator' => ',',
                    'thousandSeparator' => '.',
                    'prefix' => 'R$ ',
                    'sufix' => '',
                    'width' => 100,
                    'widthType' => '%',
                    'title' => 'Faturamento',
                    'showLegend' => 'false',
                    'showPercentage' => 'false',
                    'barDirection' => 'false'
                ]);

                parent::add($chart);
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
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

}


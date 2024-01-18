<?php

class AulaDadaReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'AulaDada';
    private static $primaryKey = 'aula_id';
    private static $formName = 'form_AulaDadaReport';

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
        $this->form->setFormTitle("Aulas dadas");

        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $professor_id = new TDBCombo('professor_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $data_aula = new TDate('data_aula');
        $data_aula_fim = new TDate('data_aula_fim');

        $unidade_id->enableSearch();
        $professor_id->enableSearch();

        $data_aula->setMask('dd/mm/yyyy');
        $data_aula_fim->setMask('dd/mm/yyyy');

        $data_aula->setDatabaseMask('yyyy-mm-dd');
        $data_aula_fim->setDatabaseMask('yyyy-mm-dd');

        $data_aula->setSize(150);
        $unidade_id->setSize('100%');
        $data_aula_fim->setSize(150);
        $professor_id->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unidade_id]);
        $row2 = $this->form->addFields([new TLabel("Professor:", null, '14px', null)],[$professor_id]);
        $row3 = $this->form->addFields([new TLabel("Aulas de:", null, '14px', null)],[$data_aula],[new TLabel("até:", null, '14px', null)],[$data_aula_fim]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongeneratehtml = $this->form->addAction("Gerar HTML", new TAction([$this, 'onGenerateHtml']), 'far:file-code #ffffff');
        $this->btn_ongeneratehtml = $btn_ongeneratehtml;
        $btn_ongeneratehtml->addStyleClass('btn-primary'); 

        $btn_ongeneratepdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onGeneratePdf']), 'far:file-pdf #d44734');
        $this->btn_ongeneratepdf = $btn_ongeneratepdf;

        $btn_ongeneratexls = $this->form->addAction("Gerar XLS", new TAction([$this, 'onGenerateXls']), 'far:file-excel #00a65a');
        $this->btn_ongeneratexls = $btn_ongeneratexls;

        $btn_ongeneratertf = $this->form->addAction("Gerar RTF", new TAction([$this, 'onGenerateRtf']), 'far:file-alt #324bcc');
        $this->btn_ongeneratertf = $btn_ongeneratertf;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Relatórios","Aulas dadas"]));
        $container->add($this->form);

        parent::add($container);

    }

    public function onGenerateHtml($param = null) 
    {
        $this->onGenerate('html');
    }

    public function onGeneratePdf($param = null) 
    {
        $this->onGenerate('pdf');
    }

    public function onGenerateXls($param = null) 
    {
        $this->onGenerate('xls');
    }

    public function onGenerateRtf($param = null) 
    {
        $this->onGenerate('rtf');
    }

    /**
     * Register the filter in the session
     */
    public function getFilters()
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
        if (isset($data->professor_id) AND ( (is_scalar($data->professor_id) AND $data->professor_id !== '') OR (is_array($data->professor_id) AND (!empty($data->professor_id)) )) )
        {

            $filters[] = new TFilter('professor_id', '=', $data->professor_id);// create the filter 
        }
        if (isset($data->data_aula) AND ( (is_scalar($data->data_aula) AND $data->data_aula !== '') OR (is_array($data->data_aula) AND (!empty($data->data_aula)) )) )
        {

            $filters[] = new TFilter('data_aula', '>=', $data->data_aula);// create the filter 
        }
        if (isset($data->data_aula_fim) AND ( (is_scalar($data->data_aula_fim) AND $data->data_aula_fim !== '') OR (is_array($data->data_aula_fim) AND (!empty($data->data_aula_fim)) )) )
        {

            $filters[] = new TFilter('data_aula', '<=', $data->data_aula_fim);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);

        return $filters;
    }

    public function onGenerate($format)
    {
        try
        {
            $filters = $this->getFilters();
            // open a transaction with database 'cdi'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for AulaDada
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'professor,data_aula';
            $param['direction'] = 'asc';

            $criteria->setProperties($param);

            if ($filters)
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
                $widths = array(80,40,350,150,200,100);

                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'L', 'A4');
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths, 'L', 'A4');
                        break;
                }

                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Helvetica', '10', 'B',   '#000000', '#dbdbdb');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#333333', '#f0f0f0');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#333333', '#ffffff');
                    $tr->addStyle('header', 'Helvetica', '16', 'B',   '#5a5a5a', '#6B6B6B');
                    $tr->addStyle('footer', 'Helvetica', '10', 'B',  '#5a5a5a', '#A3A3A3');
                    $tr->addStyle('break', 'Helvetica', '10', 'B',  '#ffffff', '#9a9a9a');
                    $tr->addStyle('total', 'Helvetica', '10', 'I',  '#000000', '#c7c7c7');
                    $tr->addStyle('breakTotal', 'Helvetica', '10', 'I',  '#000000', '#c6c8d0');

                    // add titles row
                    $tr->addRow();
                    $tr->addCell("Data", 'left', 'title');
                    $tr->addCell("Tipo", 'left', 'title');
                    $tr->addCell("Turma/Aluno", 'left', 'title');
                    $tr->addCell("Unidade", 'left', 'title');
                    $tr->addCell("Professor", 'left', 'title');
                    $tr->addCell("Valor", 'right', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->professor !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_remuneracao = array_sum($breakTotal['remuneracao']);

                                $breakTotal_remuneracao = call_user_func(function($value)
                                {
                                    if(!$value)
                                    {
                                        $value = 0;
                                    }

                                    if(is_numeric($value))
                                    {
                                        return "R$ " . number_format($value, 2, ",", ".");
                                    }
                                    else
                                    {
                                        return $value;
                                    }
                                }, $breakTotal_remuneracao); 

                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_remuneracao, 'right', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{professor}'), 'left', 'break', 6);
                            $breakTotal = [];
                        }
                        $breakValue = $object->professor;

                        $grandTotal['remuneracao'][] = $object->remuneracao;
                        $breakTotal['remuneracao'][] = $object->remuneracao;

                        $firstRow = false;

                        $object->data_aula = call_user_func(function($value, $object, $row) 
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
                        }, $object->data_aula, $object, null);

                        $object->tipo_aula = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->tipo_aula, $object, null);

                        $object->turma = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->turma, $object, null);

                        $object->unidade = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->unidade, $object, null);

                        $object->professor = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->professor, $object, null);

                        $object->remuneracao = call_user_func(function($value, $object, $row) 
                        {
                            if(!$value)
                            {
                                $value = 0;
                            }

                            if(is_numeric($value))
                            {
                                return "R$ " . number_format($value, 2, ",", ".");
                            }
                            else
                            {
                                return $value;
                            }
                        }, $object->remuneracao, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->data_aula, 'left', $style);
                        $tr->addCell($object->tipo_aula, 'left', $style);
                        $tr->addCell($object->turma, 'left', $style);
                        $tr->addCell($object->unidade, 'left', $style);
                        $tr->addCell($object->professor, 'left', $style);
                        $tr->addCell($object->remuneracao, 'right', $style);

                        $colour = !$colour;

                    }

                    $tr->addRow();

                    $breakTotal_remuneracao = array_sum($breakTotal['remuneracao']);

                    $breakTotal_remuneracao = call_user_func(function($value)
                    {
                        if(!$value)
                        {
                            $value = 0;
                        }

                        if(is_numeric($value))
                        {
                            return "R$ " . number_format($value, 2, ",", ".");
                        }
                        else
                        {
                            return $value;
                        }
                    }, $breakTotal_remuneracao); 

                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_remuneracao, 'right', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_remuneracao = array_sum($grandTotal['remuneracao']);

                    $grandTotal_remuneracao = call_user_func(function($value)
                    {
                        if(!$value)
                        {
                            $value = 0;
                        }

                        if(is_numeric($value))
                        {
                            return "R$ " . number_format($value, 2, ",", ".");
                        }
                        else
                        {
                            return $value;
                        }
                    }, $grandTotal_remuneracao); 

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_remuneracao, 'right', 'total');

                    $file = 'report_'.uniqid().".{$format}";
                    // stores the file
                    if (!file_exists("app/output/{$file}") || is_writable("app/output/{$file}"))
                    {
                        $tr->save("app/output/{$file}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/{$file}");
                    }

                    parent::openFile("app/output/{$file}");

                    // shows the success message
                    new TMessage('info', _t('Report generated. Please, enable popups'));
                }
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


<?php

class FaturamentoMensalReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'FaturamentoMensal';
    private static $primaryKey = 'aluno_id';
    private static $formName = 'form_FaturamentoMensalReport';

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
        $this->form->setFormTitle("Faturamento Mensal");

        $unidade = new TDBCombo('unidade', 'cdi', 'FaturamentoMensal', 'unidade', '{unidade}','unidade asc'  );
        $plano_descricao = new TDBCombo('plano_descricao', 'cdi', 'FaturamentoMensal', 'plano_descricao', '{plano_descricao}','plano_descricao asc'  );
        $convenio_descricao = new TDBCombo('convenio_descricao', 'cdi', 'FaturamentoMensal', 'convenio_descricao', '{convenio_descricao}','convenio_descricao asc'  );

        $unidade->addValidation("Unidade:", new TRequiredValidator()); 

        $unidade->setSize('100%');
        $plano_descricao->setSize('100%');
        $convenio_descricao->setSize('100%');

        $unidade->enableSearch();
        $plano_descricao->enableSearch();
        $convenio_descricao->enableSearch();

        $row1 = $this->form->addFields([new TLabel("Unidade:", '#F44336', '14px', null)],[$unidade]);
        $row2 = $this->form->addFields([new TLabel("Plano:", null, '14px', null)],[$plano_descricao]);
        $row3 = $this->form->addFields([new TLabel("Convênio:", null, '14px', null)],[$convenio_descricao]);

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
        $container->add(TBreadCrumb::create(["Relatórios","Faturamento Mensal"]));
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

        if (isset($data->unidade) AND ( (is_scalar($data->unidade) AND $data->unidade !== '') OR (is_array($data->unidade) AND (!empty($data->unidade)) )) )
        {

            $filters[] = new TFilter('unidade', 'like', "%{$data->unidade}%");// create the filter 
        }
        if (isset($data->plano_descricao) AND ( (is_scalar($data->plano_descricao) AND $data->plano_descricao !== '') OR (is_array($data->plano_descricao) AND (!empty($data->plano_descricao)) )) )
        {

            $filters[] = new TFilter('plano_descricao', 'like', "%{$data->plano_descricao}%");// create the filter 
        }
        if (isset($data->convenio_descricao) AND ( (is_scalar($data->convenio_descricao) AND $data->convenio_descricao !== '') OR (is_array($data->convenio_descricao) AND (!empty($data->convenio_descricao)) )) )
        {

            $filters[] = new TFilter('convenio_descricao', 'like', "%{$data->convenio_descricao}%");// create the filter 
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
            // creates a repository for FaturamentoMensal
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'aluno_nome';
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
                $widths = array(200,200,200,200,200,200,200);

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
                    $tr->addCell("Id", 'left', 'title');
                    $tr->addCell("Aluno", 'left', 'title');
                    $tr->addCell("Plano", 'left', 'title');
                    $tr->addCell("Convênio", 'left', 'title');
                    $tr->addCell("Status", 'left', 'title');
                    $tr->addCell("Unidade", 'left', 'title');
                    $tr->addCell("R$ Plano", 'right', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->aluno_nome !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_plano_valor = array_sum($breakTotal['plano_valor']);

                                $breakTotal_plano_valor = call_user_func(function($value)
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
                                }, $breakTotal_plano_valor); 

                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_plano_valor, 'right', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{aluno_nome}'), 'left', 'break', 7);
                            $breakTotal = [];
                        }
                        $breakValue = $object->aluno_nome;

                        $grandTotal['plano_valor'][] = $object->plano_valor;
                        $breakTotal['plano_valor'][] = $object->plano_valor;

                        $firstRow = false;

                        $object->plano_valor = call_user_func(function($value, $object, $row) 
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
                        }, $object->plano_valor, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->aluno_id, 'left', $style);
                        $tr->addCell($object->aluno_nome, 'left', $style);
                        $tr->addCell($object->plano_descricao, 'left', $style);
                        $tr->addCell($object->convenio_descricao, 'left', $style);
                        $tr->addCell($object->situacao_status, 'left', $style);
                        $tr->addCell($object->unidade, 'left', $style);
                        $tr->addCell($object->plano_valor, 'right', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $breakTotal_plano_valor = array_sum($breakTotal['plano_valor']);

                    $breakTotal_plano_valor = call_user_func(function($value)
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
                    }, $breakTotal_plano_valor); 

                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_plano_valor, 'right', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_plano_valor = array_sum($grandTotal['plano_valor']);

                    $grandTotal_plano_valor = call_user_func(function($value)
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
                    }, $grandTotal_plano_valor); 

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_plano_valor, 'right', 'total');

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


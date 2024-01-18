<?php

class ContratoAlunoTotalReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'ContratoAlunoParcela';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContratoAlunoParcelaReport';

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
        $this->form->setFormTitle("Contratos Alunos");

        $criteria_unidade_id = new TCriteria();

        $filterVar = TSession::getValue("userunitids");
        $criteria_unidade_id->add(new TFilter('id', 'in', $filterVar)); 

        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc' , $criteria_unidade_id );
        $contrato_aluno_id = new TDBCombo('contrato_aluno_id', 'cdi', 'ContratoAluno', 'id', '{curriculo_aluno->aluno->nome}','id asc'  );
        $data_vencimento = new TDate('data_vencimento');
        $data_vencimento_ate = new TDate('data_vencimento_ate');

        $unidade_id->enableSearch();
        $contrato_aluno_id->enableSearch();

        $data_vencimento->setMask('dd/mm/yyyy');
        $data_vencimento_ate->setMask('dd/mm/yyyy');

        $data_vencimento->setDatabaseMask('yyyy-mm-dd');
        $data_vencimento_ate->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(100);
        $unidade_id->setSize('100%');
        $data_vencimento->setSize(110);
        $data_vencimento_ate->setSize(110);
        $contrato_aluno_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Unidade:", null, '14px', null)],[$unidade_id]);
        $row2 = $this->form->addFields([new TLabel("Aluno:", null, '14px', null)],[$contrato_aluno_id]);
        $row3 = $this->form->addFields([new TLabel("Vencimento de:", null, '14px', null)],[$data_vencimento],[new TLabel("até:", null, '14px', null)],[$data_vencimento_ate]);

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
        $container->add(TBreadCrumb::create(["Relatórios","Contratos Alunos (total)"]));
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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }
        if (isset($data->unidade_id) AND ( (is_scalar($data->unidade_id) AND $data->unidade_id !== '') OR (is_array($data->unidade_id) AND (!empty($data->unidade_id)) )) )
        {

            $filters[] = new TFilter('contrato_aluno_id', 'in', "(SELECT id FROM contrato_aluno WHERE curriculo_aluno_id in (SELECT id FROM curriculo_aluno WHERE unidade_id = '{$data->unidade_id}'))");// create the filter 
        }
        if (isset($data->contrato_aluno_id) AND ( (is_scalar($data->contrato_aluno_id) AND $data->contrato_aluno_id !== '') OR (is_array($data->contrato_aluno_id) AND (!empty($data->contrato_aluno_id)) )) )
        {

            $filters[] = new TFilter('contrato_aluno_id', '=', $data->contrato_aluno_id);// create the filter 
        }
        if (isset($data->data_vencimento) AND ( (is_scalar($data->data_vencimento) AND $data->data_vencimento !== '') OR (is_array($data->data_vencimento) AND (!empty($data->data_vencimento)) )) )
        {

            $filters[] = new TFilter('data_vencimento', '>=', $data->data_vencimento);// create the filter 
        }
        if (isset($data->data_vencimento_ate) AND ( (is_scalar($data->data_vencimento_ate) AND $data->data_vencimento_ate !== '') OR (is_array($data->data_vencimento_ate) AND (!empty($data->data_vencimento_ate)) )) )
        {

            $filters[] = new TFilter('data_vencimento', '<=', $data->data_vencimento_ate);// create the filter 
        }

        if (isset($data->dt_recebimento) AND ( (is_scalar($data->dt_recebimento) AND $data->dt_recebimento !== '') OR (is_array($data->dt_recebimento) AND (!empty($data->dt_recebimento)) )) )
        {
            $whap_else = '2020-01-01';
            if($data->dt_recebimento == 1)
            {
                $filters[] = new TFilter('data_recebimento', 'IS',null);// create the filter 
            }
            else
            {
                $filters[] = new TFilter('data_recebimento', '>=',$whap_else );// create the filter 
            }            
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
            // creates a repository for ContratoAlunoParcela
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'contrato_aluno_id';
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
                $widths = array(200,200,300,200,200,200,200,200,200,200,200);

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
                    $tr->addCell("Unidade", 'left', 'title');
                    $tr->addCell("Aluno", 'left', 'title');
                    $tr->addCell("Plano", 'left', 'title');
                    $tr->addCell("R$ Plano", 'right', 'title');
                    $tr->addCell("R$ Contrato", 'right', 'title');
                    $tr->addCell("Parcela", 'center', 'title');
                    $tr->addCell("Vence em", 'center', 'title');
                    $tr->addCell("Recebido em", 'center', 'title');
                    $tr->addCell("Forma", 'center', 'title');
                    $tr->addCell("R$ Recebido", 'right', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->contrato_aluno_id !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_id = count($breakTotal['id']);
                                $breakTotal_valor_real = array_sum($breakTotal['valor_real']);
                                $breakTotal_valor = array_sum($breakTotal['valor']);
                                $breakTotal_valor_recebido = array_sum($breakTotal['valor_recebido']);

                                $breakTotal_valor_real = call_user_func(function($value)
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
                                }, $breakTotal_valor_real); 

                                $breakTotal_valor = call_user_func(function($value)
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
                                }, $breakTotal_valor); 

                                $breakTotal_valor_recebido = call_user_func(function($value)
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
                                }, $breakTotal_valor_recebido); 

                                $tr->addCell($breakTotal_id, 'left', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_valor_real, 'right', 'breakTotal');
                                $tr->addCell($breakTotal_valor, 'right', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_valor_recebido, 'right', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{id} - {contrato_aluno->curriculo_aluno->aluno->nome} | {contrato_aluno->curriculo_aluno->idioma->descricao}'), 'left', 'break', 11);
                            $breakTotal = [];
                        }
                        $breakValue = $object->contrato_aluno_id;

                        $grandTotal['id'][] = $object->id;
                        $breakTotal['id'][] = $object->id;
                        $grandTotal['valor_real'][] = $object->valor_real;
                        $breakTotal['valor_real'][] = $object->valor_real;
                        $grandTotal['valor'][] = $object->valor;
                        $breakTotal['valor'][] = $object->valor;
                        $grandTotal['valor_recebido'][] = $object->valor_recebido;
                        $breakTotal['valor_recebido'][] = $object->valor_recebido;

                        $firstRow = false;

                        $object->contrato_aluno->curriculo_aluno->unidade->name = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->contrato_aluno->curriculo_aluno->unidade->name, $object, null);

                        $object->contrato_aluno->curriculo_aluno->aluno->nome = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->contrato_aluno->curriculo_aluno->aluno->nome, $object, null);

                        $object->contrato_aluno->curriculo_aluno->plano->descricao = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->contrato_aluno->curriculo_aluno->plano->descricao, $object, null);

                        $object->valor_real = call_user_func(function($value, $object, $row) 
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
                        }, $object->valor_real, $object, null);

                        $object->valor = call_user_func(function($value, $object, $row) 
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
                        }, $object->valor, $object, null);

                        $object->data_vencimento = call_user_func(function($value, $object, $row) 
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
                        }, $object->data_vencimento, $object, null);

                        $object->data_recebimento = call_user_func(function($value, $object, $row) 
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
                        }, $object->data_recebimento, $object, null);

                        $object->forma_pagamento->descricao = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->forma_pagamento->descricao, $object, null);

                        $object->valor_recebido = call_user_func(function($value, $object, $row) 
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
                        }, $object->valor_recebido, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->contrato_aluno->curriculo_aluno->unidade->name, 'left', $style);
                        $tr->addCell($object->contrato_aluno->curriculo_aluno->aluno->nome, 'left', $style);
                        $tr->addCell($object->contrato_aluno->curriculo_aluno->plano->descricao, 'left', $style);
                        $tr->addCell($object->valor_real, 'right', $style);
                        $tr->addCell($object->valor, 'right', $style);
                        $tr->addCell($object->parcela, 'center', $style);
                        $tr->addCell($object->data_vencimento, 'center', $style);
                        $tr->addCell($object->data_recebimento, 'center', $style);
                        $tr->addCell($object->forma_pagamento->descricao, 'center', $style);
                        $tr->addCell($object->valor_recebido, 'right', $style);

                        $colour = !$colour;

                    }

                    $tr->addRow();

                    $breakTotal_id = count($breakTotal['id']);
                    $breakTotal_valor_real = array_sum($breakTotal['valor_real']);
                    $breakTotal_valor = array_sum($breakTotal['valor']);
                    $breakTotal_valor_recebido = array_sum($breakTotal['valor_recebido']);

                    $breakTotal_valor_real = call_user_func(function($value)
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
                    }, $breakTotal_valor_real); 

                    $breakTotal_valor = call_user_func(function($value)
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
                    }, $breakTotal_valor); 

                    $breakTotal_valor_recebido = call_user_func(function($value)
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
                    }, $breakTotal_valor_recebido); 

                    $tr->addCell($breakTotal_id, 'left', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_valor_real, 'right', 'breakTotal');
                    $tr->addCell($breakTotal_valor, 'right', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_valor_recebido, 'right', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_id = count($grandTotal['id']);
                    $grandTotal_valor_real = array_sum($grandTotal['valor_real']);
                    $grandTotal_valor = array_sum($grandTotal['valor']);
                    $grandTotal_valor_recebido = array_sum($grandTotal['valor_recebido']);

                    $grandTotal_valor_real = call_user_func(function($value)
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
                    }, $grandTotal_valor_real); 

                    $grandTotal_valor = call_user_func(function($value)
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
                    }, $grandTotal_valor); 

                    $grandTotal_valor_recebido = call_user_func(function($value)
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
                    }, $grandTotal_valor_recebido); 

                    $tr->addCell($grandTotal_id, 'left', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_valor_real, 'right', 'total');
                    $tr->addCell($grandTotal_valor, 'right', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_valor_recebido, 'right', 'total');

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

    public static function onYes($param = null) 
    {
        try 
        {
            //code here
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onNo($param = null) 
    {
        try 
        {
            //code here
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

}


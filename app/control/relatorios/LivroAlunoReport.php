<?php

class LivroAlunoReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'CurriculoAluno';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_CurriculoAluno';

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
        $this->form->setFormTitle("Livros por aluno");

        $id = new TDBCombo('id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $status = new TDBCombo('status', 'cdi', 'Situacao', 'status', '{status}','status asc'  );
        $idioma_id = new TDBCombo('idioma_id', 'cdi', 'Idioma', 'id', '{descricao}','descricao asc'  );
        $book_id = new TCombo('book_id');
        $stage_id = new TCombo('stage_id');

        $idioma_id->setChangeAction(new TAction([$this,'onChangeidioma_id']));
        $book_id->setChangeAction(new TAction([$this,'onChangebook_id']));

        $id->setSize('100%');
        $status->setSize('100%');
        $book_id->setSize('100%');
        $stage_id->setSize('100%');
        $idioma_id->setSize('100%');

        $id->enableSearch();
        $status->enableSearch();
        $book_id->enableSearch();
        $stage_id->enableSearch();
        $idioma_id->enableSearch();

        $row1 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$id],[new TLabel("Situação:", null, '14px', null)],[$status]);
        $row2 = $this->form->addFields([new TLabel("Idioma:", null, '14px', null)],[$idioma_id]);
        $row3 = $this->form->addFields([new TLabel("Book:", null, '14px', null)],[$book_id]);
        $row4 = $this->form->addFields([new TLabel("Stage:", null, '14px', null)],[$stage_id]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

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
        $container->add(TBreadCrumb::create(["Relatórios","Livros por aluno"]));
        $container->add($this->form);

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

            $filters[] = new TFilter('unidade_id', '=', $data->id);// create the filter 
        }
        if (isset($data->status) AND ( (is_scalar($data->status) AND $data->status !== '') OR (is_array($data->status) AND (!empty($data->status)) )) )
        {

            $filters[] = new TFilter('status', '=', $data->status);// create the filter 
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

        // fill the form with data again
        $this->form->setData($data);
        $this->fireEvents($data);

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
            // creates a repository for CurriculoAluno
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $filterVar = "1";
            $criteria->add(new TFilter('aluno_id', 'in', "(SELECT id FROM aluno WHERE situacao_id = '{$filterVar}')"));

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
                $widths = array(150,160,150,160,300,200);

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
                    $tr->addCell("Unidade", 'left', 'title');
                    $tr->addCell("Idioma", 'left', 'title');
                    $tr->addCell("Book", 'left', 'title');
                    $tr->addCell("Stage", 'left', 'title');
                    $tr->addCell("Aluno", 'left', 'title');
                    $tr->addCell("Status", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        $firstRow = false;

                        $object->book->idioma->descricao = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->book->idioma->descricao, $object, null);

                        $object->book->descricao = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->book->descricao, $object, null);

                        $object->stage->descricao = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->stage->descricao, $object, null);

                        $object->aluno->nome = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->aluno->nome, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->unidade->name, 'left', $style);
                        $tr->addCell($object->book->idioma->descricao, 'left', $style);
                        $tr->addCell($object->book->descricao, 'left', $style);
                        $tr->addCell($object->stage->descricao, 'left', $style);
                        $tr->addCell($object->aluno->nome, 'left', $style);
                        $tr->addCell($object->status, 'left', $style);

                        $colour = !$colour;
                    }

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


}


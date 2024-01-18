<?php

class UsuarioReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'cdi';
    private static $activeRecord = 'Usuario';
    private static $primaryKey = 'id_user';
    private static $formName = 'form_UsuarioReport';

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
        $this->form->setFormTitle("Usuários");

        $unit = new TDBCombo('unit', 'cdi', 'Usuario', 'unit', '{unit}','unit asc'  );
        $active = new TCombo('active');
        $name_user = new TEntry('name_user');
        $login = new TEntry('login');

        $active->addItems(["Y"=>"Sim","N"=>"Não"]);
        $unit->enableSearch();
        $active->enableSearch();

        $unit->setSize('100%');
        $login->setSize('100%');
        $active->setSize('100%');
        $name_user->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unit]);
        $row2 = $this->form->addFields([new TLabel("Usuário ativo?", null, '14px', null)],[$active]);
        $row3 = $this->form->addFields([new TLabel("Nome:", null, '14px', null)],[$name_user],[new TLabel("Login:", null, '14px', null)],[$login]);

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
        $container->add(TBreadCrumb::create(["Relatórios","Usuários"]));
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

        if (isset($data->unit) AND ( (is_scalar($data->unit) AND $data->unit !== '') OR (is_array($data->unit) AND (!empty($data->unit)) )) )
        {

            $filters[] = new TFilter('unit', 'like', "%{$data->unit}%");// create the filter 
        }
        if (isset($data->active) AND ( (is_scalar($data->active) AND $data->active !== '') OR (is_array($data->active) AND (!empty($data->active)) )) )
        {

            $filters[] = new TFilter('active', 'like', "%{$data->active}%");// create the filter 
        }
        if (isset($data->name_user) AND ( (is_scalar($data->name_user) AND $data->name_user !== '') OR (is_array($data->name_user) AND (!empty($data->name_user)) )) )
        {

            $filters[] = new TFilter('name_user', 'like', "%{$data->name_user}%");// create the filter 
        }
        if (isset($data->login) AND ( (is_scalar($data->login) AND $data->login !== '') OR (is_array($data->login) AND (!empty($data->login)) )) )
        {

            $filters[] = new TFilter('login', 'like', "%{$data->login}%");// create the filter 
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
            // creates a repository for Usuario
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'unit,unit';
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
                $widths = array(60,200,200,200,200,90);

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
                    $tr->addCell("Nome", 'left', 'title');
                    $tr->addCell("Login", 'left', 'title');
                    $tr->addCell("Email", 'left', 'title');
                    $tr->addCell("Unidade", 'left', 'title');
                    $tr->addCell("Ativo", 'center', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->unit !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{unit}'), 'left', 'break', 6);
                            $breakTotal = [];
                        }
                        $breakValue = $object->unit;

                        $firstRow = false;

                        $object->name_user = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->name_user, $object, null);

                        $object->login = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->login, $object, null);

                        $object->email = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->email, $object, null);

                        $object->unit = call_user_func(function($value, $object, $row) 
                        {
                            if($value)
                            {
                                return mb_strtoupper($value);
                            }
                        }, $object->unit, $object, null);

                        $object->active = call_user_func(function($value, $object, $row) 
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

                        }, $object->active, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->id_user, 'left', $style);
                        $tr->addCell($object->name_user, 'left', $style);
                        $tr->addCell($object->login, 'left', $style);
                        $tr->addCell($object->email, 'left', $style);
                        $tr->addCell($object->unit, 'left', $style);
                        $tr->addCell($object->active, 'center', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();


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


<?php

class AlunoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Aluno';
    private static $primaryKey = 'id';
    private static $formName = 'form_Aluno';

    use Adianti\Base\AdiantiMasterDetailTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de aluno");

        $criteria_unidade_id = new TCriteria();

        $filterVar = TSession::getValue("userunitids");
        $criteria_unidade_id->add(new TFilter('id', 'in', $filterVar)); 

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $data_nascimento = new TEntry('data_nascimento');
        $rg = new TEntry('rg');
        $cpf = new TEntry('cpf');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc' , $criteria_unidade_id );
        $convenio_id = new TDBCombo('convenio_id', 'cdi', 'Convenio', 'id', '{descricao} - {unidade->name} - {desconto} ','descricao asc'  );
        $fone_1 = new TEntry('fone_1');
        $fone_2 = new TEntry('fone_2');
        $endereco = new TEntry('endereco');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cep = new TEntry('cep');
        $estado_id = new TDBCombo('estado_id', 'cdi', 'Estado', 'id', '{uf}','uf asc'  );
        $cidade_id = new TCombo('cidade_id');
        $situacao_id = new TDBCombo('situacao_id', 'cdi', 'Situacao', 'id', '{status}','status asc'  );
        $email = new TEntry('email');
        $responsavel_aluno_aluno_nome = new TEntry('responsavel_aluno_aluno_nome');
        $responsavel_aluno_aluno_parentesco = new TEntry('responsavel_aluno_aluno_parentesco');
        $responsavel_aluno_aluno_rg = new TEntry('responsavel_aluno_aluno_rg');
        $responsavel_aluno_aluno_cpf = new TEntry('responsavel_aluno_aluno_cpf');
        $responsavel_aluno_aluno_fone_1 = new TEntry('responsavel_aluno_aluno_fone_1');
        $responsavel_aluno_aluno_fone_2 = new TEntry('responsavel_aluno_aluno_fone_2');
        $curriculo_aluno_aluno_data_matricula = new TDate('curriculo_aluno_aluno_data_matricula');
        $curriculo_aluno_aluno_unidade_id = new TDBCombo('curriculo_aluno_aluno_unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $curriculo_aluno_aluno_idioma_id = new TDBCombo('curriculo_aluno_aluno_idioma_id', 'cdi', 'Idioma', 'id', '{descricao}','descricao asc'  );
        $curriculo_aluno_aluno_book_id = new TCombo('curriculo_aluno_aluno_book_id');
        $curriculo_aluno_aluno_stage_id = new TCombo('curriculo_aluno_aluno_stage_id');
        $curriculo_aluno_aluno_plano_id = new TCombo('curriculo_aluno_aluno_plano_id');
        $curriculo_aluno_aluno_status = new TRadioGroup('curriculo_aluno_aluno_status');
        $curriculo_aluno_aluno_qtd_hora = new TNumeric('curriculo_aluno_aluno_qtd_hora', '0', '', '' );
        $curriculo_aluno_aluno_valor_parcela = new TNumeric('curriculo_aluno_aluno_valor_parcela', '2', ',', '.' );
        $curriculo_aluno_aluno_qtd_parcela = new TNumeric('curriculo_aluno_aluno_qtd_parcela', '0', '', '' );
        $curriculo_aluno_aluno_observacao = new TEntry('curriculo_aluno_aluno_observacao');
        $responsavel_aluno_aluno_id = new THidden('responsavel_aluno_aluno_id');
        $curriculo_aluno_aluno_id = new THidden('curriculo_aluno_aluno_id');

        $estado_id->setChangeAction(new TAction([$this,'onChangeestado_id']));
        $curriculo_aluno_aluno_idioma_id->setChangeAction(new TAction([$this,'onChangecurriculo_aluno_aluno_idioma_id']));
        $curriculo_aluno_aluno_book_id->setChangeAction(new TAction([$this,'onChangecurriculo_aluno_aluno_book_id']));
        $unidade_id->setChangeAction(new TAction([$this,'onChangeunidade_id']));
        $curriculo_aluno_aluno_plano_id->setChangeAction(new TAction([$this,'onValorPlano']));

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $cpf->addValidation("Cpf:", new TCPFValidator(), []); 
        $email->addValidation("Email:", new TEmailValidator(), []); 

        $id->setEditable(false);
        $curriculo_aluno_aluno_data_matricula->setDatabaseMask('yyyy-mm-dd');
        $curriculo_aluno_aluno_plano_id->setDefaultOption(false);
        $curriculo_aluno_aluno_status->addItems(["Ativo"=>"Ativo","Inativo"=>"Inativo"]);
        $curriculo_aluno_aluno_status->setLayout('horizontal');
        $curriculo_aluno_aluno_status->setUseButton();

        $curriculo_aluno_aluno_qtd_parcela->setAllowNegative(false);
        $curriculo_aluno_aluno_valor_parcela->setAllowNegative(false);

        $curriculo_aluno_aluno_book_id->enableSearch();
        $curriculo_aluno_aluno_plano_id->enableSearch();
        $curriculo_aluno_aluno_idioma_id->enableSearch();
        $curriculo_aluno_aluno_unidade_id->enableSearch();

        $cep->setMask('99999-999');
        $cpf->setMask('999.999.999-99');
        $data_nascimento->setMask('99/99/9999');
        $responsavel_aluno_aluno_cpf->setMask('999.999.999-99');
        $curriculo_aluno_aluno_data_matricula->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $rg->setSize('72%');
        $cpf->setSize('73%');
        $cep->setSize('70%');
        $nome->setSize('72%');
        $numero->setSize('70%');
        $email->setSize('100%');
        $fone_1->setSize('70%');
        $fone_2->setSize('70%');
        $bairro->setSize('70%');
        $endereco->setSize('73%');
        $estado_id->setSize('70%');
        $cidade_id->setSize('70%');
        $unidade_id->setSize('70%');
        $convenio_id->setSize('70%');
        $situacao_id->setSize('70%');
        $data_nascimento->setSize(110);
        $responsavel_aluno_aluno_rg->setSize('72%');
        $responsavel_aluno_aluno_cpf->setSize('72%');
        $responsavel_aluno_aluno_nome->setSize('72%');
        $curriculo_aluno_aluno_status->setSize('100%');
        $responsavel_aluno_aluno_fone_1->setSize('72%');
        $responsavel_aluno_aluno_fone_2->setSize('72%');
        $curriculo_aluno_aluno_book_id->setSize('100%');
        $curriculo_aluno_aluno_qtd_hora->setSize('29%');
        $curriculo_aluno_aluno_stage_id->setSize('100%');
        $curriculo_aluno_aluno_plano_id->setSize('100%');
        $curriculo_aluno_aluno_idioma_id->setSize('100%');
        $curriculo_aluno_aluno_unidade_id->setSize('100%');
        $curriculo_aluno_aluno_observacao->setSize('100%');
        $responsavel_aluno_aluno_parentesco->setSize('72%');
        $curriculo_aluno_aluno_data_matricula->setSize(110);
        $curriculo_aluno_aluno_qtd_parcela->setSize('100%');
        $curriculo_aluno_aluno_valor_parcela->setSize('32%');

        $this->form->appendPage("Dados cadastrais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Nome:", '#ff0000', '14px', null)],[$nome],[],[$data_nascimento]);
        $row3 = $this->form->addFields([new TLabel("Rg:", null, '14px', null)],[$rg],[new TLabel("Cpf:", null, '14px', null)],[$cpf]);
        $row4 = $this->form->addFields([new TLabel("Unidade Principal:", '#ff0000', '14px', null)],[$unidade_id],[new TLabel("Convênio:", null, '14px', null)],[$convenio_id]);
        $row5 = $this->form->addFields([new TLabel("Fone 1:", null, '14px', null)],[$fone_1],[new TLabel("Fone 2:", null, '14px', null)],[$fone_2]);
        $row6 = $this->form->addFields([new TLabel("Endereço:", null, '14px', null)],[$endereco],[new TLabel("Numero:", null, '14px', null)],[$numero]);
        $row7 = $this->form->addFields([new TLabel("Bairro:", null, '14px', null)],[$bairro],[new TLabel("CEP:", null, '14px', null)],[$cep]);
        $row8 = $this->form->addFields([new TLabel("Estado:", null, '14px', null)],[$estado_id],[new TLabel("Cidade:", null, '14px', null)],[$cidade_id]);
        $row9 = $this->form->addFields([new TLabel("Situação:", null, '14px', null)],[$situacao_id],[new TLabel("Email:", null, '14px', null)],[$email]);
        $row10 = $this->form->addContent([new TFormSeparator("Responsável", '#333333', '18', '#eeeeee')]);
        $row11 = $this->form->addFields([new TLabel("Nome:", null, '14px', null)],[$responsavel_aluno_aluno_nome],[new TLabel("Parentesco:", null, '14px', null)],[$responsavel_aluno_aluno_parentesco]);
        $row12 = $this->form->addFields([new TLabel("Rg:", null, '14px', null)],[$responsavel_aluno_aluno_rg],[new TLabel("Cpf:", null, '14px', null)],[$responsavel_aluno_aluno_cpf]);
        $row13 = $this->form->addFields([new TLabel("Fone 1:", null, '14px', null)],[$responsavel_aluno_aluno_fone_1],[new TLabel("Fone 2:", null, '14px', null)],[$responsavel_aluno_aluno_fone_2]);
        $row14 = $this->form->addFields([$responsavel_aluno_aluno_id]);         
        $add_responsavel_aluno_aluno = new TButton('add_responsavel_aluno_aluno');

        $action_responsavel_aluno_aluno = new TAction(array($this, 'onAddResponsavelAlunoAluno'));

        $add_responsavel_aluno_aluno->setAction($action_responsavel_aluno_aluno, "Gravar item");
        $add_responsavel_aluno_aluno->setImage('fas:save #000000');

        $this->form->addFields([$add_responsavel_aluno_aluno]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->responsavel_aluno_aluno_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->responsavel_aluno_aluno_list->style = 'width:100%';
        $this->responsavel_aluno_aluno_list->class .= ' table-bordered';
        $this->responsavel_aluno_aluno_list->disableDefaultClick();
        $this->responsavel_aluno_aluno_list->addQuickColumn('', 'edit', 'left', 50);
        $this->responsavel_aluno_aluno_list->addQuickColumn('', 'delete', 'left', 50);

        $column_responsavel_aluno_aluno_nome = $this->responsavel_aluno_aluno_list->addQuickColumn("Nome", 'responsavel_aluno_aluno_nome', 'left');
        $column_responsavel_aluno_aluno_cpf = $this->responsavel_aluno_aluno_list->addQuickColumn("Cpf", 'responsavel_aluno_aluno_cpf', 'left');
        $column_responsavel_aluno_aluno_fone_1 = $this->responsavel_aluno_aluno_list->addQuickColumn("Fone 1", 'responsavel_aluno_aluno_fone_1', 'left');
        $column_responsavel_aluno_aluno_fone_2 = $this->responsavel_aluno_aluno_list->addQuickColumn("Fone 2", 'responsavel_aluno_aluno_fone_2', 'left');
        $column_responsavel_aluno_aluno_parentesco = $this->responsavel_aluno_aluno_list->addQuickColumn("Parentesco", 'responsavel_aluno_aluno_parentesco', 'left');

        $this->responsavel_aluno_aluno_list->createModel();
        $this->form->addContent([$this->responsavel_aluno_aluno_list]);
        $row15 = $this->form->addFields([new TFormSeparator("Currículo", '#333333', '18', '#eeeeee')]);
        $row15->layout = [' col-sm-12'];

        $row16 = $this->form->addFields([new TLabel("Data matrícula:", null, '14px', null)],[$curriculo_aluno_aluno_data_matricula]);
        $row17 = $this->form->addFields([new TLabel("Unidade:", '#ff0000', '14px', null)],[$curriculo_aluno_aluno_unidade_id],[new TLabel("Idioma:", '#ff0000', '14px', null)],[$curriculo_aluno_aluno_idioma_id]);
        $row18 = $this->form->addFields([new TLabel("Book:", '#ff0000', '14px', null)],[$curriculo_aluno_aluno_book_id],[new TLabel("Stage:", '#ff0000', '14px', null)],[$curriculo_aluno_aluno_stage_id]);
        $row19 = $this->form->addFields([new TLabel("Plano:", '#ff0000', '14px', null)],[$curriculo_aluno_aluno_plano_id],[new TLabel("Status:", null, '14px', null)],[$curriculo_aluno_aluno_status]);
        $row20 = $this->form->addFields([new TLabel("Qtd Horas:", '#FF0000', '14px', null)],[$curriculo_aluno_aluno_qtd_hora,new TLabel("R$ Parcela:", '#FF0000', '14px', null),$curriculo_aluno_aluno_valor_parcela],[new TLabel("Qtd Parcelas:", '#FF0000', '14px', null)],[$curriculo_aluno_aluno_qtd_parcela]);
        $row21 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$curriculo_aluno_aluno_observacao]);
        $row22 = $this->form->addFields([$curriculo_aluno_aluno_id]);         
        $add_curriculo_aluno_aluno = new TButton('add_curriculo_aluno_aluno');

        $action_curriculo_aluno_aluno = new TAction(array($this, 'onAddCurriculoAlunoAluno'));

        $add_curriculo_aluno_aluno->setAction($action_curriculo_aluno_aluno, "Adicionar");
        $add_curriculo_aluno_aluno->setImage('fas:plus #000000');

        $this->form->addFields([$add_curriculo_aluno_aluno]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->curriculo_aluno_aluno_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->curriculo_aluno_aluno_list->style = 'width:100%';
        $this->curriculo_aluno_aluno_list->class .= ' table-bordered';
        $this->curriculo_aluno_aluno_list->disableDefaultClick();
        $this->curriculo_aluno_aluno_list->addQuickColumn('', 'edit', 'left', 50);
        $this->curriculo_aluno_aluno_list->addQuickColumn('', 'delete', 'left', 50);

        $column_curriculo_aluno_aluno_idioma_id = $this->curriculo_aluno_aluno_list->addQuickColumn("Idioma", 'curriculo_aluno_aluno_idioma_id', 'left');
        $column_curriculo_aluno_aluno_book_id = $this->curriculo_aluno_aluno_list->addQuickColumn("Book", 'curriculo_aluno_aluno_book_id', 'left');
        $column_curriculo_aluno_aluno_stage_id = $this->curriculo_aluno_aluno_list->addQuickColumn("Stage", 'curriculo_aluno_aluno_stage_id', 'left');
        $column_curriculo_aluno_aluno_plano_id = $this->curriculo_aluno_aluno_list->addQuickColumn("Plano", 'curriculo_aluno_aluno_plano_id', 'left');
        $column_curriculo_aluno_aluno_qtd_hora = $this->curriculo_aluno_aluno_list->addQuickColumn("Qtd hora", 'curriculo_aluno_aluno_qtd_hora', 'center');
        $column_curriculo_aluno_aluno_qtd_parcela = $this->curriculo_aluno_aluno_list->addQuickColumn("Qtd parcela", 'curriculo_aluno_aluno_qtd_parcela', 'center');
        $column_curriculo_aluno_aluno_unidade_id = $this->curriculo_aluno_aluno_list->addQuickColumn("Unidade", 'curriculo_aluno_aluno_unidade_id', 'left');
        $column_curriculo_aluno_aluno_status = $this->curriculo_aluno_aluno_list->addQuickColumn("Status", 'curriculo_aluno_aluno_status', 'left');

        $this->curriculo_aluno_aluno_list->createModel();
        $this->form->addContent([$this->curriculo_aluno_aluno_list]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de aluno"]));
        }
        $container->add($this->form);

        // Validador de CPF
        // $cpf->addValidation('cpf', new TCPFValidator); 

        /*    if (empty($responsavel_aluno_aluno_cpf))
            {

            } else 
            {
                $responsavel_aluno_aluno_cpf->addValidation('responsavel_aluno_aluno_cpf', new TCPFValidator);
            }
        // $responsavel_aluno_aluno_cpf->addValidation('responsavel_aluno_aluno_cpf', new TCPFValidator);
        */

        parent::add($container);

    }

    public static function onChangeestado_id($param)
    {
        try
        {

            if (isset($param['estado_id']) && $param['estado_id'])
            { 
                $criteria = TCriteria::create(['estado_id' => $param['estado_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'cidade_id', 'cdi', 'Cidade', 'id', '{nome}', 'nome asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'cidade_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public static function onChangecurriculo_aluno_aluno_idioma_id($param)
    {
        try
        {

            if (isset($param['curriculo_aluno_aluno_idioma_id']) && $param['curriculo_aluno_aluno_idioma_id'])
            { 
                $criteria = TCriteria::create(['idioma_id' => $param['curriculo_aluno_aluno_idioma_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'curriculo_aluno_aluno_book_id', 'cdi', 'Book', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'curriculo_aluno_aluno_book_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public static function onChangecurriculo_aluno_aluno_book_id($param)
    {
        try
        {

            if (isset($param['curriculo_aluno_aluno_book_id']) && $param['curriculo_aluno_aluno_book_id'])
            { 
                $criteria = TCriteria::create(['book_id' => $param['curriculo_aluno_aluno_book_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'curriculo_aluno_aluno_stage_id', 'cdi', 'Stage', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'curriculo_aluno_aluno_stage_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public static function onChangeunidade_id($param)
    {
        try
        {

            if (isset($param['unidade_id']) && $param['unidade_id'])
            { 
                $criteria = TCriteria::create(['unidade_id' => $param['unidade_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'curriculo_aluno_aluno_plano_id', 'cdi', 'Plano', 'id', '{descricao} {duracao_aula} R$ {valor} ', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'curriculo_aluno_aluno_plano_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public static function onValorPlano($param = null) 
    {
        try 
        {
            /*
            TTransaction::open(self::$database); // open a transaction
            $plano = new Plano($param['key']);

            $object = new stdClass();
            $object->curriculo_aluno_aluno_valor_hora = $plano->valor;

            TForm::sendData(self::$formName, $object);

            //Debug
            //var_dump($object->curriculo_aluno_aluno_valor_hora);exit;
            //return "{$plano->valor}";
            TTransaction::close();
            */

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
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

            $object = new Aluno(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if($object->data_nascimento)
            {
                $object->data_nascimento = TDate::date2us($object->data_nascimento);
            }

            if (empty($object->id))
            {
                $object->criado_por_user_id = TSession::getValue('userid');
                $object->criado_em = date('Y-m-d H:i:s');
            } else {
                $object->alterado_por_user_id = TSession::getValue('userid');
                $object->alterado_em = date('Y-m-d H:i:s');
            }

            $object->store(); // save the object 

            $this->fireEvents($object);

            $messageAction = new TAction(['AlunoList', 'onShow']);   

            if(!empty($param['target_container']))
            {
                $messageAction->setParameter('target_container', $param['target_container']);
            }

            $curriculo_aluno_aluno_items = $this->storeItems('CurriculoAluno', 'aluno_id', $object, 'curriculo_aluno_aluno', function($masterObject, $detailObject){ 

                if ($masterObject->situacao_id == 2) {
                    $detailObject->status = 'Inativo';
                }

            }); 

            $responsavel_aluno_aluno_items = $this->storeItems('ResponsavelAluno', 'aluno_id', $object, 'responsavel_aluno_aluno', function($masterObject, $detailObject){ 

                //code here

            }); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode>  

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Aluno($key); // instantiates the Active Record 

                if($object->data_nascimento)
                {
                    $object->data_nascimento = TDate::date2br($object->data_nascimento);
                }

                                $object->unidade_id = $object->unidade->id;
                $object->estado_id = $object->estado->id;

                $curriculo_aluno_aluno_items = $this->loadItems('CurriculoAluno', 'aluno_id', $object, 'curriculo_aluno_aluno', function($masterObject, $detailObject, $objectItems){ 

                    //code here

                    $objectItems->curriculo_aluno_aluno_book_id = null;
                    if(isset($detailObject->book_id) && $detailObject->book_id)
                    {
                        $objectItems->curriculo_aluno_aluno_book_id = $detailObject->book_id;
                    }
                    $objectItems->curriculo_aluno_aluno_idioma_id = null;
                    if(isset($detailObject->idioma_id) && $detailObject->idioma_id)
                    {
                        $objectItems->curriculo_aluno_aluno_idioma_id = $detailObject->idioma_id;
                    }
                    $objectItems->curriculo_aluno_aluno_stage_id = null;
                    if(isset($detailObject->stage_id) && $detailObject->stage_id)
                    {
                        $objectItems->curriculo_aluno_aluno_stage_id = $detailObject->stage_id;
                    }
                    $objectItems->curriculo_aluno_aluno_book_id = null;
                    if(isset($detailObject->book_id) && $detailObject->book_id)
                    {
                        $objectItems->curriculo_aluno_aluno_book_id = $detailObject->book_id;
                    }
                    $objectItems->curriculo_aluno_aluno_plano_id = null;
                    if(isset($detailObject->plano_id) && $detailObject->plano_id)
                    {
                        $objectItems->curriculo_aluno_aluno_plano_id = $detailObject->plano_id;
                    }
                    $objectItems->unidade_id = null;
                    if(isset($detailObject->unidade->id) && $detailObject->unidade->id)
                    {
                        $objectItems->unidade_id = $detailObject->unidade->id;
                    }

                }); 

                $responsavel_aluno_aluno_items = $this->loadItems('ResponsavelAluno', 'aluno_id', $object, 'responsavel_aluno_aluno', function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);
                $this->onReload();

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

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        TSession::setValue('responsavel_aluno_aluno_items', null);
        TSession::setValue('curriculo_aluno_aluno_items', null);

        $this->onReload();
    }

    public function onAddResponsavelAlunoAluno( $param )
    {
        try
        {
            $data = $this->form->getData();

            $responsavel_aluno_aluno_items = TSession::getValue('responsavel_aluno_aluno_items');
            $key = isset($data->responsavel_aluno_aluno_id) && $data->responsavel_aluno_aluno_id ? $data->responsavel_aluno_aluno_id : 'b'.uniqid();
            $fields = []; 

            $fields['responsavel_aluno_aluno_nome'] = $data->responsavel_aluno_aluno_nome;
            $fields['responsavel_aluno_aluno_parentesco'] = $data->responsavel_aluno_aluno_parentesco;
            $fields['responsavel_aluno_aluno_rg'] = $data->responsavel_aluno_aluno_rg;
            $fields['responsavel_aluno_aluno_cpf'] = $data->responsavel_aluno_aluno_cpf;
            $fields['responsavel_aluno_aluno_fone_1'] = $data->responsavel_aluno_aluno_fone_1;
            $fields['responsavel_aluno_aluno_fone_2'] = $data->responsavel_aluno_aluno_fone_2;
            $responsavel_aluno_aluno_items[ $key ] = $fields;

            TSession::setValue('responsavel_aluno_aluno_items', $responsavel_aluno_aluno_items);

            $data->responsavel_aluno_aluno_id = '';
            $data->responsavel_aluno_aluno_nome = '';
            $data->responsavel_aluno_aluno_parentesco = '';
            $data->responsavel_aluno_aluno_rg = '';
            $data->responsavel_aluno_aluno_cpf = '';
            $data->responsavel_aluno_aluno_fone_1 = '';
            $data->responsavel_aluno_aluno_fone_2 = '';

            $this->form->setData($data);
            $this->fireEvents($data);
            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            $this->fireEvents($data);
            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditResponsavelAlunoAluno( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('responsavel_aluno_aluno_items');

        // get the session item
        $item = $items[$param['responsavel_aluno_aluno_id_row_id']];

        $data->responsavel_aluno_aluno_nome = $item['responsavel_aluno_aluno_nome'];
        $data->responsavel_aluno_aluno_parentesco = $item['responsavel_aluno_aluno_parentesco'];
        $data->responsavel_aluno_aluno_rg = $item['responsavel_aluno_aluno_rg'];
        $data->responsavel_aluno_aluno_cpf = $item['responsavel_aluno_aluno_cpf'];
        $data->responsavel_aluno_aluno_fone_1 = $item['responsavel_aluno_aluno_fone_1'];
        $data->responsavel_aluno_aluno_fone_2 = $item['responsavel_aluno_aluno_fone_2'];

        $data->responsavel_aluno_aluno_id = $param['responsavel_aluno_aluno_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->fireEvents($data);

        $this->onReload( $param );

    }

    public function onDeleteResponsavelAlunoAluno( $param )
    {
        $data = $this->form->getData();

        $data->responsavel_aluno_aluno_nome = '';
        $data->responsavel_aluno_aluno_parentesco = '';
        $data->responsavel_aluno_aluno_rg = '';
        $data->responsavel_aluno_aluno_cpf = '';
        $data->responsavel_aluno_aluno_fone_1 = '';
        $data->responsavel_aluno_aluno_fone_2 = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('responsavel_aluno_aluno_items');

        // delete the item from session
        unset($items[$param['responsavel_aluno_aluno_id_row_id']]);
        TSession::setValue('responsavel_aluno_aluno_items', $items);

        $this->fireEvents($data);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadResponsavelAlunoAluno( $param )
    {
        $items = TSession::getValue('responsavel_aluno_aluno_items'); 

        $this->responsavel_aluno_aluno_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteResponsavelAlunoAluno')); 
                $action_del->setParameter('responsavel_aluno_aluno_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditResponsavelAlunoAluno'));  
                $action_edi->setParameter('responsavel_aluno_aluno_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_responsavel_aluno_aluno'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = "Excluir";
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_responsavel_aluno_aluno'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = "Editar";
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->responsavel_aluno_aluno_nome = isset($item['responsavel_aluno_aluno_nome']) ? $item['responsavel_aluno_aluno_nome'] : '';
                $rowItem->responsavel_aluno_aluno_parentesco = isset($item['responsavel_aluno_aluno_parentesco']) ? $item['responsavel_aluno_aluno_parentesco'] : '';
                $rowItem->responsavel_aluno_aluno_rg = isset($item['responsavel_aluno_aluno_rg']) ? $item['responsavel_aluno_aluno_rg'] : '';
                $rowItem->responsavel_aluno_aluno_cpf = isset($item['responsavel_aluno_aluno_cpf']) ? $item['responsavel_aluno_aluno_cpf'] : '';
                $rowItem->responsavel_aluno_aluno_fone_1 = isset($item['responsavel_aluno_aluno_fone_1']) ? $item['responsavel_aluno_aluno_fone_1'] : '';
                $rowItem->responsavel_aluno_aluno_fone_2 = isset($item['responsavel_aluno_aluno_fone_2']) ? $item['responsavel_aluno_aluno_fone_2'] : '';

                $row = $this->responsavel_aluno_aluno_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onAddCurriculoAlunoAluno( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->curriculo_aluno_aluno_idioma_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Idioma id"));
            }             
            if(!$data->curriculo_aluno_aluno_book_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Book id"));
            }             
            if(!$data->curriculo_aluno_aluno_stage_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Stage id"));
            }             
            if(!$data->curriculo_aluno_aluno_plano_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Plano"));
            }             
            if(!$data->curriculo_aluno_aluno_qtd_hora)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Qtd Horas:"));
            }             
            if(!$data->curriculo_aluno_aluno_valor_parcela)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "R$ Parcela:"));
            }             
            if(!$data->curriculo_aluno_aluno_qtd_parcela)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Qtd Parcelas:"));
            }             

            $curriculo_aluno_aluno_items = TSession::getValue('curriculo_aluno_aluno_items');
            $key = isset($data->curriculo_aluno_aluno_id) && $data->curriculo_aluno_aluno_id ? $data->curriculo_aluno_aluno_id : 'b'.uniqid();
            $fields = []; 

            $fields['curriculo_aluno_aluno_data_matricula'] = $data->curriculo_aluno_aluno_data_matricula;
            $fields['curriculo_aluno_aluno_unidade_id'] = $data->curriculo_aluno_aluno_unidade_id;
            $fields['curriculo_aluno_aluno_idioma_id'] = $data->curriculo_aluno_aluno_idioma_id;
            $fields['curriculo_aluno_aluno_book_id'] = $data->curriculo_aluno_aluno_book_id;
            $fields['curriculo_aluno_aluno_stage_id'] = $data->curriculo_aluno_aluno_stage_id;
            $fields['curriculo_aluno_aluno_plano_id'] = $data->curriculo_aluno_aluno_plano_id;
            $fields['curriculo_aluno_aluno_status'] = $data->curriculo_aluno_aluno_status;
            $fields['curriculo_aluno_aluno_qtd_hora'] = $data->curriculo_aluno_aluno_qtd_hora;
            $fields['curriculo_aluno_aluno_valor_parcela'] = $data->curriculo_aluno_aluno_valor_parcela;
            $fields['curriculo_aluno_aluno_qtd_parcela'] = $data->curriculo_aluno_aluno_qtd_parcela;
            $fields['curriculo_aluno_aluno_observacao'] = $data->curriculo_aluno_aluno_observacao;
            $curriculo_aluno_aluno_items[ $key ] = $fields;

            TSession::setValue('curriculo_aluno_aluno_items', $curriculo_aluno_aluno_items);

            $data->curriculo_aluno_aluno_id = '';
            $data->curriculo_aluno_aluno_data_matricula = '';
            $data->curriculo_aluno_aluno_unidade_id = '';
            $data->curriculo_aluno_aluno_idioma_id = '';
            $data->curriculo_aluno_aluno_book_id = '';
            $data->curriculo_aluno_aluno_stage_id = '';
            $data->curriculo_aluno_aluno_plano_id = '';
            $data->curriculo_aluno_aluno_status = '';
            $data->curriculo_aluno_aluno_qtd_hora = '';
            $data->curriculo_aluno_aluno_valor_parcela = '';
            $data->curriculo_aluno_aluno_qtd_parcela = '';
            $data->curriculo_aluno_aluno_observacao = '';

            $this->form->setData($data);
            $this->fireEvents($data);
            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            $this->fireEvents($data);
            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditCurriculoAlunoAluno( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('curriculo_aluno_aluno_items');

        // get the session item
        $item = $items[$param['curriculo_aluno_aluno_id_row_id']];

        $data->curriculo_aluno_aluno_data_matricula = $item['curriculo_aluno_aluno_data_matricula'];
        $data->curriculo_aluno_aluno_unidade_id = $item['curriculo_aluno_aluno_unidade_id'];
        $data->curriculo_aluno_aluno_idioma_id = $item['curriculo_aluno_aluno_idioma_id'];
        $data->curriculo_aluno_aluno_book_id = $item['curriculo_aluno_aluno_book_id'];
        $data->curriculo_aluno_aluno_stage_id = $item['curriculo_aluno_aluno_stage_id'];
        $data->curriculo_aluno_aluno_plano_id = $item['curriculo_aluno_aluno_plano_id'];
        $data->curriculo_aluno_aluno_status = $item['curriculo_aluno_aluno_status'];
        $data->curriculo_aluno_aluno_qtd_hora = $item['curriculo_aluno_aluno_qtd_hora'];
        $data->curriculo_aluno_aluno_valor_parcela = $item['curriculo_aluno_aluno_valor_parcela'];
        $data->curriculo_aluno_aluno_qtd_parcela = $item['curriculo_aluno_aluno_qtd_parcela'];
        $data->curriculo_aluno_aluno_observacao = $item['curriculo_aluno_aluno_observacao'];

        $data->curriculo_aluno_aluno_id = $param['curriculo_aluno_aluno_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->fireEvents($data);

        $this->onReload( $param );

    }

    public function onDeleteCurriculoAlunoAluno( $param )
    {
        $data = $this->form->getData();

        $data->curriculo_aluno_aluno_data_matricula = '';
        $data->curriculo_aluno_aluno_unidade_id = '';
        $data->curriculo_aluno_aluno_idioma_id = '';
        $data->curriculo_aluno_aluno_book_id = '';
        $data->curriculo_aluno_aluno_stage_id = '';
        $data->curriculo_aluno_aluno_plano_id = '';
        $data->curriculo_aluno_aluno_status = '';
        $data->curriculo_aluno_aluno_qtd_hora = '';
        $data->curriculo_aluno_aluno_valor_parcela = '';
        $data->curriculo_aluno_aluno_qtd_parcela = '';
        $data->curriculo_aluno_aluno_observacao = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('curriculo_aluno_aluno_items');

        // delete the item from session
        unset($items[$param['curriculo_aluno_aluno_id_row_id']]);
        TSession::setValue('curriculo_aluno_aluno_items', $items);

        $this->fireEvents($data);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadCurriculoAlunoAluno( $param )
    {
        $items = TSession::getValue('curriculo_aluno_aluno_items'); 

        $this->curriculo_aluno_aluno_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteCurriculoAlunoAluno')); 
                $action_del->setParameter('curriculo_aluno_aluno_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditCurriculoAlunoAluno'));  
                $action_edi->setParameter('curriculo_aluno_aluno_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_curriculo_aluno_aluno'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = "Excluir";
                $button_del->setImage('fas:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_curriculo_aluno_aluno'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = "Editar";
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->curriculo_aluno_aluno_data_matricula = isset($item['curriculo_aluno_aluno_data_matricula']) ? $item['curriculo_aluno_aluno_data_matricula'] : '';
                $rowItem->curriculo_aluno_aluno_unidade_id = '';
                if(isset($item['curriculo_aluno_aluno_unidade_id']) && $item['curriculo_aluno_aluno_unidade_id'])
                {
                    TTransaction::open('permission');
                    $system_unit = SystemUnit::find($item['curriculo_aluno_aluno_unidade_id']);
                    if($system_unit)
                    {
                        $rowItem->curriculo_aluno_aluno_unidade_id = $system_unit->render('{name}');
                    }
                    TTransaction::close();
                }

                $rowItem->curriculo_aluno_aluno_idioma_id = '';
                if(isset($item['curriculo_aluno_aluno_idioma_id']) && $item['curriculo_aluno_aluno_idioma_id'])
                {
                    TTransaction::open('cdi');
                    $idioma = Idioma::find($item['curriculo_aluno_aluno_idioma_id']);
                    if($idioma)
                    {
                        $rowItem->curriculo_aluno_aluno_idioma_id = $idioma->render('{descricao}');
                    }
                    TTransaction::close();
                }

                $rowItem->curriculo_aluno_aluno_book_id = '';
                if(isset($item['curriculo_aluno_aluno_book_id']) && $item['curriculo_aluno_aluno_book_id'])
                {
                    TTransaction::open('cdi');
                    $book = Book::find($item['curriculo_aluno_aluno_book_id']);
                    if($book)
                    {
                        $rowItem->curriculo_aluno_aluno_book_id = $book->render('{descricao}');
                    }
                    TTransaction::close();
                }

                $rowItem->curriculo_aluno_aluno_stage_id = '';
                if(isset($item['curriculo_aluno_aluno_stage_id']) && $item['curriculo_aluno_aluno_stage_id'])
                {
                    TTransaction::open('cdi');
                    $stage = Stage::find($item['curriculo_aluno_aluno_stage_id']);
                    if($stage)
                    {
                        $rowItem->curriculo_aluno_aluno_stage_id = $stage->render('{descricao}');
                    }
                    TTransaction::close();
                }

                $rowItem->curriculo_aluno_aluno_plano_id = '';
                if(isset($item['curriculo_aluno_aluno_plano_id']) && $item['curriculo_aluno_aluno_plano_id'])
                {
                    TTransaction::open('cdi');
                    $plano = Plano::find($item['curriculo_aluno_aluno_plano_id']);
                    if($plano)
                    {
                        $rowItem->curriculo_aluno_aluno_plano_id = $plano->render('{descricao} {duracao_aula} R$ {valor} ');
                    }
                    TTransaction::close();
                }

                $rowItem->curriculo_aluno_aluno_status = isset($item['curriculo_aluno_aluno_status']) ? $item['curriculo_aluno_aluno_status'] : '';
                $rowItem->curriculo_aluno_aluno_qtd_hora = isset($item['curriculo_aluno_aluno_qtd_hora']) ? $item['curriculo_aluno_aluno_qtd_hora'] : '';
                $rowItem->curriculo_aluno_aluno_valor_parcela = isset($item['curriculo_aluno_aluno_valor_parcela']) ? $item['curriculo_aluno_aluno_valor_parcela'] : '';
                $rowItem->curriculo_aluno_aluno_qtd_parcela = isset($item['curriculo_aluno_aluno_qtd_parcela']) ? $item['curriculo_aluno_aluno_qtd_parcela'] : '';
                $rowItem->curriculo_aluno_aluno_observacao = isset($item['curriculo_aluno_aluno_observacao']) ? $item['curriculo_aluno_aluno_observacao'] : '';

                $row = $this->curriculo_aluno_aluno_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {

        TSession::setValue('responsavel_aluno_aluno_items', null);
        TSession::setValue('curriculo_aluno_aluno_items', null);

        $this->onReload();

    } 

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->estado_id))
            {
                $value = $object->estado_id;

                $obj->estado_id = $value;
            }
            if(isset($object->cidade_id))
            {
                $value = $object->cidade_id;

                $obj->cidade_id = $value;
            }
            if(isset($object->curriculo_aluno_aluno_idioma_id))
            {
                $value = $object->curriculo_aluno_aluno_idioma_id;

                $obj->curriculo_aluno_aluno_idioma_id = $value;
            }
            if(isset($object->curriculo_aluno_aluno_book_id))
            {
                $value = $object->curriculo_aluno_aluno_book_id;

                $obj->curriculo_aluno_aluno_book_id = $value;
            }
            if(isset($object->curriculo_aluno_aluno_stage_id))
            {
                $value = $object->curriculo_aluno_aluno_stage_id;

                $obj->curriculo_aluno_aluno_stage_id = $value;
            }
            if(isset($object->unidade_id))
            {
                $value = $object->unidade_id;

                $obj->unidade_id = $value;
            }
            if(isset($object->curriculo_aluno_aluno_plano_id))
            {
                $value = $object->curriculo_aluno_aluno_plano_id;

                $obj->curriculo_aluno_aluno_plano_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->estado->id))
            {
                $value = $object->estado->id;

                $obj->estado_id = $value;
            }
            if(isset($object->cidade_id))
            {
                $value = $object->cidade_id;

                $obj->cidade_id = $value;
            }
            if(isset($object->curriculo_aluno->aluno->idioma_id))
            {
                $value = $object->curriculo_aluno->aluno->idioma_id;

                $obj->curriculo_aluno_aluno_idioma_id = $value;
            }
            if(isset($object->curriculo_aluno->aluno->book_id))
            {
                $value = $object->curriculo_aluno->aluno->book_id;

                $obj->curriculo_aluno_aluno_book_id = $value;
            }
            if(isset($object->curriculo_aluno->aluno->stage_id))
            {
                $value = $object->curriculo_aluno->aluno->stage_id;

                $obj->curriculo_aluno_aluno_stage_id = $value;
            }
            if(isset($object->unidade->id))
            {
                $value = $object->unidade->id;

                $obj->unidade_id = $value;
            }
            if(isset($object->curriculo_aluno->aluno->plano_id))
            {
                $value = $object->curriculo_aluno->aluno->plano_id;

                $obj->curriculo_aluno_aluno_plano_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadResponsavelAlunoAluno($params);
        $this->onReloadCurriculoAlunoAluno($params);
    }

    public function show() 
    { 
        $param = func_get_arg(0);
        if(!empty($param['current_tab']))
        {
            $this->form->setCurrentPage($param['current_tab']);
        }

        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') ) 
        { 
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

}


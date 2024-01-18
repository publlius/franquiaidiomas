<?php

class MatriculaAlunoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Aluno';
    private static $primaryKey = 'id';
    private static $formName = 'form_MatriculaAlunoForm';

    use BuilderMasterDetailTrait;

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
        $this->form->setFormTitle("Matrícula");


        $cpf = new TEntry('cpf');
        $rg = new TEntry('rg');
        $contrato_url = new THidden('contrato_url');
        $situacao_id = new THidden('situacao_id');
        $nome = new TEntry('nome');
        $id = new THidden('id');
        $data_nascimento = new TDate('data_nascimento');
        $endereco = new TEntry('endereco');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cep = new TEntry('cep');
        $estado_id = new TDBCombo('estado_id', 'cdi', 'Estado', 'id', '{uf}','uf asc'  );
        $cidade_id = new TCombo('cidade_id');
        $fone_1 = new TEntry('fone_1');
        $fone_2 = new TEntry('fone_2');
        $email = new TEntry('email');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $responsavel_aluno_aluno_nome = new TEntry('responsavel_aluno_aluno_nome');
        $responsavel_aluno_aluno_id = new THidden('responsavel_aluno_aluno_id');
        $responsavel_aluno_aluno_parentesco = new TEntry('responsavel_aluno_aluno_parentesco');
        $responsavel_aluno_aluno_rg = new TEntry('responsavel_aluno_aluno_rg');
        $responsavel_aluno_aluno_cpf = new TEntry('responsavel_aluno_aluno_cpf');
        $responsavel_aluno_aluno_fone_1 = new TNumeric('responsavel_aluno_aluno_fone_1', '0', '', '' );
        $responsavel_aluno_aluno_fone_2 = new TNumeric('responsavel_aluno_aluno_fone_2', '0', '', '' );
        $button_adicionar_responsavel_responsavel_aluno_aluno = new TButton('button_adicionar_responsavel_responsavel_aluno_aluno');
        $regulamento_2022_2023 = new TElement('iframe');

        $estado_id->setChangeAction(new TAction([$this,'onChangeestado_id']));

        $cpf->setExitAction(new TAction([$this,'onCpf']));

        $cpf->addValidation("CPF", new TRequiredValidator()); 
        $rg->addValidation("RG", new TRequiredValidator()); 
        $nome->addValidation("Nome", new TRequiredValidator()); 
        $estado_id->addValidation("Estado id", new TRequiredValidator()); 
        $cidade_id->addValidation("Cidade id", new TRequiredValidator()); 
        $fone_1->addValidation("Fone 1", new TRequiredValidator()); 
        $email->addValidation("Email", new TRequiredValidator()); 
        $unidade_id->addValidation("Escola", new TRequiredValidator()); 
        $cpf->addValidation("CPF", new TCPFValidator(), []); 
        $email->addValidation("Email", new TEmailValidator(), []); 

        $data_nascimento->setDatabaseMask('yyyy-mm-dd');
        $button_adicionar_responsavel_responsavel_aluno_aluno->setAction(new TAction([$this, 'onAddDetailResponsavelAlunoAluno'],['static' => 1]), "Adicionar Responsável");
        $button_adicionar_responsavel_responsavel_aluno_aluno->addStyleClass('btn-default');
        $button_adicionar_responsavel_responsavel_aluno_aluno->setImage('fas:plus #2ecc71');
        $situacao_id->setValue('4');
        $contrato_url->setValue('https://cditiagodenardi.com/contrato_speasy_2223.pdf');

        $estado_id->enableSearch();
        $cidade_id->enableSearch();

        $cpf->setMask('999.999.999-99');
        $data_nascimento->setMask('dd/mm/yyyy');
        $responsavel_aluno_aluno_cpf->setMask('999.999.999-99');

        $regulamento_2022_2023->width = '100%';
        $regulamento_2022_2023->height = '260px';
        $button_adicionar_responsavel_responsavel_aluno_aluno->id = '619fe1ff67ea4';
        $regulamento_2022_2023->src = "https://sis.cditiagodenardi.com/app/resources/cadastros/regulamento_2022_2023rev.html";

        $rg->setMaxLength(20);
        $cpf->setMaxLength(20);
        $cep->setMaxLength(10);
        $nome->setMaxLength(240);
        $numero->setMaxLength(10);
        $fone_1->setMaxLength(20);
        $fone_2->setMaxLength(20);
        $bairro->setMaxLength(100);
        $endereco->setMaxLength(240);
        $responsavel_aluno_aluno_rg->setMaxLength(20);
        $responsavel_aluno_aluno_cpf->setMaxLength(20);
        $responsavel_aluno_aluno_nome->setMaxLength(240);
        $responsavel_aluno_aluno_parentesco->setMaxLength(20);

        $id->setSize(200);
        $rg->setSize('100%');
        $cpf->setSize('100%');
        $nome->setSize('98%');
        $cep->setSize('100%');
        $email->setSize('100%');
        $fone_2->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $fone_1->setSize('100%');
        $situacao_id->setSize(200);
        $endereco->setSize('100%');
        $cidade_id->setSize('100%');
        $estado_id->setSize('100%');
        $contrato_url->setSize(200);
        $unidade_id->setSize('100%');
        $data_nascimento->setSize(110);
        $responsavel_aluno_aluno_id->setSize(200);
        $responsavel_aluno_aluno_rg->setSize('100%');
        $responsavel_aluno_aluno_cpf->setSize('100%');
        $responsavel_aluno_aluno_nome->setSize('100%');
        $responsavel_aluno_aluno_fone_1->setSize('100%');
        $responsavel_aluno_aluno_fone_2->setSize('100%');
        $responsavel_aluno_aluno_parentesco->setSize('100%');

        $this->regulamento_2022_2023 = $regulamento_2022_2023;

        $row1 = $this->form->addFields([new TLabel("CPF:", '#F44336', '14px', null, '100%'),$cpf],[new TLabel("RG:", '#F44336', '14px', null, '100%'),$rg]);
        $row1->layout = ['col-sm-6',' col-sm-6'];

        $row2 = $this->form->addFields([$contrato_url,$situacao_id,new TLabel("Nome:", '#ff0000', '14px', null, '100%'),$nome],[$id,new TLabel("Data nascimento:", '#F44336', '14px', null, '100%'),$data_nascimento]);
        $row2->layout = [' col-sm-8',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Endereco:", '#F44336', '14px', null, '100%'),$endereco],[new TLabel("Numero:", null, '14px', null, '100%'),$numero]);
        $row3->layout = [' col-sm-8',' col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Bairro:", '#F44336', '14px', null, '100%'),$bairro],[new TLabel("CEP:", '#F44336', '14px', null, '100%'),$cep]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Estado:", '#ff0000', '14px', null, '100%'),$estado_id],[new TLabel("Cidade:", '#ff0000', '14px', null, '100%'),$cidade_id]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel("Fone 2:", '#F44336', '14px', null, '100%'),$fone_1],[new TLabel("Fone 1:", null, '14px', null, '100%'),$fone_2]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $row7 = $this->form->addFields([new TLabel("Email:", '#FF0000', '14px', null, '100%'),$email],[new TLabel("Escola:", '#FF0000', '14px', null, '100%'),$unidade_id]);
        $row7->layout = ['col-sm-6',' col-sm-6'];

        $this->detailFormResponsavelAlunoAluno = new BootstrapFormBuilder('detailFormResponsavelAlunoAluno');
        $this->detailFormResponsavelAlunoAluno->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormResponsavelAlunoAluno->setProperty('class', 'form-horizontal builder-detail-form');

        $row8 = $this->detailFormResponsavelAlunoAluno->addFields([new TFormSeparator("Responsáveis (para menores de 18 anos)", '#333', '18', '#eee')]);
        $row8->layout = [' col-sm-12'];

        $row9 = $this->detailFormResponsavelAlunoAluno->addFields([new TLabel("Responsável:", null, '14px', null)],[$responsavel_aluno_aluno_nome,$responsavel_aluno_aluno_id],[new TLabel("Parentesco (Pai, Mãe, Responsável...):", null, '14px', null)],[$responsavel_aluno_aluno_parentesco]);
        $row10 = $this->detailFormResponsavelAlunoAluno->addFields([new TLabel("RG:", null, '14px', null)],[$responsavel_aluno_aluno_rg],[new TLabel("CPF:", null, '14px', null)],[$responsavel_aluno_aluno_cpf]);
        $row11 = $this->detailFormResponsavelAlunoAluno->addFields([new TLabel("Fone 1:", null, '14px', null)],[$responsavel_aluno_aluno_fone_1],[new TLabel("Fone 2:", null, '14px', null)],[$responsavel_aluno_aluno_fone_2]);
        $row12 = $this->detailFormResponsavelAlunoAluno->addFields([$button_adicionar_responsavel_responsavel_aluno_aluno]);
        $row12->layout = [' col-sm-12'];

        $row13 = $this->detailFormResponsavelAlunoAluno->addFields([new THidden('responsavel_aluno_aluno__row__id')]);
        $this->responsavel_aluno_aluno_criteria = new TCriteria();

        $this->responsavel_aluno_aluno_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->responsavel_aluno_aluno_list->disableHtmlConversion();;
        $this->responsavel_aluno_aluno_list->generateHiddenFields();
        $this->responsavel_aluno_aluno_list->setId('responsavel_aluno_aluno_list');

        $this->responsavel_aluno_aluno_list->style = 'width:100%';
        $this->responsavel_aluno_aluno_list->class .= ' table-bordered';

        $column_responsavel_aluno_aluno_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_responsavel_aluno_aluno_parentesco = new TDataGridColumn('parentesco', "Parentesco", 'left');
        $column_responsavel_aluno_aluno_rg = new TDataGridColumn('rg', "Rg", 'left');
        $column_responsavel_aluno_aluno_cpf = new TDataGridColumn('cpf', "Cpf", 'left');
        $column_responsavel_aluno_aluno_fone_1 = new TDataGridColumn('fone_1', "Fone 1", 'left');
        $column_responsavel_aluno_aluno_fone_2 = new TDataGridColumn('fone_2', "Fone 2", 'left');

        $column_responsavel_aluno_aluno__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_responsavel_aluno_aluno__row__data->setVisibility(false);

        $action_onEditDetailResponsavelAluno = new TDataGridAction(array('MatriculaAlunoForm', 'onEditDetailResponsavelAluno'));
        $action_onEditDetailResponsavelAluno->setUseButton(false);
        $action_onEditDetailResponsavelAluno->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailResponsavelAluno->setLabel("Editar");
        $action_onEditDetailResponsavelAluno->setImage('far:edit #478fca');
        $action_onEditDetailResponsavelAluno->setFields(['__row__id', '__row__data']);

        $this->responsavel_aluno_aluno_list->addAction($action_onEditDetailResponsavelAluno);
        $action_onDeleteDetailResponsavelAluno = new TDataGridAction(array('MatriculaAlunoForm', 'onDeleteDetailResponsavelAluno'));
        $action_onDeleteDetailResponsavelAluno->setUseButton(false);
        $action_onDeleteDetailResponsavelAluno->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailResponsavelAluno->setLabel("Excluir");
        $action_onDeleteDetailResponsavelAluno->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailResponsavelAluno->setFields(['__row__id', '__row__data']);

        $this->responsavel_aluno_aluno_list->addAction($action_onDeleteDetailResponsavelAluno);

        $this->responsavel_aluno_aluno_list->addColumn($column_responsavel_aluno_aluno_nome);
        $this->responsavel_aluno_aluno_list->addColumn($column_responsavel_aluno_aluno_parentesco);
        $this->responsavel_aluno_aluno_list->addColumn($column_responsavel_aluno_aluno_rg);
        $this->responsavel_aluno_aluno_list->addColumn($column_responsavel_aluno_aluno_cpf);
        $this->responsavel_aluno_aluno_list->addColumn($column_responsavel_aluno_aluno_fone_1);
        $this->responsavel_aluno_aluno_list->addColumn($column_responsavel_aluno_aluno_fone_2);

        $this->responsavel_aluno_aluno_list->addColumn($column_responsavel_aluno_aluno__row__data);

        $this->responsavel_aluno_aluno_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->responsavel_aluno_aluno_list);
        $this->detailFormResponsavelAlunoAluno->addContent([$tableResponsiveDiv]);

        $row14 = $this->form->addFields([$this->detailFormResponsavelAlunoAluno]);
        $row14->layout = [' col-sm-12'];

        $row15 = $this->form->addContent([new TFormSeparator("Regulamento", '#333', '18', '#eee')]);
        $row16 = $this->form->addFields([$regulamento_2022_2023]);
        $row16->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar os dados e FINALIZAR  a matrícula", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
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
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

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

    public static function onCpf($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            // Recupera o CPF do Aluno informado no Form
            $c_cpf = $param['cpf'];
            // Busca dados do aluno
            //$aluno = Aluno::find( $c_cpf );
            $aluno = Aluno::where('cpf', '=',$c_cpf )->first();

            //var_dump ($aluno);

            // se encontrou CPF
            if($aluno)
            {
                // Recupera os dados nos campos do Form
                $obj = new stdClass;
                $obj->id = $aluno->id;
                $obj->rg   = $aluno->rg;
                $obj->nome = $aluno->nome;
                $obj->situacao_id = 5;
                $obj->data_nascimento = $aluno->data_nascimento;
                $obj->endereco = $aluno->endereco;
                $obj->bairro = $aluno->bairro;
                $obj->cep = $aluno->cep;
                $obj->numero = $aluno->numero;
                $obj->estado_id = $aluno->estado_id;
                $obj->cidade_id = $aluno->cidade_id;
                $obj->fone_1 = $aluno->fone_1;
                $obj->fone_2 = $aluno->fone_2;
                $obj->unidade_id = $aluno->unidade_id;
                $obj->email = $aluno->email;

                // Carrega os saldos no Form
                TForm::sendData(self::$formName, $obj);
            }
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailResponsavelAlunoAluno($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"CPF Responsável", 'name'=>"", 'class'=>'TCPFValidator', 'value'=>[]];
            foreach($requiredFields as $requiredField)
            {
                try
                {
                    (new $requiredField['class'])->validate($requiredField['label'], $data->{$requiredField['name']}, $requiredField['value']);
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->responsavel_aluno_aluno__row__id) ? $data->responsavel_aluno_aluno__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new ResponsavelAluno();
            $grid_data->__row__id = $__row__id;
            $grid_data->nome = $data->responsavel_aluno_aluno_nome;
            $grid_data->id = $data->responsavel_aluno_aluno_id;
            $grid_data->parentesco = $data->responsavel_aluno_aluno_parentesco;
            $grid_data->rg = $data->responsavel_aluno_aluno_rg;
            $grid_data->cpf = $data->responsavel_aluno_aluno_cpf;
            $grid_data->fone_1 = $data->responsavel_aluno_aluno_fone_1;
            $grid_data->fone_2 = $data->responsavel_aluno_aluno_fone_2;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['nome'] =  $param['responsavel_aluno_aluno_nome'] ?? null;
            $__row__data['__display__']['id'] =  $param['responsavel_aluno_aluno_id'] ?? null;
            $__row__data['__display__']['parentesco'] =  $param['responsavel_aluno_aluno_parentesco'] ?? null;
            $__row__data['__display__']['rg'] =  $param['responsavel_aluno_aluno_rg'] ?? null;
            $__row__data['__display__']['cpf'] =  $param['responsavel_aluno_aluno_cpf'] ?? null;
            $__row__data['__display__']['fone_1'] =  $param['responsavel_aluno_aluno_fone_1'] ?? null;
            $__row__data['__display__']['fone_2'] =  $param['responsavel_aluno_aluno_fone_2'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->responsavel_aluno_aluno_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('responsavel_aluno_aluno_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->responsavel_aluno_aluno_nome = '';
            $data->responsavel_aluno_aluno_id = '';
            $data->responsavel_aluno_aluno_parentesco = '';
            $data->responsavel_aluno_aluno_rg = '';
            $data->responsavel_aluno_aluno_cpf = '';
            $data->responsavel_aluno_aluno_fone_1 = '';
            $data->responsavel_aluno_aluno_fone_2 = '';
            $data->responsavel_aluno_aluno__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#619fe1ff67ea4');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailResponsavelAluno($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->responsavel_aluno_aluno_nome = $__row__data->__display__->nome ?? null;
            $data->responsavel_aluno_aluno_id = $__row__data->__display__->id ?? null;
            $data->responsavel_aluno_aluno_parentesco = $__row__data->__display__->parentesco ?? null;
            $data->responsavel_aluno_aluno_rg = $__row__data->__display__->rg ?? null;
            $data->responsavel_aluno_aluno_cpf = $__row__data->__display__->cpf ?? null;
            $data->responsavel_aluno_aluno_fone_1 = $__row__data->__display__->fone_1 ?? null;
            $data->responsavel_aluno_aluno_fone_2 = $__row__data->__display__->fone_2 ?? null;
            $data->responsavel_aluno_aluno__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#619fe1ff67ea4');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailResponsavelAluno($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->responsavel_aluno_aluno_nome = '';
            $data->responsavel_aluno_aluno_id = '';
            $data->responsavel_aluno_aluno_parentesco = '';
            $data->responsavel_aluno_aluno_rg = '';
            $data->responsavel_aluno_aluno_cpf = '';
            $data->responsavel_aluno_aluno_fone_1 = '';
            $data->responsavel_aluno_aluno_fone_2 = '';
            $data->responsavel_aluno_aluno__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('responsavel_aluno_aluno_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Aluno(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
/*
            //inicio Email
            //$tos =  explode(';',$data->email);
            $tos = 'teste@whap.com.br';
            //$contratante_id = $param->id;
            $contratante = $data->nome;
            $status = $data->status;
            $contrato = $data->contrato_url;
            $regulamento = '
            <ul>
                <li>Trabalhamos com duas modalidades aulas presencias e educação a distância;</li>
                <li>Trabalhamos com plano de horas, e não aulas mensais;</li>
                <li>Trabalhamos com planos individual, duplas ou turmas de no máximo 3 alunos por sala;</li>
                <li>Nossa escola não trabalha com taxa de matrícula;</li>
                <li>O aluno tem direito de reposição total das aulas contratadas, desde que siga o regulamento. Deverá o mesmo concluir as horas contratadas enquanto o contrato está vigente. Caso o prazo do contrato expire e o aluno não tenha concluído as horas contratadas, este perde o direito de realizar estas aulas.</li>
                <li>Quando houver renovação de contrato, as horas contratadas não realizadas do contrato anterior, irão somar no novo contrato.</li>
                <li>Para reposições no plano individual, avisar com 1h de antecedência, fazer agendamento junto a secretaria; caso o aluno desmarque a aula em tempo menor, será contado como aula dada sem direito a reposição;</li>
                <li>Para reposições de duplas e/ou turmas, deverá ser agendado com a secretária antes da data da próxima aula, a reposição será de 30 minutos;</li>
                <li>Para alunos com horários de aula às 9h ou 10h o cancelamento deverá ser feito até a noite anterior da aula, até as 20:30h. Para alunos com aulas às 13h30, o cancelamento deverá ser feito até as 11h30 do mesmo dia;</li>
                <li>Para alunos com horários de aula às 9h ou 10h nas segundas-feiras o cancelamento deverá ser feito até as 11h30 do sábado anterior a aula;</li>
                <li>O aluno pode pedir o cancelamento do curso quando desejar, entrando em contato com o setor financeiro para avisar, será aplicado a multa de 20% do valor faltante do contrato;</li>
            </ul>';

            $footer = '<b><i>Este é um e-mail automático e não deve ser respondido!</i></b><BR>';

            $subject = 'Contrato de matrícula:'.' '. $contratante;
            $body = '<b>Matrícula:</b>'.' '.$contratante_id .' <BR/> '.
                    '<b>Status:</b>'.' '.$status .' <BR/> '.
                    '<b>Contratante:</b>'.' '.$contratante .' <BR/> '.

                    '<b>Regulamento:</b>'.' '.$regulamento .' <BR/> '.
                    '<b>Contrato:</b>'.' '.$contrato .' <BR/><BR/><BR/> '.
                    $footer;

            $type = 'html';
*/
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

/*            $this->saveFilesByComma($object, $data, 'anexo1', 'regulamento_anexo1');

            $anexos = [];

            if ($object->anexo1)
            {
                foreach (explode(',', $object->anexo1) as $anexo)
                {
                    $anexos[] = [$anexo, pathinfo($anexo, PATHINFO_FILENAME)]; 
                }
            }
*/

 /*           SpeasyMailService::send(
                $tos,
                $subject,
                $body,
                $type,
                $contrato
            );
*/

            $this->fireEvents($object);

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $responsavel_aluno_aluno_items = $this->storeMasterDetailItems('ResponsavelAluno', 'aluno_id', 'responsavel_aluno_aluno', $object, $param['responsavel_aluno_aluno_list___row__data'] ?? [], $this->form, $this->responsavel_aluno_aluno_list, function($masterObject, $detailObject){ 

            /*    //envia Email
                $email_cadastro = $email->email;
                $type = 'html'; // ou = 'text'
                MailService::send([$email_cadastrado], 'Assunto', 'Mensagem', $type);
            */

            }, $this->responsavel_aluno_aluno_criteria); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

                //envia Email
/*
                $email_cadastrado = $email->email;
                $type = 'html'; // ou = 'text'
                MailService::send([$email_cadastrado], 'Assunto', 'Mensagem', $type);
*/

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

                $responsavel_aluno_aluno_items = $this->loadMasterDetailItems('ResponsavelAluno', 'aluno_id', 'responsavel_aluno_aluno', $object, $this->form, $this->responsavel_aluno_aluno_list, $this->responsavel_aluno_aluno_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);

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

    }

    public function onShow($param = null)
    {

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
        }
        elseif(is_object($object))
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
        }
        TForm::sendData(self::$formName, $obj);
    }  

}


<?php

class AlunoFormView extends TPage
{
    protected $form; // form
    private static $database = 'cdi';
    private static $activeRecord = 'Aluno';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Aluno';

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

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $aluno = new Aluno($param['key']);
        // define the form title
        $this->form->setFormTitle("Alunos");

        $label1 = new TLabel("Id:", '#333', '12px', '');
        $text1 = new TTextDisplay($aluno->id, '#333333', '12px', '');
        $label14 = new TLabel("Unidade:", '#333333', '12px', '');
        $text14 = new TTextDisplay($aluno->unidade->name, '#333333', '12px', '');
        $label2 = new TLabel("Nome:", '#333', '12px', '');
        $text2 = new TTextDisplay($aluno->nome, '#333', '12px', '');
        $label6 = new TLabel("Data nascimento:", '#333333', '12px', '');
        $text7 = new TTextDisplay(TDate::convertToMask($aluno->data_nascimento, 'yyyy-mm-dd', 'dd/mm/yyyy'), '#333333', '12px', '');
        $label3 = new TLabel("Situação:", '#333333', '12px', '');
        $text3 = new TTextDisplay($aluno->situacao->status, '#333333', '12px', '');
        $Convenio = new TLabel("Convênio:", '#333333', '12px', '');
        $text6 = new TTextDisplay($aluno->convenio->descricao, '#333333', '12px', '');
        $label4 = new TLabel("Rg:", '#333', '12px', '');
        $text4 = new TTextDisplay($aluno->rg, '#333', '12px', '');
        $Cpf = new TLabel("Cpf:", '#333', '12px', '');
        $text5 = new TTextDisplay($aluno->cpf, '#333333', '12px', '');
        $label8 = new TLabel("Endereco:", '#333', '12px', '');
        $text8 = new TTextDisplay($aluno->endereco, '#333', '12px', '');
        $label10 = new TLabel("Numero:", '#333', '12px', '');
        $text11 = new TTextDisplay($aluno->numero, '#333333', '12px', '');
        $label11 = new TLabel("Bairro:", '#333333', '12px', '');
        $text9 = new TTextDisplay($aluno->bairro, '#333333', '12px', '');
        $Cep = new TLabel("Cep:", '#333333', '12px', '');
        $text10 = new TTextDisplay($aluno->cep, '#333333', '12px', '');
        $label12 = new TLabel("Cidade:", '#333333', '12px', '');
        $text13 = new TTextDisplay($aluno->cidade->nome, '#333333', '12px', '');
        $uf = new TLabel("UF:", '#333333', '12px', '');
        $text12 = new TTextDisplay($aluno->estado->uf, '#333333', '12px', '');
        $label15 = new TLabel("Fone 2:", '#333', '12px', '');
        $text15 = new TTextDisplay($aluno->fone_2, '#333', '12px', '');
        $label16 = new TLabel("Fone 1:", '#333333', '12px', '');
        $text16 = new TTextDisplay($aluno->fone_1, '#333333', '12px', '');

        $row1 = $this->form->addFields([$label1],[$text1]);
        $row2 = $this->form->addFields([$label14],[$text14]);
        $row3 = $this->form->addFields([$label2],[$text2],[$label6],[$text7]);
        $row4 = $this->form->addFields([$label3],[$text3],[$Convenio],[$text6]);
        $row5 = $this->form->addFields([$label4],[$text4],[$Cpf],[$text5]);
        $row6 = $this->form->addFields([$label8],[$text8],[$label10],[$text11]);
        $row7 = $this->form->addFields([$label11],[$text9],[$Cep],[$text10]);
        $row8 = $this->form->addFields([$label12],[$text13],[$uf],[$text12]);
        $row9 = $this->form->addFields([$label15],[$text15],[$label16],[$text16]);

        $this->curriculo_aluno_aluno_id_list = new TQuickGrid;
        $this->curriculo_aluno_aluno_id_list->disableHtmlConversion();
        $this->curriculo_aluno_aluno_id_list->style = 'width:100%';
        $this->curriculo_aluno_aluno_id_list->disableDefaultClick();

        $column_matricula = $this->curriculo_aluno_aluno_id_list->addQuickColumn("Matricula", 'matricula', 'left');
        $column_idioma_descricao = $this->curriculo_aluno_aluno_id_list->addQuickColumn("Idioma", 'idioma->descricao', 'left');
        $column_stage_book_descricao = $this->curriculo_aluno_aluno_id_list->addQuickColumn("Book", 'stage->book->descricao', 'left');
        $column_stage_descricao = $this->curriculo_aluno_aluno_id_list->addQuickColumn("Stage", 'stage->descricao', 'left');
        $column_unidade_name = $this->curriculo_aluno_aluno_id_list->addQuickColumn("Unidade", 'unidade->name', 'left');
        $column_status = $this->curriculo_aluno_aluno_id_list->addQuickColumn("Status", 'status', 'left');
        $column_observacao = $this->curriculo_aluno_aluno_id_list->addQuickColumn("Observacao", 'observacao', 'left');

        $this->curriculo_aluno_aluno_id_list->createModel();

        $criteria_curriculo_aluno_aluno_id = new TCriteria();
        $criteria_curriculo_aluno_aluno_id->add(new TFilter('aluno_id', '=', $aluno->id));

        $criteria_curriculo_aluno_aluno_id->setProperty('order', 'id desc');

        $curriculo_aluno_aluno_id_items = CurriculoAluno::getObjects($criteria_curriculo_aluno_aluno_id);

        $this->curriculo_aluno_aluno_id_list->addItems($curriculo_aluno_aluno_id_items);

        $icon = new TImage('far:circle #000000');
        $title = new TTextDisplay("{$icon} Currículo", '#333333', '12px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#f5f5f5');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->curriculo_aluno_aluno_id_list));

        $this->form->addContent([$panel]);

        $this->responsavel_aluno_aluno_id_list = new TQuickGrid;
        $this->responsavel_aluno_aluno_id_list->disableHtmlConversion();
        $this->responsavel_aluno_aluno_id_list->style = 'width:100%';
        $this->responsavel_aluno_aluno_id_list->disableDefaultClick();

        $column_nome = $this->responsavel_aluno_aluno_id_list->addQuickColumn("Nome", 'nome', 'left');
        $column_parentesco = $this->responsavel_aluno_aluno_id_list->addQuickColumn("Parentesco", 'parentesco', 'left');
        $column_rg = $this->responsavel_aluno_aluno_id_list->addQuickColumn("Rg", 'rg', 'left');
        $column_cpf = $this->responsavel_aluno_aluno_id_list->addQuickColumn("Cpf", 'cpf', 'left');
        $column_fone_1 = $this->responsavel_aluno_aluno_id_list->addQuickColumn("Fone 1", 'fone_1', 'left');
        $column_fone_2 = $this->responsavel_aluno_aluno_id_list->addQuickColumn("Fone 2", 'fone_2', 'left');

        $this->responsavel_aluno_aluno_id_list->createModel();

        $criteria_responsavel_aluno_aluno_id = new TCriteria();
        $criteria_responsavel_aluno_aluno_id->add(new TFilter('aluno_id', '=', $aluno->id));

        $criteria_responsavel_aluno_aluno_id->setProperty('order', 'id desc');

        $responsavel_aluno_aluno_id_items = ResponsavelAluno::getObjects($criteria_responsavel_aluno_aluno_id);

        $this->responsavel_aluno_aluno_id_list->addItems($responsavel_aluno_aluno_id_items);

        $icon = new TImage('far:circle #000000');
        $title = new TTextDisplay("{$icon} Responsável", '#333333', '12px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#f5f5f5');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->responsavel_aluno_aluno_id_list));

        $this->form->addContent([$panel]);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Alunos View"]));
        }
        $container->add($this->form);

        TTransaction::close();
        parent::add($container);

    }

    public function onShow($param = null)
    {     

    }

}


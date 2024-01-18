<?php

class ContratoAlunoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'ContratoAluno';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContratoAlunoForm';

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
        $this->form->setFormTitle("Cadastro de contrato aluno");

        $criteria_contrato_aluno_parcela_contrato_aluno_forma_pagamento_id = new TCriteria();

        $filterVar = "s";
        $criteria_contrato_aluno_parcela_contrato_aluno_forma_pagamento_id->add(new TFilter('ativo', '=', $filterVar)); 

        $id = new TEntry('id');
        $vigente = new TRadioGroup('vigente');
        $curriculo_aluno_id = new TDBCombo('curriculo_aluno_id', 'cdi', 'CurriculoAluno', 'id', '{aluno->id} {aluno->nome} | {idioma->descricao} - {plano->descricao} - {plano->duracao_aula} | R$ {plano->valor} | {status}','id asc'  );
        $valor_real = new THidden('valor_real');
        $qtd_parcela = new TNumeric('qtd_parcela', '0', '', '' );
        $valor_parcela = new TNumeric('valor_parcela', '2', ',', '.' );
        $qtd_hora = new TNumeric('qtd_hora', '0', '', '' );
        $primeiro_vencimento = new TDate('primeiro_vencimento');
        $button_gerar_parcelas = new TButton('button_gerar_parcelas');
        $contrato_aluno_parcela_contrato_aluno_parcela = new TEntry('contrato_aluno_parcela_contrato_aluno_parcela');
        $contrato_aluno_parcela_contrato_aluno_valor = new TNumeric('contrato_aluno_parcela_contrato_aluno_valor', '2', ',', '.' );
        $contrato_aluno_parcela_contrato_aluno_valor_real = new THidden('contrato_aluno_parcela_contrato_aluno_valor_real');
        $contrato_aluno_parcela_contrato_aluno_data_vencimento = new TDate('contrato_aluno_parcela_contrato_aluno_data_vencimento');
        $contrato_aluno_parcela_contrato_aluno_valor_recebido = new TNumeric('contrato_aluno_parcela_contrato_aluno_valor_recebido', '2', ',', '.' );
        $contrato_aluno_parcela_contrato_aluno_forma_pagamento_id = new TDBCombo('contrato_aluno_parcela_contrato_aluno_forma_pagamento_id', 'cdi', 'FormaPagamento', 'id', '{descricao}','descricao asc' , $criteria_contrato_aluno_parcela_contrato_aluno_forma_pagamento_id );
        $contrato_aluno_parcela_contrato_aluno_data_recebimento = new TDate('contrato_aluno_parcela_contrato_aluno_data_recebimento');
        $button_confirmar_recebimento_da_parcela_contrato_aluno_parcela_contrato_aluno = new TButton('button_confirmar_recebimento_da_parcela_contrato_aluno_parcela_contrato_aluno');

        $curriculo_aluno_id->setChangeAction(new TAction([$this,'onCarregaParcela']));

        $curriculo_aluno_id->addValidation("Aluno/Currículo", new TRequiredValidator()); 
        $qtd_parcela->addValidation("Valor parcela", new TRequiredValidator()); 
        $qtd_hora->addValidation("Valor total", new TRequiredValidator()); 

        $vigente->addItems(["1"=>"Sim","2"=>"Não"]);
        $vigente->setLayout('horizontal');
        $vigente->setValue('1');
        $vigente->setBooleanMode();
        $vigente->setUseButton();
        $qtd_hora->setAllowNegative(false);
        $curriculo_aluno_id->enableSearch();
        $contrato_aluno_parcela_contrato_aluno_forma_pagamento_id->enableSearch();

        $button_gerar_parcelas->setAction(new TAction([$this, 'onGerarParcelas'],['static' => 1]), "Gerar Parcelas");
        $button_confirmar_recebimento_da_parcela_contrato_aluno_parcela_contrato_aluno->setAction(new TAction([$this, 'onAddDetailContratoAlunoParcelaContratoAluno'],['static' => 1]), "Confirmar recebimento da parcela");

        $button_gerar_parcelas->addStyleClass('btn-default');
        $button_confirmar_recebimento_da_parcela_contrato_aluno_parcela_contrato_aluno->addStyleClass('btn-default');

        $button_gerar_parcelas->setImage('fas:money-bill-wave #4CAF50');
        $button_confirmar_recebimento_da_parcela_contrato_aluno_parcela_contrato_aluno->setImage('fas:check-circle #2ecc71');

        $button_confirmar_recebimento_da_parcela_contrato_aluno_parcela_contrato_aluno->id = '6310f97a10c5b';

        $primeiro_vencimento->setMask('dd/mm/yyyy');
        $contrato_aluno_parcela_contrato_aluno_data_vencimento->setMask('dd/mm/yyyy');
        $contrato_aluno_parcela_contrato_aluno_data_recebimento->setMask('dd/mm/yyyy');

        $primeiro_vencimento->setDatabaseMask('yyyy-mm-dd');
        $contrato_aluno_parcela_contrato_aluno_data_vencimento->setDatabaseMask('yyyy-mm-dd');
        $contrato_aluno_parcela_contrato_aluno_data_recebimento->setDatabaseMask('yyyy-mm-dd');

        $id->setEditable(false);
        $qtd_hora->setEditable(false);
        $qtd_parcela->setEditable(false);
        $valor_parcela->setEditable(false);
        $contrato_aluno_parcela_contrato_aluno_valor->setEditable(false);
        $contrato_aluno_parcela_contrato_aluno_parcela->setEditable(false);
        $contrato_aluno_parcela_contrato_aluno_data_vencimento->setEditable(false);

        $id->setSize(100);
        $vigente->setSize(80);
        $valor_real->setSize(200);
        $qtd_hora->setSize('100%');
        $qtd_parcela->setSize('100%');
        $valor_parcela->setSize('100%');
        $primeiro_vencimento->setSize(110);
        $curriculo_aluno_id->setSize('100%');
        $contrato_aluno_parcela_contrato_aluno_valor->setSize('100%');
        $contrato_aluno_parcela_contrato_aluno_parcela->setSize('100%');
        $contrato_aluno_parcela_contrato_aluno_valor_real->setSize(200);
        $contrato_aluno_parcela_contrato_aluno_data_vencimento->setSize(110);
        $contrato_aluno_parcela_contrato_aluno_data_recebimento->setSize(110);
        $contrato_aluno_parcela_contrato_aluno_valor_recebido->setSize('100%');
        $contrato_aluno_parcela_contrato_aluno_forma_pagamento_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Contrato:", null, '14px', null)],[$id],[new TLabel("Vigente?", null, '14px', null)],[$vigente]);
        $row2 = $this->form->addFields([new TLabel("Aluno/Currículo:", '#ff0000', '14px', null)],[$curriculo_aluno_id]);
        $row3 = $this->form->addFields([$valor_real],[new TLabel("Quantidade parcelas:", '#ff0000', '14px', null, '100%'),$qtd_parcela],[new TLabel("Valor parcela:", '#FF0000', '14px', null, '100%'),$valor_parcela],[new TLabel("Horas contratadas:", '#ff0000', '14px', null, '100%'),$qtd_hora],[new TLabel("Primeiro vencimento:", '#FF0000', '14px', null, '100%'),$primeiro_vencimento],[new TLabel(".", '#FFFFFF', '14px', null, '100%'),$button_gerar_parcelas]);
        $row3->layout = ['col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $this->detailFormContratoAlunoParcelaContratoAluno = new BootstrapFormBuilder('detailFormContratoAlunoParcelaContratoAluno');
        $this->detailFormContratoAlunoParcelaContratoAluno->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormContratoAlunoParcelaContratoAluno->setProperty('class', 'form-horizontal builder-detail-form');

        $row4 = $this->detailFormContratoAlunoParcelaContratoAluno->addFields([new TFormSeparator("Parcelas", '#333', '18', '#eee')]);
        $row4->layout = ['col-sm-12'];

        $row5 = $this->detailFormContratoAlunoParcelaContratoAluno->addFields([new TLabel("Parcela:", null, '14px', null, '100%'),$contrato_aluno_parcela_contrato_aluno_parcela],[new TLabel("Valor:", null, '14px', null, '100%'),$contrato_aluno_parcela_contrato_aluno_valor,$contrato_aluno_parcela_contrato_aluno_valor_real],[new TLabel("Data vencimento:", null, '14px', null, '100%'),$contrato_aluno_parcela_contrato_aluno_data_vencimento],[new TLabel("Valor recebido:", null, '14px', null),$contrato_aluno_parcela_contrato_aluno_valor_recebido],[new TLabel("Forma recebimento:", null, '14px', null),$contrato_aluno_parcela_contrato_aluno_forma_pagamento_id],[new TLabel("Data recebimento:", null, '14px', null, '100%'),$contrato_aluno_parcela_contrato_aluno_data_recebimento]);
        $row5->layout = [' col-sm-2','col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row6 = $this->detailFormContratoAlunoParcelaContratoAluno->addFields([$button_confirmar_recebimento_da_parcela_contrato_aluno_parcela_contrato_aluno]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->detailFormContratoAlunoParcelaContratoAluno->addFields([new THidden('contrato_aluno_parcela_contrato_aluno__row__id')]);
        $this->contrato_aluno_parcela_contrato_aluno_criteria = new TCriteria();

        $this->contrato_aluno_parcela_contrato_aluno_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->contrato_aluno_parcela_contrato_aluno_list->disableHtmlConversion();;
        $this->contrato_aluno_parcela_contrato_aluno_list->generateHiddenFields();
        $this->contrato_aluno_parcela_contrato_aluno_list->setId('contrato_aluno_parcela_contrato_aluno_list');

        $this->contrato_aluno_parcela_contrato_aluno_list->style = 'width:100%';
        $this->contrato_aluno_parcela_contrato_aluno_list->class .= ' table-bordered';

        $column_contrato_aluno_parcela_contrato_aluno_parcela = new TDataGridColumn('parcela', "Parcela", 'left');
        $column_contrato_aluno_parcela_contrato_aluno_data_vencimento_transformed = new TDataGridColumn('data_vencimento', "Vencimento", 'center');
        $column_contrato_aluno_parcela_contrato_aluno_valor_transformed = new TDataGridColumn('valor', "Valor a receber", 'right');
        $column_contrato_aluno_parcela_contrato_aluno_data_recebimento_transformed = new TDataGridColumn('data_recebimento', "Data recebimento", 'center');
        $column_contrato_aluno_parcela_contrato_aluno_valor_recebido_transformed = new TDataGridColumn('valor_recebido', "Valor recebido", 'right');
        $column_contrato_aluno_parcela_contrato_aluno_forma_pagamento_id_transformed = new TDataGridColumn('forma_pagamento_id', "Forma recebimento", 'center');

        $column_contrato_aluno_parcela_contrato_aluno__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_contrato_aluno_parcela_contrato_aluno__row__data->setVisibility(false);

        $column_contrato_aluno_parcela_contrato_aluno_valor_transformed->enableTotal('sum', 'R$', 2, ',', '.');
        $column_contrato_aluno_parcela_contrato_aluno_valor_recebido_transformed->enableTotal('sum', 'R$', 2, ',', '.');

        $action_onEditDetailContratoAlunoParcela = new TDataGridAction(array('ContratoAlunoForm', 'onEditDetailContratoAlunoParcela'));
        $action_onEditDetailContratoAlunoParcela->setUseButton(false);
        $action_onEditDetailContratoAlunoParcela->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailContratoAlunoParcela->setLabel("Receber parcela");
        $action_onEditDetailContratoAlunoParcela->setImage('fas:cash-register #4CAF50');
        $action_onEditDetailContratoAlunoParcela->setFields(['__row__id', '__row__data']);

        $this->contrato_aluno_parcela_contrato_aluno_list->addAction($action_onEditDetailContratoAlunoParcela);
        $action_onDeleteDetailContratoAlunoParcela = new TDataGridAction(array('ContratoAlunoForm', 'onDeleteDetailContratoAlunoParcela'));
        $action_onDeleteDetailContratoAlunoParcela->setUseButton(false);
        $action_onDeleteDetailContratoAlunoParcela->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailContratoAlunoParcela->setLabel("Excluir");
        $action_onDeleteDetailContratoAlunoParcela->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailContratoAlunoParcela->setFields(['__row__id', '__row__data']);

        $this->contrato_aluno_parcela_contrato_aluno_list->addAction($action_onDeleteDetailContratoAlunoParcela);

        $this->contrato_aluno_parcela_contrato_aluno_list->addColumn($column_contrato_aluno_parcela_contrato_aluno_parcela);
        $this->contrato_aluno_parcela_contrato_aluno_list->addColumn($column_contrato_aluno_parcela_contrato_aluno_data_vencimento_transformed);
        $this->contrato_aluno_parcela_contrato_aluno_list->addColumn($column_contrato_aluno_parcela_contrato_aluno_valor_transformed);
        $this->contrato_aluno_parcela_contrato_aluno_list->addColumn($column_contrato_aluno_parcela_contrato_aluno_data_recebimento_transformed);
        $this->contrato_aluno_parcela_contrato_aluno_list->addColumn($column_contrato_aluno_parcela_contrato_aluno_valor_recebido_transformed);
        $this->contrato_aluno_parcela_contrato_aluno_list->addColumn($column_contrato_aluno_parcela_contrato_aluno_forma_pagamento_id_transformed);

        $this->contrato_aluno_parcela_contrato_aluno_list->addColumn($column_contrato_aluno_parcela_contrato_aluno__row__data);

        $this->contrato_aluno_parcela_contrato_aluno_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->contrato_aluno_parcela_contrato_aluno_list);
        $this->detailFormContratoAlunoParcelaContratoAluno->addContent([$tableResponsiveDiv]);

        $column_contrato_aluno_parcela_contrato_aluno_data_vencimento_transformed->setTransformer(function($value, $object, $row) 
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

        $column_contrato_aluno_parcela_contrato_aluno_valor_transformed->setTransformer(function($value, $object, $row) 
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
        });

        $column_contrato_aluno_parcela_contrato_aluno_data_recebimento_transformed->setTransformer(function($value, $object, $row) 
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

        $column_contrato_aluno_parcela_contrato_aluno_valor_recebido_transformed->setTransformer(function($value, $object, $row) 
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
        });

        $column_contrato_aluno_parcela_contrato_aluno_forma_pagamento_id_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });        $row8 = $this->form->addFields([$this->detailFormContratoAlunoParcelaContratoAluno]);
        $row8->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ContratoAlunoHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Lançamentos","Cadastro de contrato aluno"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onCarregaParcela($param = null) 
    {
        try 
        {

            TTransaction::open(self::$database); // open a transaction
            $parcela   = new CurriculoAluno($param['key']);

            $object = new stdClass();
            $object->qtd_parcela   = $parcela->qtd_parcela;
            $object->valor_parcela = $parcela->valor_parcela;
            $object->qtd_hora      = $parcela->qtd_hora;
            $object->valor_real    = $parcela->plano->valor;

            TForm::sendData(self::$formName, $object);

            //Debug
            //var_dump($object->valor_parcela);exit;
            //return "{$parcela->valor_hora}";

            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onGerarParcelas($param = null) 
    {
        try 
        {

        $data = $this->form->getData();
        // verificando se os campos foram preenchidos
        /*(new TRequiredValidator)->validate('Valor real', $data->valor_real);
        (new TRequiredValidator)->validate('Valor parcela', $data->valor_parcela);
        (new TRequiredValidator)->validate('Qtd parcelas', $data->qtd_parcela);
        (new TRequiredValidator)->validate('Primeiro vencimento', $data->primeiro_vencimento);*/

        // calcula o valor da parcela
        //$valorParcela = $data->valor_parcela; 
        // convertendo o valor que vem no formato brasileiro para formato americano
        $valor_parcela = (double) str_replace('.', ',', str_replace(',', '.', $param['valor_parcela']));
        $valor_real    = (double) str_replace('.', ',', str_replace(',', '.', $param['valor_real']));

        //$valorReal    = $data->valor_real;
        // transforma a data de vencimento em um objeto da classe DateTime
        $data_vencimento = new DateTime($data->primeiro_vencimento);

        for($i = 0 ; $i < $data->qtd_parcela; $i++)
        {
            // cria um objeto para simular um envio de dados
            $formData = new stdClass();
            // propriedades com os nomes dos campos do detalhe

            $formData->contrato_aluno_parcela_contrato_aluno_valor_real = $valor_real;
            $formData->contrato_aluno_parcela_contrato_aluno_valor      = $valor_parcela;
            $formData->contrato_aluno_parcela_contrato_aluno_id         = null;
            $formData->contrato_aluno_parcela_contrato_aluno_parcela    = $i + 1;
            //
            $formData->contrato_aluno_parcela_contrato_aluno_data_vencimento = $data_vencimento->format('Y-m-d');
            // chama a função padrão que adiciona o detalhe, passando por parâmetro
            // a variável formData que acabamos de criar que irá simular o preenchimento
            // do formulário detalhe
            $this->onAddDetailContratoAlunoParcelaContratoAluno([
                'customFormData' => $formData,
                // simulando os dados enviados de forma bruta do formulário detalhe
                // ou seja, valores formatados
                'contrato_aluno_parcela_contrato_aluno_valor_real'      => $formData->contrato_aluno_parcela_contrato_aluno_valor_real,
                'contrato_aluno_parcela_contrato_aluno_valor'           => $formData->contrato_aluno_parcela_contrato_aluno_valor,
                'contrato_aluno_parcela_contrato_aluno_id'              => $formData->contrato_aluno_parcela_contrato_aluno_id, 
                'contrato_aluno_parcela_contrato_aluno_parcela'         => $formData->contrato_aluno_parcela_contrato_aluno_parcela,
                'contrato_aluno_parcela_contrato_aluno_data_vencimento' => $data_vencimento->format('d/m/Y')
            ]);
            // acrescenta um mês a data de vencimento
            $data_vencimento->modify( 'next month' );

        }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailContratoAlunoParcelaContratoAluno($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            if(!empty($param['customFormData']))
            {
                $data = $param['customFormData'];
            }

            $__row__id = !empty($data->contrato_aluno_parcela_contrato_aluno__row__id) ? $data->contrato_aluno_parcela_contrato_aluno__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new ContratoAlunoParcela();
            $grid_data->__row__id = $__row__id;
            $grid_data->parcela = $data->contrato_aluno_parcela_contrato_aluno_parcela;
            $grid_data->valor = $data->contrato_aluno_parcela_contrato_aluno_valor;
            $grid_data->valor_real = $data->contrato_aluno_parcela_contrato_aluno_valor_real;
            $grid_data->data_vencimento = $data->contrato_aluno_parcela_contrato_aluno_data_vencimento;
            $grid_data->valor_recebido = $data->contrato_aluno_parcela_contrato_aluno_valor_recebido;
            $grid_data->forma_pagamento_id = $data->contrato_aluno_parcela_contrato_aluno_forma_pagamento_id;
            $grid_data->data_recebimento = $data->contrato_aluno_parcela_contrato_aluno_data_recebimento;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['parcela'] =  $param['contrato_aluno_parcela_contrato_aluno_parcela'] ?? null;
            $__row__data['__display__']['valor'] =  $param['contrato_aluno_parcela_contrato_aluno_valor'] ?? null;
            $__row__data['__display__']['valor_real'] =  $param['contrato_aluno_parcela_contrato_aluno_valor_real'] ?? null;
            $__row__data['__display__']['data_vencimento'] =  $param['contrato_aluno_parcela_contrato_aluno_data_vencimento'] ?? null;
            $__row__data['__display__']['valor_recebido'] =  $param['contrato_aluno_parcela_contrato_aluno_valor_recebido'] ?? null;
            $__row__data['__display__']['forma_pagamento_id'] =  $param['contrato_aluno_parcela_contrato_aluno_forma_pagamento_id'] ?? null;
            $__row__data['__display__']['data_recebimento'] =  $param['contrato_aluno_parcela_contrato_aluno_data_recebimento'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->contrato_aluno_parcela_contrato_aluno_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('contrato_aluno_parcela_contrato_aluno_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->contrato_aluno_parcela_contrato_aluno_parcela = '';
            $data->contrato_aluno_parcela_contrato_aluno_valor = '';
            $data->contrato_aluno_parcela_contrato_aluno_valor_real = '';
            $data->contrato_aluno_parcela_contrato_aluno_data_vencimento = '';
            $data->contrato_aluno_parcela_contrato_aluno_valor_recebido = '';
            $data->contrato_aluno_parcela_contrato_aluno_forma_pagamento_id = '';
            $data->contrato_aluno_parcela_contrato_aluno_data_recebimento = '';
            $data->contrato_aluno_parcela_contrato_aluno__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6310f97a10c5b');
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

    public static function onEditDetailContratoAlunoParcela($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->contrato_aluno_parcela_contrato_aluno_parcela = $__row__data->__display__->parcela ?? null;
            $data->contrato_aluno_parcela_contrato_aluno_valor = $__row__data->__display__->valor ?? null;
            $data->contrato_aluno_parcela_contrato_aluno_valor_real = $__row__data->__display__->valor_real ?? null;
            $data->contrato_aluno_parcela_contrato_aluno_data_vencimento = $__row__data->__display__->data_vencimento ?? null;
            $data->contrato_aluno_parcela_contrato_aluno_valor_recebido = $__row__data->__display__->valor_recebido ?? null;
            $data->contrato_aluno_parcela_contrato_aluno_forma_pagamento_id = $__row__data->__display__->forma_pagamento_id ?? null;
            $data->contrato_aluno_parcela_contrato_aluno_data_recebimento = $__row__data->__display__->data_recebimento ?? null;
            $data->contrato_aluno_parcela_contrato_aluno__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6310f97a10c5b');
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
    public static function onDeleteDetailContratoAlunoParcela($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->contrato_aluno_parcela_contrato_aluno_parcela = '';
            $data->contrato_aluno_parcela_contrato_aluno_valor = '';
            $data->contrato_aluno_parcela_contrato_aluno_valor_real = '';
            $data->contrato_aluno_parcela_contrato_aluno_data_vencimento = '';
            $data->contrato_aluno_parcela_contrato_aluno_valor_recebido = '';
            $data->contrato_aluno_parcela_contrato_aluno_forma_pagamento_id = '';
            $data->contrato_aluno_parcela_contrato_aluno_data_recebimento = '';
            $data->contrato_aluno_parcela_contrato_aluno__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('contrato_aluno_parcela_contrato_aluno_list', $__row__data->__row__id);

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

            $object = new ContratoAluno(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $contrato_aluno_parcela_contrato_aluno_items = $this->storeMasterDetailItems('ContratoAlunoParcela', 'contrato_aluno_id', 'contrato_aluno_parcela_contrato_aluno', $object, $param['contrato_aluno_parcela_contrato_aluno_list___row__data'] ?? [], $this->form, $this->contrato_aluno_parcela_contrato_aluno_list, function($masterObject, $detailObject){ 

                //code here

            }, $this->contrato_aluno_parcela_contrato_aluno_criteria); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ContratoAlunoHeaderList', 'onShow', $loadPageParam); 

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

                $object = new ContratoAluno($key); // instantiates the Active Record 

                $contrato_aluno_parcela_contrato_aluno_items = $this->loadMasterDetailItems('ContratoAlunoParcela', 'contrato_aluno_id', 'contrato_aluno_parcela_contrato_aluno', $object, $this->form, $this->contrato_aluno_parcela_contrato_aluno_list, $this->contrato_aluno_parcela_contrato_aluno_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

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

}


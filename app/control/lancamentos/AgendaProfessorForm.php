<?php

class AgendaProfessorForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Agenda';
    private static $primaryKey = 'id';
    private static $formName = 'form_Agenda';
    private static $startDateField = 'horario_inicial';
    private static $endDateField = 'horario_final';

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
        $this->form->setFormTitle("Agenda Professor");

        $view = new THidden('view');

        $criteria_unidade_id = new TCriteria();
        $criteria_sala_id = new TCriteria();
        $criteria_professor_id = new TCriteria();
        $criteria_aula_agenda_professor_id = new TCriteria();

        $filterVar = TSession::getValue("userunitids");
        $criteria_unidade_id->add(new TFilter('id', 'in', $filterVar)); 
        $filterVar = TSession::getValue("userunitids");
        $criteria_sala_id->add(new TFilter('unidade_id', 'in', $filterVar)); 
        $filterVar = TSession::getValue("userid");
        $criteria_professor_id->add(new TFilter('id', '=', $filterVar)); 
        $filterVar = TSession::getValue("userid");
        $criteria_aula_agenda_professor_id->add(new TFilter('id', '=', $filterVar)); 

        //$this->link_sal->href = 'https://meet.google.com/xsx-qaia-bbn';
        //$this->link_sal->target = '_blank';
        //$this->link_sal->add('Sala virtual, clique aqui.');

        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc' , $criteria_unidade_id );
        $sala_id = new TDBCombo('sala_id', 'cdi', 'Sala', 'id', '{descricao}','descricao asc' , $criteria_sala_id );
        $professor_id = new TDBCombo('professor_id', 'permission', 'SystemUsers', 'id', '{name}','name asc' , $criteria_professor_id );
        $turma_id = new TDBCombo('turma_id', 'cdi', 'Turma', 'id', '{descricao}','id asc'  );
        $horario_inicial = new TDateTime('horario_inicial');
        $horario_final = new TDateTime('horario_final');
        $link_sala = new TEntry('link_sala');
        $aula_realizada = new TRadioGroup('aula_realizada');
        $ultimas_aulas = new BPageContainer();
        $aula_agenda_professor_id = new TDBCombo('aula_agenda_professor_id', 'permission', 'SystemUsers', 'id', '{name}','name asc' , $criteria_aula_agenda_professor_id );
        $aula_agenda_tipo_aula = new TCombo('aula_agenda_tipo_aula');
        $aula_agenda_data_aula = new TDate('aula_agenda_data_aula');
        $aula_agenda_curriculo_aluno_id = new TDBCombo('aula_agenda_curriculo_aluno_id', 'cdi', 'CurriculoAluno', 'id', '{aluno->nome} | {idioma->descricao} {stage->descricao}','id asc'  );
        $aula_agenda_presente = new TRadioGroup('aula_agenda_presente');
        $aula_agenda_ultima_pagina = new TEntry('aula_agenda_ultima_pagina');
        $aula_agenda_ultima_palavra = new TEntry('aula_agenda_ultima_palavra');
        $aula_agenda_observacao = new TText('aula_agenda_observacao');
        $aula_agenda_id = new THidden('aula_agenda_id');

        $turma_id->addValidation("Turma id", new TRequiredValidator()); 
        $horario_inicial->addValidation("Horário inicial", new TRequiredValidator()); 
        $horario_final->addValidation("Horário final", new TRequiredValidator()); 
        $aula_realizada->addValidation("Confirmar aula?", new TRequiredValidator()); 

        $aula_realizada->setBooleanMode();
        $ultimas_aulas->setAction(new TAction(['AulaSimpleList', 'onShow'], $param));
        $ultimas_aulas->setId('b61ef325cb76de');
        $ultimas_aulas->hide();
        $aula_agenda_professor_id->setValue(TSession::getValue('userid'));
        $aula_agenda_curriculo_aluno_id->enableSearch();
        $aula_realizada->setLayout('horizontal');
        $aula_agenda_presente->setLayout('horizontal');

        $aula_realizada->setUseButton();
        $aula_agenda_presente->setUseButton();

        $aula_agenda_ultima_pagina->setMaxLength(100);
        $aula_agenda_ultima_palavra->setMaxLength(240);

        $horario_final->setMask('dd/mm/yyyy hh:ii');
        $horario_inicial->setMask('dd/mm/yyyy hh:ii');
        $aula_agenda_data_aula->setMask('dd/mm/yyyy');

        $horario_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');
        $aula_agenda_data_aula->setDatabaseMask('yyyy-mm-dd');

        $aula_realizada->addItems(["1"=>"Sim","2"=>"Não"]);
        $aula_agenda_presente->addItems(["p"=>"Presente","a"=>"Ausente"]);
        $aula_agenda_tipo_aula->addItems(["n"=>"Normal","r"=>"Reposição"]);

        $id->setEditable(false);
        $sala_id->setEditable(false);
        $turma_id->setEditable(false);
        $unidade_id->setEditable(false);
        $professor_id->setEditable(false);
        $horario_final->setEditable(false);
        $horario_inicial->setEditable(false);
        $aula_agenda_professor_id->setEditable(false);

        $id->setSize(100);
        $sala_id->setSize('100%');
        $turma_id->setSize('100%');
        $link_sala->setSize('82%');
        $unidade_id->setSize('100%');
        $horario_final->setSize(150);
        $professor_id->setSize('100%');
        $horario_inicial->setSize(150);
        $ultimas_aulas->setSize('100%');
        $aula_realizada->setSize('100%');
        $aula_agenda_data_aula->setSize(110);
        $aula_agenda_presente->setSize('100%');
        $aula_agenda_tipo_aula->setSize('100%');
        $aula_agenda_professor_id->setSize('100%');
        $aula_agenda_ultima_pagina->setSize('100%');
        $aula_agenda_ultima_palavra->setSize('100%');
        $aula_agenda_observacao->setSize('100%', 70);
        $aula_agenda_curriculo_aluno_id->setSize('100%');


        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $ultimas_aulas->add($loadingContainer);

        $this->ultimas_aulas = $ultimas_aulas;

        TTransaction::open(self::$database);
        $agenda = Agenda::find($param['key']);
        $turma = Turma::find($agenda->turma_id);

        $curriculo = CurriculoAluno::where(
            'idioma_id', '=', $turma->idioma_id
        )->where(
            'book_id', '=', $turma->book_id
        )->where(
            'stage_id', '=', $turma->stage_id
        )->first();

        if($curriculo)
        {
            $turmaAlunos = TurmaAlunos::where('turma_id', '=', $turma->id)->load();

            $turmaAlunos = array_map(function($item){
                return $item->aluno->nome;
            }, $turmaAlunos);

            $alunos = implode(',', $turmaAlunos);
            $descricaoCurriculo = "{$curriculo->idioma->descricao} - {$curriculo->book->descricao} - {$curriculo->stage->descricao} ({$alunos})";
            $aula_agenda_curriculo_aluno_id->addItems([$curriculo->id => $descricaoCurriculo]);
            $aula_agenda_curriculo_aluno_id->setValue($curriculo->id);
        }

        TTransaction::close();

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[],[]);
        $row2 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unidade_id],[new TLabel("Sala:", null, '14px', null)],[$sala_id]);
        $row3 = $this->form->addFields([new TLabel("Professor:", null, '14px', null)],[$professor_id]);
        $row4 = $this->form->addFields([new TLabel("Turma:", null, '14px', null)],[$turma_id]);
        $row5 = $this->form->addFields([new TLabel("Horário inicial:", null, '14px', null)],[$horario_inicial],[new TLabel("Horário final:", null, '14px', null)],[$horario_final]);
        $row6 = $this->form->addFields([new TLabel("Link sala:", null, '14px', null)],[$link_sala]);
        $row7 = $this->form->addFields([new TLabel("Aula dada?", '#FF0000', '14px', null)],[$aula_realizada],[],[]);
        $row8 = $this->form->addFields([new TFormSeparator("Ultimas aulas", '#333', '18', '#eee'),$ultimas_aulas]);
        $row8->layout = [' col-sm-12'];

        $row9 = $this->form->addFields([new TFormSeparator("Registro da aula", '#333333', '18', '#eeeeee')]);
        $row9->layout = [' col-sm-12'];

        $row10 = $this->form->addFields([new TLabel("Professor:", null, '14px', null)],[$aula_agenda_professor_id]);
        $row11 = $this->form->addFields([new TLabel("Tipo aula:", '#ff0000', '14px', null)],[$aula_agenda_tipo_aula],[new TLabel("Data aula:", '#ff0000', '14px', null)],[$aula_agenda_data_aula]);
        $row12 = $this->form->addFields([new TLabel("Currículo/aluno:", '#ff0000', '14px', null)],[$aula_agenda_curriculo_aluno_id],[new TLabel("Presença:", '#ff0000', '14px', null)],[$aula_agenda_presente]);
        $row13 = $this->form->addFields([new TLabel("Página:", '#ff0000', '14px', null)],[$aula_agenda_ultima_pagina],[new TLabel("Ultima palavra:", '#ff0000', '14px', null)],[$aula_agenda_ultima_palavra]);
        $row14 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$aula_agenda_observacao]);
        $row15 = $this->form->addFields([$aula_agenda_id]);         
        $add_aula_agenda = new TButton('add_aula_agenda');

        $action_aula_agenda = new TAction(array($this, 'onAddAulaAgenda'));

        $add_aula_agenda->setAction($action_aula_agenda, "Adicionar");
        $add_aula_agenda->setImage('fas:plus #000000');

        $this->form->addFields([$add_aula_agenda]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->aula_agenda_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->aula_agenda_list->style = 'width:100%';
        $this->aula_agenda_list->class .= ' table-bordered';
        $this->aula_agenda_list->disableDefaultClick();
        $this->aula_agenda_list->addQuickColumn('', 'edit', 'left', 50);
        $this->aula_agenda_list->addQuickColumn('', 'delete', 'left', 50);

        $column_aula_agenda_tipo_aula_transformed = $this->aula_agenda_list->addQuickColumn("Tipo", 'aula_agenda_tipo_aula', 'left' , '10%');
        $column_aula_agenda_data_aula_transformed = $this->aula_agenda_list->addQuickColumn("Data", 'aula_agenda_data_aula', 'left' , '10%');
        $column_aula_agenda_ultima_pagina = $this->aula_agenda_list->addQuickColumn("Página", 'aula_agenda_ultima_pagina', 'left' , '10%');
        $column_aula_agenda_ultima_palavra = $this->aula_agenda_list->addQuickColumn("Ultima palavra", 'aula_agenda_ultima_palavra', 'left' , '30%');
        $column_aula_agenda_observacao = $this->aula_agenda_list->addQuickColumn("Observação", 'aula_agenda_observacao', 'left' , '40%');

        $this->aula_agenda_list->createModel();
        $this->form->addContent([$this->aula_agenda_list]);

        $column_aula_agenda_tipo_aula_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if($value)
            {
                return mb_strtoupper($value);
            }
        });

        $column_aula_agenda_data_aula_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $this->form->addFields([$view]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_ondelete = $this->form->addAction("Excluir", new TAction([$this, 'onDelete']), 'fas:trash-alt #dd5a43');
        $this->btn_ondelete = $btn_ondelete;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=AgendaProfessorForm]');
        $style->width = '80% !important';   
        $style->show(true);

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

            $object = new Agenda(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $messageAction = new TAction(['AgendaProfessorFormView', 'onReload']);
            $messageAction->setParameter('view', $data->view);
            $messageAction->setParameter('date', explode(' ', $data->horario_inicial)[0]);

            $aula_agenda_items = $this->storeItems('Aula', 'agenda_id', $object, 'aula_agenda', function($masterObject, $detailObject){ 

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

            new TMessage('info', "Registro salvo", $messageAction); 

                        TScript::create("Template.closeRightPanel();"); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onDelete($param = null) 
    {
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $key = $param[self::$primaryKey];

                // open a transaction with database
                TTransaction::open(self::$database);

                $class = self::$activeRecord;

                // instantiates object
                $object = new $class($key, FALSE);

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                $messageAction = new TAction(array(__CLASS__.'View', 'onReload'));
                $messageAction->setParameter('view', $param['view']);
                $messageAction->setParameter('date', explode(' ',$param[self::$startDateField])[0]);

                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'), $messageAction);
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters((array) $this->form->getData());
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
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

                $object = new Agenda($key); // instantiates the Active Record 

                                $this->ultimas_aulas->unhide();
                $this->ultimas_aulas->setParameter('agenda_id', $object->id);
                $object->view = !empty($param['view']) ? $param['view'] : 'agendaWeek'; 

                $aula_agenda_items = $this->loadItems('Aula', 'agenda_id', $object, 'aula_agenda', function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

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

        TSession::setValue('aula_agenda_items', null);

        $this->onReload();
    }

    public function onAddAulaAgenda( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->aula_agenda_professor_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Professor"));
            }             
            if(!$data->aula_agenda_data_aula)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Data aula"));
            }             
            if(!$data->aula_agenda_curriculo_aluno_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Curriculo aluno id"));
            }             
            if(!$data->aula_agenda_presente)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Presença:"));
            }             
            if(!$data->aula_agenda_ultima_pagina)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Ultima página"));
            }             
            if(!$data->aula_agenda_ultima_palavra)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Ultima palavra"));
            }             

            $aula_agenda_items = TSession::getValue('aula_agenda_items');
            $key = isset($data->aula_agenda_id) && $data->aula_agenda_id ? $data->aula_agenda_id : 'b'.uniqid();
            $fields = []; 

            $fields['aula_agenda_professor_id'] = $data->aula_agenda_professor_id;
            $fields['aula_agenda_tipo_aula'] = $data->aula_agenda_tipo_aula;
            $fields['aula_agenda_data_aula'] = $data->aula_agenda_data_aula;
            $fields['aula_agenda_curriculo_aluno_id'] = $data->aula_agenda_curriculo_aluno_id;
            $fields['aula_agenda_presente'] = $data->aula_agenda_presente;
            $fields['aula_agenda_ultima_pagina'] = $data->aula_agenda_ultima_pagina;
            $fields['aula_agenda_ultima_palavra'] = $data->aula_agenda_ultima_palavra;
            $fields['aula_agenda_observacao'] = $data->aula_agenda_observacao;
            $aula_agenda_items[ $key ] = $fields;

            TSession::setValue('aula_agenda_items', $aula_agenda_items);

            $data->aula_agenda_id = '';
            $data->aula_agenda_professor_id = '';
            $data->aula_agenda_tipo_aula = '';
            $data->aula_agenda_data_aula = '';
            $data->aula_agenda_curriculo_aluno_id = '';
            $data->aula_agenda_presente = '';
            $data->aula_agenda_ultima_pagina = '';
            $data->aula_agenda_ultima_palavra = '';
            $data->aula_agenda_observacao = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditAulaAgenda( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('aula_agenda_items');

        // get the session item
        $item = $items[$param['aula_agenda_id_row_id']];

        $data->aula_agenda_professor_id = $item['aula_agenda_professor_id'];
        $data->aula_agenda_tipo_aula = $item['aula_agenda_tipo_aula'];
        $data->aula_agenda_data_aula = $item['aula_agenda_data_aula'];
        $data->aula_agenda_curriculo_aluno_id = $item['aula_agenda_curriculo_aluno_id'];
        $data->aula_agenda_presente = $item['aula_agenda_presente'];
        $data->aula_agenda_ultima_pagina = $item['aula_agenda_ultima_pagina'];
        $data->aula_agenda_ultima_palavra = $item['aula_agenda_ultima_palavra'];
        $data->aula_agenda_observacao = $item['aula_agenda_observacao'];

        $data->aula_agenda_id = $param['aula_agenda_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );

    }

    public function onDeleteAulaAgenda( $param )
    {
        $data = $this->form->getData();

        $data->aula_agenda_professor_id = '';
        $data->aula_agenda_tipo_aula = '';
        $data->aula_agenda_data_aula = '';
        $data->aula_agenda_curriculo_aluno_id = '';
        $data->aula_agenda_presente = '';
        $data->aula_agenda_ultima_pagina = '';
        $data->aula_agenda_ultima_palavra = '';
        $data->aula_agenda_observacao = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('aula_agenda_items');

        // delete the item from session
        unset($items[$param['aula_agenda_id_row_id']]);
        TSession::setValue('aula_agenda_items', $items);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadAulaAgenda( $param )
    {
        $items = TSession::getValue('aula_agenda_items'); 

        $this->aula_agenda_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteAulaAgenda')); 
                $action_del->setParameter('aula_agenda_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditAulaAgenda'));  
                $action_edi->setParameter('aula_agenda_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_aula_agenda'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = "Excluir";
                $button_del->setImage('fas:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_aula_agenda'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = "Editar";
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->aula_agenda_professor_id = '';
                if(isset($item['aula_agenda_professor_id']) && $item['aula_agenda_professor_id'])
                {
                    TTransaction::open('permission');
                    $system_users = SystemUsers::find($item['aula_agenda_professor_id']);
                    if($system_users)
                    {
                        $rowItem->aula_agenda_professor_id = $system_users->render('{name}');
                    }
                    TTransaction::close();
                }

                $rowItem->aula_agenda_tipo_aula = isset($item['aula_agenda_tipo_aula']) ? $item['aula_agenda_tipo_aula'] : '';
                $rowItem->aula_agenda_data_aula = isset($item['aula_agenda_data_aula']) ? $item['aula_agenda_data_aula'] : '';
                $rowItem->aula_agenda_curriculo_aluno_id = '';
                if(isset($item['aula_agenda_curriculo_aluno_id']) && $item['aula_agenda_curriculo_aluno_id'])
                {
                    TTransaction::open('cdi');
                    $curriculo_aluno = CurriculoAluno::find($item['aula_agenda_curriculo_aluno_id']);
                    if($curriculo_aluno)
                    {
                        $rowItem->aula_agenda_curriculo_aluno_id = $curriculo_aluno->render('{aluno->nome} | {idioma->descricao} {stage->descricao}');
                    }
                    TTransaction::close();
                }

                $rowItem->aula_agenda_presente = isset($item['aula_agenda_presente']) ? $item['aula_agenda_presente'] : '';
                $rowItem->aula_agenda_ultima_pagina = isset($item['aula_agenda_ultima_pagina']) ? $item['aula_agenda_ultima_pagina'] : '';
                $rowItem->aula_agenda_ultima_palavra = isset($item['aula_agenda_ultima_palavra']) ? $item['aula_agenda_ultima_palavra'] : '';
                $rowItem->aula_agenda_observacao = isset($item['aula_agenda_observacao']) ? $item['aula_agenda_observacao'] : '';

                $row = $this->aula_agenda_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {

        TSession::setValue('aula_agenda_items', null);

        $this->onReload();

    } 

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadAulaAgenda($params);
    }

    public function show() 
    { 
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') ) 
        { 
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

    public function onStartEdit($param)
    {
        TSession::setValue('aula_agenda_items', null);

        $this->form->clear(true);

        $data = new stdClass;
        $data->view = $param['view'] ?? 'agendaWeek'; // calendar view
        $data->status_cor = new stdClass();
        $data->status_cor->cor = '#3a87ad';

        if (!empty($param['date']))
        {
            if(strlen($param['date']) == '10')
                $param['date'].= ' 09:00';

            $data->horario_inicial = str_replace('T', ' ', $param['date']);

            $horario_final = new DateTime($data->horario_inicial);
            $horario_final->add(new DateInterval('PT1H'));
            $data->horario_final = $horario_final->format('Y-m-d H:i:s');

        }

        $this->form->setData( $data );
    }

    public static function onUpdateEvent($param)
    {
        try
        {
            if (isset($param['id']))
            {
                TTransaction::open(self::$database);

                $class = self::$activeRecord;
                $object = new $class($param['id']);

                $object->horario_inicial = str_replace('T', ' ', $param['start_time']);
                $object->horario_final   = str_replace('T', ' ', $param['end_time']);

                $object->store();

                // close the transaction
                TTransaction::close();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }

}


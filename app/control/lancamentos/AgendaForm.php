<?php

class AgendaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Agenda';
    private static $primaryKey = 'id';
    private static $formName = 'form_Agenda';
    private static $startDateField = 'horario_inicial';
    private static $endDateField = 'horario_final';

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
        $this->form->setFormTitle("Agenda");

        $view = new THidden('view');

        $criteria_professor_id = new TCriteria();

        $filterVar = "Y";
        $criteria_professor_id->add(new TFilter('active', '=', $filterVar)); 

        // **** ISSO NAO SERVE PRA NADA **** ROLA UM BUG MALUCO
        //$criteria_professor_id->add(new TFilter('', 'EXISTS', "NOESC: (SELECT system_user_group.group_id FROM system_user_group WHERE system_user_group.group_id = 5 AND system_user_id = system_users.id )")); 

        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $sala_id = new TDBCombo('sala_id', 'cdi', 'Sala', 'id', '{descricao} ','descricao asc'  );
        $professor_id = new TDBCombo('professor_id', 'permission', 'SystemUsers', 'id', '{name}','name asc' , $criteria_professor_id );
        $turma_id = new TCombo('turma_id');
        $horario_inicial = new TDateTime('horario_inicial');
        $horario_final = new TDateTime('horario_final');
        $status_cor_id = new TDBCombo('status_cor_id', 'cdi', 'StatusCor', 'id', '{nome_cor} {descricao} ','nome_cor asc'  );
        $div_cor = new BElement('span');
        $cor = new THidden('cor');
        $data_final = new TDate('data_final');
        $link_sala = new TEntry('link_sala');
        $aulas_dadas = new BPageContainer();

        $unidade_id->setChangeAction(new TAction([$this,'onChangeunidade_id']));
        $turma_id->setChangeAction(new TAction([$this,'onAulasDadas']));
        $status_cor_id->setChangeAction(new TAction([$this,'onColor']));

        $unidade_id->addValidation("Unidade", new TRequiredValidator()); 
        $turma_id->addValidation("Turma", new TRequiredValidator()); 
        $horario_inicial->addValidation("Horário inicial", new TRequiredValidator()); 
        $horario_final->addValidation("Horário final", new TRequiredValidator()); 
        $status_cor_id->addValidation("Cor", new TRequiredValidator()); 
        $data_final->addValidation("Repetir até", new TRequiredValidator()); 

        $aulas_dadas->setAction(new TAction(['AulaSimpleList', 'onShow'], $param));
        $id->setEditable(false);
        $aulas_dadas->setId('b61ef325cb7xcd');
        $turma_id->enableSearch();
        $professor_id->enableSearch();

        $div_cor->id = 'div_cor';

        $data_final->setMask('dd/mm/yyyy');
        $horario_final->setMask('dd/mm/yyyy hh:ii');
        $horario_inicial->setMask('dd/mm/yyyy hh:ii');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $horario_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $cor->setSize(200);
        $sala_id->setSize('70%');
        $turma_id->setSize('70%');
        $div_cor->setSize(25, 25);
        $data_final->setSize(110);
        $unidade_id->setSize('70%');
        $link_sala->setSize('100%');
        $horario_final->setSize(150);
        $professor_id->setSize('70%');
        $aulas_dadas->setSize('100%');
        $horario_inicial->setSize(150);
        $status_cor_id->setSize('80%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $aulas_dadas->add($loadingContainer);

        $this->div_cor = $div_cor;
        $this->aulas_dadas = $aulas_dadas;

        //$link->

        //$this->link_aula->href = 'https://meet.google.com/xsx-qaia-bbn';
        //$this->link_aula->target = '_blank';
        //$this->link_aula->add('Sala virtual, clique aqui.');
        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Unidade:", '#ff0000', '14px', null)],[$unidade_id],[new TLabel("Sala:", null, '14px', null)],[$sala_id]);
        $row3 = $this->form->addFields([new TLabel("Professor:", null, '14px', null)],[$professor_id]);
        $row4 = $this->form->addFields([new TLabel("Turma:", '#ff0000', '14px', null)],[$turma_id]);
        $row5 = $this->form->addFields([new TLabel("Horário inicial:", '#ff0000', '14px', null)],[$horario_inicial],[new TLabel("Horário final:", '#ff0000', '14px', null)],[$horario_final]);
        $row6 = $this->form->addFields([new TLabel("Cor:", '#ff0000', '14px', null)],[$status_cor_id,$div_cor,$cor],[new TLabel("Repetir até:", '#ff0000', '14px', null)],[$data_final]);
        $row7 = $this->form->addFields([new TLabel("Link sala:", null, '14px', null)],[$link_sala]);
        $row8 = $this->form->addFields([$aulas_dadas]);
        $row8->layout = [' col-sm-12'];

        $row8->style = 'display: none;';

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

        $style = new TStyle('right-panel > .container-part[page-name=AgendaForm]');
        $style->width = '50% !important';   
        $style->show(true);

    }

    public static function onChangeunidade_id($param)
    {
        try
        {

            if (isset($param['unidade_id']) && $param['unidade_id'])
            { 
                $criteria = TCriteria::create(['unidade_id' => $param['unidade_id']]);
                $filterVar = "1";
                $criteria->add(new TFilter('situacao', '=', $filterVar)); 
                TDBCombo::reloadFromModel(self::$formName, 'turma_id', 'cdi', 'Turma', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'turma_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public static function onAulasDadas($param = null) 
    {
        try 
        {
            if (! empty($param['turma_id']) && ! empty($param['horario_inicial']))
            {
                TScript::create("$('#b61ef325cb7xcd').closest('.row').show()");
                TApplication::loadPage('AulaSimpleList', 'onShow', [
                    'register_state' => 'false',
                    'target_container' => 'b61ef325cb7xcd',
                    'show_loading' => 'false',
                    'turma_id' => $param['turma_id']??0,
                    'data_aula' => TDate::date2us($param['horario_inicial']),
                ]);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onColor($param = null) 
    {
        try 
        {
                TTransaction::open(self::$database); // open a transaction
                $cor = new StatusCor($param['key']);
                TTransaction::close();

                TScript::create("$('#div_cor').css('background',  '{$cor->cor}');");

        }
        catch (Exception $e) 
        {
            TTransaction::close();
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

            $object = new Agenda(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $data_fin = $data->data_final;
            $hora_fin = $data->horario_final;
            $hora_ini = $data->horario_inicial;

                /*echo '<pre>';
                    var_dump($data->data_inicial);
                echo '</pre>';
                */

            // É uma  ediçao e é pra salvar todos
             if($data->id)
             {
             //    Agenda::where('turma_id', '=', $data->turma_id)->where('horario_inicial', '>=', $data->horario_inicial)->delete();
                        /*Agenda::where('turma_id', '=', $data->turma_id)
                              ->where("date_format(horario_inicial, '%w')", '=', date('w', strtotime($data->horario_inicial))) 
                              ->where('horario_inicial', '>=', $data->horario_inicial)
                              ->where("date_format(horario_inicial, '%H:%i')", '=', date('H:i', strtotime($data->horario_inicial)))
                              ->delete();             
                        */
             }

            // É um novo ou é pra salvar todos
            if (!$data->id)
            {
                 while($hora_ini < $data_fin)
                {
                    $agenda = clone $object; // instancia da Agenda (model)

                    if(!empty($agenda->id))
                    {
                        unset($agenda->id); 

                    }

                    $dateTimeInicial = new DateTime($hora_ini);
                    $dateTimeInicial->add(new DateInterval('P7D')); // adiciona 1 dia na data inicial
                    $dateTimeFinal = new DateTime($hora_fin);
                    $dateTimeFinal->add(new DateInterval('P7D')); // adiciona 1 dia na data inicial
                    $hora_ini = $dateTimeInicial->format('Y-m-d H:i:s');
                    $hora_fin = $dateTimeFinal->format('Y-m-d H:i:s');

                    $agenda->horario_inicial = $hora_ini." {$data->horario_inicial}";
                    $agenda->horario_final   = $hora_fin." {$data->horario_final}";

                    //Adicionado por Leo em 11/03/2022.
                    //$agenda->criado_por_id = TSession::getValue('userid');

                    // Fim adicionado por Leo em 11/03/2022.

                    if (empty($agenda->id))
                    {
                        $agenda->criado_por_id = TSession::getValue('userid');
                        //$object->criado_em = date('Y-m-d H:i:s');
                    } else {
                        $agenda->alterado_por_id = TSession::getValue('userid');
                        //$object->alterado_em = date('Y-m-d H:i:s');
                    }                    

                    $agenda->validate();

                    $agenda->store();
                }

            }

            $object->validate();
            $object->store(); // save the object 

            $this->fireEvents($object);

            $messageAction = new TAction(['AgendaGestoresFIlterForm', 'onShow']);   

            if(!empty($param['target_container']))
            {
                $messageAction->setParameter('target_container', $param['target_container']);
            }

            $messageAction->setParameter('view', $data->view);
            $messageAction->setParameter('date', explode(' ', $data->horario_inicial)[0]);

            $edit = !! $data->id;

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            if ($edit) {
                $messageAction = new TAction(['AgendaForm', 'onReplicar']);
                $messageAction->setParameter('id', $data->id);
                $messageAction->setParameter('turma_id', $data->turma_id);
                $messageAction->setParameter('horario_inicial', $data->horario_inicial);
                $messageAction->setParameter('hora_ini', $data->horario_inicial);
                $messageAction->setParameter('hora_fin', $data->horario_final);
                $messageAction->setParameter('data_fin', $data->data_final);
                $messageAction->setParameter('view', $data->view);
                $messageAction->setParameter('date', explode(' ', $data->horario_inicial)[0]);

                /* *** Ver bug de exclusão de registros em dias da semana diferente do selecionado/editado ***
                new TQuestion('Editado com sucesso!<br/><b>Deseja editar os agendamentos futuros?</b>', $messageAction);
                */
                //new TQuestion('Editado com sucesso!<br/><b>Deseja editar os agendamentos futuros?</b>', $messageAction);

            } else {
                new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);
            }

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
    public static function onDelete($param = null) 
    {
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            if (isset($param['delete_option'])) {
                 try
                {

                    $key = $param[self::$primaryKey];

                    // open a transaction with database
                    TTransaction::open(self::$database);

                    $class = self::$activeRecord;

                    // instantiates object
                    $object = new $class($key, FALSE);

                /*echo '<pre>';
                    var_dump($dayofweek->dayofweek);
                echo '</pre>';
                */

                    if(!empty($param['delete_option']) && $param['delete_option'] == 2) {
                        // Apaga todos maior que a data
                        Agenda::where('turma_id', '=', $object->turma_id)
                              ->where("date_format(horario_inicial, '%w')", '=', date('w', strtotime($object->horario_inicial))) 
                              ->where('horario_inicial', '>=', $object->horario_inicial)
                              ->where("date_format(horario_inicial, '%H:%i')", '=', date('H:i', strtotime($object->horario_inicial)))
                              ->delete();
                    }

                    $date = explode(' ', $object->horario_inicial)[0];

                    // deletes the object from the database
                    $object->delete();

                    // close the transaction
                    TTransaction::close();

                    $messageAction = new TAction(array(__CLASS__.'View', 'onReload'));
                    $messageAction->setParameter('view', $param['view']);
                    $messageAction->setParameter('date', $date);

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
            } else {
                // define the delete action
                $action = new TAction(array(__CLASS__, 'onDelete'));
                $action->setParameters($param);
                $action->setParameter('delete', 1);
                $action->setParameter('delete_option', 2);

                $action2 = new TAction(array(__CLASS__, 'onDelete'));
                $action2->setParameters($param);
                $action2->setParameter('delete', 1);
                $action2->setParameter('delete_option', 1);

                // shows a dialog to the user
                new TQuestion('Você deseja apagar os agendamentos futuros?', $action, $action2,  'ATENÇÃO');
            }

        }
        else
        {
            // define the delete action
            $action = new TAction(array(__CLASS__, 'onDelete'));
            $action->setParameters($param);
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

                                $this->aulas_dadas->setParameter('agenda_id', $object->id);
                $object->view = !empty($param['view']) ? $param['view'] : 'agendaWeek'; 

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);

                self::onColor(['key' => $object->status_cor_id]);

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
            if(isset($object->unidade_id))
            {
                $value = $object->unidade_id;

                $obj->unidade_id = $value;
            }
            if(isset($object->turma_id))
            {
                $value = $object->turma_id;

                $obj->turma_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->unidade_id))
            {
                $value = $object->unidade_id;

                $obj->unidade_id = $value;
            }
            if(isset($object->turma_id))
            {
                $value = $object->turma_id;

                $obj->turma_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public function onStartEdit($param)
    {

        $this->form->clear(true);

        $data = new stdClass;
        $data->view = $param['view'] ?? 'week'; // calendar view
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

                $object->validate();

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

    public static function onReplicar($param)
    {
        try {
            TTransaction::open(self::$database);

            Agenda::where('turma_id', '=', $param['turma_id'])
                  ->where('horario_inicial', '>', $param['horario_inicial'])
                  ->where("date_format(horario_inicial, '%w')", '=', date('w', strtotime($object->horario_inicial))) 
                  ->where('id', '<>', $param['id'])->delete();

            $object = Agenda::find($param['id']);

            $hora_ini = $param['hora_ini'];
            $hora_fin = $param['hora_fin'];
            $data_fin = $param['data_fin'];

            while($hora_ini < $data_fin)
            {
                $agenda = clone $object; // instancia da Agenda (model)

                if(!empty($agenda->id))
                {
                    unset($agenda->id);    
                }

                $dateTimeInicial = new DateTime($hora_ini);
                $dateTimeInicial->add(new DateInterval('P7D')); // adiciona 1 dia na data inicial
                $dateTimeFinal = new DateTime($hora_fin);
                $dateTimeFinal->add(new DateInterval('P7D')); // adiciona 1 dia na data inicial
                $hora_ini = $dateTimeInicial->format('Y-m-d H:i:s');
                $hora_fin = $dateTimeFinal->format('Y-m-d H:i:s');

                $agenda->horario_inicial = $hora_ini." {$hora_ini}";
                $agenda->horario_final   = $hora_fin." {$hora_fin}";

                $agenda->store();
            }

                $messageAction = new TAction(['AgendaFormView', 'onReload']);
                $messageAction->setParameter('view', $param['view']);
                $messageAction->setParameter('date', $param['date']);

                new TMessage('info', 'Editado com sucesso! Teste', $messageAction);

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }

}


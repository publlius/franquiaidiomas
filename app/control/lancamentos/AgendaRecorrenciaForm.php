<?php

class AgendaRecorrenciaForm extends TWindow
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
        parent::setSize(0.8, null);
        parent::setTitle("Agenda Recorrência");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Agenda Recorrência");

        $view = new THidden('view');

        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $sala_id = new TDBCombo('sala_id', 'cdi', 'Sala', 'id', '{id}','id asc'  );
        $professor_id = new TDBCombo('professor_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $turma_id = new TDBCombo('turma_id', 'cdi', 'Turma', 'id', '{id}','id asc'  );
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');
        $horario_inicial = new TDateTime('horario_inicial');
        $horario_final = new TDateTime('horario_final');
        $cor = new TColor('cor');
        $observacao = new TEntry('observacao');
        $criado_em = new TDateTime('criado_em');
        $criado_por_id = new TEntry('criado_por_id');
        $alterado_em = new TDateTime('alterado_em');
        $alterado_por_id = new TEntry('alterado_por_id');

        $sala_id->addValidation("Sala id", new TRequiredValidator()); 
        $turma_id->addValidation("Turma id", new TRequiredValidator()); 
        $horario_inicial->addValidation("Horário inicial", new TRequiredValidator()); 
        $horario_final->addValidation("Horário final", new TRequiredValidator()); 

        $id->setEditable(false);

        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');
        $criado_em->setMask('dd/mm/yyyy hh:ii');
        $alterado_em->setMask('dd/mm/yyyy hh:ii');
        $horario_final->setMask('dd/mm/yyyy hh:ii');
        $horario_inicial->setMask('dd/mm/yyyy hh:ii');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');
        $criado_em->setDatabaseMask('yyyy-mm-dd hh:ii');
        $alterado_em->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $cor->setSize(100);
        $criado_em->setSize(150);
        $sala_id->setSize('100%');
        $data_final->setSize(110);
        $turma_id->setSize('100%');
        $alterado_em->setSize(150);
        $data_inicial->setSize(110);
        $unidade_id->setSize('100%');
        $horario_final->setSize(150);
        $observacao->setSize('100%');
        $professor_id->setSize('100%');
        $horario_inicial->setSize(150);
        $criado_por_id->setSize('100%');
        $alterado_por_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Unidade id:", null, '14px', null)],[$unidade_id]);
        $row3 = $this->form->addFields([new TLabel("Sala id:", '#ff0000', '14px', null)],[$sala_id]);
        $row4 = $this->form->addFields([new TLabel("Professor id:", null, '14px', null)],[$professor_id]);
        $row5 = $this->form->addFields([new TLabel("Turma id:", '#ff0000', '14px', null)],[$turma_id]);
        $row6 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null)],[$data_inicial]);
        $row7 = $this->form->addFields([new TLabel("Data final:", null, '14px', null)],[$data_final]);
        $row8 = $this->form->addFields([new TLabel("Horário inicial:", '#ff0000', '14px', null)],[$horario_inicial]);
        $row9 = $this->form->addFields([new TLabel("Horário final:", '#ff0000', '14px', null)],[$horario_final]);
        $row10 = $this->form->addFields([new TLabel("Cor:", null, '14px', null)],[$cor]);
        $row11 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$observacao]);
        $row12 = $this->form->addFields([new TLabel("Criado em:", null, '14px', null)],[$criado_em]);
        $row13 = $this->form->addFields([new TLabel("Criado por id:", null, '14px', null)],[$criado_por_id]);
        $row14 = $this->form->addFields([new TLabel("Alterado em:", null, '14px', null)],[$alterado_em]);
        $row15 = $this->form->addFields([new TLabel("Alterado por id:", null, '14px', null)],[$alterado_por_id]);

        $this->form->addFields([$view]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_ondelete = $this->form->addAction("Excluir", new TAction([$this, 'onDelete']), 'fas:trash-alt #dd5a43');
        $this->btn_ondelete = $btn_ondelete;

        parent::add($this->form);

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

            $messageAction = new TAction(['AgendaRecorrenciaFormView', 'onReload']);
            $messageAction->setParameter('view', $data->view);
            $messageAction->setParameter('date', explode(' ', $data->horario_inicial)[0]);

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 

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

                                $object->view = !empty($param['view']) ? $param['view'] : 'agendaWeek'; 

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

    public function onStartEdit($param)
    {
        TSession::setValue('{$detailId}_items', null);

        $this->form->clear(true);

        $data = new stdClass;
        $data->view = $param['view']; // calendar view
        $data->cor = '#3a87ad';

        if ($param['date'])
        {
            if(strlen($param['date']) == '10')
                $param['date'].= ' 09:00';

            $data->horario_inicial = str_replace('T', ' ', $param['date']);

            $horario_final = new DateTime($data->horario_inicial);
            $horario_final->add(new DateInterval('PT1H'));
            $data->horario_final = $horario_final->format('Y-m-d H:i:s');

            $this->form->setData( $data );
        }
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


<?php

class TurmaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'cdi';
    private static $activeRecord = 'Turma';
    private static $primaryKey = 'id';
    private static $formName = 'form_Turma';

    //private static $permission = 'permission';

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
        $this->form->setFormTitle("Cadastro de turma");


        $id = new TEntry('id');
        $unidade_id = new TDBCombo('unidade_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $situacao = new TRadioGroup('situacao');
        $pasta_id = new TCombo('pasta_id');
        $tamanho_turma = new TEntry('tamanho_turma');
        $idioma_id = new TDBCombo('idioma_id', 'cdi', 'Idioma', 'id', '{descricao}','id asc'  );
        $descricao = new TEntry('descricao');
        $book_id = new TCombo('book_id');
        $stage_id = new TCombo('stage_id');
        $turma_alunos_turma_aluno_id = new TCombo('turma_alunos_turma_aluno_id');
        $turma_alunos_turma_aluno_nome = new THidden('turma_alunos_turma_aluno_nome');
        $turma_alunos_turma_id = new THidden('turma_alunos_turma_id');

        $unidade_id->setChangeAction(new TAction([$this,'onChangeunidade_id']));
        $idioma_id->setChangeAction(new TAction([$this,'onChangeidioma_id']));
        $book_id->setChangeAction(new TAction([$this,'onChangebook_id']));
        $pasta_id->setChangeAction(new TAction([$this,'onTamanhoTurma']));
        $stage_id->setChangeAction(new TAction([$this,'onAlunoTurma']));
        $turma_alunos_turma_aluno_id->setChangeAction(new TAction([$this,'onAlunoNome']));

        $situacao->addValidation("Situação turma", new TRequiredValidator()); 
        $pasta_id->addValidation("Pasta", new TRequiredValidator()); 
        $tamanho_turma->addValidation("Tamanho turma", new TRequiredValidator()); 
        $idioma_id->addValidation("Idioma", new TRequiredValidator()); 
        $book_id->addValidation("Book", new TRequiredValidator()); 
        $stage_id->addValidation("Stage", new TRequiredValidator()); 

        $situacao->addItems(["1"=>"Andamento","2"=>"Encerrada"]);
        $situacao->setLayout('horizontal');
        $situacao->setUseButton();
        $tamanho_turma->setMaxLength(3);

        $id->setEditable(false);
        $tamanho_turma->setEditable(false);

        $book_id->enableSearch();
        $pasta_id->enableSearch();
        $stage_id->enableSearch();
        $idioma_id->enableSearch();

        $id->setSize(100);
        $book_id->setSize('70%');
        $situacao->setSize('80%');
        $pasta_id->setSize('70%');
        $stage_id->setSize('70%');
        $idioma_id->setSize('70%');
        $descricao->setSize('70%');
        $unidade_id->setSize('70%');
        $tamanho_turma->setSize('70%');
        $turma_alunos_turma_aluno_id->setSize('70%');
        $turma_alunos_turma_aluno_nome->setSize(200);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Unidade:", null, '14px', null)],[$unidade_id],[new TLabel("Situação turma:", '#FF0000', '14px', null)],[$situacao]);
        $row3 = $this->form->addFields([new TLabel("Tipo turma:", '#ff0000', '14px', null)],[$pasta_id],[new TLabel("Tamanho turma:", '#ff0000', '14px', null)],[$tamanho_turma]);
        $row4 = $this->form->addFields([new TLabel("Idioma:", '#ff0000', '14px', null)],[$idioma_id]);
        $row5 = $this->form->addFields([new TLabel("Nome pasta:", null, '14px', null)],[$descricao]);
        $row6 = $this->form->addFields([new TLabel("Book:", '#ff0000', '14px', null)],[$book_id]);
        $row7 = $this->form->addFields([new TLabel("Stage:", '#ff0000', '14px', null)],[$stage_id]);
        $row8 = $this->form->addFields([new TFormSeparator("Alunos da turma", '#333333', '18', '#eeeeee')]);
        $row8->layout = [' col-sm-12'];

        $row9 = $this->form->addFields([new TLabel("Aluno:", '#ff0000', '14px', null)],[$turma_alunos_turma_aluno_id,$turma_alunos_turma_aluno_nome]);
        $row10 = $this->form->addFields([$turma_alunos_turma_id]);         
        $add_turma_alunos_turma = new TButton('add_turma_alunos_turma');

        $action_turma_alunos_turma = new TAction(array($this, 'onAddTurmaAlunosTurma'));

        $add_turma_alunos_turma->setAction($action_turma_alunos_turma, "Adicionar");
        $add_turma_alunos_turma->setImage('fas:plus #000000');

        $this->form->addFields([$add_turma_alunos_turma]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->turma_alunos_turma_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->turma_alunos_turma_list->style = 'width:100%';
        $this->turma_alunos_turma_list->class .= ' table-bordered';
        $this->turma_alunos_turma_list->disableDefaultClick();
        $this->turma_alunos_turma_list->addQuickColumn('', 'edit', 'left', 50);
        $this->turma_alunos_turma_list->addQuickColumn('', 'delete', 'left', 50);

        $column_turma_alunos_turma_aluno_id = $this->turma_alunos_turma_list->addQuickColumn("Cód. Aluno", 'turma_alunos_turma_aluno_id', 'left' , '100px');
        $column_turma_alunos_turma_aluno_nome = $this->turma_alunos_turma_list->addQuickColumn("Aluno", 'turma_alunos_turma_aluno_nome', 'left' , '90%');

        $this->turma_alunos_turma_list->createModel();
        $this->form->addContent([$this->turma_alunos_turma_list]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
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
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de turma"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeunidade_id($param)
    {
        try
        {

            if (isset($param['unidade_id']) && $param['unidade_id'])
            { 
                $criteria = TCriteria::create(['unidade_id' => $param['unidade_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'pasta_id', 'cdi', 'Pasta', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'pasta_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
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

    public static function onTamanhoTurma($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database); // open a transaction
            $pasta = new Pasta($param['key']);
            TTransaction::close();

            $object = new stdClass();
            $object->tamanho_turma = $pasta->maximo;

            TForm::sendData(self::$formName, $object);

            //Debug
            //var_dump($object->tamanho_turma);exit;
            //return "{$pasta->maximo}";

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onAlunoTurma($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            //if($param['book_id'])
            if($param['book_id'])
            {
                $criteria = new TCriteria();
                $criteria->add(new TFilter('aluno_id', 'in', "(SELECT aluno.id 
                                                                 FROM aluno 
                                                                WHERE aluno.unidade_id = '{$param['unidade_id']}' 
                                                                  AND aluno.situacao_id = 1)" ));                

                $criteria->add(new TFilter('idioma_id', 'in', "(SELECT id 
                                                                  FROM idioma 
                                                                 WHERE idioma_id = '{$param['idioma_id']}' )" ));
                $criteria->add(new TFilter('book_id', 'in', "(SELECT id 
                                                                FROM book 
                                                               WHERE book_id = '{$param['book_id']}' )" ));
                $criteria->add(new TFilter('stage_id', 'in', "(SELECT id 
                                                                 FROM stage 
                                                                WHERE stage_id = '{$param['stage_id']}' )" ));

                $alunos = CurriculoAluno::getIndexedArray('aluno_id', '{aluno->nome}', $criteria);

                /*$alunos = CurriculoAluno::getIndexedArray('aluno_id', 
                                               '{aluno->nome} {idioma->descricao} {book->descricao} {stage->descricao}', $criteria); */

                    if(($param['unidade_id']))
                    {
                    TCombo::reload(self::$formName, 'turma_alunos_turma_aluno_id', $alunos, true, true);
                //TCombo::reload(self::$formName, 'turma_alunos_turma_aluno_id', CurriculoAluno::getIndexedArray('id', 'aluno->nome', $criteria));
                    } else { 
                        //vazio
                        }
            } else {
                // vazio
            }

            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onAlunoNome($param = null) 
    {
        try 
        {
            if(!empty($param['key']))
            {
                TTransaction::open(self::$database); // open a transaction
                $aluno = new Aluno($param['key']);
                TTransaction::close();

                $object = new stdClass();
                $object->turma_alunos_turma_aluno_nome = $aluno->nome;

                TForm::sendData(self::$formName, $object);    
            }

            //Debug
            //var_dump($object->tamanho_turma);exit;
            //return "{$pasta->maximo}";

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

            $object = new Turma(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $this->fireEvents($object);

            $turma_alunos_turma_items = $this->storeItems('TurmaAlunos', 'turma_id', $object, 'turma_alunos_turma', function($masterObject, $detailObject){ 

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

                $object = new Turma($key); // instantiates the Active Record 

                $turma_alunos_turma_items = $this->loadItems('TurmaAlunos', 'turma_id', $object, 'turma_alunos_turma', function($masterObject, $detailObject, $objectItems){ 

                    //code here
                    $objectItems->turma_alunos_turma_aluno_nome = $detailObject->aluno->nome;

                    $objectItems->turma_alunos_turma_aluno_id = null;
                    if(isset($detailObject->aluno_id) && $detailObject->aluno_id)
                    {
                        $objectItems->turma_alunos_turma_aluno_id = $detailObject->aluno_id;
                    }
                    $objectItems->stage_id = null;
                    if(isset($detailObject->stage_id) && $detailObject->stage_id)
                    {
                        $objectItems->stage_id = $detailObject->stage_id;
                    }

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

        TSession::setValue('turma_alunos_turma_items', null);

        $this->onReload();
    }

    public function onAddTurmaAlunosTurma( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->turma_alunos_turma_aluno_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Aluno"));
            }             

            $turma_alunos_turma_items = TSession::getValue('turma_alunos_turma_items');
            $key = isset($data->turma_alunos_turma_id) && $data->turma_alunos_turma_id ? $data->turma_alunos_turma_id : 'b'.uniqid();
            $fields = []; 

            $fields['turma_alunos_turma_aluno_id'] = $data->turma_alunos_turma_aluno_id;
            $fields['turma_alunos_turma_aluno_nome'] = $data->turma_alunos_turma_aluno_nome;
            $turma_alunos_turma_items[ $key ] = $fields;

            if(count($turma_alunos_turma_items) > $data->tamanho_turma)
            {
                throw new Exception('Quantidade máxima de alunos atingida para esta turma!');
            }

            TSession::setValue('turma_alunos_turma_items', $turma_alunos_turma_items);

            $data->turma_alunos_turma_id = '';
            $data->turma_alunos_turma_aluno_id = '';
            $data->turma_alunos_turma_aluno_nome = '';

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

    public function onEditTurmaAlunosTurma( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('turma_alunos_turma_items');

        // get the session item
        $item = $items[$param['turma_alunos_turma_id_row_id']];

        $data->turma_alunos_turma_aluno_id = $item['turma_alunos_turma_aluno_id'];
        $data->turma_alunos_turma_aluno_nome = $item['turma_alunos_turma_aluno_nome'];

        $data->turma_alunos_turma_id = $param['turma_alunos_turma_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->fireEvents($data);

        $this->onReload( $param );

    }

    public function onDeleteTurmaAlunosTurma( $param )
    {
        $data = $this->form->getData();

        $data->turma_alunos_turma_aluno_id = '';
        $data->turma_alunos_turma_aluno_nome = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('turma_alunos_turma_items');

        // delete the item from session
        unset($items[$param['turma_alunos_turma_id_row_id']]);
        TSession::setValue('turma_alunos_turma_items', $items);

        $this->fireEvents($data);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadTurmaAlunosTurma( $param )
    {
        $items = TSession::getValue('turma_alunos_turma_items'); 

        $this->turma_alunos_turma_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteTurmaAlunosTurma')); 
                $action_del->setParameter('turma_alunos_turma_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditTurmaAlunosTurma'));  
                $action_edi->setParameter('turma_alunos_turma_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_turma_alunos_turma'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = "Excluir";
                $button_del->setImage('fas:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_turma_alunos_turma'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = "Editar";
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->turma_alunos_turma_aluno_id = isset($item['turma_alunos_turma_aluno_id']) ? $item['turma_alunos_turma_aluno_id'] : '';
                $rowItem->turma_alunos_turma_aluno_nome = isset($item['turma_alunos_turma_aluno_nome']) ? $item['turma_alunos_turma_aluno_nome'] : '';

                $row = $this->turma_alunos_turma_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {

        TSession::setValue('turma_alunos_turma_items', null);

        $this->onReload();

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
            if(isset($object->pasta_id))
            {
                $value = $object->pasta_id;

                $obj->pasta_id = $value;
            }
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
            if(isset($object->turma_alunos_turma_aluno_id))
            {
                $value = $object->turma_alunos_turma_aluno_id;

                $obj->turma_alunos_turma_aluno_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->unidade_id))
            {
                $value = $object->unidade_id;

                $obj->unidade_id = $value;
            }
            if(isset($object->pasta_id))
            {
                $value = $object->pasta_id;

                $obj->pasta_id = $value;
            }
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
            if(isset($object->stage_id))
            {
                $value = $object->stage_id;

                $obj->stage_id = $value;
            }
            if(isset($object->aluno_id))
            {
                $value = $object->aluno_id;

                $obj->turma_alunos_turma_aluno_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadTurmaAlunosTurma($params);
    }

    public function show() 
    { 
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') ) 
        { 
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

}


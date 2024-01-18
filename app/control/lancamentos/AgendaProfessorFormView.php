<?php
/**
 * AgendaProfessorForm Form
 * @author  <your name here>
 */
class AgendaProfessorFormView extends TPage
{
    private $fc;

    /**
     * Page constructor
     */
    public function __construct($param = null)
    {
        parent::__construct();

        $this->fc = new TFullCalendar(date('Y-m-d'), 'month');
        $this->fc->enableDays([1,2,3,4,5,6]);
        $this->fc->setReloadAction(new TAction(array($this, 'getEvents'), $param));
        $this->fc->setEventClickAction(new TAction(array('AgendaProfessorForm', 'onEdit')));
        $this->fc->setCurrentView('agendaWeek');
        $this->fc->setTimeRange('05:00', '23:00');
        $this->fc->enablePopover('Detalhes do Agendamento', "{turma->descricao} - {turma->idioma->descricao} - {turma->book->descricao} - {turma->stage->descricao}  <br>{link_sala} <br> Aula dada?  {aula_ok} ");
        $this->fc->setOption('slotTime', "00:30:00");
        $this->fc->setOption('slotDuration', "00:30:00");
        $this->fc->setOption('slotLabelInterval', 30);

        parent::add( $this->fc );
    }

    /**
     * Output events as an json
     */
    public static function getEvents($param=NULL)
    {
        $return = array();
        try
        {
            TTransaction::open('cdi');

            $criteria = new TCriteria(); 

            $criteria->add(new TFilter('horario_inicial', '<=', substr($param['end'], 0, 10).' 23:59:59'));
            $criteria->add(new TFilter('horario_final', '>=', substr($param['start'], 0, 10).' 00:00:00'));

            $filterVar = TSession::getValue("userid");
            $criteria->add(new TFilter('professor_id', '=', $filterVar)); 

            $events = Agenda::getObjects($criteria);

            if ($events)
            {
                foreach ($events as $event)
                {
                    $event_array = $event->toArray();
                    $event_array['start'] = str_replace( ' ', 'T', $event_array['horario_inicial']);
                    $event_array['end'] = str_replace( ' ', 'T', $event_array['horario_final']);
                    $event_array['id'] = $event->id;
                    $event_array['color'] = $event->render("{status_cor->cor}");
                    $event_array['title'] = TFullCalendar::renderPopover($event->render(" {unidade->name} - {turma->descricao} - {sala->descricao} "), $event->render("Detalhes do Agendamento"), $event->render("{turma->descricao} - {turma->idioma->descricao} - {turma->book->descricao} - {turma->stage->descricao}  <br>{link_sala} <br> Aula dada?  {aula_ok} "));

                    $return[] = $event_array;
                }
            }
            TTransaction::close();
            echo json_encode($return);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Reconfigure the callendar
     */
    public function onReload($param = null)
    {
        if (isset($param['view']))
        {
            $this->fc->setCurrentView($param['view']);
        }

        if (isset($param['date']))
        {
            $this->fc->setCurrentDate($param['date']);
        }
    }

}


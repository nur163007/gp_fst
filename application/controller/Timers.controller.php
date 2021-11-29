<?php


class Timers
{
    private $timers = [];

    public function startTimer($name, $description = null)
    {
        $this->timers[$name] = [
            'start' => microtime(true),
            'desc' => $description,
        ];
    }

    public function endTimer($name)
    {
        $this->timers[$name]['end'] = microtime(true);
    }

    public function getTimers()
    {
        $metrics = [];

        if (count($this->timers)) {
            foreach($this->timers as $name => $timer) {
                $timeTaken = ($timer['end'] - $timer['start']) * 1000;
                $output = sprintf('%s;dur=%f', $name, $timeTaken);

                if ($timer['desc'] != null) {
                    $output .= sprintf(';desc="%s"', addslashes($timer['desc']));
                }
                $metrics[] = $output;
            }
        }

        return implode($metrics, ', ');
    }
}


/*!
 * USAGE
 * ************************************/

/*
$Timers = new Timers();

$Timers->startTimer('db');
usleep('200000');
$Timers->endTimer('db');

$Timers->startTimer('tpl', 'Templating');
usleep('300000');
$Timers->endTimer('tpl');

$Timers->startTimer('geo', 'Geocoding');
usleep('400000');
$Timers->endTimer('geo');

header('Server-Timing: '.$Timers->getTimers());

*/
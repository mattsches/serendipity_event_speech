<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

/**
 * Class serendipity_event_speech
 *
 * @author Matthias Gutjahr <mattsches@gmail.com>
 */
class serendipity_event_speech extends serendipity_event
{
    /**
     * @var string
     */
    public $title = PLUGIN_SPEECH_TITLE;

    /**
     * @param serendipity_property_bag $propbag
     * @return void
     */
    public function introspect(&$propbag)
    {
        $propbag->add('name', PLUGIN_SPEECH_TITLE);
        $propbag->add('description', PLUGIN_SPEECH_DESC);
        $propbag->add('stackable', false);
        $propbag->add('author', 'Matthias Gutjahr');
        $propbag->add(
            'requirements',
            array(
                'serendipity' => '2',
                'smarty' => '3',
                'php' => '5.4'
            )
        );
        $propbag->add('version', '0.0.1');
        $propbag->add('groups', array('FRONTEND_VIEWS'));
        $propbag->add(
            'event_hooks',
            array(
                'js' => true,
                'entry_display' => true,
            )
        );
    }

    /**
     * @param $title
     * @return void
     */
    public function generate_content(&$title)
    {
        $title = PLUGIN_SPEECH_TITLE;
    }

    /**
     * @param string $event
     * @param serendipity_property_bag $bag
     * @param array $eventData
     * @param null $addData
     * @return bool
     */
    public function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        $hooks = & $bag->get('event_hooks');

        if (isset($hooks[$event])) {
            switch ($event) {
                case 'entry_display':
//                    if (isset($addData['from']) && $addData['from'] == 'functions_entries:printEntries') {
                    if (count($eventData) > 1) {
                        return true;
                    }
                    if (isset($eventData[0]['add_footer'])) {
                        $eventData[0]['add_footer'] .= $this->getSpeechButton($eventData[0]['id']);
                    } else {
                        $eventData[0]['add_footer'] = $this->getSpeechButton($eventData[0]['id']);
                    }
                    return true;

                case 'js':
                    $eventData .= file_get_contents(__DIR__ . '/js/serendipity_event_speech.js');
                    return true;

                default:
                    return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param int $entryId
     * @return string
     */
    protected function getSpeechButton($entryId)
    {
        $button = "<div id=\"speech-button-" . $entryId . "\" class=\"speech-button\">" . PLUGIN_SPEECH_READ_ENTRY . "</div><script type=\"text/javascript\">var entryId = " . $entryId . ";</script>";
        return $button;
    }
}

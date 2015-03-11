<?php

require_once S9Y_INCLUDE_PATH . 'tests/plugins/PluginTest.php';
require_once S9Y_INCLUDE_PATH . 'plugins/additional_plugins/serendipity_event_speech/serendipity_event_speech.php';

/**
 * Class serendipity_event_speechTest
 *
 * @author Matthias Gutjahr <mattsches@gmail.com>
 */
class serendipity_event_speechTest extends PluginTest
{
    /**
     * @var serendipity_event_speech
     */
    protected $object;

    /**
     * @var serendipity_property_bag
     */
    protected $propBag;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->object = new serendipity_event_speech('test');
        $this->propBag = new serendipity_property_bag();
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function testGenerateContent()
    {
        $title = 'foobar'; // we need to pass this by reference
        $this->object->generate_content($title);
        $this->assertEquals('SPEECH comments', $title);
    }

    /**
     * @test
     */
    public function testJsEvent()
    {
        $eventData = '';
        $this->object->introspect($this->propBag);
        $this->object->event_hook('js', $this->propBag, $eventData);
        $this->assertContains('if ("speechSynthesis" in window) {', $eventData);
        $this->assertContains('readEntry', $eventData);
        $this->assertContains('getVoices', $eventData);
    }

    /**
     * @test
     */
    public function testFrontendDisplayEvent()
    {
        $eventData = array(
            'id' => 1,
            'body' => 'The entry body.',
            'extended' => 'The extended body.',
        );
        $this->object->introspect($this->propBag);
        $this->object->event_hook('frontend_display', $this->propBag, $eventData);
        $expectedBody = '<div id="speech-button-1" class="speech-button">Artikel vorlesen</div><script type="text/javascript">var entryId = 1;</script>';
        $this->assertEquals($expectedBody, $eventData['add_footer']);
    }
}

<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaTemplateEngineXslTest extends PapayaTestCase {

  private $_internalErrors = NULL;

  public function tearDown() {
    if (isset($this->_internalErrors)) {
      libxml_use_internal_errors($this->_internalErrors);
    }
  }

  /**
  * @covers PapayaTemplateEngineXsl::setTemplateString
  */
  public function testSetTemplateString() {
    $engine = new PapayaTemplateEngineXsl();
    $engine->setTemplateString($string = file_get_contents(dirname(__FILE__).'/TestData/valid.xsl'));
    $this->assertAttributeEquals(
      $string, '_template', $engine
    );
    $this->assertAttributeEquals(
      FALSE, '_templateFile', $engine
    );
    $this->assertFalse($engine->useCache());
  }

  /**
  * @covers PapayaTemplateEngineXsl::setTemplateFile
  */
  public function testSetTemplateFile() {
    $engine = new PapayaTemplateEngineXsl();
    $engine->setTemplateFile(dirname(__FILE__).'/TestData/valid.xsl');
    $this->assertAttributeEquals(
      dirname(__FILE__).'/TestData/valid.xsl',
      '_templateFile',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::setTemplateFile
  */
  public function testSetTemplateFileWithInvalidFileNameExpectingException() {
    $engine = new PapayaTemplateEngineXsl();
    $this->setExpectedException('InvalidArgumentException');
    $engine->setTemplateFile('NONEXISTING_FILENAME.XSL');
  }

  /**
  * @covers PapayaTemplateEngineXsl::useCache
  */
  public function testUseCacheSetToTrue() {
    $engine = new PapayaTemplateEngineXsl();
    $this->assertTrue(
      $engine->useCache(TRUE)
    );
    $this->assertAttributeEquals(
      TRUE,
      '_useCache',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::useCache
  */
  public function testUseCacheSetToFalse() {
    $engine = new PapayaTemplateEngineXsl();
    $this->assertFalse(
      $engine->useCache(FALSE)
    );
    $this->assertAttributeEquals(
      FALSE,
      '_useCache',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::useCache
  */
  public function testUseCacheSetToTrueWithXsltProcessorObject() {
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($this->getProcessorMock());
    $engine->useCache(TRUE);
    $this->assertAttributeNotInstanceOf(
      'XsltProcessor',
      '_processor',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::useCache
  */
  public function testUseCacheSetToFalseWithXsltCacheObject() {
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($this->getProcessorMock('XsltCache'));
    $engine->useCache(FALSE);
    $this->assertAttributeNotInstanceOf(
      'XsltCache',
      '_processor',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::setProcessor
  */
  public function testSetProcessorWithXsltProcessor() {
    $processor = $this->getProcessorMock('XsltProcessor');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $this->assertAttributeSame(
      $processor,
      '_processor',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::setProcessor
  */
  public function testSetProcessorWithXsltCache() {
    $processor = $this->getProcessorMock('XsltCache');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $this->assertAttributeSame(
      $processor,
      '_processor',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::setProcessor
  */
  public function testSetProcessorWithInvalidProcessorExpectingException() {
    $engine = new PapayaTemplateEngineXsl();
    $this->setExpectedException('UnexpectedValueException');
    $engine->setProcessor(new stdClass);
  }

  /**
  * @covers PapayaTemplateEngineXsl::getProcessor
  */
  public function testGetProcessor() {
    $processor = $this->getProcessorMock();
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $this->assertSame(
      $processor,
      $engine->getProcessor()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::getProcessor
  */
  public function testGetProcessorWithImplizitCreateXsltProccessor() {
    $engine = new PapayaTemplateEngineXsl();
    $engine->useCache(FALSE);
    $this->assertInstanceOf(
      'XsltProcessor',
      $engine->getProcessor()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::getProcessor
  */
  public function testGetProcessorWithImplizitCreateXsltCache() {
    $engine = new PapayaTemplateEngineXsl();
    $engine->useCache(TRUE);
    $this->assertInstanceOf(
      'XsltCache',
      $engine->getProcessor()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::setErrorHandler
  */
  public function testSetErrorHandler() {
    $errors = $this->getMock('PapayaXmlErrors');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setErrorHandler($errors);
    $this->assertAttributeSame(
      $errors,
      '_errorHandler',
      $engine
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::getErrorHandler
  */
  public function testGetErrorHandler() {
    $errors = $this->getMock('PapayaXmlErrors');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setErrorHandler($errors);
    $this->assertSame(
      $errors,
      $engine->getErrorHandler()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::getErrorHandler
  */
  public function testGetErrorHandlerWithImplicitCreate() {
    $engine = new PapayaTemplateEngineXsl();
    $this->assertInstanceOf(
      'PapayaXmlErrors',
      $engine->getErrorHandler()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::prepare
  */
  public function testPrepareWithXsltCache() {
    $templateFile = dirname(__FILE__).'/TestData/valid.xsl';
    $processor = $this->getProcessorMock('XsltCache');
    $processor
      ->expects($this->once())
      ->method('importStylesheet')
      ->with($this->equalTo($templateFile), $this->equalTo(TRUE))
      ->will($this->returnValue(TRUE));
    $errors = $this->getMock(
      'PapayaXmlErrors', array('activate', 'deactivate', 'emit')
    );
    $errors
      ->expects($this->once())
      ->method('activate');
    $errors
      ->expects($this->once())
      ->method('deactivate');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $engine->setErrorHandler($errors);
    $engine->setTemplateFile($templateFile);
    $this->assertTrue(
      $engine->prepare()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::prepare
  */
  public function testPrepareWithXsltProcessorOnFile() {
    $templateFile = dirname(__FILE__).'/TestData/valid.xsl';
    $processor = $this->getProcessorMock('XsltProcessor');
    $processor
      ->expects($this->once())
      ->method('importStylesheet')
      ->with($this->isInstanceOf('DOMDocument'))
      ->will($this->returnValue(TRUE));
    $errors = $this->getMock(
      'PapayaXmlErrors', array('activate', 'deactivate', 'emit')
    );
    $errors
      ->expects($this->once())
      ->method('activate');
    $errors
      ->expects($this->once())
      ->method('deactivate');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $engine->setErrorHandler($errors);
    $engine->setTemplateFile($templateFile);
    $this->assertTrue(
      $engine->prepare()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::prepare
  */
  public function testPrepareWithXsltProcessorOnString() {
    $templateString = file_get_contents(dirname(__FILE__).'/TestData/valid.xsl');
    $processor = $this->getProcessorMock('XsltProcessor');
    $processor
      ->expects($this->once())
      ->method('importStylesheet')
      ->with($this->isInstanceOf('DOMDocument'))
      ->will($this->returnValue(TRUE));
    $errors = $this->getMock(
      'PapayaXmlErrors', array('activate', 'deactivate', 'emit')
    );
    $errors
      ->expects($this->once())
      ->method('activate');
    $errors
      ->expects($this->once())
      ->method('deactivate');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $engine->setErrorHandler($errors);
    $engine->setTemplateString($templateString);
    $this->assertTrue(
      $engine->prepare()
    );
  }

  /**
  * @covers PapayaTemplateEngineXsl::prepare
  */
  public function testPrepareWithXsltProcessorAndEmptyFileExpectingException() {
    $this->_internalErrors = libxml_use_internal_errors(TRUE);
    $templateFile = dirname(__FILE__).'/TestData/empty.xsl';
    $processor = $this->getProcessorMock('XsltProcessor');
    $errors = $this->getMock(
      'PapayaXmlErrors', array('activate', 'deactivate', 'emit')
    );
    $errors
      ->expects($this->once())
      ->method('activate');
    $errors
      ->expects($this->once())
      ->method('emit')
      ->will($this->returnCallback(array($this, 'throwXmlException')));
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $engine->setErrorHandler($errors);
    $engine->setTemplateFile($templateFile);

    $this->setExpectedException('PapayaXmlException');
    $engine->prepare();
  }

  /**
  * @covers PapayaTemplateEngineXsl::run
  * @covers PapayaTemplateEngineXsl::getResult
  */
  public function testRunSuccessful() {
    $processor = $this->getProcessorMock('XsltProcessor');
    $processor
      ->expects($this->once())
      ->method('setParameter')
      ->with($this->equalTo(''), $this->equalTo('SAMPLE'), $this->equalTo(42))
      ->will($this->returnValue(TRUE));
    $processor
      ->expects($this->once())
      ->method('transformToXML')
      ->with($this->isInstanceOf('DOMDocument'))
      ->will($this->returnValue('success'));
    $errors = $this->getMock(
      'PapayaXmlErrors', array('activate', 'deactivate', 'emit')
    );
    $errors
      ->expects($this->once())
      ->method('activate');
    $errors
      ->expects($this->once())
      ->method('emit');
    $errors
      ->expects($this->once())
      ->method('deactivate');
    $engine = new PapayaTemplateEngineXsl();
    $engine->parameters(array('SAMPLE' => 42));
    $engine->setProcessor($processor);
    $engine->setErrorHandler($errors);
    $this->assertTrue(
      $engine->run()
    );
    $this->assertEquals(
      'success',
      $engine->getResult()
    );
  }
  /**
  * @covers PapayaTemplateEngineXsl::run
  * @covers PapayaTemplateEngineXsl::getResult
  */
  public function testRunExpectingException() {
    $processor = $this->getProcessorMock('XsltProcessor');
    $processor
      ->expects($this->once())
      ->method('transformToXML')
      ->with($this->isInstanceOf('DOMDocument'))
      ->will($this->returnCallback(array($this, 'throwXmlException')));
    $errors = $this->getMock(
      'PapayaXmlErrors', array('activate', 'deactivate', 'emit')
    );
    $errors
      ->expects($this->once())
      ->method('activate');
    $errors
      ->expects($this->once())
      ->method('emit');
    $errors
      ->expects($this->once())
      ->method('deactivate');
    $engine = new PapayaTemplateEngineXsl();
    $engine->setProcessor($processor);
    $engine->setErrorHandler($errors);
    $this->assertFalse(
      $engine->run()
    );
    $this->assertEquals(
      '',
      $engine->getResult()
    );
  }

  public function throwXmlException() {
    $error = new libXMLError();
    $error->level = LIBXML_ERR_WARNING;
    $error->code = 42;
    $error->message = 'Test';
    $error->file = '';
    $error->line = 23;
    $error->column = 21;
    throw new PapayaXmlException($error);
  }

  private function getProcessorMock($class = 'XsltProcessor') {
    $result = $this
      ->getMockBuilder($class)
      ->setMethods(
        array(
          'importStylesheet', 'transformToXML', 'setParameter'
        )
      )
      ->getMock();
    return $result;
  }
}

if (!class_exists('XsltCache', FALSE)) {
  class XsltCache {
    public function importStylesheet($fileName, $useCache = TRUE) {
    }
    public function transformToXML(DOMNode $context = NULL) {
    }
    public function setParameter($namespace, $name, $value) {
    }
  }
}
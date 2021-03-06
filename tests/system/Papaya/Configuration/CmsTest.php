<?php
require_once(dirname(__FILE__).'/../../../bootstrap.php');

class PapayaConfigurationCmsTest extends PapayaTestCase {

  /**
  * @covers PapayaConfigurationCms::__construct
  */
  public function testConstructor() {
    $configuration = new PapayaConfigurationCms();
    $this->assertNotEmpty(
      iterator_to_array($configuration->getIterator())
    );
  }

  /**
  * @covers PapayaConfigurationCms::getOptionsList
  */
  public function testGetOptionsList() {
    $configuration = new PapayaConfigurationCms();
    $this->assertNotEmpty(
      $configuration->getOptionsList()
    );
  }

  /**
  * @covers PapayaConfigurationCms::loadAndDefine
  * @preserveGlobalState disabled
  * @runInSeparateProcess
  */
  public function testLoadAndDefineExpectingFalse() {
    $storage = $this->getMock('PapayaConfigurationStorage');
    $storage
      ->expects($this->once())
      ->method('load')
      ->will($this->returnValue(FALSE));
    $configuration = new PapayaConfigurationCms();
    $configuration->storage($storage);
    $this->assertFalse($configuration->loadAndDefine());
  }

  /**
  * @covers PapayaConfigurationCms::loadAndDefine
  * @covers PapayaConfigurationCms::defineConstants
  * @covers PapayaConfigurationCms::setupPaths
  * @covers PapayaConfigurationCms::defineDatabaseTables
  * @preserveGlobalState disabled
  * @runInSeparateProcess
  */
  public function testLoadAndDefine() {
    $storage = $this->getMock('PapayaConfigurationStorage');
    $storage
      ->expects($this->once())
      ->method('load')
      ->will($this->returnValue(TRUE));
    $storage
      ->expects($this->once())
      ->method('getIterator')
      ->will($this->returnValue(new ArrayIterator(array())));
    $configuration = new PapayaConfigurationCms();
    $configuration->storage($storage);
    $this->assertTrue($configuration->loadAndDefine());
  }

  /**
  * @covers PapayaConfigurationCms::setupPaths
  * @preserveGlobalState disabled
  * @runInSeparateProcess
  */
  public function testSetupPathsDefaultLocal() {
    $configuration = new PapayaConfigurationCms();
    $configuration->setupPaths();
    $this->assertEquals('cache/', $configuration['PAPAYA_PATH_CACHE']);
    $this->assertEquals('media/', $configuration['PAPAYA_MEDIA_STORAGE_DIRECTORY']);
    $this->assertEquals('', $configuration['PAPAYA_MEDIA_PUBLIC_DIRECTORY']);
    $this->assertEquals('', $configuration['PAPAYA_MEDIA_PUBLIC_URL']);
    $this->assertEquals('media/files/', $configuration['PAPAYA_PATH_MEDIAFILES']);
    $this->assertEquals('media/thumbs/', $configuration['PAPAYA_PATH_THUMBFILES']);
    $this->assertEquals('/templates/', $configuration['PAPAYA_PATH_TEMPLATES']);
    $this->assertEquals('/papaya/', $configuration['PAPAYA_PATHWEB_ADMIN']);
  }

  /**
  * @covers PapayaConfigurationCms::setupPaths
  * @preserveGlobalState disabled
  * @runInSeparateProcess
  */
  public function testSetupPathsLocal() {
    $_SERVER['DOCUMENT_ROOT'] = '/document/root/';

    $configuration = new PapayaConfigurationCms();
    $configuration['PAPAYA_PATH_DATA'] = '/data/path/';
    $configuration['PAPAYA_PATH_PUBLICFILES'] = '/public/files/';
    $configuration->setupPaths();
    $this->assertEquals('/data/path/cache/', $configuration['PAPAYA_PATH_CACHE']);
    $this->assertEquals('/data/path/media/', $configuration['PAPAYA_MEDIA_STORAGE_DIRECTORY']);
    $this->assertEquals(
      '/document/root/public/files/', $configuration['PAPAYA_MEDIA_PUBLIC_DIRECTORY']
    );
    $this->assertEquals('/public/files/', $configuration['PAPAYA_MEDIA_PUBLIC_URL']);
    $this->assertEquals('/data/path/media/files/', $configuration['PAPAYA_PATH_MEDIAFILES']);
    $this->assertEquals('/data/path/media/thumbs/', $configuration['PAPAYA_PATH_THUMBFILES']);
    $this->assertEquals('/data/path/templates/', $configuration['PAPAYA_PATH_TEMPLATES']);
    $this->assertEquals('/papaya/', $configuration['PAPAYA_PATHWEB_ADMIN']);
  }

  /**
  * @covers PapayaConfigurationCms::setupPaths
  * @preserveGlobalState disabled
  * @runInSeparateProcess
  */
  public function testSetupPathsAwsS3() {
    $_SERVER['DOCUMENT_ROOT'] = '/document/root/';

    $configuration = new PapayaConfigurationCms();
    $configuration['PAPAYA_PATH_DATA'] = '/data/path/';
    $configuration['PAPAYA_MEDIA_STORAGE_SERVICE'] = 's3';
    $configuration->setupPaths();
    $this->assertEquals('/data/path/cache/', $configuration['PAPAYA_PATH_CACHE']);
    $this->assertNull($configuration['PAPAYA_MEDIA_STORAGE_DIRECTORY']);
    $this->assertNull($configuration['PAPAYA_MEDIA_PUBLIC_DIRECTORY']);
    $this->assertNull($configuration['PAPAYA_MEDIA_PUBLIC_URL']);
    $this->assertEquals('s3://:@/media/files/', $configuration['PAPAYA_PATH_MEDIAFILES']);
    $this->assertEquals('s3://:@/media/thumbs/', $configuration['PAPAYA_PATH_THUMBFILES']);
    $this->assertEquals('/data/path/templates/', $configuration['PAPAYA_PATH_TEMPLATES']);
    $this->assertEquals('/papaya/', $configuration['PAPAYA_PATHWEB_ADMIN']);
  }
}
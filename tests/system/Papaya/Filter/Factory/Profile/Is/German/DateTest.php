<?php
require_once(dirname(__FILE__).'/../../../../../../../bootstrap.php');

class PapayaFilterFactoryProfileIsGermanDateTest extends PapayaTestCase {

  /**
   * @covers PapayaFilterFactoryProfileIsGermanDate::getFilter
   */
  public function testGetFilterExpectTrue() {
    $profile = new PapayaFilterFactoryProfileIsGermanDate();
    $this->assertTrue($profile->getFilter()->validate('15.08.2012'));
  }

  /**
   * @covers PapayaFilterFactoryProfileIsGermanDate::getFilter
   */
  public function testGetFilterExpectException() {
    $profile = new PapayaFilterFactoryProfileIsGermanDate();
    $this->setExpectedException('PapayaFilterException');
    $profile->getFilter()->validate('foo');
  }
}

<?php
require_once(dirname(__FILE__).'/../../../../../bootstrap.php');

class PapayaCacheIdentifierDefinitionUrlTest extends PapayaTestCase {

  /**
   * @covers PapayaCacheIdentifierDefinitionUrl
   */
  public function testGetStatus() {
    $environment = $_SERVER;
    $_SERVER = array(
      'HTTPS' => 'on',
      'HTTP_HOST' => 'www.sample.tld',
      'SERVER_PORT' => 443
    );
    $definition = new PapayaCacheIdentifierDefinitionUrl();
    $this->assertEquals(
      array(
        'PapayaCacheIdentifierDefinitionUrl' => 'https://www.sample.tld/'
      ),
      $definition->getStatus()
    );
    $_SERVER = $environment;
  }

  /**
   * @covers PapayaCacheIdentifierDefinitionUrl
   */
  public function testGetSources() {
    $definition = new PapayaCacheIdentifierDefinitionUrl();
    $this->assertEquals(
      PapayaCacheIdentifierDefinition::SOURCE_URL,
      $definition->getSources()
    );
  }
}
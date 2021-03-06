<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaThemeWrapperUrlTest extends PapayaTestCase {

  /**
  * @covers PapayaThemeWrapperUrl::__construct
  */
  public function testConstructorWithUrl() {
    $requestUrl = $this->getMock('PapayaUrl');
    $wrapperUrl = new PapayaThemeWrapperUrl($requestUrl);
    $this->assertAttributeSame(
      $requestUrl, '_requestUrl', $wrapperUrl
    );
  }

  /**
  * @covers PapayaThemeWrapperUrl::__construct
  */
  public function testConstructorWithoutUrl() {
    $wrapperUrl = new PapayaThemeWrapperUrl();
    $this->assertAttributeInstanceOf(
      'PapayaUrlCurrent', '_requestUrl', $wrapperUrl
    );
  }

  /**
  * @covers PapayaThemeWrapperUrl::getMimetype
  * @dataProvider provideValidWrapperUrls
  */
  public function testGetMimetypeExpectingMimeType($mimetype, $url) {
    $wrapperUrl = new PapayaThemeWrapperUrl(new PapayaUrl($url));
    $this->assertEquals($mimetype, $wrapperUrl->getMimetype());
  }

  /**
  * @covers PapayaThemeWrapperUrl::getMimetype
  * @dataProvider provideInvalidWrapperUrls
  */
  public function testGetMimetypeExpectingFalse($url) {
    $wrapperUrl = new PapayaThemeWrapperUrl(new PapayaUrl($url));
    $this->assertFalse($wrapperUrl->getMimetype());
  }

  /**
  * @covers PapayaThemeWrapperUrl::getThemeSet
  */
  public function testGetThemeSet() {
    $wrapperUrl = new PapayaThemeWrapperUrl(
      new PapayaUrl('http://www.sample.tld/papaya-themes/theme/js?set=42')
    );
    $this->assertEquals(42, $wrapperUrl->getThemeSet());
  }

  /**
  * @covers PapayaThemeWrapperUrl::getMimetype
  */
  public function testGetThemeSetExpectingZero() {
    $wrapperUrl = new PapayaThemeWrapperUrl(
      new PapayaUrl('http://www.sample.tld/papaya-themes/theme/js')
    );
    $this->assertEquals(0, $wrapperUrl->getThemeSet());
  }

  /**
  * @covers PapayaThemeWrapperUrl::parameters
  */
  public function testParametersSetParameters() {
    $parameters = $this->getMock('PapayaRequestParameters');
    $wrapper = new PapayaThemeWrapperUrl(new PapayaUrl('http://www.sample.tld'));
    $wrapper->parameters($parameters);
    $this->assertAttributeSame(
      $parameters, '_parameters', $wrapper
    );
  }

  /**
  * @covers PapayaThemeWrapperUrl::parameters
  */
  public function testParametersGetParametersAfterSet() {
    $parameters = $this->getMock('PapayaRequestParameters');
    $wrapper = new PapayaThemeWrapperUrl(new PapayaUrl('http://www.sample.tld'));
    $this->assertSame(
      $parameters, $wrapper->parameters($parameters)
    );
  }

  /**
  * @covers PapayaThemeWrapperUrl::parameters
  */
  public function testParametersGetParametersImplicitCreate() {
    $wrapper = new PapayaThemeWrapperUrl(new PapayaUrl('http://www.sample.tld?foo=bar'));
    $parameters = $wrapper->parameters();
    $this->assertInstanceOf('PapayaRequestParameters', $parameters);
    $this->assertEquals(array('foo' => 'bar'), $parameters->toArray());
  }

  /**
  * @covers PapayaThemeWrapperUrl::getFiles
  */
  public function testGetFiles() {
    $wrapperUrl = new PapayaThemeWrapperUrl(
      new PapayaUrl('http://www.sample.tld/papaya-themes/theme/js?files=foo,bar&rev=42')
    );
    $this->assertEquals(
      array('foo', 'bar'), $wrapperUrl->getFiles()
    );
  }

  /**
  * @covers PapayaThemeWrapperUrl::getGroup
  */
  public function testGetGroup() {
    $wrapperUrl = new PapayaThemeWrapperUrl(
      new PapayaUrl('http://www.sample.tld/papaya-themes/theme/js?group=foo&rev=42')
    );
    $this->assertEquals(
      'foo', $wrapperUrl->getGroup()
    );
  }

  /**
  * @covers PapayaThemeWrapperUrl::getTheme
  * @dataProvider provideThemesFromUrl
  */
  public function testGetTheme($theme, $url) {
    $wrapperUrl = new PapayaThemeWrapperUrl(
      new PapayaUrl($url)
    );
    $this->assertEquals(
      $theme, $wrapperUrl->getTheme($url)
    );
  }

  /**
  * @covers PapayaThemeWrapperUrl::allowDirectories
  */
  public function testAllowDirectoriesExpectingTrue() {
    $wrapperUrl = new PapayaThemeWrapperUrl(
      new PapayaUrl('http://www.sample.tld/papaya-themes/theme/css?rec=yes')
    );
    $this->assertTrue($wrapperUrl->allowDirectories());
  }

  /**
  * @covers PapayaThemeWrapperUrl::allowDirectories
  */
  public function testAllowDirectoriesExpectingFalse() {
    $wrapperUrl = new PapayaThemeWrapperUrl(
      new PapayaUrl('http://www.sample.tld/papaya-themes/theme/css')
    );
    $this->assertFalse($wrapperUrl->allowDirectories());
  }

  /***************************
  * DataProvider
  ***************************/

  public static function provideValidWrapperUrls() {
    return array(
      array(
        'text/css',
        'http://www.sample.tld/papaya-themes/theme/css.php'
      ),
      array(
        'text/css',
        'http://www.sample.tld/papaya-themes/theme/css'
      ),
      array(
        'text/css',
        'http://www.sample.tld/papaya-themes/theme/css.php?files=sample.css'
      ),
      array(
        'text/css',
        'http://www.sample.tld/papaya-themes/theme/css?files=sample.css'
      ),
      array(
        'text/css',
        'http://www.sample.tld/papaya-themes/theme/css?files=sample'
      ),
      array(
        'text/css',
        'http://www.sample.tld/papaya-themes/theme/css?files=foo,bar'
      ),
      array(
        'text/css',
        'http://www.sample.tld/papaya-themes/theme/css?files=foo,bar&rev=42'
      ),
      array(
        'text/javascript',
        'http://www.sample.tld/papaya-themes/theme/js.php'
      ),
      array(
        'text/javascript',
        'http://www.sample.tld/papaya-themes/theme/js'
      ),
      array(
        'text/javascript',
        'http://www.sample.tld/papaya-themes/theme/js.php?files=sample.js'
      ),
      array(
        'text/javascript',
        'http://www.sample.tld/papaya-themes/theme/js?files=sample.js'
      ),
      array(
        'text/javascript',
        'http://www.sample.tld/papaya-themes/theme/js?files=sample'
      ),
      array(
        'text/javascript',
        'http://www.sample.tld/papaya-themes/theme/js?files=foo,bar'
      ),
      array(
        'text/javascript',
        'http://www.sample.tld/papaya-themes/theme/js?files=foo,bar&rev=42'
      ),
      array(
        'text/javascript',
        'http://theme.sample.tld/theme/js?files=foo,bar&rev=42'
      ),
      array(
        'text/css',
        'http://theme.sample.tld/theme/css.php?files=foo,bar&rev=42'
      )
    );
  }

  public static function provideInvalidWrapperUrls() {
    return array(
      array('http://www.sample.tld/'),
      array('http://www.sample.tld/css'),
      array('http://www.sample.tld/css.php'),
      array('http://www.sample.tld/js'),
      array('http://www.sample.tld/js.php'),
      array('http://www.sample.tld/index.html'),
      array('http://www.sample.tld/index.de.html')
    );
  }

  public static function provideThemesFromUrl() {
    return array(
      array('theme', 'http://www.sample.tld/papaya-themes/theme/css')
    );
  }
}
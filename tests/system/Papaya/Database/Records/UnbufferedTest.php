<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaDatabaseRecordsUnbufferedTest extends PapayaTestCase {

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::load
  * @covers PapayaDatabaseRecordsUnbuffered::_loadSql
  * @covers PapayaDatabaseRecordsUnbuffered::_compileCondition
  * @covers PapayaDatabaseRecordsUnbuffered::_compileOrderBy
  */
  public function testLoad() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->setMethods(array('getSqlCondition', 'queryFmt'))
      ->getMock();
    $databaseAccess
      ->expects($this->once())
      ->method('getSqlCondition')
      ->with(array('field_id' => 42))
      ->will($this->returnValue(" field_id = '42'"));
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with(
        $this->isType('string'),
        array('tablename')
      )
      ->will($this->returnValue($databaseResult));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->setDatabaseAccess($databaseAccess);
    $this->assertTrue($records->load(42));
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::load
  * @covers PapayaDatabaseRecordsUnbuffered::_loadSql
  * @covers PapayaDatabaseRecordsUnbuffered::_compileCondition
  * @covers PapayaDatabaseRecordsUnbuffered::_compileOrderBy
  * @see https://bugs.papaya-cms.com/view.php?id=2982 Reason for checking if SQL contains WHERE
  */
  public function testLoadWithConditionObject() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->setMethods(array('getSqlCondition', 'queryFmt'))
      ->getMock();
    $databaseAccess
      ->expects($this->never())
      ->method('getSqlCondition');
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with(
        $this->logicalAnd(
          $this->isType('string'),
          $this->matchesRegularExpression('/.+ WHERE .+/i')
        ),
        array('tablename')
      )
      ->will($this->returnValue($databaseResult));
    $condition = $this
      ->getMockBuilder('PapayaDatabaseConditionElement')
      ->disableOriginalConstructor()
      ->getMock();
    $condition
      ->expects($this->once())
      ->method('getSql')
      ->will($this->returnValue(" field_id = '42'"));

    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->setDatabaseAccess($databaseAccess);
    $this->assertTrue($records->load($condition));
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::load
  * @covers PapayaDatabaseRecordsUnbuffered::_loadSql
  * @covers PapayaDatabaseRecordsUnbuffered::_compileCondition
  * @covers PapayaDatabaseRecordsUnbuffered::_compileOrderBy
  */
  public function testLoadWithoutConditions() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->setMethods(array('queryFmt'))
      ->getMock();
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with(
        $this->isType('string'),
        array('tablename')
      )
      ->will($this->returnValue($databaseResult));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->setDatabaseAccess($databaseAccess);
    $this->assertTrue($records->load());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::load
  * @covers PapayaDatabaseRecordsUnbuffered::_loadSql
  * @covers PapayaDatabaseRecordsUnbuffered::_compileCondition
  * @covers PapayaDatabaseRecordsUnbuffered::_compileOrderBy
  */
  public function testLoadWithoutConditionsWithOrderBy() {
    $orderBy = $this->getMock('PapayaDatabaseInterfaceOrder');
    $orderBy
      ->expects($this->once())
      ->method('__toString')
      ->will($this->returnValue('>>ORDERBY<<'));
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->setMethods(array('queryFmt'))
      ->getMock();
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with(
        $this->stringContains('>>ORDERBY<<'),
        array('tablename')
      )
      ->will($this->returnValue($databaseResult));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->orderBy($orderBy);
    $records->setDatabaseAccess($databaseAccess);
    $this->assertTrue($records->load());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::load
  * @covers PapayaDatabaseRecordsUnbuffered::_loadSql
  */
  public function testLoadExpectingFalse() {
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->setMethods(array('getSqlCondition', 'queryFmt'))
      ->getMock();
    $databaseAccess
      ->expects($this->once())
      ->method('getSqlCondition')
      ->with(array('field_id' => 42))
      ->will($this->returnValue(" field_id = '42'"));
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with(
        $this->isType('string'),
        array('tablename')
      )
      ->will($this->returnValue(FALSE));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->setDatabaseAccess($databaseAccess);
    $this->assertFalse($records->load(42));
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::createFilter
  */
  public function testCreateFilter() {
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->getMock();
    $mapping = $this
      ->getMockBuilder('PapayaDatabaseInterfaceMapping')
      ->getMock();
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->setDatabaseAccess($databaseAccess);
    $records->mapping($mapping);
    $filter = $records->createFilter();
    $this->assertInstanceOf('PapayaDatabaseConditionRoot', $filter);
    $this->assertSame($databaseAccess, $filter->getDatabaseAccess());
    $this->assertSame($mapping, $filter->getMapping());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::count
  */
  public function testCount() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->once())
      ->method('count')
      ->will($this->returnValue(3));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->databaseResult($databaseResult);
    $this->assertEquals(3, $records->count());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::count
  */
  public function testCountWihtoutDatabaseResultExpectingZero() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $this->assertEquals(0, $records->count());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::absCount
  */
  public function testAbsCount() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->once())
      ->method('absCount')
      ->will($this->returnValue(7));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->databaseResult($databaseResult);
    $this->assertEquals(7, $records->absCount());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::absCount
  */
  public function testAbsCountWihtoutDatabaseResultExpectingZero() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $this->assertEquals(0, $records->absCount());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::toArray
  * @covers PapayaDatabaseRecordsUnbuffered::getIterator
  * @covers PapayaDatabaseRecordsUnbuffered::getResultIterator
  */
  public function testToArrayUsingGetIterator() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->any())
      ->method('fetchRow')
      ->with(PapayaDatabaseResult::FETCH_ASSOC)
      ->will(
        $this->onConsecutiveCalls(
          array(
            'field_id' => 21,
            'field_data' => 'row 1'
          ),
          array(
            'field_id' => 42,
            'field_data' => 'row 2'
          ),
          NULL
        )
      );
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->databaseResult($databaseResult);
    $this->assertEquals(
      array(
        array(
          'id' => 21,
          'data' => 'row 1'
        ),
        array(
          'id' => 42,
          'data' => 'row 2'
        ),
      ),
      $records->toArray()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::toArray
  * @covers PapayaDatabaseRecordsUnbuffered::getIterator
  * @covers PapayaDatabaseRecordsUnbuffered::getResultIterator
  */
  public function testToArrayWithEmptyResultUsingGetIterator() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->any())
      ->method('fetchRow')
      ->with(PapayaDatabaseResult::FETCH_ASSOC)
      ->will(
        $this->onConsecutiveCalls(
          NULL
        )
      );
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->databaseResult($databaseResult);
    $this->assertEquals(
      array(),
      $records->toArray()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::getIterator
  * @covers PapayaDatabaseRecordsUnbuffered::getResultIterator
  */
  public function testGetIteratorWithoutResultEmptytingEmpty() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $this->assertInstanceOf('EmptyIterator', $records->getIterator());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::mapping
  */
  public function testMappingGetAfterSet() {
    $mapping = $this->getMock('PapayaDatabaseInterfaceMapping');
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->mapping($mapping);
    $this->assertSame(
      $mapping, $records->mapping()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::mapping
  * @covers PapayaDatabaseRecordsUnbuffered::_createMapping
  */
  public function testMappingGetImplicitCreate() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $this->assertInstanceOf(
      'PapayaDatabaseRecordMapping', $records->mapping()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::orderBy
  */
  public function testOrderByGetAfterSet() {
    $orderBy = $this->getMock('PapayaDatabaseInterfaceOrder');
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->orderBy($orderBy);
    $this->assertSame(
      $orderBy, $records->orderBy()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::orderBy
  * @covers PapayaDatabaseRecordsUnbuffered::_createOrderBy
  */
  public function testOrderByGetImplicitCreateExpectingEmpty() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $this->assertFalse($records->orderBy());
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::orderBy
  * @covers PapayaDatabaseRecordsUnbuffered::_createOrderBy
  */
  public function testOrderByGetImplicitCreateWithField() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->_orderByFields = array('fieldname' => PapayaDatabaseInterfaceOrder::ASCENDING);
    $this->assertEquals(
      'fieldname ASC',
      (string)$records->orderBy()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::orderBy
  * @covers PapayaDatabaseRecordsUnbuffered::_createOrderBy
  */
  public function testOrderByGetImplicitCreateWithTwoFields() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->_orderByFields = array(
      'field_one' => PapayaDatabaseInterfaceOrder::DESCENDING,
      'field_two' => PapayaDatabaseInterfaceOrder::ASCENDING
    );
    $this->assertEquals(
      'field_one DESC, field_two ASC',
      (string)$records->orderBy()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::orderBy
  * @covers PapayaDatabaseRecordsUnbuffered::_createOrderBy
  */
  public function testOrderByGetImplicitCreateWithProperty() {
    $mapping = $this->getMock('PapayaDatabaseInterfaceMapping');
    $mapping
      ->expects($this->once())
      ->method('getField')
      ->with('name')
      ->will($this->returnValue('fieldname'));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->mapping($mapping);
    $records->_orderByProperties = array('name' => PapayaDatabaseInterfaceOrder::ASCENDING);
    $this->assertEquals(
      'fieldname ASC',
      (string)$records->orderBy()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::orderBy
  * @covers PapayaDatabaseRecordsUnbuffered::_createOrderBy
  */
  public function testOrderByGetImplicitCreateWithTwoProperties() {
    $mapping = $this->getMock('PapayaDatabaseInterfaceMapping');
    $mapping
      ->expects($this->any())
      ->method('getField')
      ->will(
        $this->returnValueMap(
          array(
            array('one', TRUE, 'field_one'),
            array('two', TRUE, 'field_two')
          )
        )
      );
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->mapping($mapping);
    $records->_orderByProperties = array(
      'one' => PapayaDatabaseInterfaceOrder::ASCENDING,
      'two' => PapayaDatabaseInterfaceOrder::DESCENDING
    );
    $this->assertEquals(
      'field_one ASC, field_two DESC',
      (string)$records->orderBy()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::orderBy
  * @covers PapayaDatabaseRecordsUnbuffered::_createOrderBy
  */
  public function testOrderByGetImplicitCreateWithPropertiesAndFields() {
    $mapping = $this->getMock('PapayaDatabaseInterfaceMapping');
    $mapping
      ->expects($this->any())
      ->method('getField')
      ->will(
        $this->returnValueMap(
          array(
            array('one', TRUE, 'field_one'),
            array('two', TRUE, NULL)
          )
        )
      );
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->mapping($mapping);
    $records->_orderByProperties = array(
      'one' => PapayaDatabaseInterfaceOrder::ASCENDING,
      'two' => PapayaDatabaseInterfaceOrder::DESCENDING
    );
    $records->_orderByFields = array(
      'field_three' => PapayaDatabaseInterfaceOrder::ASCENDING,
      'field_four' => PapayaDatabaseInterfaceOrder::DESCENDING
    );
    $this->assertEquals(
      'field_one ASC, field_three ASC, field_four DESC',
      (string)$records->orderBy()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::databaseResult
  */
  public function testDatabaseResultGetAfterSet() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->databaseResult($databaseResult);
    $this->assertSame(
      $databaseResult, $records->databaseResult()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::setDatabaseAccess
  * @covers PapayaDatabaseRecordsUnbuffered::getDatabaseAccess
  */
  public function testGetDatabaseAccessAfterSet() {
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->getMock();
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->setDatabaseAccess($databaseAccess);
    $this->assertSame(
      $databaseAccess, $records->getDatabaseAccess()
    );
  }

  /**
  * @covers PapayaDatabaseRecordsUnbuffered::getDatabaseAccess
  */
  public function testGetDatabaseAccessImplicitCreate() {
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->getMock();
    $databaseManager = $this->getMock('PapayaDatabaseManager');
    $databaseManager
      ->expects($this->any())
      ->method('createDatabaseAccess')
      ->will($this->returnValue($databaseAccess));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->papaya(
      $this->mockPapaya()->application(
        array('database' => $databaseManager)
      )
    );
    $this->assertEquals(
      $databaseAccess, $records->getDatabaseAccess()
    );
  }

  /**
   * @covers PapayaDatabaseRecordsUnbuffered::_createItem
   * @covers PapayaDatabaseRecordsUnbuffered::getItem
   */
  public function testGetItemExpectingException() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $this->setExpectedException('LogicException');
    $records->getItem();
  }

  /**
   * @covers PapayaDatabaseRecordsUnbuffered::_createItem
   * @covers PapayaDatabaseRecordsUnbuffered::getItem
   */
  public function testGetItem() {
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy();
    $records->_itemClass = 'PapayaDatabaseRecordsUnbuffered_TestItemProxy';
    $this->assertInstanceOf('PapayaDatabaseRecordsUnbuffered_TestItemProxy', $records->getItem());
  }

  /**
   * @covers PapayaDatabaseRecordsUnbuffered::getItem
   */
  public function testGetItemWithFilterCallingLoad() {
    $record = $this->getMock('PapayaDatabaseRecord');
    $record
      ->expects($this->once())
      ->method('load')
      ->with(array('id' => '42'));
    $records = new PapayaDatabaseRecordsUnbuffered_TestProxy;
    $records->item = $record;
    $records->getItem(array('id' => '42'));
  }
}

class PapayaDatabaseRecordsUnbuffered_TestProxy extends PapayaDatabaseRecordsUnbuffered {

  public $_fields = array(
    'id' => 'field_id',
    'data' => 'field_data'
  );

  public $_orderByFields = array();
  public $_orderByProperties = array();

  protected $_tableName = 'tablename';

  public $_itemClass = NULL;
  public $item = NULL;

  public function _createItem() {
    if (isset($this->item)) {
      return $this->item;
    } else {
      return parent::_createItem();
    }
  }
}

class PapayaDatabaseRecordsUnbuffered_TestItemProxy extends PapayaDatabaseRecord {

}
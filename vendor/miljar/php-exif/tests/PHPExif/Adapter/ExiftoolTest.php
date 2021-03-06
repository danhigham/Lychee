<?php
/**
 * @covers \PHPExif\Adapter\Exiftool::<!public>
 */
class ExiftoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPExif\Adapter\Exiftool
     */
    protected $adapter;

    public function setUp()
    {
        $this->adapter = new \PHPExif\Adapter\Exiftool();
    }

    /**
     * @group exiftool
     * @covers \PHPExif\Adapter\Exiftool::getToolPath
     */
    public function testGetToolPathFromProperty()
    {
        $reflProperty = new \ReflectionProperty('\PHPExif\Adapter\Exiftool', 'toolPath');
        $reflProperty->setAccessible(true);
        $expected = '/foo/bar/baz';
        $reflProperty->setValue($this->adapter, $expected);

        $this->assertEquals($expected, $this->adapter->getToolPath());
    }

    /**
     * @group exiftool
     * @covers \PHPExif\Adapter\Exiftool::setToolPath
     */
    public function testSetToolPathInProperty()
    {
        $reflProperty = new \ReflectionProperty('\PHPExif\Adapter\Exiftool', 'toolPath');
        $reflProperty->setAccessible(true);

        $expected = '/tmp';
        $this->adapter->setToolPath($expected);

        $this->assertEquals($expected, $reflProperty->getValue($this->adapter));
    }

    /**
     * @group exiftool
     * @covers \PHPExif\Adapter\Exiftool::setToolPath
     * @expectedException InvalidArgumentException
     */
    public function testSetToolPathThrowsException()
    {
        $this->adapter->setToolPath('/foo/bar');
    }


    /**
     * @group exiftool
     * @covers \PHPExif\Adapter\Exiftool::getToolPath
     */
    public function testGetToolPathLazyLoadsPath()
    {
        $this->assertInternalType('string', $this->adapter->getToolPath());
    }

    /**
     * @group exiftool
     * @covers \PHPExif\Adapter\Exiftool::setNumeric
     */
    public function testSetNumericInProperty()
    {
        $reflProperty = new \ReflectionProperty('\PHPExif\Adapter\Exiftool', 'numeric');
        $reflProperty->setAccessible(true);

        $expected = true;
        $this->adapter->setNumeric($expected);

        $this->assertEquals($expected, $reflProperty->getValue($this->adapter));
    }

    /**
     * @group exiftool
     * @covers \PHPExif\Adapter\Exiftool::getExifFromFile
     */
    public function testGetExifFromFile()
    {
        $file = PHPEXIF_TEST_ROOT . '/files/morning_glory_pool_500.jpg';
        $result = $this->adapter->getExifFromFile($file);
        $this->assertInstanceOf('\PHPExif\Exif', $result);
        $this->assertInternalType('array', $result->getRawData());
        $this->assertNotEmpty($result->getRawData());
    }

    /**
     * @group exiftool
     * @covers \PHPExif\Adapter\Exiftool::getCliOutput
     */
    public function testGetCliOutput()
    {
        $reflMethod = new \ReflectionMethod('\PHPExif\Adapter\Exiftool', 'getCliOutput');
        $reflMethod->setAccessible(true);

        $result = $reflMethod->invoke(
            $this->adapter,
            sprintf(
                '%1$s',
                'pwd'
            )
        );

        $this->assertInternalType('string', $result);
    }
}

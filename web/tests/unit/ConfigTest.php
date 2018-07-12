<?php
namespace Tests\Unit;

use App\Config\Config;
use Webmozart\Assert\Assert;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $configAry;

    public function setUp()
    {
        $config = parse_ini_file(__DIR__ . '/../../config.ini.php');
        $this->configAry = $config;
    }

    public function testConfig()
    {
        $config = Config::getDBConfig();
        $this->assertSame(array_diff($this->configAry, $config), array_diff($config, $this->configAry));
    }
}
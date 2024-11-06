<?php

namespace CpmsCommonTest;

use Laminas\Mvc\Application;

/**
 * Test bootstrap, for setting up auto loading and paths
 */
class Bootstrap
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected static $serviceManager;

    /** @var  string This is the root directory where the test is run from which likely the test directory */
    protected static $dir;

    protected static Application $application;

    private static ?Bootstrap $instance = null;

    private function __construct()
    {
    }

    /**
     * @return Bootstrap
     */
    public static function getInstance()
    {
        if (!Bootstrap::$instance) {
            Bootstrap::$instance = new self();
        }

        return Bootstrap::$instance;
    }

    public function init(string $dir, string $testModule = ''): void
    {
        static::$dir = $dir;

        $this->setPaths();

        $zf2ModulePaths = array(dirname(dirname($dir)));
        if (($path = static::findParentPath('vendor'))) {
            $zf2ModulePaths[] = $path;
        }
        if (($path = static::findParentPath('src')) !== $zf2ModulePaths[0]) {
            $zf2ModulePaths[] = $path;
        }

        $zf2ModulePaths[] = './';

        $config = include __DIR__ . '/../../config/application.config.php';

        if ($testModule) {
            $config['modules'][] = $testModule;
        }

        include __DIR__ . '/../../init_autoloader.php';

        $application    = Application::init($config);
        $serviceManager = $application->getServiceManager();

        static::$serviceManager = $serviceManager;
        static::$application    = $application;
    }

    protected function setPaths(): void 
    {
        $basePath = realpath(static::$dir) . '/';

        set_include_path(
            implode(
                PATH_SEPARATOR,
                array(
                    $basePath,
                    $basePath . '/vendor',
                    $basePath . '/test',
                    get_include_path(),
                )
            )
        );
    }

    /**
     *
     * @param string $path
     *
     * @return boolean|string false if the path cannot be found
     */
    protected function findParentPath($path)
    {
        $srcDir = realpath(static::$dir . '/../');

        return $srcDir . '/' . $path;
    }

    /**
     * @return \Laminas\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return static::$serviceManager;
    }

    /**
     * @return mixed
     */
    public static function getApplication()
    {
        return self::$application;
    }

    private function __clone()
    {
    }
}

$path = realpath(__DIR__ . '/../');

if ($path) {
    chdir(dirname($path));
    Bootstrap::getInstance()->init($path, 'CpmsCommonTest');
}

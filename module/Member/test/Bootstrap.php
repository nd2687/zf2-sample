<?php

namespace MemberTest;

use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Db\Adapter\AdapterInterface;

error_reporting(E_ALL | E_STRICT);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap
{
    protected static $serviceManager;

    public static function getApplicationConfig()
    {
        return require('config/application.config.php');
    }

    public static function init()
    {
        putenv("APP_ENV=test");

        self::chroot();
        static::initAutoloader();

        $config = self::getApplicationConfig();

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        static::$serviceManager = $serviceManager;
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        $adapter = $serviceManager->get('Zend\Db\Adapter\AdapterInterface');
        GlobalAdapterFeature::setStaticAdapter($adapter);
    }

    public static function chroot()
    {
        $root = implode(
            DIRECTORY_SEPARATOR,
            array_merge([__DIR__], array_fill(0, 3, '..'))
        );
        chdir($root);
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        if (file_exists($vendorPath.'/autoload.php')) {
            $loader = include $vendorPath.'/autoload.php';
        }

        if (!isset($loader)) {
            $msg = 'vendor/autoload.php could not be found. Did you run `php composer.phar install`?';
            throw new \RuntimeException($msg);
        }

        AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        ));
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();

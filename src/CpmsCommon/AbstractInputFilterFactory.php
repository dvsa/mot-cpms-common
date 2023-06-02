<?php

namespace CpmsCommon;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Router\RouteMatch;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractInputFilterFactory
 *
 * @package CpmsCommon
 */
class AbstractInputFilterFactory implements AbstractFactoryInterface
{
    const VERSION_PREFIX = 'V';

    private $configPrefix;

    protected $versionedNamespaces
        = [
            'Payment',
            'DirectDebit',
        ];

    public function __construct()
    {
        $this->configPrefix = '\inputFilter\\';
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     * @throws ContainerException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this($serviceLocator, $requestedName);
    }

    /**
     * @param                         $name
     * @param ContainerInterface $serviceLocator
     *
     * @return string
     */
    public function getClassName($name, ContainerInterface $serviceLocator)
    {
        $namespaceParts = explode('\\', $name);

        for ($i = 0; $i < count($namespaceParts); $i++) {
            $namespaceParts[$i] = ucfirst($namespaceParts[$i]);
        }

        if (count($namespaceParts) > 0 and in_array($namespaceParts[0], $this->versionedNamespaces)) {

            $config         = $serviceLocator->get('config');
            $defaultVersion = $config['api-tools-versioning']['default_version'];
            /** @var RouteMatch $routeMatch */
            $routeMatch = $serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();

            if (empty($routeMatch)) {
                $version = $defaultVersion;
            } else {
                $version = (int)$routeMatch->getParam('version');
                if ($version == 0) {
                    $version = $defaultVersion;
                }
            }

            array_splice($namespaceParts, 1, 0, [self::VERSION_PREFIX . $version]);
        }

        return implode('\\', $namespaceParts);
    }

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (stripos($requestedName, $this->configPrefix) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var InputFilterProviderInterface $filterProvider */
        $filterProviderClass = $this->getClassName($requestedName . 'Provider', $container);
        $filterProvider      = new $filterProviderClass();
        if ($filterProvider instanceof ServiceLocatorAwareInterface) {
            $filterProvider->setServiceLocator($container);
        }
        $factory = new Factory();

        return $factory->createInputFilter($filterProvider->getInputFilterSpecification());
    }
}

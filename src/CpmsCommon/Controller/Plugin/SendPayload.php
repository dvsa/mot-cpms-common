<?php

namespace CpmsCommon\Controller\Plugin;

use CpmsCommon\Service\ErrorCodeService;
use CpmsCommon\Controller\AbstractRestfulController;
use Laminas\Http\Header\ContentType;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Mvc\Application;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Router\Http\RouteMatch;

/**
 * Class SendPayload
 * @method AbstractRestfulController getController()
 *
 * @package     CpmsCommon\Controller\Plugin
 * @author      Pele Odiase <pele.odiase@valtech.co.uk>
 * @since       22 June 2014
 */
class SendPayload extends AbstractPlugin
{
    public const API_VERSION_KEY = 'api_version';

    /**
     * Set HTTP status code if specified and return appropriate view model
     *
     * @param array|object $payLoad
     *
     * @return \Laminas\View\Model\ModelInterface
     */
    public function __invoke($payLoad)
    {
        $controller  = $this->getController();
        $serviceLocator = $controller->getServiceLocator();

        $payLoad = (array)$payLoad;
        /** @var array $config */
        $config = $serviceLocator->get('config');
        /** @var string $contentType */
        $contentType = $serviceLocator->get('cpms\api\contentType');
        /** @var Response $response */
        $response = $controller->getResponse();
        /** @var Application $application */
        $application = $serviceLocator->get('application');
        /** @var RouteMatch $routeMatch */
        $routeMatch = $application->getMvcEvent()->getRouteMatch();

        $response->getHeaders()->addHeader(ContentType::fromString($contentType));

        if (isset($payLoad[ErrorCodeService::HTTP_STATUS_KEY])) {
            $response->setStatusCode($payLoad[ErrorCodeService::HTTP_STATUS_KEY]);
            unset($payLoad[ErrorCodeService::HTTP_STATUS_KEY]);
        }

        $payLoad   = $this->setApiVersion($payLoad, $config, $routeMatch);
        $viewModel = $controller->acceptableViewModelSelector($config['accept_criteria']);
        $viewModel->setVariables((array) $payLoad);

        return $viewModel;
    }

    /**
     * Set the api version in the response
     *
     * @param array $payLoad
     * @param array $config
     * @param ?RouteMatch $routeMatch
     *
     * @return mixed
     */
    public function setApiVersion($payLoad, $config, $routeMatch)
    {
        if ($routeMatch instanceof RouteMatch) {
            $version = $routeMatch->getParam('zf_ver_version', $routeMatch->getParam('version', null));
        }

        if (empty($version) and isset($config['api-tools-versioning']['default_version'])) {
            $version = $config['api-tools-versioning']['default_version'];
        }

        if (!empty($version) && $version > 1) {
            $payLoad[self::API_VERSION_KEY] = $version;
        }

        return $payLoad;
    }
}

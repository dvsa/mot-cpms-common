<?php

namespace CpmsCommon\Controller\Plugin;

use CpmsCommon\Service\ErrorCodeService;
use Laminas\Http\Header\ContentType;
use Laminas\Mvc\Application;
use Laminas\Mvc\Controller\AbstractRestfulController;
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
     * @param $payLoad
     *
     * @return \Laminas\View\Model\ModelInterface
     */
    public function __invoke($payLoad)
    {
        /** @var \Laminas\Http\PhpEnvironment\Response $response */
        /** @var RouteMatch $routeMatch */
        /** @var Application $application */
        $controller  = $this->getController();
        $serviceLocator = $controller->getServiceLocator();

        $payLoad     = (array)$payLoad;
        $config      = $serviceLocator->get('config');
        $contentType = $serviceLocator->get('cpms\api\contentType');
        $response    = $controller->getResponse();
        $application = $serviceLocator->get('application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();

        $response->getHeaders()->addHeader(ContentType::fromString($contentType));

        if (isset($payLoad[ErrorCodeService::HTTP_STATUS_KEY])) {
            $response->setStatusCode($payLoad[ErrorCodeService::HTTP_STATUS_KEY]);
            unset($payLoad[ErrorCodeService::HTTP_STATUS_KEY]);
        }

        $payLoad   = $this->setApiVersion($payLoad, $config, $routeMatch);
        $viewModel = $controller->acceptableViewModelSelector($config['accept_criteria']);
        $viewModel->setVariables($payLoad);

        return $viewModel;
    }

    /**
     * Set the api version in the response
     *
     * @param $payLoad
     * @param $config
     * @param $routeMatch
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

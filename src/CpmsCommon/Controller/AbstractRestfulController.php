<?php

/**
 * An abstract controller that all CPMS restful controllers inherit from
 *
 * @package     olcscommon
 * @subpackage  controller
 * @author      Pelle Wessman <pelle.wessman@valtech.se>
 */

namespace CpmsCommon\Controller;

use CpmsCommon\ContentTypeAwareInterface;
use CpmsCommon\ControllerTrait\ContentTypeTrait;
use CpmsCommon\Service\ErrorCodeService;
use CpmsCommon\Utility\ErrorCodeAwareTrait;
use CpmsCommon\Utility\LoggerAwareTrait;
use Laminas\Http\Header\ContentType;
use Laminas\Http\PhpEnvironment\Request as HttpRequest;
use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use Laminas\Mvc\Controller\AbstractRestfulController as ZendRestfulController;
use Laminas\Stdlib\RequestInterface as Request;
use Laminas\Stdlib\ResponseInterface as Response;
use Laminas\View\Model\JsonModel;

/**
 * Class AbstractRestfulController
 * @method HttpRequest getRequest()
 * @method HttpResponse getResponse()
 * @method sendPayload($payLoad)
 * @method download()
 *
 * @package CpmsCommon\Controller
 */
abstract class AbstractRestfulController extends ZendRestfulController implements ContentTypeAwareInterface
{
    use LoggerAwareTrait;
    use ErrorCodeAwareTrait;
    use ContentTypeTrait;

    // TODO this is an anti-pattern added here to make PoC zf2->zf3 migration happen. Sorry. This should be fixed in the future!
    private $serviceLocator;

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Inspect controller response and return appropriate messages for method not implemented
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return mixed|\Laminas\Http\PhpEnvironment\Response|Response
     */
    public function dispatch(Request $request, Response $response = null)
    {
        $logger = $this->getLogger();
        try {
            /** @var $response HttpResponse $data */
            $data     = parent::dispatch($request, $response);
            $response = $this->getResponse();
            $logger->debug($request->toString());

            /** @var $request \Laminas\Http\PhpEnvironment\Request $data */

            if ($response->getStatusCode() == $response::STATUS_CODE_405) {
                $request = $this->getRequest();

                $viewModel = new JsonModel(
                    $this->getErrorMessage(
                        ErrorCodeService::NOT_IMPLEMENTED,
                        $request->getMethod()
                    )
                );
                $viewModel->setTerminal(true);

                $response->setContent($viewModel->serialize());

                return $this->setContentType($response);
            }

            $logger->debug($response->toString());

            return $data;
        } catch (\Exception $exception) {
            $this->logException($exception);
            $response  = new HttpResponse();
            $viewModel = new JsonModel(
                $this->getErrorMessage(
                    ErrorCodeService::AN_ERROR_OCCURRED,
                    '',
                    HttpResponse::STATUS_CODE_500
                )
            );
            $viewModel->setTerminal(true);

            $response->setStatusCode(HttpResponse::STATUS_CODE_500);
            $response->setContent($viewModel->serialize());
            $logger->debug($response->toString());

            return $this->setContentType($response);
        }
    }

    /**
     * @param HttpResponse $response
     *
     * @return HttpResponse
     */
    protected function setContentType(HttpResponse $response)
    {
        $headers = $response->getHeaders();
        $headers->addHeader(ContentType::fromString('Content-Type: application/json'));
        $response->setHeaders($headers);

        return $response;
    }
}

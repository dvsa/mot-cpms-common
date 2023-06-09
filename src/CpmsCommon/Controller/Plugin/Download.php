<?php
namespace CpmsCommon\Controller\Plugin;

use CpmsCommon\Controller\AbstractRestfulController;
use CpmsCommon\Service\ErrorCodeService;
use Laminas\Http\Headers;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Http\Response\Stream;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class Download
 * @method AbstractRestfulController getController()
 *
 * @package     CpmsCommon\Controller\Plugin
 * @author      Pele Odiase <pele.odiase@valtech.co.uk>
 */
class Download extends AbstractPlugin
{
    /**
     * Force file download
     *
     * @param      $file
     * @param null $maskedFile
     *
     * @return Response|Stream
     */
    public function __invoke($file, $maskedFile = null)
    {
        if (file_exists($file)) {
            /** @var Stream $response */
            $response       = new Stream();
            $headers        = new Headers();
            $maskedFilename = $maskedFile ?: $this->getMaskFilename($file);

            $response->setStream(fopen($file, 'r'));
            $response->setHeaders($headers);
            $response->setStatusCode(Response::STATUS_CODE_200);

            $headers->clearHeaders()
                ->addHeaderLine('Content-Type', 'application/octet-stream')
                ->addHeaderLine('Content-Disposition', "attachment; filename={$maskedFilename}")
                ->addHeaderLine('Content-Length', filesize($file))
                ->addHeaderLine('Content-Description', 'File Transfer');
        } else {

            $message  = $this->getController()->getMessage(ErrorCodeService::RESOURCE_NOT_FOUND, $file);
            $response = $this->getController()->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_404);
            $response->setReasonPhrase($message);
        }

        return $response;
    }

    /**
     * Get friendly filename
     *
     * @param           $file
     *
     * @return string
     */
    protected function getMaskFilename($file)
    {
        $pathInfo = pathinfo($file);
        $name     = substr(basename($file), 0, -4);
        $fileName = $name . '-' . date('d-m-Y') . '.' . $pathInfo['extension'];

        return $fileName;
    }
}

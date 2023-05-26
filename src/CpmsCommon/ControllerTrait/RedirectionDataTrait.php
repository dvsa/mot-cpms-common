<?php

namespace CpmsCommon\ControllerTrait;

use CpmsCommon\Utility\Util;
use Laminas\Mvc\Controller\Plugin\Redirect;
use Laminas\View\Model\ViewModel;

/**
 * Class RedirectionDataTrait
 * @method sendPayload(array)
 * @method Redirect redirect()
 *
 * @package CpmsCommon\ControllerTrait
 */
trait RedirectionDataTrait
{
    /**
     * Handle redirect to third party gateway if we have a gateway url
     * Redirect to redirect_uri if request is not restful
     *
     * @param $redirectionData
     *
     * @return ViewModel
     */
    protected function handleRedirectionData($redirectionData)
    {

        if (!empty($redirectionData['gateway_url'])) {

            $gatewayUrl = $redirectionData['gateway_url'];
            unset($redirectionData['gateway_url']);

            $view = new ViewModel(
                array(
                    'data'       => $redirectionData,
                    'gatewayUrl' => $gatewayUrl,
                )
            );

            $view->setTemplate('general/gateway-redirect.phtml');

            return $view;
        } elseif (!empty($redirectionData['redirect_uri'])) {
            $redirectionUrl = Util::appendQueryString($redirectionData['redirect_uri'], $redirectionData);

            return $this->redirect()->toUrl($redirectionUrl);
        }

        return $this->sendPayload($redirectionData);
    }
}

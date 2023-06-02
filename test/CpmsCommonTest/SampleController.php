<?php
namespace CpmsCommonTest;

use CpmsCommon\Controller\AbstractRestfulController;
use CpmsCommon\ControllerTrait\RedirectionDataTrait;
use Laminas\View\Model\JsonModel;

/**
 * Class SampleController
 *
 * @package CpmsCommonTest
 */
class SampleController extends AbstractRestfulController
{
    use RedirectionDataTrait;

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function get($id)
    {
        return $this->sendPayload(array('id' => $id));
    }

    /**
     * @param mixed $id
     * @param mixed $data
     *
     * @return array|mixed
     */
    public function update($id, $data)
    {
        return $this->getSuccessMessage();
    }

    public function indexAction()
    {
        $view = new JsonModel();

        //$view->setTemplate('sample\index.phtml');
        return $view;
    }

    public function getList()
    {
        $data = array(
            'redirect_uri' => '/'
        );

        return $this->handleRedirectionData($data);
    }
}

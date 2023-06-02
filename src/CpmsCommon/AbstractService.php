<?php
namespace CpmsCommon;

use CpmsCommon\Service\Config\ServiceOptions;
use CpmsCommon\Service\ErrorCodeService;
use CpmsCommon\Utility\ErrorCodeAwareTrait;
use CpmsCommon\Utility\LoggerAwareTrait;
use Interop\Container\ContainerInterface;
use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Http\Response;

/**
 * Class AbstractService
 *
 * @package PaymentService\Service
 */
abstract class AbstractService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;
    use LoggerAwareTrait;
    use ErrorCodeAwareTrait;

    const RESULT_ITEMS = 'items';
    const RESULT_PAGE  = 'page';
    const RESULT_TOTAL = 'total';
    const RESULT_LIMIT = 'limit';

    /** @var array */
    public $requiredDataKeys = array();
    /** @var array */
    public $params = array();
    /** @var  ServiceOptions */
    public $options;

    // TODO this is an anti-pattern added here to make PoC zf2->zf3 migration happen. Sorry. This should be fixed in the future!
    /**
     * @var ContainerInterface $serviceLocator
     */
    private $serviceLocator;

    /**
     * @return ContainerInterface
     */
    public function getServiceLocator(): ContainerInterface
    {
        return $this->serviceLocator;
    }

    /**
     * @param ContainerInterface $serviceLocator
     * @return AbstractService
     */
    public function setServiceLocator(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Return a database model
     *
     * @param $modelName
     *
     * @return array|object
     */
    public function getModel($modelName)
    {
        return $this->getServiceLocator()->get('cpms\model\\' . $modelName);
    }

    /**
     * Checks for required params and sends back array or error message
     *
     * @param array $data
     * @param array $required
     *
     * @return array
     */
    public function getParams($data, $required)
    {
        $params = [];

        foreach ($required as $requiredItem) {
            if (array_key_exists($requiredItem, $data)) {
                $params[$requiredItem] = $data[$requiredItem];
            } else {
                return $this->getErrorMessage(
                    ErrorCodeService::MISSING_PARAMETER,
                    $requiredItem,
                    Response::STATUS_CODE_400
                );
            }
        }

        return array('params' => $params);
    }

    /**
     * Validates an amount
     *
     * @param string|float $amount
     *
     * @return bool
     */
    public function validPositiveAmount($amount)
    {
        return is_numeric($amount) and $amount > 0;
    }

    /**
     * @param ServiceOptions $options
     */
    public function setOptions(ServiceOptions $options)
    {
        $this->options = $options;
    }

    /**
     * @return ServiceOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get Page results
     *
     * @param $items
     * @param $page
     * @param $total
     * @param $limit
     *
     * @return array
     */
    protected function getPagedResultArray($items, $page, $total, $limit)
    {
        return array(
            self::RESULT_ITEMS => (array)$items,
            self::RESULT_PAGE  => (int)$page,
            self::RESULT_TOTAL => (int)$total,
            self::RESULT_LIMIT => (int)$limit
        );
    }
}

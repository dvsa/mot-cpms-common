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
use Laminas\ServiceManager\ServiceLocatorInterface;

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

    public const RESULT_ITEMS = 'items';
    public const RESULT_PAGE  = 'page';
    public const RESULT_TOTAL = 'total';
    public const RESULT_LIMIT = 'limit';

    public array $requiredDataKeys = array();
    public array $params = array();
    public ServiceOptions $options;

    // This is an anti-pattern added here to make PoC zf2->zf3 migration happen. Sorry. This should be fixed in the future!
    private ServiceLocatorInterface $serviceLocator;

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AbstractService
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Return a database model
     *
     * @param string $modelName
     *
     * @return array|object
     */
    public function getModel($modelName)
    {
        /** @var array|object */
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
     *
     * @return void
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
     * @param mixed $items
     * @param string|int|float $page
     * @param string|int|float $total
     * @param string|int|float $limit
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

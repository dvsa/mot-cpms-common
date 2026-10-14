<?php
/**
 * OLCS Common Configuration
 *
 * @package     olcscommon
 * @author      Pelle Wessman <pelle.wessman@valtech.se>
 */

use CpmsCommon\Service\ProfilingInitializer;
use DvsaLogger\Factory\MotLoggerFactory;
use DvsaLogger\Logger\MotLogger;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * Module configuration
 */
return array(
    'service_manager'                 => array(
        'factories'          => array(
            'ServiceApiResolver'             => 'CpmsCommon\Service\ServiceApiResolver',
            'cpms\api\contentType'           => 'CpmsCommon\Service\ApiContentTypeFactory',
            'OpenAmServiceFactory'           => 'CpmsCommon\Service\OpenAmServiceFactory',
            'Logger'                         => 'CpmsCommon\Service\LoggerServiceFactory',
            'cpms\errorCodeService'          => 'CpmsCommon\Service\ErrorCodeServiceFactory',
            'cpms\service\validationService' => 'CpmsCommon\Service\ValidationServiceFactory',
            'cpms\queue\synchronous'         => 'CpmsCommon\Queue\Adapter\Synchronous\SynchronousQueueAdapterFactory',
            'cpms\queue'                     => 'CpmsCommon\Queue\DefaultQueueFactory',
            MotLogger::class                 => MotLoggerFactory::class,
        ),

        'aliases'            => array(
            'logger' => 'Logger',
        ),

        'initializers'       => [
            'cpms_profiler' => ProfilingInitializer::CLASS_PATH,
        ],

        'abstract_factories' => array(
            'CpmsCommon\AbstractInputFilterFactory'
        )
    ),

    'controller_plugins'              => array(
        'factories' => array(
            \CpmsCommon\Controller\Plugin\Download::class => InvokableFactory::class,
            \CpmsCommon\Controller\Plugin\SendPayload::class => InvokableFactory::class,
        ),
        'aliases' => array(
            'sendPayload' => \CpmsCommon\Controller\Plugin\SendPayload::class,
            'download'    => \CpmsCommon\Controller\Plugin\Download::class,
        ),
    ),
    'mot_logger'                      => array(
        'loggers' => array(
            'mot-cpms-common' => array(
                'channel' => 'cpms-common',
                'writers' => array(
                    'stderr' => array(
                        'type'      => 'stream',
                        'path'      => 'php://stderr',
                        'formatter' => 'pipe',
                        'level'     => 'error',
                        'enabled'   => true,
                    ),
                    'file' => array(
                        'type'      => 'stream',
                        'path'      => '/var/log/dvsa/' . \date('Y-m-d') . '-cpms-common.log',
                        'formatter' => 'json',
                        'level'     => 'error',
                        'enabled'   => true,
                    ),
                ),
            ),
        ),
    ),
    'error_code'                      => array(
        'messages' => array(),
        'provider' => 'CpmsCommon\Service\ErrorCodeService'
    ),
    'accept_criteria'                 => array(
        'Laminas\View\Model\JsonModel' => array(
            '*/*',
            'application/json',
            'application/jsonp',
            'application/javascript'
        )
    ),
    'view_manager'                    => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers'                    => array(
        'invokables' => array(
            'displayRevision' => 'CpmsCommon\View\Helper\Revision'
        )
    ),
    'revision_file'                   => 'revision.txt',

    'queue_adapters' => array(
        'synchronous' => array(
            'class' => 'cpms\queue\synchronous',
            'options' => array(),
        ),
    ),
    'default_queue_adapter' => 'synchronous',

);

<?php
namespace CpmsCommon\Log\Writer;

use CpmsCommon\Log\LogDataAwareInterface;
use Interop\Container\ContainerInterface;
use Laminas\Log\Filter\Priority;
use Laminas\Log\Writer\Stream;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Service factory for Stream Log Writer
 *
 * @package       CpmsCommon
 * @subpackage    Log
 * @author        Pele Odiase <pele.odiase@valtech.co.uk>
 */
class StreamWriterFactory implements FactoryInterface
{
    private $logConfig = array();

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig   = $container->get('config');
        $this->logConfig = $serviceConfig['logger'];
        $priority        = $this->getLogPriority();
        $filePath        = $this->getFilePath();
        $filter          = new Priority($priority);
        $fileWriter      = new Stream($filePath, null, $this->logConfig['separator']);
        $logData         = null;

        if (!empty($serviceConfig['logger']['replacement'])) {
            $logData = $container->get($serviceConfig['logger']['replacement']);
        }

        if (!empty($this->logConfig['formatter'])) {
            /** @var \Laminas\Log\Formatter\FormatterInterface | LogDataAwareInterface $formatter */
            $formatter = $container->get($this->logConfig['formatter']);
            $fileWriter->setFormatter($formatter);

            if ($logData and $formatter instanceof LogDataAwareInterface) {
                $formatter->setLogData($logData);
            }
        }

        $fileWriter->addFilter($filter);

        return $fileWriter;
    }

    /**
     * Check log location
     *
     * @return string
     */
    private function checkLogDirectory()
    {
        //create log directory if set but does not exists
        if (isset($this->logConfig['location']) and !\file_exists($this->logConfig['location'])) {
            \mkdir($this->logConfig['location'], $this->logConfig['mode'], true);
        }

        if (empty($this->logConfig['location'])) {
            $location = sys_get_temp_dir();
        } else {
            $location = $this->logConfig['location'];
        }

        return $location;
    }

    /**
     * GEt log filename
     *
     * @return string
     */
    private function getLogFilename()
    {
        if (empty($this->logConfig['filename'])) {
            $filename = \date('Y-m-d') . '-app.log';
        } else {
            $filename = $this->logConfig['filename'];
        }

        return $filename;
    }

    /**
     * Get priority
     *
     * @return int
     */
    private function getLogPriority()
    {

        if (empty($this->logConfig['priority'])) {
            $priority = \LOG_DEBUG;
        } else {
            $priority = $this->logConfig['priority'];
        }

        return $priority;
    }

    /**
     * Get file path
     *
     * @return string
     */
    private function getFilePath()
    {
        //Create file name by date if not set in the config
        $filename = $this->getLogFilename();

        //create log directory if set but does not exists
        $location = $this->checkLogDirectory();

        $filePath = $location . \DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filePath)) {
            touch($filePath);
            chmod($filePath, $this->logConfig['mode']);
        }

        return $filePath;
    }
}

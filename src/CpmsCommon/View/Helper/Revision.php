<?php

namespace CpmsCommon\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\View\Helper\AbstractHelper;

/**
 * Class Revision
 * Displays the current revision, application environment and deployment date
 *
 * @package Application\View\Helper\Revision
 */
class Revision extends AbstractHelper
{
    const REVISION_DATE_KEY    = 'data';
    const REVISION_ENV_KEY     = 'environment';
    const REVISION_RELEASE_KEY = 'release';

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
     * @return Revision
     */
    public function setServiceLocator(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * The release_file is created when CPMS is deployed in the CI environment
     * When run locally, this file would not exists, the information returned would be
     * obtained from GIT
     * Invoke helper to render release / deployment information
     */
    public function __invoke($format = false)
    {
        /** @var ContainerInterface $serviceLocator */
        $serviceLocator = $this->getServiceLocator();

        $config         = $serviceLocator->get('config');
        $revisionFile   = $config['revision_file'];

        if (file_exists($revisionFile)) {
            $revisionData = file_get_contents($revisionFile);
            list($release, $dateDeployed) = explode(';', $revisionData);
        } else {
            $branch       = exec('git rev-parse --abbrev-ref HEAD');
            $revision     = exec('git rev-parse HEAD');
            $dateDeployed = date('r');
            $release      = $branch . ' ' . $revision;
        }

        if ($format) {
            $html = <<<HTML
<h3>Version Information</h3>
<ul>
<li><label>Application Environment: </label> {$config['application_env']}</li>
<li><label>Revision / Version: </label> {$release}</li>
<li><label>Release Date: </label> {$dateDeployed}</li>
</ul>
HTML;

            return $html;
        }

        return implode(
            ' | ',
            array(
                self::REVISION_ENV_KEY     => $config['application_env'],
                self::REVISION_RELEASE_KEY => $release,
                self::REVISION_DATE_KEY    => $dateDeployed
            )
        );
    }
}

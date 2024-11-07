<?php

namespace CpmsCommon\ControllerTrait;

use CpmsCommon\Controller\AbstractRestfulController;

/**
 * Class RedirectionDataTrait
 * @method sendPayload(array $payload)
 *
 * @package CpmsCommon\ControllerTrait
 */
trait ContentTypeTrait
{
    /**
     * Add custom content types to default JSON types
     *
     * @param $type
     */
    public function setCustomContentType($type)
    {
        if (!in_array($type, $this->contentTypes[AbstractRestfulController::CONTENT_TYPE_JSON])) {
            $this->contentTypes[AbstractRestfulController::CONTENT_TYPE_JSON][] = $type;
        }
    }
}

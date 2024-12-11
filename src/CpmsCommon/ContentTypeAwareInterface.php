<?php

namespace CpmsCommon;

/**
 * Interface ContentTypeAwareInterface
 *
 * @package CpmsCommon
 */
interface ContentTypeAwareInterface
{
    /**
     * @param string $type
     *
     * @return mixed
     */
    public function setCustomContentType($type);
}

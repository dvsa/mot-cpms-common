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
     * @param $type string
     *
     * @return mixed
     */
    public function setCustomContentType($type);
}

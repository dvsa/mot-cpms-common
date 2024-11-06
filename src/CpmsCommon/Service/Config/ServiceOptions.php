<?php

namespace CpmsCommon\Service\Config;

use Laminas\Stdlib\AbstractOptions;

/**
 *Class ServiceOptions
 *
 * @package CpmsCommon\Service\Config
 */
class ServiceOptions extends AbstractOptions
{
    public const MAX_LIMIT = 25;
    /** @var int */
    protected $limit = 25;
    /** @var int */
    protected $page = 1;
    /** @var int */
    protected $depth = 0;
    /** @var array */
    protected $requiredFields = array();
    /** @var array */
    protected $filters = array();
    /** @var array */
    protected $params = array();
    /** @var mixed */
    protected $sort;

    /**
     * @param int $depth
     *
     * @return void
     */
    public function setDepth($depth)
    {
        $this->depth = (int)$depth;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param array $filters
     *
     * @return void
     */
    public function setFilters($filters)
    {
        $this->filters = (array)$filters;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param int $limit
     *
     * @return void
     */
    public function setLimit($limit)
    {
        if ($limit > self::MAX_LIMIT) {
            $limit = self::MAX_LIMIT;
        }
        $this->limit = (int)$limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $page
     *
     * @return void
     */
    public function setPage($page)
    {
        $this->page = (int)$page;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function setParams($params)
    {
        $this->params = (array)$params;

        if (isset($params['depth'])) {
            $this->setDepth($params['depth']);
        }

        if (isset($params['required_fields'])) {
            $this->setRequiredFields($params['required_fields']);
        }
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $requiredFields
     *
     * @return void
     */
    public function setRequiredFields($requiredFields)
    {
        if (is_array($requiredFields)) {
            $this->requiredFields = $requiredFields;
        }
    }

    /**
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->requiredFields;
    }

    /**
     * @param mixed $sortOrder
     *
     * @return void
     */
    public function setSort($sortOrder)
    {
        if (is_string($sortOrder)) {
            list($sortField, $direction) = explode(':', $sortOrder . ':');
            $direction = strtoupper($direction);
            $direction = ($direction == 'ASC') ? $direction : 'DESC';

            $sortOrder = array($sortField => $direction);
        }

        $this->sort = $sortOrder;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param boolean $_strictMode__
     *
     * @return void
     */
    public function setStrictMode($_strictMode__)
    {
        $this->__strictMode__ = $_strictMode__;
    }
}

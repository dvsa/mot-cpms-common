<?php

namespace CpmsCommonTest\InputFilter;

use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterProviderInterface;


/**
 * Class MockFilterProvider
 *
 * @package CpmsCommonTest\Mock
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class MockFilterProvider implements InputFilterProviderInterface
{
    private $serviceLocator;

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'test'  => [
                'name'     => 'test',
                'required' => true,
            ],
            'test2' => [
                'name'       => 'test2',
                'validators' => [
                    [
                        'name'    => 'Laminas\Validator\StringLength',
                        'options' => [
                            'min' => '1',
                            'max' => '2',
                        ]
                    ]
                ]
            ],
        ];
    }
}

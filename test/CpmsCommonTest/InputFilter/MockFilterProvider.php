<?php

namespace CpmsCommonTest\InputFilter;

use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class MockFilterProvider
 *
 * @package CpmsCommonTest\Mock
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class MockFilterProvider implements InputFilterProviderInterface
{
    private ServiceLocatorInterface $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator): MockFilterProvider
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

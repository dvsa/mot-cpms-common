<?php

namespace CpmsCommon\Service;

use CpmsCommon\AbstractService;
use CpmsCommon\Utility\ErrorCodeAwareTrait;
use Laminas\Http\Response;
use Laminas\InputFilter\InputFilter;

/**
 * Class ValidationService
 *
 * @author Phil Burnett <phil.burnett@valtech.co.uk>
 */
class ValidationService extends AbstractService
{
    use ErrorCodeAwareTrait;

    // Payment validators
    const BATCH_REFUND_FILTER         = 'payment\inputFilter\batchRefundFilter';
    const BATCH_REFUND_PAYMENT_FILTER = 'payment\inputFilter\batchRefundPaymentFilter';
    const REFUND_FILTER               = 'payment\inputFilter\refundFilter';
    const CHARGEBACK_FILTER           = 'payment\inputFilter\chargeBackFilter';
    const PAYMENT_FILTER              = 'payment\inputFilter\paymentFilter';
    const REGISTER_STORED_CARD_FILTER = 'payment\inputFilter\registerStoredCardFilter';
    const STORED_CARD_FILTER          = 'payment\inputFilter\storedCardFilter';
    const STORED_CARD_PAYMENT_FILTER  = 'payment\inputFilter\storedCardPaymentFilter';
    const LIST_CRITERIA_FILTER        = 'payment\inputFilter\listCriteriaFilter';
    const REALLOCATE_FILTER           = 'payment\inputFilter\ReallocateFilter';

    // Direct debit validators
    const CREATE_MANDATE_FILTER = 'directDebit\inputFilter\createMandateFilter';
    const UPDATE_MANDATE_FILTER = 'directDebit\inputFilter\updateMandateFilter';

    const SCOPE_FILTER = 'cpmsAuth\inputFilter\scopeFilter';

    /**
     * @param array $data
     * @param       $inputFilterAlias
     * @param null  $redirectUriOnFail
     *
     * @return array | boolean
     */
    public function validateData($data, $inputFilterAlias, $redirectUriOnFail = null)
    {
        // because filters might be re-used across different services, they cannot be shared.
        $data = (array)$data;
        $this->getServiceLocator()->setShared($inputFilterAlias, false);
        /** @var InputFilter $inputFilter */
        $inputFilter = $this->getServiceLocator()->get($inputFilterAlias);

        $inputFilter->setData($data);

        if ($inputFilter->isValid()) {
            return true;
        }

        return $this->getErrorFromFilter($inputFilter, $redirectUriOnFail);
    }

    /**
     * @param InputFilter $inputFilter
     *
     * @return array
     */
    private function getErrorFromFilter(InputFilter $inputFilter)
    {
        $messages = $inputFilter->getMessages();
        $data     = [];

        foreach ((array)$messages as $fieldName => $fieldDetails) {
            if (is_array($fieldDetails)) {
                foreach ($fieldDetails as $validatorName => $errorMessage) {
                    // check if the validator name is a string (as an integer indicates a nested error message) and is
                    // missing. Otherwise, don't give too much away and return a "invalid parameter" message.
                    if (
                        (is_string($validatorName) && $validatorName == 'isEmpty')
                        || $errorMessage == "Value is required"
                    ) {
                        return $this->getErrorMessage(
                            ErrorCodeService::MISSING_PARAMETER,
                            $fieldName,
                            Response::STATUS_CODE_400,
                            $data
                        );
                    } else {
                        return $this->getErrorMessage(
                            ErrorCodeService::INVALID_PARAMETER,
                            $fieldName,
                            Response::STATUS_CODE_400,
                            [
                                ErrorCodeService::ERROR_MESSAGE_KEY => $errorMessage
                            ]
                        );
                    }
                }
            }
        }

        $filterClass = get_class($inputFilter);

        return $this->getErrorMessage(
            ErrorCodeService::UNKNOWN_ERROR,
            "[$filterClass validation]",
            Response::STATUS_CODE_400,
            $data
        );
    }
}

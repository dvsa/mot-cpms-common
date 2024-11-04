<?php

/**
 * Payment service reference codes
 *
 * @package     cpms-common
 * @subpackage  service-api
 * @author      Michael Cooper
 */

namespace CpmsCommon\Utility;

/**
 * Class to define payment service codes
 */
class PaymentScopeCodes
{
    const CARD               = 'CARD';
    const CNP                = 'CNP';
    const CHIP_AND_PIN       = 'CHIP_PIN';
    const DIRECT_DEBIT       = 'DIRECT_DEBIT';
    const CASH               = 'CASH';
    const CHEQUE             = 'CHEQUE';
    const POSTAL_ORDER       = 'POSTAL_ORDER';
    const TRANSITION         = 'TRANSITION';
    const PRE_AUTHORISE      = 'PRE_AUTH';
    const TRANSACTION_QUERY  = 'QUERY_TXN';
    const CANCEL_TRANSACTION = 'CANCEL_TXN';
    const REFUND_TRANSACTION = 'REFUND';
    const SETTLE_TRANSACTION = 'SETTLE_TXN';
    const STORED_CARD        = 'STORED_CARD';
    const CHARGE_BACK        = 'CHARGE_BACK';
    const ADJUSTMENT         = 'ADJUSTMENT';
    const REPORT             = 'REPORT';
    const CHEQUE_RD          = 'CHEQUE_RD'; // refer to drawer
    const DIRECT_DEBIT_IC    = 'DIRECT_DEBIT_IC'; // indemnity claim
    const REALLOCATE_PAYMENT = 'REALLOCATE'; // Reallocate payments by switch customer reference

    //Used for generation references and not directly associated with a payment type
    const REFERENCE_MANDATE     = 'REFERENCE_MANDATE';
    const REFERENCE_STORED_CARD = 'REFERENCE_STORED_CARD';

    /**
     * Return scopes which indicate that payment was recorded then taken back
     *
     * @return array
     */
    public static function getReversalPaymentScopeCodes()
    {
        return [
            self::CHARGE_BACK,
            self::CHEQUE_RD,
            self::DIRECT_DEBIT_IC,
        ];
    }

    /**
     * Card payment scopes
     *
     * @return array
     */
    public static function getCardScopes()
    {
        return [
            self::STORED_CARD,
            self::CARD,
            self::CNP
        ];
    }
}

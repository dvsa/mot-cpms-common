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
    public const CARD = 'CARD';
    public const CNP = 'CNP';
    public const CHIP_AND_PIN = 'CHIP_PIN';
    public const DIRECT_DEBIT = 'DIRECT_DEBIT';
    public const CASH = 'CASH';
    public const CHEQUE = 'CHEQUE';
    public const POSTAL_ORDER = 'POSTAL_ORDER';
    public const TRANSITION = 'TRANSITION';
    public const PRE_AUTHORISE = 'PRE_AUTH';
    public const TRANSACTION_QUERY = 'QUERY_TXN';
    public const CANCEL_TRANSACTION = 'CANCEL_TXN';
    public const REFUND_TRANSACTION = 'REFUND';
    public const SETTLE_TRANSACTION = 'SETTLE_TXN';
    public const STORED_CARD = 'STORED_CARD';
    public const CHARGE_BACK = 'CHARGE_BACK';
    public const ADJUSTMENT = 'ADJUSTMENT';
    public const REPORT = 'REPORT';
    public const CHEQUE_RD = 'CHEQUE_RD'; // refer to drawer
    public const DIRECT_DEBIT_IC = 'DIRECT_DEBIT_IC'; // indemnity claim
    public const REALLOCATE_PAYMENT = 'REALLOCATE'; // Reallocate payments by switch customer reference

    //Used for generation references and not directly associated with a payment type
    public const REFERENCE_MANDATE     = 'REFERENCE_MANDATE';
    public const REFERENCE_STORED_CARD = 'REFERENCE_STORED_CARD';

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

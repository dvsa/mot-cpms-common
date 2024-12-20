<?php

namespace CpmsCommon\Service;

use Laminas\Http\Response;

/**
 * Class Error CodeService
 * å* Define error codes and function to return error message array
 *
 * @author       Pele Odiase <pele.odiase@valtech.co.uk>
 * @copyright    2014 Valtech
 */
class ErrorCodeService
{
    public const ERROR_CODE_KEY    = 'code';
    public const ERROR_MESSAGE_KEY = 'message';
    public const HTTP_STATUS_KEY   = 'http_status_code';
    public const SUCCESS_MESSAGE   = '000';
    public const REPLACEMENT_KEY   = 'replacement';

    //API errors
    public const METHOD_NOT_ALLOWED                        = 101;
    public const NOT_PERMITTED                             = 102;
    public const MISSING_PARAMETER                         = 103;
    public const INVALID_PARAMETER                         = 104;
    public const UNKNOWN_ERROR                             = 105;
    public const RESOURCE_NOT_FOUND                        = 106;
    public const NOT_IMPLEMENTED                           = 107;
    public const AN_ERROR_OCCURRED                         = 108;
    public const SCHEME_NOT_AUTHORISED                     = 110;
    public const TOKEN_GENERATION_ERROR                    = 112;
    public const ACCESS_TOKEN_EXPIRED                      = 113;
    public const INVALID_ACCESS_TOKEN                      = 114;
    public const UNAUTHORISED_SCOPE                        = 115;
    public const MISSING_AUTHORISATION_HEADER              = 116;
    public const ACCESS_TOKEN_NOT_MATCHING_SALES_REFERENCE = 117;
    public const MALFORMED_JSON_DATA                       = 118;
    public const MISSING_POST_ACCESS_TOKEN                 = 119;
    public const INVALID_ENDPOINT                          = 120;
    public const NEGATIVE_AMOUNT                           = 121;
    public const INVALID_REDIRECT_DOMAIN                   = 122;
    public const GENERIC_ERROR_CODE                        = 123;
    public const MAINTENANCE_MODE                          = 124;
    public const INVALID_BATCH_REQUEST                     = 125;
    public const INVALID_ALLOCATED_AMOUNT                  = 126;
    public const INVALID_ALLOCATED_AMOUNT_SUM              = 127;
    public const INVOICE_ALREADY_REFUNDED                  = 128;
    public const OVERPAYMENT_ALREADY_REFUNDED              = 129;
    public const INVOICE_ALREADY_IN_BATCH                  = 130;

    public const CRITICAL_ERROR = 999;

    //Amendment errors

    public const CHARGE_BACK_NOT_ALLOWED          = 201;
    public const CHARGE_BACK_ALREADY_EXISTS       = 202;
    public const PAYMENT_ALREADY_AMENDED          = 203;
    public const PAYMENT_ALREADY_ADJUSTED         = 204;
    public const PAYMENT_TYPE_CANNOT_BE_AMENDED   = 205;
    public const PAYMENT_NOT_AMENDABLE            = 206;
    public const PAYMENT_NOT_FOUND_NOT_REFUNDABLE = 207;
    public const REFUND_API_NOT_AVAILABLE         = 208;
    public const CARD_REFUND_FAILED               = 209;
    public const CUSTOMER_REFERENCE_MISMATCH      = 210;
    public const UNCHANGED_CUSTOMER_REFERENCE     = 211;
    public const REALLOCATION_NOT_ALLOWED         = 212;
    public const PAYMENT_ALREADY_REALLOCATED      = 213;
    public const INVALID_OVERPAYMENT_AMOUNT       = 214;
    public const DUPLICATE_SALES_REFERENCE_FOUND  = 215;
    public const ERROR_ALLOCATING_PAYMENT         = 216;
    public const NO_MATCHING_INVOICE_FOUND        = 217;

    //Gateway Errors
    public const GATEWAY_ERROR = 501;

    /**
     * OAuth 2.0 Error Codes
     *
     * @see http://tools.ietf.org/html/rfc6749#section-5.2
     */
    public const INVALID_CLIENT         = 601;
    public const INVALID_REQUEST        = 602;
    public const INVALID_GRANT          = 603;
    public const UNAUTHORISED_CLIENT    = 604;
    public const UNSUPPORTED_GRANT_TYPE = 605;

    /* Database error codes */
    public const TRANSACTION_NOT_FOUND                     = 701;
    public const ERROR_CREATING_TRANSACTION                = 702;
    public const ERROR_COULD_NOT_AUTHENTICATE              = 703;
    public const ERROR_INVALID_REQUEST_METHOD              = 704;
    public const ERROR_CREATE_TRANSACTION_FAILED           = 705;
    public const ERROR_AUTHORISE_TRANSACTION_FAILED        = 706;
    public const ERROR_TRANSACTION_NOT_FOUND               = 707;
    public const ERROR_FAILED_TO_SET_STATUS                = 708;
    public const ERROR_REFUND_FAILED_NO_PARENT_TRANSACTION = 709;
    public const ERROR_REFUND_ALREADY_EXISTS               = 710;
    public const ERROR_REFUND_AMOUNT_EXCEEDED              = 711;
    public const ERROR_CREATING_MANDATE                    = 712;
    public const ERROR_UPDATING_MANDATE                    = 713;
    public const ERROR_CREATING_DIRECT_DEBIT_TRANSACTION   = 714;
    public const ERROR_IN_DIRECT_DEBIT_WEBHOOK_DATA        = 715;
    public const ERROR_UPDATING_DIRECT_DEBIT_TRANSACTION   = 716;
    public const ERROR_MANDATE_NOT_FOUND                   = 717;
    public const ERROR_INCORRECT_MANDATE_STATUS            = 718;
    public const ERROR_INVALID_MANDATE_STATUS              = 719;
    public const ERROR_GETTING_MANDATE_STATUS              = 720;
    public const INVALID_STATUS_CHANGE                     = 721;
    public const ERROR_INSUFFICIENT_TRANSACTION_BALANCE    = 722;
    public const ERROR_GETTING_STORED_CARDS                = 723;
    public const ERROR_INVALID_REFUND_TRANSACTION_SCOPE    = 724;
    public const ERROR_MANDATE_CANCELLED_OR_SUSPENDED      = 725;
    public const ERROR_GETTING_STORED_CARD                 = 726;
    public const ERROR_SCHEDULED_JOB_LOCKED                = 727;
    public const ERROR_REGISTERING_STORED_CARD             = 728;
    public const CUSTOMER_NOT_FOUND                        = 729;
    public const ADJUSTABLE_TXN_NOT_FOUND                  = 730;
    public const PAYMENT_CHARGE_BACK_NOT_POSSIBLE          = 731;
    public const ERROR_MANDATE_CANCELLED                   = 732;
    public const ERROR_MANDATE_CANCELLED_3RD_PARTY         = 733;
    public const INVALID_MANDATE_UPDATE                    = 734;
    public const ERROR_SUBSCRIBING_MANDATE_PAYMENTS        = 735;
    public const API_ERROR_CREATING_MANDATE                = 736;
    public const API_ERROR_QUERYING_CARD_PAYMENT           = 737;
    public const PAYMENT_GATEWAY_ID_NOT_SET                = 738;
    public const ENDPOINT_NOT_IMPLEMENTED                  = 739;
    public const REFUND_AMOUNT_MISMATCH                    = 740;

    //success messages
    public const SUCCESS_MANDATE_SETUP = 821;

    //Direct Debit Mandate Setup Errors
    public const NO_MANDATE_ASSOCIATED_WITH_EVENT = 901;

    public const UNKNOWN_ERROR_MESSAGE = 'Unknown error occurred occurred, unable to log the error. Please contact support';

    protected static array $errorCodes = array();

    /**
     * Allow injection of error messages
     *
     * @param array $message
     */
    public function __construct(array $message = null)
    {
        $this->setDefinedMessages($message);
    }

    /**
     * Set default messages for error codes that do not have messages
     *
     * @return void
     */
    private function processErrorCodes()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        $constants  = $reflection->getConstants();

        foreach ($constants as $key => $code) {
            if (is_numeric($code) and !isset(static::$errorCodes[$code])) {
                static::$errorCodes[$code] = 'DYN: ' . ucwords(str_replace('_', ' ', $key));
            }
        }
    }

    /**
     * Get error message for the specified error code
     * If $httpStatusCode is specified,this will overwrite the default HTTP STATUS
     *
     * @param int $code
     * @param array  $replacement
     * @param int|null   $httpStatusCode
     * @param array  $data
     *
     * @return array
     */
    public function getErrorMessage($code, $replacement = [], $httpStatusCode = null, $data = array())
    {
        $replacement = (array)$replacement;
        if (!isset(static::$errorCodes[$code])) {
            $replacement[] = $code;
            $code          = static::GENERIC_ERROR_CODE;
        }

        $message = $this->printMessage(static::$errorCodes[$code], $replacement);
        /** @var string $message */
        $message = preg_replace('/(\n|\s+)/', ' ', $message);
        $return  = array(
            static::ERROR_CODE_KEY    => $code,
            static::ERROR_MESSAGE_KEY => $message
        );

        if (!empty($httpStatusCode)) {
            $return[static::HTTP_STATUS_KEY] = $httpStatusCode;
        }

        $return = array_merge($return, $data);

        return $return;
    }

    private function printMessage(string $format, array $replacements): string
    {
        $replacements = array_values($replacements);
        switch (count($replacements)) {
            case 1:
                return sprintf($format, $replacements[0]);
            case 2:
                return sprintf($format, $replacements[0], $replacements[1]);
            case 3:
                return sprintf($format, $replacements[0], $replacements[1], $replacements[2]);
            case 4:
                return sprintf($format, $replacements[0], $replacements[1], $replacements[2], $replacements[3]);
            default:
                return $format;
        }
    }

    /**
     * Return success message for payload
     *
     * @param array $data
     *
     * @return array
     */
    public function getSuccessMessage(array $data = null)
    {
        if (empty($data)) {
            $data = array();
        }

        $response[static::ERROR_CODE_KEY] = static::SUCCESS_MESSAGE;

        if (isset($data[static::ERROR_CODE_KEY])) {
            $response[static::ERROR_CODE_KEY] = $data[static::ERROR_CODE_KEY];
        }

        $response[static::ERROR_MESSAGE_KEY] = static::$errorCodes[$response[static::ERROR_CODE_KEY]];
        $response[static::HTTP_STATUS_KEY]   = Response::STATUS_CODE_200;

        return array_merge($response, $data);
    }

    /**
     * @param \Throwable $exception
     *
     * @return \Throwable
     */
    public static function getFirstException($exception)
    {
        if ($previous = $exception->getPrevious()) {
            return static::getFirstException($previous);
        }

        return $exception;
    }

    /**
     * @param int $code
     * @param array  $replacement
     *
     * @return string
     */
    public static function getMessage($code, $replacement = [])
    {
        $mySelf       = new self();
        $messageArray = $mySelf->getErrorMessage($code, $replacement);

        return $messageArray[$mySelf::ERROR_MESSAGE_KEY];
    }

    /**
     * @param array $messages
     *
     * @return void
     */
    private function setDefinedMessages(array $messages = null)
    {
        static::$errorCodes = array(
            static::PAYMENT_NOT_FOUND_NOT_REFUNDABLE          => 'Payment %s not found or not refundable',
            static::CHARGE_BACK_ALREADY_EXISTS                => 'Payment has already been reversed or charged back',
            static::PAYMENT_ALREADY_ADJUSTED                  => 'This payment has previously been adjusted',
            static::PAYMENT_TYPE_CANNOT_BE_AMENDED            => 'This Payment[%s] cannot be amended',
            static::CHARGE_BACK_NOT_ALLOWED                   => 'Charge back not permitted for this payment %s',
            static::PAYMENT_ALREADY_AMENDED                   => 'Can not adjust a payment that is already amended',
            static::SUCCESS_MESSAGE                           => 'Success',
            static::METHOD_NOT_ALLOWED                        => 'Request method not allowed',
            static::NOT_PERMITTED                             => 'Forbidden. You do not have permission to use this
            resource',
            static::MISSING_PARAMETER                         => 'Missing %s parameter',
            static::INVALID_PARAMETER                         => 'Invalid %s parameter',
            static::UNKNOWN_ERROR                             => 'An unknown %s error occurred please try again later',
            static::RESOURCE_NOT_FOUND                        => 'Requested resource %s not found',
            static::NOT_IMPLEMENTED                           => 'Requested HTTP Method (%s) method is not implemented',
            static::SCHEME_NOT_AUTHORISED                     => 'Scheme %s not authorised to use requested resource',
            static::AN_ERROR_OCCURRED                         => 'A system error occurred and has been logged, please
            try again later',
            static::TOKEN_GENERATION_ERROR                    => 'Unable to generate token',
            static::ACCESS_TOKEN_EXPIRED                      => 'Access token %s has expired',
            static::INVALID_ACCESS_TOKEN                      => 'Invalid access token',
            static::UNAUTHORISED_SCOPE                        => 'Client is not authorised to use this service: (%s)',
            static::MISSING_AUTHORISATION_HEADER              => 'Missing access_token and/or HTTP Header: %s',
            //Gateway Errors
            static::GATEWAY_ERROR                             => 'Gateway unavailable, please try again later',
            //OAuth 2.0 Messages
            static::INVALID_CLIENT                            => 'invalid_client: Client failed authentication',
            static::INVALID_REQUEST                           => 'invalid_request:The request is missing a required
            parameter. Required  parameter is either a valid access token or the following: client_id, client_secret,
            user_id, grant_type, scope',
            static::UNAUTHORISED_CLIENT                       => 'unauthorised_client: Client is not authorised to
            use this resource.
            Ensure the access token used is valid for the scope (%s) and authorised to use it',
            static::UNSUPPORTED_GRANT_TYPE                    => 'The grant type (%s) is not supported',
            static::INVALID_GRANT                             => 'invalid_grant: The provided grant type %s is invalid',
            //DB error messages
            static::TRANSACTION_NOT_FOUND                     => 'Payment identified by %s was not found',
            static::ERROR_CREATING_TRANSACTION                => 'An error occurred when creating the payment',
            static::ERROR_CREATING_DIRECT_DEBIT_TRANSACTION   => 'Error occurred creating the DIRECT DEBIT payment',
            static::ERROR_IN_DIRECT_DEBIT_WEBHOOK_DATA        => 'Incomplete data received from DD bureau',
            static::ERROR_UPDATING_DIRECT_DEBIT_TRANSACTION   => 'Error occurred updating the DIRECT DEBIT payment',
            static::ERROR_CREATING_MANDATE                    => 'An error occurred when creating the mandate',
            static::ERROR_UPDATING_MANDATE                    => 'An error occurred when updating the mandate',
            static::ERROR_MANDATE_NOT_FOUND                   => 'No mandate was found with the token: %s',
            static::ERROR_INCORRECT_MANDATE_STATUS            => 'Attempt to set an incorrect mandate status with the
            token: %s',
            static::INVALID_MANDATE_UPDATE                    => 'Mandates with status %s cannot be updated',
            static::ERROR_INVALID_MANDATE_STATUS              => 'Attempt to set a mandate status that does not exist
            with token: %s',
            static::ERROR_GETTING_MANDATE_STATUS              => 'Error retrieving mandate status',
            static::INVALID_STATUS_CHANGE                     => 'Not permitted to change status %s',
            static::ERROR_GETTING_STORED_CARDS                => 'Error getting stored cards for %s',
            static::ERROR_INSUFFICIENT_TRANSACTION_BALANCE    => 'Total payment balance is too low',
            static::ERROR_INVALID_REFUND_TRANSACTION_SCOPE    => 'Original payment has invalid scope (%s)',
            static::ERROR_MANDATE_CANCELLED_OR_SUSPENDED      => 'The mandate has been cancelled or suspended (%s)',
            static::ERROR_GETTING_STORED_CARD                 => 'Stored card with the token (%s) does not exist',
            static::ERROR_REGISTERING_STORED_CARD             => 'An error occurred when registering stored card',
            static::ERROR_SCHEDULED_JOB_LOCKED                => 'Scheduled job is locked because another
            job is running',
            static::CUSTOMER_NOT_FOUND                        => 'Invalid customer associated with requested
            payment: %s',
            static::MALFORMED_JSON_DATA                       => 'Malformed JSON data found in request body',
            static::ACCESS_TOKEN_NOT_MATCHING_SALES_REFERENCE => 'Access token does not match sales reference',
            static::ADJUSTABLE_TXN_NOT_FOUND                  => 'Requested payment (%s) is not found, invalid or
            not adjustable',
            static::MISSING_POST_ACCESS_TOKEN                 => 'Missing parameter (%s). A valid access token must
            be provided in the POST body',
            static::INVALID_ENDPOINT                          => 'The requested endpoint is invalid',
            static::PAYMENT_CHARGE_BACK_NOT_POSSIBLE          => 'Payment has already been amended, cannot charge back',
            static::NEGATIVE_AMOUNT                           => 'Payment amount should not be a negative value',
            static::ERROR_MANDATE_CANCELLED                   => 'An error occurred when cancelling the mandate',
            static::NO_MANDATE_ASSOCIATED_WITH_EVENT          => 'No mandate associated with webhook event %s',
            static::ERROR_MANDATE_CANCELLED_3RD_PARTY         => 'Third party gateway responded with failure
            while cancelling a mandate',
            static::INVALID_REDIRECT_DOMAIN                   => 'The domain %s in the redirect_uri is not a valid or
             white listed domain. Redirection not permitted.',
            static::GENERIC_ERROR_CODE                        => 'An error with code %s occurred. Please retry later',
            static::MAINTENANCE_MODE                          => 'The system is temporarily down for maintenance.
            Please try again later. We are sorry for any inconvenience caused.',

            //success messages
            static::SUCCESS_MANDATE_SETUP                     => 'Mandate request received & is being processed',
            static::API_ERROR_CREATING_MANDATE                => 'Third party gateway responded with an error,
            unable to create mandate: %s. This error has been logged, please try again later',
            static::REFUND_API_NOT_AVAILABLE                  => 'The refund api is not available',
            static::ERROR_COULD_NOT_AUTHENTICATE              => 'Access denied: client failed authentication (%s)',
            self::ENDPOINT_NOT_IMPLEMENTED                    =>
                'This endpoint is yet to be implemented in CPMS API version %s',
            self::CUSTOMER_REFERENCE_MISMATCH                 =>
                'Customer reference provided [%s] does not match the expected value [%s]',
            self::UNCHANGED_CUSTOMER_REFERENCE                =>
                'Unable to reallocate payment, customer reference is unchanged: %s',
            self::DUPLICATE_SALES_REFERENCE_FOUND             =>
                'Duplicate sales_reference [%s] found. Invoice Numbers must be unique for each payment.',
            self::REALLOCATION_NOT_ALLOWED                    =>
                'Reallocation of this payment is currently not supported: [%s]',
            self::ERROR_ALLOCATING_PAYMENT                    => 'An Error occurred - unable to reallocate payment',
            self::PAYMENT_ALREADY_REALLOCATED                 => 'This payment has previously been reallocated',
            self::REFUND_AMOUNT_MISMATCH                      => 'Incorrect refund amount. Amount requested' .
                ' does not match the amount allocated: %s',
            self::INVALID_OVERPAYMENT_AMOUNT                  =>
                'The overpayment amount requested for payment %s does not match ' .
                'the value of overpayment available for this payment: [%s is not equal to %s]',

            self::NO_MATCHING_INVOICE_FOUND                   => 'Could not find a matching invoice: [%s]',
            self::INVALID_BATCH_REQUEST                       => 'Batch request must contain 2 or more items, %s found',
            self::INVOICE_ALREADY_REFUNDED                    => 'Invoice with reference %s paid with receipt ' .
                ' reference %s has been fully refunded',
            self::INVOICE_ALREADY_IN_BATCH                    => 'Invoice with reference %s paid with receipt ' .
                ' reference %s is already scheduled to be refunded in the batch',
            self::INVALID_ALLOCATED_AMOUNT                    => 'Invalid allocated_amount. Allocated amount '
                . 'cannot be greater than total_amount. [%s > %s]',
            self::INVALID_ALLOCATED_AMOUNT_SUM                => 'Invalid allocated_amount. Sum of '
                . 'allocated amounts cannot be greater than total_amount. [%s > %s]',
            self::OVERPAYMENT_ALREADY_REFUNDED                => 'Overpayment already processed for this payment: %s'
        );

        if ($messages) {
            foreach ($messages as $code => $message) {
                static::$errorCodes[$code] = $message;
            }
        }

        $this->processErrorCodes();
    }
}

<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace AktiveMerchant\Billing\Gateways;

use AktiveMerchant\Billing\Interfaces as Interfaces;
use AktiveMerchant\Billing\Gateway;
use AktiveMerchant\Billing\CreditCard;
use AktiveMerchant\Billing\Response;

/**
 * Description of \AktiveMerchant\Billing\Bogus
 *
 * @package Aktive-Merchant
 * @author  Andreas Kollaros
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Bogus extends Gateway implements Interfaces\Charge, Interfaces\Credit, Interfaces\Store
{
    const AUTHORIZATION = '53433';

    const SUCCESS_MESSAGE = "Bogus Gateway: Forced success";
    const FAILURE_MESSAGE = "Bogus Gateway: Forced failure";
    const ERROR_MESSAGE = "Bogus Gateway: Use CreditCard number 1 for success, 2 for exception and anything else for error";
    const CREDIT_ERROR_MESSAGE = "Bogus Gateway: Use trans_id 1 for success, 2 for exception and anything else for error";
    const UNSTORE_ERROR_MESSAGE = "Bogus Gateway: Use trans_id 1 for success, 2 for exception and anything else for error";
    const CAPTURE_ERROR_MESSAGE = "Bogus Gateway: Use authorization number 1 for exception, 2 for error and anything else for success";
    const VOID_ERROR_MESSAGE = "Bogus Gateway: Use authorization number 1 for exception, 2 for error and anything else for success";

    public static $supported_countries = array('US', 'GR');
    public static $supported_cardtypes = array('bogus');
    public static $homepage_url = 'http://example.com';
    public static $display_name = 'Bogus';

    public function authorize($money, CreditCard $creditcard, $options = array())
    {
        switch ($creditcard->number) {
            case '1':
                return new Response(true, self::SUCCESS_MESSAGE, array('authorized_amount' => $money), array('test' => true, 'authorization' => self::AUTHORIZATION));
                break;
            case '2':
                return new Response(false, self::FAILURE_MESSAGE, array('authorized_amount' => $money, 'error' => self::FAILURE_MESSAGE), array('test' => true));
                break;
            default:
                throw new Exception(self::ERROR_MESSAGE);
                break;
        }
    }

    public function purchase($money, CreditCard $creditcard, $options = array())
    {
        switch ($creditcard->number) {
            case '1':
                return new Response(true, self::SUCCESS_MESSAGE, array('paid_amount' => $money), array('test' => true));
                break;
            case '2':
                return new Response(false, self::FAILURE_MESSAGE, array('paid_amount' => $money, 'error' => self::FAILURE_MESSAGE), array('test' => true));
                break;
            default:
                throw new Exception(self::ERROR_MESSAGE);
                break;
        }
    }

    public function credit($money, $ident, $options = array())
    {
        switch ($ident) {
            case '1':
                throw new Exception(self::CREDIT_ERROR_MESSAGE);
                break;
            case '2':
                return new Response(false, self::FAILURE_MESSAGE, array('paid_amount' => $money, 'error' => self::FAILURE_MESSAGE), array('test' => true));
                break;
            default:
                return new Response(true, self::SUCCESS_MESSAGE, array('paid_amount' => $money), array('test' => true));
                break;
        }
    }

    public function capture($money, $ident, $options = array())
    {
        switch ($ident) {
            case '1':
                throw new Exception(self::CREDIT_ERROR_MESSAGE);
                break;
            case '2':
                return new Response(false, self::FAILURE_MESSAGE, array('paid_amount' => $money, 'error' => self::FAILURE_MESSAGE), array('test' => true));
                break;
            default:
                return new Response(true, self::SUCCESS_MESSAGE, array('paid_amount' => $money), array('test' => true));
                break;
        }
    }

    public function void($ident, $options = array())
    {
        switch ($ident) {
            case '1':
                throw new Exception(self::VOID_ERROR_MESSAGE);
                break;
            case '2':
                return new Response(false, self::FAILURE_MESSAGE, array('authorization' => $ident, 'error' => self::FAILURE_MESSAGE), array('test' => true));
                break;
            default:
                return new Response(true, self::SUCCESS_MESSAGE, array('authorization' => $ident), array('test' => true));
                break;
        }
    }

    public function store(CreditCard $creditcard, $options = array())
    {
        switch ($creditcard->number) {
            case '1':
                return new Response(true, self::SUCCESS_MESSAGE, array('billingid' => '1'), array('test' => true, 'authorization' => self::AUTHORIZATION));
                break;
            case '2':
                return new Response(false, self::FAILURE_MESSAGE, array('billingid' => null, 'error' => self::FAILURE_MESSAGE), array('test' => true));
                break;

            default:
                throw new Exception(self::ERROR_MESSAGE);
                break;
        }
    }

    public function unstore($identification, $options = array())
    {
        switch ($identification) {
            case '1':
                return new Response(true, self::SUCCESS_MESSAGE, array(), array('test' => true));
                break;
            case '2':
                return new Response(false, self::FAILURE_MESSAGE, array('error' => self::FAILURE_MESSAGE), array('test' => true));
                break;

            default:
                throw new Exception(self::UNSTORE_ERROR_MESSAGE);
                break;
        }
    }

}

<?php

namespace Bri\utils;

class Constant
{

    #SNAP
    const URL_SNAP_GET_TOKEN_B2B = '/snap/v1.0/access-token/b2b';
    const URL_SNAP_GET_BALANCE_INQUIRY = '/snap/v1.0/balance-inquiry';
    const URL_SNAP_GET_STATEMENT = '/snap/v1.1/bank-statement';
    const URL_SNAP_INTERNAL_ACCOUNT_INQUIRY = '/intrabank/snap/v2.0/account-inquiry-internal';
    const URL_SNAP_TRANSFER_INTRABANK = '/intrabank/snap/v2.0/transfer-intrabank';
    const URL_SNAP_TRANSFER_STATUS = '/snap/v2.0/transfer/status';

    #BI FAST
    const URL_BIFAST_ACCOUNT_INQUIRY = '/v1.0/transfer-bifast/inquiry-bifast';
    const URL_BIFAST_TRANSFER_INTERBANK = '/v1.0/transfer-bifast/payment-bifast';
    const URL_BIFAST_TRANSFER_STATUS = '/v1.0/transfer-bifast/check-status-bifast';

}

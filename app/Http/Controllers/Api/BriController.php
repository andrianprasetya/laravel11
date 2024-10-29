<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Bri\Bri;
use Bri\utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BriController extends Controller
{
    private $log;

    private $env;
    private $consumer_key;
    private $consumer_secret;
    private $bri;
    private $utils;

    public function __construct()
    {
        parent::__construct();
        //$this->log = new LogService();

        $this->env = config('bri.env');
        $this->consumer_key = config('bri.consumer_key');
        $this->consumer_secret = config('bri.consumer_secret');

        $this->bri = new Bri(
            $this->env, // dev, sandbox, prod
            $this->consumer_key,
            $this->consumer_secret
        );

        $this->utils = new Util();
    }

    /**
     * Get Token
     *
     */
    public function getTokenB2B()
    {
        try {
            return $this->bri->getTokenB2B();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get BALANCE INQUIRY
     *
     */
    public function getBalanceInquiry(Request $request)
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "accountNo" => $request->account_number
            ];
            return $this->bri->getBalanceInquiry($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get BALANCE INQUIRY
     *
     */
    public function getStatement(Request $request)
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "accountNo" => $request->account_number,
                "fromDateTime" => $request->from_date_time,
                "toDateTime" => $request->to_date_time
            ];
            return $this->bri->getStatement($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * INTERNAL ACCOUNT INQUIRY
     *
     */
    public function internalAccountInquiry(Request $request)
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "beneficiaryAccountNo" => $request->beneficiary_account_number,
            ];
            return $this->bri->internalAccountInquiry($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * EXTERNAL ACCOUNT INQUIRY
     *
     */
    public function externalAccountInquiry(Request $request)
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "beneficiaryAccountNo" => $request->beneficiary_account_number,
                "beneficiaryBankCode" => $request->beneficiary_bank_code
            ];
            return $this->bri->externalAccountInquiry($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * INTERNAL ACCOUNT INQUIRY
     *
     */
    public function transferIntrabank(Request $request)
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "partnerReferenceNo" => $this->utils->generatePartnerReferenceNo(),
                "amount" => [
                    "value" => $request->amount["value"],
                    "currency" => $request->amount["currency"]
                ],
                "beneficiaryAccountNo" => $request->beneficiary_account_number,
                "customerReference" => $request->customer_reference,
                "feeType" => $request->fee_type,
                "remark" => $request->remark,
                "sourceAccountNo" => $request->source_account_number,
                "transactionDate" => Carbon::now()->toIso8601String(),
                "additionalInfo" => new \stdClass()
            ];
            return $this->bri->transferIntrabank($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * INTERNAL ACCOUNT INQUIRY
     *
     */
    public function transferInterbank(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "customerReference" => $request->customer_reference/*$this->utils->generatePartnerReferenceNo()*/,
                "senderIdentityNumber" => $request->sender_identity_number,
                "sourceAccountNo" => $request->source_account_number,
                "amount" => [
                    "value" => $request->amount["value"],
                    "currency" => $request->amount["currency"]
                ],
                "beneficiaryBankCode" => $request->beneficiary_bank_code,
                "beneficiaryAccountNo" => $request->beneficiary_account_number,
                "referenceNo" => $request->reference_number,
                "externalId" => $request->external_id,
                "transactionDate" => "2022-02-22T13:07:24Z"/*Carbon::now()->format('Y-m-d\TH:i:s\Z')*/,
                "paymentInfo" => "testing bifast",
                "senderType" => $request->sender_type,
                "senderResidentStatus" => $request->sender_resident_status,
                "senderTownName" => $request->sender_town_name,
                "additionalInfo" => [
                    "deviceId" => "123456789237",
                    "channel" => "mobilephone"
                ]

            ];
            return $this->bri->transferInterbank($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * TRANSFER STATUS
     *
     */
    public function transferStatus(Request $request)
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "originalPartnerReferenceNo" => $request->original_partner_reference,
                "serviceCode" => "18",
                "transactionDate" => $request->transaction_date,
            ];
            return $this->bri->transferStatus($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * TRANSFER STATUS
     *
     */
    public function transferStatusInterbank(Request $request)
    {
        try {
            //beneficiaryBankCode => bank swift_code
            $data = [
                "originalPartnerReferenceNo" => $request->original_partner_reference_number,
                "serviceCode" => $request->service_code,
                "transactionDate" => $request->transaction_date
            ];
            return $this->bri->transferStatusInterbank($request->access_token, $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => $e->getCode(),
                'body' => $e->getMessage()
            ]);
        }
    }
}

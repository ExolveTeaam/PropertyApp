<?php
namespace App\Core\Services\Implementations;

use App\Models\User;
use Psr\Log\LoggerInterface;
use App\Traits\HttpResponses;
use App\Utils\Dtos\BaseResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use App\Utils\Dtos\FlutterwaveTransactionData;
use App\Core\Services\Interfaces\IFlutterWaveService;

/**
 * Summary of FlutterwaveService
 */
class FlutterwaveService implements IFlutterWaveService {
    use HttpResponses;
    /**
     * Summary of apiUrl
     * @var string
     */
    private string $apiUrl;
    /**
     * Summary of secretKey
     * @var string
     */
    private string $secretKey;
    /**
     * Summary of logger
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Summary of __construct
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
        $this->apiUrl = env("FLW_ENDPOINT");
        $this->secretKey = "Bearer ".env("FLW_SECRET_KEY");
        $this->logger = $logger;
    }

    /**
     * Summary of VerifyTransaction
     * @param string $transactionId
     * @return \App\Utils\Dtos\BaseResponse
     */
    public function VerifyTransaction(string $transactionId): BaseResponse {
        $this->logger->info("FLUTTERWAVE_SERVICE Verify_Transactions => Proccess started");

        $response = Http::withHeaders([
            'Authorization' => $this->secretKey,
            'Accept' => 'application/json'
        ])->get("{$this->apiUrl}/transactions/$transactionId/verify");
        try {
            $result = new BaseResponse();
            $responseData = $response->json();
            if ($response->status() === 200) {
                if ($responseData !== null) {
                    $result->Status = strtolower($responseData['status']) === 'success' && ($responseData['data'] !== null && $responseData['data']['status'] === 'successful');
                    $result->Message = $responseData['message'];
                    $data = new FlutterwaveTransactionData($responseData['data']);
                    $result->Data = $data;
                    $this->logger->info("FLUTTERWAVE_SERVICE Verify_Transactions => Transaction verified - Transaction Id- {$transactionId}");
                }
            } else {
                if ($responseData !== null) {
                    $result->Message = $responseData['message'];
                }
                $result->Status = false;
                $this->logger->info("FLUTTERWAVE_SERVICE Verify_Transactions => Unable to verify transaction at the moment. - Transaction Id- {$transactionId}");
            }
            $this->logger->info("FLUTTERWAVE_SERVICE Verify_Transactions => {$result->Message}");
            return $result;
        } catch (RequestException $ex) {
            $this->logger->error("FLUTTERWAVE_SERVICE Verify_Transactions => APPLICATION ERROR while verifying transaction details".$ex);
            return new BaseResponse(false, "Applicaton ran into an error while trying to verify transaction details, please try again. Contact support if you are not able to continue after multiple tries");
        } catch (GuzzleException $ex) {
            $this->logger->error("FLUTTERWAVE_SERVICE Verify_Transactions => APPLICATION ERROR while verifying transaction details".$ex);
            return new BaseResponse(false, "Applicaton ran into an error while trying to verify transaction details, please try again. Contact support if you are not able to continue after multiple tries");
        }
    }
    public function InitializeFLutterWavePayment(User $user,float $amount,string $ref): JsonResponse{
        $flutterwaveSecretKey = env('FLW_SECRET_KEY');
        $paymentUrl = env("FLW_ENDPOINT")."/payments";
        $data = [
            'tx_ref' => $ref,
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' =>config('app.url'). '/v1/flutterwave-callback',
            'payment_options' => 'card,banktransfer',
            'customer' => [
                'email' => $user->email,
                'name' => $user->first_name.' '.$user->last_name,
            ],
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $flutterwaveSecretKey,
            'Content-Type' => 'application/json'
        ])->post($paymentUrl, $data);

        $responseBody = $response->json();

        if ($response->successful() && $responseBody['status'] == 'success') {
            return $this->success($responseBody['data']['link']);
        }
        $this->logger->error('Error creating Flutterwave payment', ['response' => $responseBody]);
        return $this->error('Unable to initiate payment.');
    }


}


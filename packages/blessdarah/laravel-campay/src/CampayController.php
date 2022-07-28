<?php

namespace BlessDarah\LaravelCampay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CampayController extends Controller
{
    public $base_url;
    public $token;

    public function __construct()
    {
        $this->base_url = 'https://demo.campay.net/api/';
        $this->token = $this->getAccessToken();
    }

    public function getAccessToken()
    {
        $url = $this->base_url . 'token/';
        $params = [
            "username" => env('CAMPAY_USERNAME'),
            "password" => env('CAMPAY_PASSWORD')
        ];

        $response = Http::acceptJson()->post($url, $params);
        return $response['token'];
    }


    /**
     * Request collection
     *
     * @param  mixed $request
     * @param  string $country_code
     * @return void
     */
    public function collect(Request $request, $country_code = '237')
    {
        $url = $this->base_url . 'collect/';
        $this->token = $this->getAccessToken();

        $data = $request->validate([
            "amount" => 'required',
            "currency" => 'string|required',
            "from" => 'string|required',
            "description" => 'string|required',
            "external_reference" => 'nullable'
        ]);

        $headers = [
            "Authorization" => "Token " . $this->token,
        ];

        $response = Http::acceptJson()->withHeaders($headers)->post($url, $data);

        if (!$response || !$response->ok()) {
            return response()->json([
                'success'   => false,
                'message'   => 'Collection request failed',
                'data'  => $response->body()
            ]);
        }

        // create a transaction
        $transaction_data = [
            "amount"                => $data['amount'],
            "phoneNumber"           => $data['from'],
            "description"           => $data['description'],
            "externalReference"     => $data['externalReference'],
            'reference'             => $response['reference'],
            'collectionType'        => $data['collectionType'],
            'collectionTypeCode'    => $data['collectionTypeCode'],
        ];
    }

    /**
     * Check/confirm a transaction from campay
     *
     * @param  string $reference
     * @return void
     */
    public function checkTransactionStatus(string $reference)
    {
        $url = $this->base_url . 'transaction/' . $reference;

        $headers = [
            "Authorization" => "Token " . $this->token,
        ];

        $response = Http::acceptJson()->withHeaders($headers)->get($url);

        if (!$response || !$response->ok()) {
            return response()->json([
                'success'   => false,
                'message'   => 'Verifcation failed',
                'data'      => null
            ]);
        }
    }


    public function withdraw(Request $request, $country_code = '237')
    {
        $url = $this->base_url . 'withdraw/';

        $this->token = $this->getAccessToken();

        $data = $request->validate([
            "amount" => 'required',
            "currency" => 'string|required',
            "from" => 'string|required',
            "description" => 'string|required',
            "external_reference" => 'nullable'
        ]);

        $headers = [
            "Authorization" => "Token " . $this->token,
        ];

        $response = Http::acceptJson()->withHeaders($headers)->post($url, $data);

        if (!$response || !$response->ok()) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction failed',
                'data' => $response->body()
            ]);
        }

        // create a transaction
        $transaction_data = [
            "amount"                => $data['amount'],
            "phoneNumber"           => $country_code . $data['phoneNumber'],
            "description"           => $data['description'],
            "externalReference"     => $data['externalReference'],
            'reference'             => $response['reference'],
            'collectionType'        => $data['collectionType'],
            'collectionTypeCode'    => $data['collectionTypeCode'],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Please wait for confirmation feedback',
            'data' => $transaction_data
        ]);
    }

    public function balance()
    {
        $url = $this->base_url . 'balance/';
        $this->token = $this->getAccessToken();

        $headers = [
            "Authorization" => "Token " . $this->token,
        ];

        $response = Http::acceptJson()->withHeaders($headers)->get($url);

        if (!$response || !$response->ok()) {
            return response()->json([
                'success'   => false,
                'message'   => 'Could not get app balance',
                'data'  => $response->body()
            ]);
        }

        $data = [
            'total_balance'  => $response['total_balance'],
            'mtn_balance'    => $response['mtn_balance'],
            'orange_balance' => $response['orange_balance']
        ];

        return response()->json($data);
    }
}

<?php


namespace App\Helpers;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientHelper
{
    const URL_CURRENCIES = 'https://api.exchangeratesapi.io/latest?base=EUR';

    public static function fetchCurrency(string $currency = "EUR"): float
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            self::URL_CURRENCIES
        );

        $content = json_decode($response->getContent());

        // retorno 1 por si en la query viene EUR
        return $content->rates->$currency ?? 1;
    }
}
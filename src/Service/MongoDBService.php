<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

class MongoDBService
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private string $apiEndpoint;
    private string $apiKey;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        string $apiEndpoint = 'https://us-east-2.aws.neurelo.com/rest/visits/__one',
        string $apiKey = null
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->apiEndpoint = $apiEndpoint;
        $this->apiKey = $apiKey ?? $_ENV['NEURELO_API_KEY']; // Fallback to env variable
    }

    public function insertVisit(string $pageName)
    {
            $response = $this->httpClient->request('POST', $this->apiEndpoint, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'pageName' => $pageName,
                    'visitedAt' => (new DateTimeImmutable())->format('c'), // ISO 8601
                ],
 ]);
                }            
}

<?php
namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class DecathlonSportsTracking extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string
     */
    const BASE_URL = 'https://api-eu.decathlon.net/connect/oauth/';

    /**
     * @var string
     */
    const BASE_DATA_URL = 'https://api-eu.decathlon.net/sportstrackingdata/v2/';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @inheritDoc
     */
    public function getAccessToken($grant, array $options = [])
    {
        $grant = $this->verifyGrant($grant);

        $params = [
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code'
        ];

        $params   = $grant->prepareRequestParameters($params, $options);
        $request  = $this->getAccessTokenRequest($params);
        $response = $this->getParsedResponse($request);
        $prepared = $this->prepareAccessTokenResponse($response);
        $token    = $this->createAccessToken($prepared, $grant);

        return $token;
    }

    /**
     * Returns a prepared request for requesting an access token.
     *
     * @param array $params Query string parameters
     * @return RequestInterface
     */
    public function getBasicRequest($method, $url, array $options = [])
    {
        $options['headers']['Authorization'] = 'Basic ' . base64_encode(implode(':', [
                $this->clientId,
                $this->clientSecret,
            ]));
        return $this->getRequest($method, $url, $options);
    }

    /**
     *
     * Get the default scopes used by this provider.
     *
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return ['sports_tracking_data:write email contacts sports_tracking_data profile'];
    }

    /**
     * Builds request options used for requesting an access token.
     *
     * @param  array $params
     * @return array
     */
    protected function getAccessTokenOptions(array $params)
    {
        $options = parent::getAccessTokenOptions($params);

        $options['headers']['Authorization'] = 'Basic ' . base64_encode(implode(':', [
                $this->clientId,
                $this->clientSecret,
            ]));
        return $options;
    }

        /**
     * Returns a prepared request for requesting an access token.
     *
     * @param array $params Query string parameters
     * @return RequestInterface
     */
    protected function getAccessTokenRequest(array $params)
    {
        $method  = $this->getAccessTokenMethod();
        $url     = $this->getAccessTokenUrl($params);
        $options = $this->optionProvider->getAccessTokenOptions($this->getAccessTokenMethod(), $params);

        return $this->getBasicRequest($method, $url, $options);
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return self::BASE_URL . 'authorize';
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return self::BASE_URL.'token';
    }

    /**
     * @inheritDoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return self::BASE_DATA_URL.'me';
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                'Forbidden',
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return null;
    }

    /**
     * @return string
     */
    public function getBaseDataUrl()
    {
        return self::BASE_DATA_URL;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultHeaders()
    {
        return [
            'Accept-Encoding' => 'gzip',
            'api-key:' => $this->apiKey
        ];
    }
}

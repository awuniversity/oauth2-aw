<?php

namespace AwU\OAuth2\Client\Provider;

use AwU\OAuth2\Client\Exception\HostedDomainException;
use AwU\OAuth2\Client\Provider\Exception\IdentityProviderException;
use AwU\OAuth2\Client\Token\AccessToken;
use AwU\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Aw extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string If set, this will be sent to Aw as the "access_type" parameter.
     * @link https://awuniversity...
     */
    protected $accessType;

    /**
     * @var string If set, this will be sent to Aw as the "hd" parameter.
     * @link https://awuniversity...
     */
    protected $hostedDomain;

    /**
     * @var string If set, this will be sent to Aw as the "prompt" parameter.
     * @link https://awuniversity...
     */
    protected $prompt;

    /**
     * @var array List of scopes that will be used for authentication.
     * @link https://awuniversity...
     */
    protected $scopes = [];

    public function getBaseAuthorizationUrl()
    {
        return 'https://oauth.wisniowski.pl/connect/authorize'; // jurek: dodałem
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://oauth.wisniowski.pl/connect/token'; // jurek: dodałem
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://oauth.wisniowski.pl/connect/userinfo'; // jurek: dodałem
    }

    protected function getAuthorizationParameters(array $options)
    {
        if (empty($options['hd']) && $this->hostedDomain) {
            $options['hd'] = $this->hostedDomain;
        }

        if (empty($options['access_type']) && $this->accessType) {
            $options['access_type'] = $this->accessType;
        }

        if (empty($options['prompt']) && $this->prompt) {
            $options['prompt'] = $this->prompt;
        }

        // The "approval_prompt" option MUST be removed to prevent conflicts with non-empty "prompt".
        if (!empty($options['prompt'])) {
            $options['approval_prompt'] = null;
        }

        // Default scopes MUST be included for OpenID Connect.
        // Additional scopes MAY be added by constructor or option.
        $scopes = array_merge($this->getDefaultScopes(), $this->scopes);

        if (!empty($options['scope'])) {
            $scopes = array_merge($scopes, $options['scope']);
        }

        $options['scope'] = array_unique($scopes);

        return parent::getAuthorizationParameters($options);
    }

    protected function getDefaultScopes()
    {
        // "openid" MUST be the first scope in the list.
        return [
            'openid',
            'roles',
            'profile',
            'email',
            'employeeinfo'
        ];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // @codeCoverageIgnoreStart
        if (empty($data['error'])) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $code = 0;
        $error = $data['error'];

        if (is_array($error)) {
            $code = $error['code'];
            $error = $error['message'];
        }

        throw new IdentityProviderException($error, $code, $data);
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        $user = new AwUser($response);

        $this->assertMatchingDomain($user->getHostedDomain());

        return $user;
    }

    /**
     * @throws HostedDomainException If the domain does not match the configured domain.
     */
    protected function assertMatchingDomain($hostedDomain)
    {
        if ($this->hostedDomain === null) {
            // No hosted domain configured.
            return;
        }

        if ($this->hostedDomain === '*' && $hostedDomain) {
            // Any hosted domain is allowed.
            return;
        }

        if ($this->hostedDomain === $hostedDomain) {
            // Hosted domain is correct.
            return;
        }

        throw HostedDomainException::notMatchingDomain($this->hostedDomain);
    }
}

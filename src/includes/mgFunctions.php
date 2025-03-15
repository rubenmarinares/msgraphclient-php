<?php

use Microsoft\Kiota\Authentication\Oauth\TokenRequestContext;
use Microsoft\Kiota\Authentication\Oauth\BaseSecretContext;
use Microsoft\Kiota\Authentication\Oauth\DelegatedPermissionTrait;


class OnBehalfOfContextUsingRefreshToken extends BaseSecretContext implements TokenRequestContext
{
    use DelegatedPermissionTrait;
    
    public function __construct(
        string $tenantId,
        string $clientId,
        string $clientSecret,
        private readonly string $assertion,
        private readonly array $additionalParams = []
    ) {
        if (! $assertion) {
            throw new \InvalidArgumentException("Assertion cannot be empty");
        }

        parent::__construct($tenantId, $clientId, $clientSecret);
    }

    public function getParams(): array
    {
        return array_merge($this->additionalParams, parent::getParams(), [
            'refresh_token' => $this->assertion,
            'grant_type' => $this->getGrantType(),
        ]);
    }

    public function getGrantType(): string
    {
        return 'refresh_token';
    }
}





?>
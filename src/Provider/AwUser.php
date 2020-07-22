<?php

namespace AwU\OAuth2\Client\Provider;

class AwUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        throw new \Exception('Wyłączone użycie getId()');
        //return $this->response['sub'];
    }

    public function getSub()
    {
        return $this->response['sub'];
    }

    public function getUserPrincipalName()
    {
        if (array_key_exists('user_principal_name', $this->response)) {
            return $this->response['user_principal_name'];
        }
        return null;
    }

    public function getName()
    {
        return $this->response['name'];
    }

    public function getGivenName()
    {
        return $this->response['given_name'];
    }

    public function getFamilyName()
    {
        return $this->response['family_name'];
    }

    public function getRoles()
    {
        return $this->response['role'];
    }

    public function getLocale()
    {
        if (array_key_exists('locale', $this->response)) {
            return $this->response['locale'];
        }
        return null;
    }

    public function getEmail()
    {
        if (array_key_exists('email', $this->response)) {
            return $this->response['email'];
        }
        return null;
    }

    public function getHostedDomain()
    {
        if (array_key_exists('hd', $this->response)) {
            return $this->response['hd'];
        }
        return null;
    }

    public function getAvatar()
    {
        if (array_key_exists('picture', $this->response)) {
            return $this->response['picture'];
        }
        return null;
    }

    public function getEmployeeNumber()
    {
        if (array_key_exists('employee_number', $this->response)) {
            return $this->response['employee_number'];
        }
        return null;
    }

    public function toArray()
    {
        return $this->response;
    }
}

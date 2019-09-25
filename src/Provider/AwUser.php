<?php

namespace AwU\OAuth2\Client\Provider;

class AwUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['sub'];
    }

    /**
     * Get preferred display name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->response['name'];
    }

    /**
     * Get preferred first name.
     *
     * @return string
     */
    public function getGivenName()
    {
        return $this->response['given_name'];
    }

    /**
     * Get preferred last name.
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->response['family_name'];
    }

    /**
     * Get preferred roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->response['role'];
    }

    /**
     * Get locale.
     *
     * @return string|null
     */
    public function getLocale()
    {
        if (array_key_exists('locale', $this->response)) {
            return $this->response['locale'];
        }
        return null;
    }

    /**
     * Get email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        if (array_key_exists('email', $this->response)) {
            return $this->response['email'];
        }
        return null;
    }

    /**
     * Get hosted domain.
     *
     * @return string|null
     */
    public function getHostedDomain()
    {
        if (array_key_exists('hd', $this->response)) {
            return $this->response['hd'];
        }
        return null;
    }

    /**
     * Get avatar image URL.
     *
     * @return string|null
     */
    public function getAvatar()
    {
        if (array_key_exists('picture', $this->response)) {
            return $this->response['picture'];
        }
        return null;
    }

    /**
     * Get employee_number
     *
     * @return string|null
     */
    public function getEmployeeNumber()
    {
        if (array_key_exists('employee_number', $this->response)) {
            return $this->response['employee_number'];
        }
        return null;
    }

    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}

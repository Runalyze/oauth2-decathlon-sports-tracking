<?php
namespace League\OAuth2\Client\Provider;

class DecathlonSportsTrackingResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response.
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->response['id'] ?: null;
    }

    /**
     * Returns resource owner Language
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->response['language'] ?: null;
    }

    /**
     * Returns resource owners country
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->response['country'] ?: null;
    }


    /**
     * Returns resource owner birth day.
     *
     * @return string|null
     */
    public function getBirthdate()
    {
        return $this->response['birthDate'] ?: null;
    }

    /**
     * Returns resource owner registration date.
     *
     * @return string|null
     */
    public function getRegistrationdate()
    {
        return $this->response['createdAt'] ?: null;
    }

    /**
     * Returns all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
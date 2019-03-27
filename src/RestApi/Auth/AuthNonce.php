<?php

namespace RebelCode\Wpra\Core\RestApi\Auth;

use WP_REST_Request;

/**
 * A REST API auth handler implementation that verifies the validity of a WordPress nonce in the request.
 *
 * @since [*next-version*]
 */
class AuthNonce extends AbstractAuthValidator
{
    /**
     * The name of the nonce to validate.
     *
     * @since [*next-version*]
     *
     * @var string
     */
    protected $nonce;

    /**
     * The name of the request GET param or POST field from where the nonce value is read.
     *
     * @since [*next-version*]
     *
     * @var string
     */
    protected $param;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string $nonce The name of the nonce to validate.
     * @param string $param The name of the request GET param or POST field from where the nonce value is read.
     */
    public function __construct($nonce, $param)
    {
        $this->nonce = $nonce;
        $this->param = $param;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getValidationErrors($subject)
    {
        if (!($subject instanceof WP_REST_Request)) {
            return [
                __('Invalid request', 'wprss'),
            ];
        }

        $nonce = $subject->get_param($this->param);
        $valid = wp_verify_nonce($nonce, $this->nonce);

        if (!$valid) {
            return [
                __('Request nonce is invalid or has expired', 'wprss')
            ];
        }

        return [];
    }
}

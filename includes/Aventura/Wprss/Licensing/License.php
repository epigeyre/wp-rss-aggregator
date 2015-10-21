<?php

namespace Aventura\Wprss\Licensing;
use \Aventura\Wprss\Licensing\License\Status;

/**
 * This class represents a single license object.
 *
 * IMPORTANT!
 * 	This version is still untested
 *
 * @version 1.0-alpha
 * @since [<next-version>]
 */
class License {

	// Default values for license properties
	const KEY_DEFAULT			=	false;
	const STATUS_DEFAULT		=	Status::INVALID;
	const EXPIRY_DEFAULT		=	null;

	/**
	 * License key.
	 * 
	 * @var string
	 */
	protected $_key;

	/**
	 * License status.
	 *
	 * @see Aventura\Wprss\Licensing\License\Status;
	 * @var string
	 */
	protected $_status;

	/**
	 * License expiry date.
	 * 
	 * @var integer
	 */
	protected $_expiry;

	/**
	 * Constructs a new instance, using the given params or an array of properties if only the first param is given.
	 *
	 * @param string  $key     The license key, or an array containing the license data. Default: array()
	 * @param string  $status  The license status. Default: null
	 * @param integer $expiry  The expiry date of this license. Default: null
	 * @see Aventura\Wprss\Licensing\License\Status
	 */
	public function __construct( $key = array(), $status = null, $expiry = null ) {
		// If first arg is an array,
		if ( is_array( $key ) ) {
			// Get values from the appropriate keys
			$data = array_merge( self::_defaultSettingsArray(), $key );
			$key = $data['key'];
			$status = $data['status'];
			$expiry = $data['expiry'];
		}
		$this
		// Set fields
		->setKey( $key )
		->setStatus( $status )
		->setExpiry( $expiry )
		// Call secondary constructor
		->_construct();
	}

	/**
	 * Internal secondary constructor, for use when class is extended.
	 */
	protected function _construct() {}

	/**
	 * Gets the license key.
	 * 
	 * @return string
	 */
	public function getKey() {
		return $this->_key;
	}

	/**
	 * Sets the license key.
	 * 
	 * @param  string $key The license key.
	 * @return self
	 */
	public function setKey( $key ) {
		$this->_key = $key;
		return $this;
	}

	/**
	 * Gets the license status.
	 *
	 * @see Aventura\Wprss\Licensing\License\Status
	 * @return string
	 */
	public function getStatus() {
		return $this->_status;
	}

	/**
	 * Sets the license status.
	 * 
	 * @see Aventura\Wprss\Licensing\License\Status
	 * 
	 * @param  string $status The license status.
	 * @return self
	 */
	public function setStatus( $status ) {
		$this->_status = $status;
		return $this;
	}

	/**
	 * Gets the license expiry date.
	 * 
	 * @return integer
	 */
	public function getExpiry() {
		return $this->_expiry;
	}

	/**
	 * Sets the license expiry date.
	 * 
	 * @param integer $expiry The license expiry date
	 */
	public function setExpiry( $expiry ) {
		$this->_expiry = $expiry;
		return $this;
	}

	/**
	 * Gets the default values for all properties of the license.
	 * 
	 * @return array
	 */
	protected static function _defaultSettingsArray() {
		return array(
			'key'		=>	'',
			'status'	=>	Status::INVALID,
			'expiry'	=>	null
		);
	}

}

<?php

/**
 * @file
 * Contains \Drupal\cas_server\Configuration\ConfigHelper
 */

namespace Drupal\cas_server\Configuration;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class ConfigHelper
 */
Class ConfigHelper {

  /**
   * Stores settings object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $settings;

  /**
   * Constructor.
   *
   * @param ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->settings = $config_factory->get('cas_server.settings');
  }

  /**
   * Check a service against the service whitelist.
   *
   * @param string $service
   *   A supplied service string.
   *
   * @return bool
   *   Whether or not the service is allowed.
   */
  public function checkServiceAgainstWhitelist($service) {
    //TODO
    return TRUE;
  }

  /**
   * Return the list of attributes to be released for a service.
   *
   * @param string $service
   *   A supplied service string.
   *
   * @return array
   *   An array of user field names to be released as attributes.
   */
  public function getAttributesForService($service) {
    //TODO
    return [];
  }

  /**
   * Return the timeout for a proxy-granting ticket.
   *
   * @return int
   *   The number of seconds a proxy-granting ticket is valid.
   */
  public function getProxyGrantingTicketTimeout() {
    // TODO
    return 0;
  }

  /**
   * Return the timeout for a ticket-granting ticket.
   *
   * @return int
   *   The number of seconds a ticket-granting ticket is valid.
   */
  public function getTicketGrantingTicketTimeout() {
    // TODO
    return 0;
  }

  /**
   * Return the timeout for a proxy ticket.
   *
   * @return int
   *   The number of seconds a proxy ticket is valid.
   */
  public function getProxyTicketTimeout() {
    // TODO
    return 0;
  }

  /**
   * Return the timeout for a service ticket.
   *
   * @return int
   *   The number of seconds a service ticket is valid.
   */
  public function getServiceTicketTimeout() {
    // TODO
    return 0;
  }

  /**
   * Check whether a service is configured for single sign on.
   *
   * @param string $service
   *
   * @return bool
   *   Whether or not the service is authorized.
   */
  public function verifyServiceForSso($service) {
    // TODO
    return true;
  }

  /**
   * Return the custom invalid service message, or FALSE
   *
   * @return string|bool
   */
  public function getInvalidServiceMessage() {
    // TODO
    return FALSE;
  }

  /**
   * Return the custom user logout message, or FALSE
   *
   * @return string|bool
   */
  public function getUserLogoutMessage() {
    // TODO
    return FALSE;
  }

  /**
   * Return the custom logged in message, or FALSE
   *
   * @return string|bool
   */
  public function getLoggedInMessage() {
    // TODO
    return FALSE;
  }

}

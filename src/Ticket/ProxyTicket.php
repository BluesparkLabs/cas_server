<?php

/**
 * @file
 * Contains \Drupal\cas_server\Ticket\ProxyTicket
 */

namespace Drupal\cas_server\Ticket;

class ProxyTicket extends ServiceTicket {

  /**
   * @var array
   *
   * The chain of pgtUrls used to generate this ticket.
   */
  protected $proxyChain;
  
  /**
   * Constructs a proxy ticket.
   *
   * @param string $identifier
   *   The data used to identify the ticket.
   * @param string $expiry
   *   A unix timestamp describing the expiration time.
   * @param string $session_string
   *   The hashed session ID of the requestor of ticket.
   * @param string $uid
   *   The uid of the requestor.
   * @param string $username
   *   The username of the requestor.
   * @param string $service_string
   *   URL of the service the ticket is to be used for.
   * @param bool $creds_supplied
   *   Whether or not this ticket was generated by directly supplying creds.
   * @param array $proxy_chain
   *   The chain of pgtUrls used to generate this proxy ticket.
   */
  public function __construct($identifier, $expiry, $session_string, $uid, $username, $service_string, $creds_supplied, $proxy_chain) {
    $this->id = $identifier;
    $this->expirationTime = $expiry;
    $this->session = $session_string;
    $this->uid = $uid;
    $this->user = $username;
    $this->service = $service_string;
    $this->renew = $creds_supplied;
    $this->proxyChain = $proxy_chain;
  }

  /**
   * Returns the proxy chain.
   */
  public function getProxyChain() {
    return $this->proxyChain;
  }
}

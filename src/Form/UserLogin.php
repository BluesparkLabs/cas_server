<?php

/**
 * @file
 * Contains \Drupal\cas\Form\UserLogin.
 */

namespace Drupal\cas_server\Form;

use Drupal\user\UserAuthInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\Crypt;
use Drupal\cas_server\Ticket\TicketFactory;
use Drupal\cas_server\Configuration\ConfigHelper;
use Drupal\Core\Url;
use Drupal\user\PrivateTempStoreFactory;

/**
 * Class UserLogin.
 *
 * @codeCoverageIgnore
 */
class UserLogin extends FormBase {

  /**
   * Constructs a \Drupal\cas_server\Form\UserLogin object.
   *
   * @param UserAuthInterface $user_auth
   *   The authentication provider.
   * @param TicketFactory $ticket_factory
   *   The ticket factory.
   * @param ConfigHelper $config_helper
   *   The configuration helper.
   * @param PrivateTempStoreFactory $temp_store
   *   The user temporary storage factory.
   */
  public function __construct(UserAuthInterface $user_auth, TicketFactory $ticket_factory, ConfigHelper $config_helper, PrivateTempStoreFactory $temp_store) {
    $this->authService = $user_auth;
    $this->ticketFactory = $ticket_factory;
    $this->configHelper = $config_helper;
    $this->tempStore = $temp_store->get('cas_server');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('user.auth'), $container->get('cas_server.ticket_factory'), $container->get('cas_server.config_helper'), $container->get('user.private_tempstore'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cas_server_user_login';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $service = '') {
    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
    );

    $form['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#size' => 60,
      '#required' => TRUE,
    );

    $lt = 'LT-' . Crypt::randomBytesBase64(32);
    $this->tempStore->set('lt', $lt);

    $form['lt'] = array(
      '#type' => 'hidden',
      '#value' => $lt,
    );

    $form['service'] = array(
      '#type' => 'hidden',
      '#value' => $service,
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('lt') != $this->tempStore->get('lt')) {
      $form_state->setErrorByName('lt', $this->t('Login ticket invalid. Please try again.'));
    }
    $username = trim($form_state->getValue('username'));
    $password = trim($form_state->getValue('password'));
    if (!$this->authService->authenticate($username, $password)) {
      $form_state->setErrorByName('username', $this->t('Bad username/password combination given.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = trim($form_state->getValue('username'));
    $password = trim($form_state->getValue('password'));
    $service = $form_state->getValue('service');
    if ($uid = $this->authService->authenticate($username, $password)) {
      $account = User::load($uid);
      user_login_finalize($account);
      if (empty($service) || $this->configHelper->verifyServiceForSso($service)) {
        $tgt = $this->ticketFactory->createTicketGrantingTicket();
        setcookie('cas_tgc', $tgt->getId(), REQUEST_TIME + $this->configHelper->getTicketGrantingTicketTimeout(), '/cas');
      }
      if (!empty($service)) {
        $st = $this->ticketFactory->createServiceTicket($service, TRUE);
        $url = Url::fromRoute('cas_server.login', [], ['query' => ['service' => $service, 'ticket' => $st->getId()]]);
        $form_state->setRedirectUrl($url);
      }
      else {
        $form_state->setRedirectUrl(Url::fromRoute('cas_server.login'));
      }
    }

  }

}

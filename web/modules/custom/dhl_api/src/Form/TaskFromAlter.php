<?php 

namespace Drupal\dhl_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *  Class DemoFrom.
 */
class TaskFromAlter extends FormBase{

   protected $loaddata;

    /**
     * { @inheritdoc}
     */
    public function getFormId(){
        return 'taskform';
    }

    public function __construct(){
      $this->loaddata = \Drupal::service('dhl_api.apiservice');
    }

    /**
     * { @inheritdoc }
     */

     public function buildForm(array $form, FormStateInterface $form_state){
      $form['country'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Country'),
        '#maxlength' => 64,
        '#size' => 64,
        '#weight' => '0',
      ];
      $form['city'] = [
        '#type' => 'textfield',
        '#title' => $this->t('City'),
        '#maxlength' => 64,
        '#size' => 64,
        '#weight' => '0',
      ];
      $form['postalcode'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Postal Code'),
        '#maxlength' => 64,
        '#size' => 64,
        '#weight' => '0',
      ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
     }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query =$this->loaddata->getlocation($form_state);
  }
}
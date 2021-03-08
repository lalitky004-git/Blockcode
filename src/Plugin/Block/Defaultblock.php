<?php
namespace Drupal\lalit\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockPluginInterface;
/**
 * Provides a 'CustomBlock' block.
 *
 * @Block(
 *  id = "id_custom_block",
 *  admin_label = @Translation("labelcustomblock"),
 * )
 */
class DefaultBlock extends BlockBase implements BlockPluginInterface {
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['hello_block_name'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Who'),
      '#description' => $this->t('Who do you want to say hello to?'),
      '#default_value' => isset($config['name']) ? $config['name'] : '',
    );
    $form['mobile_number'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('your number'),
      '#description' => $this->t('enter your personal mobile number'),
      '#default_value' => isset($config['number']) ? $config['number'] : '',
    );
    $form['address'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('your address'),
      '#description' => $this->t('enter your home town'),
      '#default_value' => isset($config['town']) ? $config['town'] : '',
    );
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('name', $form_state->getValue('hello_block_name'));
    $this->setConfigurationValue('number', $form_state->getValue('mobile_number'));
    $this->setConfigurationValue('town', $form_state->getValue('address'));
  }
  public function build() {
    $query = \Drupal::entityQuery('node')
  ->condition('status', 1) //published or not
  ->condition('type', 'employee'); //content type
 // ->pager(10); //specify results to return
$nids = $query->execute();
//print_r($nids);
$array1=[];
$array2=[];
$i=0;
foreach ($nids as $nid) {
  $node = \Drupal\node\Entity\Node::load($nid); 
 // $body = $node->body->value;
  //$title = $node->title->value;
  //print_r($nid);
  //var_dump($node->field_employee_name->value);
  $array1[]=$node->field_employee_name->value;
  $array2[]=$node->field_employee_id->value;
  $rows[$i]=[$node->field_employee_name->value,$node->field_employee_id->value];
  $i++;
  //...
}
//print_r($array1);
//print_r($array2);
    $config = $this->getConfiguration();
    if (!empty($config['name'])) {
      $name = $config['name'];
    }
    else {
      $name = $this->t('to no one');
    }
    if (!empty($config['number'])) {
      $number = $config['number'];
    }
    else {
      $number = $this->t('mobile number');
    }
    if (!empty($config['town'])) {
      $town = $config['town'];
    }
    else {
      $town = $this->t("@town");
    }
    $header = [
      'col1' => t('Employee name'),
      'col2' => t('Employee id'),
    ];
    // dump($rows);
    // $rows = [
    //   ['', 'test'],
    //   ['test col 1', 'test'],
    //   ['test col 1', 'test'],
    // ];
    // dump($rows);
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  
  }
}
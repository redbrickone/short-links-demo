<?php

namespace Drupal\shortlinks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Displays the Short Link Form
 * 
 * @Block(
 *  id = "short_links_form_block",
 *  admin_label = @Translation("Short Links Form"),
 *  category = @Translation("Custom")
 * )
 */
class ShortlinksBlock extends BlockBase {
    
    /**
     * {@inheritdoc}
     */
    public function build() {
        return \Drupal::formBuilder()->getForm('Drupal\shortlinks\Form\ShortenShortlinksForm');
    }
}
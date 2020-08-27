<?php


namespace Drupal\shortlinks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;


class ShortlinksRedirectController extends ControllerBase {
    public function redirectToShortUrl($short_code) {

        $url_query = \Drupal::database()->select('short_urls', 's')
            ->fields('s', ['short_url', 'short_code'])
            ->condition('s.short_code', $short_code, '=');
        $url_query_result = $url_query->execute();

        $short_url = '';

        foreach ($url_query_result as $record) {
            $short_url = $record->short_url;
        }
        
        if (!empty($short_url)) {
            return new TrustedRedirectResponse($short_url);
        }
        else {
            \Drupal::messenger()->addMessage(t('An error occured returning the Short Link URL.'), 'error');
        }
    }
}
<?php


namespace Drupal\shortlinks\Controller;


use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;

class ShortlinksInfoController extends ControllerBase {

    public function getInfo($short_code) {

        $query = \Drupal::database()->select('short_urls', 's')
            ->fields('s', ['long_url', 'short_url', 'short_code'])
            ->condition('s.short_code', $short_code, '=');
        $result = $query->execute();

        $short_url = \Drupal::request()->getSchemeAndHttpHost() . "/";
        $long_url = '';

        foreach ($result as $record) {
            $short_code = $record->short_code;
            $long_url = $record->long_url;
        }
        $short_url = $short_url . "r/" . $short_code;

        if (!empty($short_url && !empty($long_url))) {
            return [
                '#type' => 'markup',
                '#markup' => $this->t("<strong>Your Shortened URL!</strong>" . "<br />" . "Original URL: $long_url" . "<br/>" . "Short URL: $short_url <br />" . "<a href='$short_url'>I'm Feeling Lucky!</a>"),
            ];
        }
        else {
            \Drupal::messenger()->addMessage(t('An error occured returning the Short Link information.'), 'error');
        }
    }
}
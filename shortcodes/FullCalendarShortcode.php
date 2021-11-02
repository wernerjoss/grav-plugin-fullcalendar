<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class FullCalendarShortcode extends Shortcode {
    public function init() {
        $this->shortcode->getHandlers()->add('fullcalendar', function(ShortcodeInterface $sc) {
            $s = $sc->getContent();
            $twig = $this->twig;
            $params = $sc->getParameters();
            $config = $this->config->get('plugins.fullcalendar');
            // icsfile is Parameter from shortcode:
            if (isset($params['icsfile'])) {
                $icsfile = $this->grav['twig']->processString($params['icsfile']);
            } else $icsfile = '';
            $output = $twig->processTemplate('partials/fullcalendar.html.twig',
                [
                    'icsfile' => $icsfile
                ]);
            return $output;
        });
    }
}

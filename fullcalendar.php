<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Language;
use RocketTheme\Toolbox\Event\Event;

class FullcalendarPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }
        // Enable the main events we are interested in
        $this->enable([
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths',0]
        ]);
        //add assets
        $assets = $this->grav['assets'];
        
        //  31.01.21: most assets loading has been moved to fullcalendar.html.twig, which is only loaded in corresponding page, NOT directly upon Plugin Initialize !
        /*
        $assets->addJs('plugin://fullcalendar/assets/ical.js/build/ical.min.js','defer');
        $assets->addJs('plugin://fullcalendar/assets/popper.min.js','defer');   // local popper
		$assets->addJs('plugin://fullcalendar/assets/tippy-bundle.umd.min.js','defer');   // local tippy
		$assets->addCss('plugin://fullcalendar/fc4/packages/core/main.css');
		$assets->addCss('plugin://fullcalendar/fc4/packages/daygrid/main.css');
        $assets->addJs('plugin://fullcalendar/fc4/packages/core/main.js','defer');
        $assets->addJs('plugin://fullcalendar/fc4/vendor/rrule.js','defer');   // see also reamde.txt file there
		$assets->addJs('plugin://fullcalendar/fc4/packages/rrule/main.js','defer'); // connector to the vendor/rrule.js Lib
        $assets->addJs('plugin://fullcalendar/fc4/packages/interaction/main.js','defer');
		$assets->addJs('plugin://fullcalendar/fc4/packages/daygrid/main.js','defer');
        $assets->addJs('plugin://fullcalendar/assets/monthpic.js','defer');
        $assets->addCss('plugin://fullcalendar/assets/daygrid.css');	// default CSS for #calendar
        */
        // do not load a predefined language, use system setting instead
        $language = $this->grav['language']->getLanguage();
        $assets->addJs('plugin://fullcalendar/fc4/packages/core/locales/'.$language.'.js');
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onShortcodeHandlers(Event $e)
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__.'/shortcodes');
    }
}

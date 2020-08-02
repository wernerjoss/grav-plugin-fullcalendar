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
        // $assets->addJs('plugin://fullcalendar/assets/lib/jquery.min.js');	// jquery should already be in system/assets

        $assets->addJs('plugin://fullcalendar/assets/bundle.js');   // see also reamde.txt file there

        /*
        $assets->addCss('plugin://fullcalendar/fc4/packages/core/main.css');
    		$assets->addCss('plugin://fullcalendar/fc4/packages/daygrid/main.css');
    		$assets->addJs('plugin://fullcalendar/fc4/vendor/rrule.js');   // see also reamde.txt file there
    		$assets->addJs('plugin://fullcalendar/fc4/packages/core/main.js');
    		$assets->addJs('plugin://fullcalendar/fc4/packages/interaction/main.js');
    		$assets->addJs('plugin://fullcalendar/fc4/packages/daygrid/main.js');
    		$assets->addJs('plugin://fullcalendar/fc4/packages/rrule/main.js');
    		// do not load a predefined language, use system setting instead
        $language = $this->grav['language']->getLanguage();
        $assets->addJs('plugin://fullcalendar/fc4/packages/core/locales/'.$language.'.js');
         */
        $assets->addJs('plugin://fullcalendar/assets/monthpic.js');
        $assets->addCss('plugin://fullcalendar/assets/daygrid.css');	// default CSS for #calendar
  
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

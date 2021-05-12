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
			'onPluginsInitialized' => ['onPluginsInitialized', 0],
			'onGetPageTemplates' => ['onGetPageTemplates',0]
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
			'onTwigTemplatePaths' => ['onTwigTemplatePaths',0],
		]);
		//add assets
		$assets = $this->grav['assets'];
		$config = $this->config->get('plugins.fullcalendar');   // get plugin config
		$use_plugin_template = false;   // default
		if ($config['use_plugin_template']) $use_plugin_template = true;
		//  dump($use_plugin_template);
		if (! $use_plugin_template) {   // load Js/Css here if plugin template is not used (default behaviour)
			//	$assets->addJs('plugin://fullcalendar/assets/lib/jquery.min.js', 'defer');	// jquery should already be in system/assets
			$assets->addJs('plugin://fullcalendar/assets/jquery.ajax-cross-origin.min.js', 'defer');   // 12.05.21  -   so gehts !!!
			$assets->addJs('plugin://fullcalendar/assets/ical.js/build/ical.min.js', 'defer');   // see also reamde.txt file there
			// for Tooltip: 
			$assets->addJs('plugin://fullcalendar/assets/popper.min.js', 'defer');
			$assets->addJs('plugin://fullcalendar/assets/tippy-bundle.umd.min.js', 'defer');
			$assets->addCss('plugin://fullcalendar/fc4/packages/core/main.css');
			$assets->addCss('plugin://fullcalendar/fc4/packages/daygrid/main.css');
			$assets->addJs('plugin://fullcalendar/fc4/packages/core/main.js', 'defer');
			$assets->addJs('plugin://fullcalendar/fc4/vendor/rrule.js', 'defer');   // see also reamde.txt file there
			$assets->addJs('plugin://fullcalendar/fc4/packages/rrule/main.js', 'defer'); // connector to the vendor/rrule.js Lib
			$assets->addJs('plugin://fullcalendar/fc4/packages/interaction/main.js', 'defer');
			$assets->addJs('plugin://fullcalendar/fc4/packages/daygrid/main.js', 'defer');
			// do not load a predefined language, use system setting instead
			$assets->addJs('plugin://fullcalendar/assets/monthpic.js', 'defer');
			$assets->addJs('plugin://fullcalendar/assets/calendar.js', 'defer');
			$assets->addCss('plugin://fullcalendar/assets/daygrid.css');	// default CSS for #calendar
		}
		//	$assets->addCss('plugin://fullcalendar/assets/custom.css');	// don't use custom CSS in Plugin folder, better from Theme !
		$language = $this->grav['language']->getLanguage();
		$assets->addJs('plugin://fullcalendar/fc4/packages/core/locales/'.$language.'.js', 'defer');
	}

	public function onTwigTemplatePaths()
	{
		$this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
	}

	public function onShortcodeHandlers(Event $e)
	{
		$this->grav['shortcode']->registerAllShortcodes(__DIR__.'/shortcodes');
	}
    
	// register Template 'calendar' so it can be used in the admin backend without having to be copied to the theme's template folder
	// see https://github.com/getgrav/grav-learn/pull/907
	public function onGetPageTemplates(Event $event)
	{
		/** @var Types $types */
		$types = $event->types;
		$types->register('calendar');
		$types->scanTemplates(__DIR__.'/templates');
	}
}

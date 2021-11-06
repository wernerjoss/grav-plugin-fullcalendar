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
			'onPageInitialized' => ['onPageInitialized', 0],
		]);
	}

	public function onPageInitialized(Event $event)
	{
		/** @var Page */
		$page = $event['page'];

		$http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'? "https://" : "http://";
		$host  = $_SERVER['HTTP_HOST'];
		$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$baseurl = $http . $host . $path . "/";
		$config = $this->config->get('plugins.fullcalendar');
		$this->grav['config']->set('plugins.fullcalendar.base_url', $baseurl);
		$this->saveConfig('fullcalendar');

		if (($page->template() === 'calendar') || ($config['useCustomPageTemplate'])) {
			$this->addAssets();
		}
	}

	private function addAssets()
	{
		/** @var Assets */
		$assets = $this->grav['assets'];
		$config = $this->config->get('plugins.fullcalendar');
		$assets->addJs('plugins://' . $this->name . '/assets/jquery.ajax-cross-origin.min.js', ['group' => 'bottom']);   // 12.05.21  -   so gehts !!!
		$assets->addJs('plugins://' . $this->name . '/assets/ical.js/build/ical.min.js', ['group' => 'bottom']);   // see also reamde.txt file there
		// for Tooltip:
		$assets->addJs('plugins://' . $this->name . '/assets/popper.min.js', ['group' => 'bottom']);
		$assets->addJs('plugins://' . $this->name . '/assets/tippy-bundle.umd.min.js', ['group' => 'bottom']);
		$assets->addCss('plugins://' . $this->name . '/fc4/packages/core/main.css');
		$assets->addCss('plugins://' . $this->name . '/fc4/packages/daygrid/main.css');
		$assets->addJs('plugins://' . $this->name . '/fc4/packages/core/main.js', ['group' => 'bottom']);
		$assets->addJs('plugins://' . $this->name . '/fc4/vendor/rrule.js', ['group' => 'bottom']);   // see also reamde.txt file there
		$assets->addJs('plugins://' . $this->name . '/fc4/packages/rrule/main.js', ['group' => 'bottom']); // connector to the vendor/rrule.js Lib
		$assets->addJs('plugins://' . $this->name . '/fc4/packages/interaction/main.js', ['group' => 'bottom']);
		$assets->addJs('plugins://' . $this->name . '/fc4/packages/daygrid/main.js', ['group' => 'bottom']);
		$assets->addJs('plugins://' . $this->name . '/assets/monthpic.js', ['group' => 'bottom']);
		$assets->addJs('plugins://' . $this->name . '/assets/calendar.js', ['group' => 'bottom']);
		$assets->addCss('plugins://' . $this->name . '/assets/daygrid.css');	// default CSS for #calendar
		// do not load a predefined language, use system setting instead
		$language = $this->grav['language']->getLanguage();
		// TODO find a (better) fallback for when $language is not set in grav
		if (!isset($language)) $language = "en";
		$assets->addJs('plugins://' . $this->name . '/fc4/packages/core/locales/'.$language.'.js', ['group' => 'bottom']);
		$languages = $this->config->get('system.languages.supported');
		if (!isset($languages)) $languages = ['en'];
		foreach ($languages as $lang)	{
			if ($lang != $language)	{
				$assets->addJs('plugins://' . $this->name . '/fc4/packages/core/locales/'.$lang.'.js', ['group' => 'bottom']);
			}
		}
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

<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class FullcalendarPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            $this->enable([
                'onGetPageTemplates' => ['onGetPageTemplates', 0], // this is finally the right place :-) 18.07.23
            ]);
            return;
        }
        // Enable the main events we are interested in
        $this->enable([
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0],
        ]);
    }

    public function onPageInitialized(Event $event)
    {
        /** @var Page */
        $page = $event['page'];

        $useCustomPageTemplate = $this->config->get('plugins.fullcalendar.useCustomPageTemplate', false); // keep this for backwards compatibility
        $enableOnAllPages = $this->config->get('plugins.fullcalendar.enableOnAllPages', false); // new Flag with better Name
        if ($useCustomPageTemplate) {
            $enableOnAllPages = true;
        }
        // use obsolete Flag useCustomPageTemplate, in case it is still present and set to true
        $enableByPageHeader = (isset($page->header()->fullcalendar) && $page->header()->fullcalendar === true);
        if (($page->template() === 'calendar') || ($enableOnAllPages) || ($enableByPageHeader)) { // see https://github.com/wernerjoss/grav-plugin-fullcalendar/issues/45
            $this->addAssets();
        }
    }

    private function addAssets()
    {
        /** @var Assets */
        $assets = $this->grav['assets'];
        $config = $this->config->get('plugins.fullcalendar');
        $assets->addJs('plugins://' . $this->name . '/assets/jquery.ajax-cross-origin.min.js', ['group' => 'bottom']); // 12.05.21  -   so gehts !!!
        $assets->addJs('plugins://' . $this->name . '/assets/ical.js/build/ical.min.js', ['group' => 'bottom']); // see also reamde.txt file there
        // Tooltip:
        $assets->addJs('plugins://' . $this->name . '/assets/popper.min.js', ['group' => 'bottom']);
        $assets->addJs('plugins://' . $this->name . '/assets/tippy-bundle.umd.min.js', ['group' => 'bottom']);
        // standard plugins + core:
        $assets->addCss('plugins://' . $this->name . '/fc4/packages/core/main.css');
        $assets->addJs('plugins://' . $this->name . '/fc4/packages/core/main.js', ['group' => 'bottom']);
        $assets->addCss('plugins://' . $this->name . '/fc4/packages/daygrid/main.css');
        $assets->addJs('plugins://' . $this->name . '/fc4/packages/interaction/main.js', ['group' => 'bottom']);
        $assets->addJs('plugins://' . $this->name . '/fc4/packages/daygrid/main.js', ['group' => 'bottom']);
        // timezone stuff:
        /* test 29.12.21
         */
        $assets->addJs('plugins://' . $this->name . '/fc4/vendor/moment.js', ['group' => 'bottom']); // moment lib ! 13.12.21
        $assets->addJs('plugins://' . $this->name . '/fc4/packages/moment/main.js', ['group' => 'bottom']); // connector to the moment.js lib ! 13.12.21
        $assets->addJs('plugins://' . $this->name . '/fc4/vendor/moment-timezone-with-data-10-year-range.js', ['group' => 'bottom']); // moment-timezone lib ! 13.12.21
        $assets->addJs('plugins://' . $this->name . '/fc4/packages/moment-timezone/main.js', ['group' => 'bottom']); // connector to the moment-timezone lib plugin ! 13.12.21
        // rrule:
        $assets->addJs('plugins://' . $this->name . '/fc4/vendor/rrule-tz.js', ['group' => 'bottom']); // see also reamde.txt file there rrule.js <-> rrule-tz.js
        $assets->addJs('plugins://' . $this->name . '/fc4/packages/rrule/main.js', ['group' => 'bottom']); // connector to the vendor/rrule.js (rrule-tz.js) Lib
        // template:
        $assets->addJs('plugins://' . $this->name . '/assets/monthpic.js', ['group' => 'bottom']);
        $assets->addJs('plugins://' . $this->name . '/assets/calendar.js', ['group' => 'bottom']);
        $assets->addCss('plugins://' . $this->name . '/assets/daygrid.css'); // default CSS for #calendar
        // do not load a predefined language, use system setting instead
        $language = $this->grav['language']->getLanguage();
        if (!isset($language)) {
            $language = "en";
        }

        $assets->addJs('plugins://' . $this->name . '/fc4/packages/core/locales/' . $language . '.js', ['group' => 'bottom']);
        $languages = $this->config->get('system.languages.supported');
        if (!isset($languages)) {
            $languages = ['en'];
        }

        foreach ($languages as $lang) {
            if ($lang != $language) {
                $assets->addJs('plugins://' . $this->name . '/fc4/packages/core/locales/' . $lang . '.js', ['group' => 'bottom']);
            }
        }
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onShortcodeHandlers(Event $e)
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__ . '/shortcodes');
    }

    // register Template 'calendar' so it can be used in the admin backend without having to be copied to the theme's template folder
    // see https://github.com/getgrav/grav-learn/pull/907
    public function onGetPageTemplates(Event $event)
    {
        /** @var Types $types */
        $types = $event->types;
        $types->register('calendar');
        $types->scanTemplates(__DIR__ . '/templates');
    }
}

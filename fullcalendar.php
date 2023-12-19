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

        $this->serveProxy();
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
        /* skip loading locales, they don't seem to work anyway :-/ 31.07.23
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
        */
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

    private function serveProxy(): void
    {
        $endpoint_url = '/_cors/proxy'; // TODO: make this configurable

        if (str_starts_with(strtolower($this->grav['uri']->uri()), $endpoint_url)) {

            $proxy_url = $this->config->get("plugins.{$this->name}.cors_api_url");
            if (!empty($proxy_url)) {
                return;
            }

            if (!(isset($_SERVER['HTTP_REFERER']) && str_starts_with(strtolower($_SERVER['HTTP_REFERER']), strtolower($_SERVER['HTTP_REFERER'])))) {
                self::endpointRespond(401, [
                    'status' => 'error',
                    'message' => 'Unauthorized request',
                ]);
            }

            if(!isset($_GET['url'])) {
                self::endpointRespond(400, [ // 422??
                    'status' => 'undefined',
                    'message' => 'Missing URL parameter',
                    // 'debug' => $hook_properties,
                ]);
            }

            $result = utf8_encode(file_get_contents($_GET['url']));

            if($result) {
                $json = json_encode($result);
                try {
                    if(isset($_GET['callback'])) {
                        self::endpointRespond(200, "{$_GET['callback']}($json)", 'application/javascript');
                    }
                    self::endpointRespond(200, $json);
                }
                catch (\Exception $e) {
                    self::endpointRespond(500, [
                        'status' => 'error',
                        'message' => "Calendar request failed",
                        // 'debug' => $hook_properties,
                    ]);
                }
            }
            else {
                self::endpointRespond(500, [
                    'status' => 'error',
                    'message' => "Calendar request failed",
                    // 'debug' => $hook_properties,
                ]);
            }
        }
    }

    /**
     * Provide a HTTP status and response (default JSON) and exit
     * @param  int    $http_status   HTTP status number to return
     * @param  string|array  $payload Payload as string or array (encoded to JSON) to be served
     * @return void
     */
    private static function endpointRespond(int $http_status, string|array $payload, $content_type='application/json'): void {
        if(is_array($payload)) {
            $payload = json_encode($payload);
        }
        header("Content-Type: $content_type");
        http_response_code($http_status);
        echo $payload;
        exit;
    }

}

<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Language;
use RocketTheme\Toolbox\Event\Event;
use Grav\Common\Taxonomy;

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
            'onTwigTemplatePaths' => ['onTwigTemplatePaths',0],
            'onGetPageTemplates'   => ['onGetPageTemplates', 0],
            'onPagesInitialized' => ['onPagesInitialized', 0]
        ]);
            }

    /**
     * Retrieves ics files which were uploaded on current page (or subpage)
     *
     */
    public function onPagesInitialized() 
    {
      $assets = $this->grav['assets'];

      //map plugin config
      $config = $this->grav['config'];
      //we choose settings for security reasons
      $json = "var GRAV = {
                            'config': { 
                              'system': { 
                                 'debugger': ". json_encode($config['system']['debugger'])."
                              },
                              'plugins': {
                                'fullcalendar': ".json_encode($config['plugins']['fullcalendar']) ."
                              }
                            } 
                          };";
      $assets->addInlineJs($json, ['loading'=>'inline', 'position'=>'before']); 

      //current page node
      $currentPage = $this->grav['page'];
      $taxo = $currentPage->taxonomy();
      //do not process something else than active and calendar page
      if ( !$currentPage->active() || empty($taxo['category']) || !in_array('calendar', $taxo['category'])) {
        return;
      }
      //we expect only one modular calendar 
      $children = $currentPage->evaluate(['@page.modular'=> $currentPage->route(),
                                          '@taxonomy.category' => 'calendar'
                                        ]);
      if ($children->count() > 1) {
        $page = $children->current();
      } else {
        $page = $currentPage;
      }
      $headers = json_encode($page->header());
      $media = $page->getMedia();
      $fileUrls = [];
      foreach($media->files() as $name=>$file) {
        if (substr(strrchr($name, "."), 1) == 'ics') {
          $fileUrls[] = ['ics'=>$file->url(), 'name'=>$name];
        }
      }
      //build page headers and media in json  
      $pageJson =
        " GRAV.page = { header:'', media:''};" . 
        " GRAV.page.header = JSON.parse('" . addslashes($headers) . "');" .
        (!empty($fileUrls)?" GRAV.page.media = JSON.parse('" . addslashes(json_encode($fileUrls)). "');":'');

      $assets->addInlineJs($pageJson, ['loading'=>'inline', 'position'=>'before']);
      //main fullcalendar assets
      $assets->addJs('plugin://fullcalendar/assets/dist/bundle.js'); 
      //@todo use webpack loaders and sass
      $assets->addCss('plugin://fullcalendar/assets/css/main.css');  // default CSS for #calendar

    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Expose plugin's page templates
     *
     * @param  Event $event
     * @return void
     */
    public function onGetPageTemplates(Event $event)
    {
      $types = $event->types;

      /* @var Locator $locator */
      $locator = $this->grav['locator'];

      // Set blueprints & templates.
      //$types->scanBlueprints($locator->findResource('plugin://fullcalendar/blueprints'));
      $types->scanTemplates($locator->findResource('plugin://fullcalendar/templates'));

      // reverse the FUBARd order of blueprints
      $event = array_reverse($types['event']);
      $types['event'] = $event;
    }


}

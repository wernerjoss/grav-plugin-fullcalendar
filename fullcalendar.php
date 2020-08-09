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
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onTwigPageVariables' => ['onTwigPageVariables', 0]
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
            'onTwigTemplatePaths' => ['onTwigTemplatePaths',0]
        ]);
        //add assets
        $assets = $this->grav['assets'];
        $assets->addJs('plugin://fullcalendar/assets/dist/bundle.js'); 
        $assets->addCss('plugin://fullcalendar/assets/css/daygrid.css');  // default CSS for #calendar

        //map plugin config
        $configJSON = json_encode($this->grav['config']);
        $assets->addInlineJs("var GRAV = {};GRAV.config = JSON.parse('" . addslashes($configJSON) . "');", ['loading'=>'inline', 'position'=>'before']); 
    }


    public function onTwigPageVariables() 
    {
      $assets = $this->grav['assets'];
      $taxonomy = $this->grav['taxonomy'];
      $pages = $this->grav['page']->evaluate(['@taxonomy.category'=>'calendar']);
      foreach($pages as $page) {
        $headers = json_encode($page->header());
        $assets->addInlineJs("GRAV.page = {header:''};  GRAV.page.header = JSON.parse('" . addslashes($headers) . "');", ['loading'=>'inline', 'position'=>'before']); 
      }
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
}

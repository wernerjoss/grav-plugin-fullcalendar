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
        //@todo use webpack loaders and sass
        $assets->addCss('plugin://fullcalendar/assets/css/daygrid.css');  // default CSS for #calendar
        $assets->addCss('plugin://fullcalendar/assets/css/tooltip.css');  // default CSS for #calendar

        //map plugin config
        $config = clone $this->grav['config'];
				//@todo filter config that should not be exposed in frontend
        $configJSON = json_encode($config);
        $assets->addInlineJs("var GRAV = {};GRAV.config = JSON.parse('" . addslashes($configJSON) . "');", ['loading'=>'inline', 'position'=>'before']); 
    }

    /**
     * Retrieves ics files which were uploaded on current page (or subpage)
     *
     */
    public function onTwigPageVariables() 
    {
      $assets = $this->grav['assets'];
      $taxonomy = $this->grav['taxonomy'];
			//@todo retrieve files on current page not only subpages
      $pages = $this->grav['page']->evaluate(['@taxonomy.category'=>'calendar']);
      foreach($pages as $page) {
        $headers = json_encode($page->header());
        $media = $page->getMedia();
        $fileUrls = [];
        foreach($media->files() as $name=>$file) {
           if (substr(strrchr($name, "."), 1) == 'ics') {
            $fileUrls[] = ['ics'=>$file->url(), 'name'=>$name];
           }
        }
				//build page headers and media in json  
        $assets->addInlineJs(
          " GRAV.page = {header:'', media:''};" . 
          " GRAV.page.header = JSON.parse('" . addslashes($headers) . "');" .
          " GRAV.page.media = JSON.parse('" . addslashes(json_encode($fileUrls)). "');",
          ['loading'=>'inline', 'position'=>'before']);
      }
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
}

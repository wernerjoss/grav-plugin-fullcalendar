# Fullcalendar Plugin

The **Fullcalendar** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It reads an ICS Calendar File and shows Events in a monthly Calendar Widget on your Page(s)

## Installation

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `fullcalendar`. You can find these files on [GitHub](https://github.com/sherpadawan/grav-plugin-fullcalendar) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/fullcalendar
    
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/fullcalendar/fullcalendar.yaml` to `user/config/plugins/fullcalendar.yaml` and only edit that copy.

See fullcalendar.yaml file in this repo as example.

Note that if you use the admin plugin, a file with your configuration, and named fullcalendar.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

fullcalendar plugins, requires you to create a page with taxonomy like, this can be achieve in expert or normal mode
if your calendar is a modular subpage you need to add this taxonomy on the parent page and on the modular child page

```
taxonomy:
  category:
    - calendar
```

## Recurrent events

ical-expander is now used to support rrules in events. [ical-expander](https://github.com/mifi/ical-expander)
some tunning performance and further testing are required.

## Developement

assets dir is a webpack built project, so use npm and webpack commands.
one can do :
```
cd assets
bash build.sh
```

## Credits

this plugin is built on 
 * [ical-expander](https://github.com/mifi/ical-expander)
 * [store2](https://www.npmjs.com/package/store2)
 * [superagent](https://www.npmjs.com/package/superagent)

## Issues

See github repo issues

## To Do

 * multicalendar ? currently only one calendar per page is supported
 * admin form plugin blueprints.yaml for extended configuration 
 * event popup on click or hover
 * use a unified log library (log does not work with webpack) 

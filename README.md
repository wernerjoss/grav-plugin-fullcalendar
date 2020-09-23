# Fullcalendar Plugin

The **Fullcalendar** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It reads an ICS Calendar File and shows Events in a monthly Calendar Widget on your Page(s)

## Installation

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `fullcalendar`. You can find these files on [GitHub](https://github.com/wernerjoss/grav-plugin-fullcalendar) or via [GetGrav.org](https://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/fullcalendar
    
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) Plugins to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/fullcalendar/fullcalendar.yaml` to `user/config/plugins/fullcalendar.yaml` and only edit that copy.  
Note that if you use the admin plugin, the file with your configuration, named fullcalendar.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.  
There is, however, no UI in the admin Backend that provides any customizations other than enable/disable - you will have to edit the config file to do that.

Here is the default configuration and an explanation of available options:

```yaml

enabled: true
colors: #3a87ad # see additional Note on custom colors in the Changelog
showlegend: false   # set to true to show calendar File Name(s) as Legend below grid
locale: en  # use your own locale here, e.g. de
weekNumbers: false  # set to true to show Week Numbers
cors_api_url: https://cors-anywhere.herokuapp.com/  # this is the default value, change if you like to use another

```
=======
See fullcalendar.yaml file in this repo as example.

## Usage

Once installed and enabled, you can use this Plugin to parse ICS Calendar File(s) (these must be found in user/data/calendars and set as parameter in Plugin shortcode, without Path !) and display Events from that Calendar(s) anywhere on your Site using this shortcode:

    [fullcalendar icsfile='example0.ics,example1.ics,...'][/fullcalendar]
    
in the appropriate page.  
You can also provide absolute URL's to ICS Files, in which case a CORS proxy will be used to access them.  
As an addition, you can show a Picture for the current month above the calendar widget.  
Just put 12 Image Files named 'January.jpg', 'February.jpg', ... , 'December.jpg' in the Folder for your Page where the Calendar will be placed.
(Note that Image File names must match Month names according to your locale setting, so, for locale: de, use 'Januar.jpg' ...)
Nothing special, just use the proper templates Fullcalendar or Modular Fullcalendar

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

* this branch is intended to be merged with [https://github.com/sherpadawan/grav-plugin-fullcalendar](https://github.com/sherpadawan/grav-plugin-fullcalendar) with the goal to create a complete new Release based on fullcalendar.io v5
=======
 * multicalendar ? currently only one calendar per page is supported
 * admin form plugin blueprints.yaml for extended configuration 
 * event popup on click or hover
 * use a unified log library (log does not work with webpack) 
 * fix missing/removed functionality from fc4 branch: multiple calendars, monthpic, legend, summary on hover, event URL ...

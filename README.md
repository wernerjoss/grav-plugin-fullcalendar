# Fullcalendar Plugin

The **Fullcalendar** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It reads an ICS Calendar File and shows Events in a monthly Calendar Widget on your Page(s)

## Installation

Installing the Fullcalendar plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install fullcalendar

This will install the Fullcalendar plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/fullcalendar`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `fullcalendar`. You can find these files on [GitHub](https://github.com/werner-hoernerfranzracing-de/grav-plugin-fullcalendar) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/fullcalendar
    
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/fullcalendar/fullcalendar.yaml` to `user/config/plugins/fullcalendar.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml

enabled: true
colors: #3a87ad # see additional Note on custom colors
showlegend: false   # set to true to show calendar File Name(s) as Legend below grid
locale: en  # use your own locale here, e.g. de
weekNumbers: false  # set to true to show Week Numbers

```

Note that if you use the admin plugin, a file with your configuration, and named fullcalendar.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

Once installed and enabled, you can use this Plugin to parse an ICS Calendar File (must be found in user/data/calendars and set as  parameter in Plugin shortcode, without Path !) and display Events from that Calendar anywhere on your Site using this shortcode:

    [fullcalendar icsfile=example.ics][/fullcalendar]
    
in the appropriate page.
As an addition, you can show a Picture for the current month above the calendar widget.
Just put 12 Image Files named 'January.jpg', 'February.jpg', ... , 'December.jpg' in the Folder for your Page where the Calendar will be placed.

### additional note on updated month pic feature (from v 0.1.1):
The plugin will now change the displayed image when visitor clicks prev/next/home button.
this will only work when picture files are named correctly according to active locale.
for locale 'en' , this means 'January.jpg, February.jpg, March.jpg ....'
for locale 'de' , this means 'Januar.jpg, Februar.jpg, März.jpg ....' and so on...

### additional note on multiple calendar files (from v 0.1.2):
As listed in the To Do section, the multiple calendar files option has now been implemented.
That means, you can now display events from more than one ics file.
just use a comma separated list of filenames in the shortcode, like

    [fullcalendar icsfile=example1.ics,example2.ics,example3.ics][/fullcalendar]

### additional note on remote calendar files (from v 0.1.4):
Calendar files can now also be remote, on other servers, these can also be mixed with local calendar files.
In this case, the complete absolute URL to the file(s) has to be provided in the shortcode, as well as enclosing quote signs (") around the whole icsfile= specification, like

    [fullcalendar icsfile="https://calendar.google.com/calendar/ical/myname%40myorganization.com/public/basic.ics" ][/fullcalendar]
    
As there have been multiple issues with remote Calendar Files not showing events as a result of [CORS](https://en.wikipedia.org/wiki/Cross-origin_resource_sharing) Policy restrictions,
an automatic addition of a [CORS Proxy URL](https://cors-anywhere.herokuapp.com/) has now been incorporated (from v 0.2.2)
    
### additional note on custom colors for multiple calendars (from v 0.1.5):
Calendars can now be assigned custom colors for nicer display by adding an entry 'colors' to fullcalendar.yaml - this should be a comma-separated list of HTML
colors (predifined Names like lightBlue can be used as well as HEX notation, like #ffaabc).
If colors entry is not defined, standard fullcalendar.css color is used for all calendars, same behaviour as before.
In Addition, a Legend (html List, css class cal_legend) can be enabled by adding showlegend: true

### additional note on repeating Events (from v 0.2.0):
Repeating Events are now basically supported by incorporating the rrule Plugin from fullcalendar.io V 4.3.1.
There are, however, some rrule Options left, that are not yet supported - should work for most common rrules, though.
Additionaly, a new configuration Option has been introduced: locale - just set this to your Preferred value in fullcalendar.yaml.
Default is en.
Also note that file assets/custom.css has been renamed to daygrid.css - custom.css is for user adaptions and will also be used, if present (but is not provided with plugin) !

## Credits

This Plugin is built on [fullcalendar.io](https://fullcalendar.io), [jakubroztocil/rrule](https://github.com/jakubroztocil/rrule) and [jsical](http://mozilla-comm.github.io/ical.js) - Javascript parser for rfc5545

## To Do

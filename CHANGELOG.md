# v0.2.8
##  05/12/2021

1. [](#new)
    * add config option use_plugin_template (improve site page speed ranking)
    * add local CORS Proxy (as default external proxy herokuapp stopped working)

# v0.2.7
##  02/26/2021

1. [](#bugfix)
    * fix weekNumbers setting not recognized, code formatting

# v0.2.6
##  02/20/2021

1. [](#new)
    * improve Monthpics search/display (disable javascript month change event watching when no month pics available)
    * introduce page Template in Plugin, make this selectable in Admin Backend without copy to Theme Templates Folder
    * move javascript out of twig template into separate js File for easier Maintenance
    * enhance blueprints.yaml for easier configuration in admin backend

# v0.2.5
##  09/05/2020

1. [](#new)
    * fix missing event time display when allDay=false 
    * fix picture artifact in html when no month pics in page folder
    * add experimental config option tz_offset as a workaround for wrong event times in repeating events (see source code..)

# v0.2.4
##  07/21/2020

1. [](#new)
    * add config Option cors_api_url (overrides default value, if present)

# v0.2.3
##  06/22/2020

1. [](#new)
    * add Event Description as Tooltip

# v0.2.2
##  04/06/2020

1. [](#new)
    * automatically add CORS proxy URL for remote ics Files

# v0.2.1
##  02/17/2020

1. [](#new)
    * fix parsing long lines in ics File(s)
    * new config Option weekNumbers (show week Numbers in calendar grid)
    * fix BYDAY n+- Bug (bysetpos)

# v0.2.0
##  01/06/2020

1. [](#new)
    * update fullcalendar.io to V 4.3.1, including rrule Plugin
    * rewrite ICAL to fullcalendar.io Objects Mapping to support repeating Events (rrule)
    * new locale configuration Option
    
Repeating Events are now basically supported by incorporating the rrule Plugin from fullcalendar.io V 4.3.1.
There are, however, some rrule Options left, that are not yet supported - should work for most common rrules, though.
Additionaly, a new configuration Option has been introduced: locale - just set this to your Preferred value in fullcalendar.yaml.
Default is en.
Also note that file assets/custom.css has been renamed to daygrid.css - custom.css is for user adaptions and will also be used, if present (but is not provided with plugin) !

# v0.1.5
##  12/30/2019

1. [](#new)
    * allow custom colors for multiple calendars
    
Calendars can now be assigned custom colors for nicer display by adding an entry 'colors' to fullcalendar.yaml - this should be a comma-separated list of HTML
colors (predifined Names like lightBlue can be used as well as HEX notation, like #ffaabc).
If colors entry is not defined, standard fullcalendar.css color is used for all calendars, same behaviour as before.
In Addition, a Legend (html List, css class cal_legend) can be enabled by adding showlegend: true

# v0.1.4
##  07/30/2019

1. [](#new)
    * allow remote ics file URL's in shortcode, fix automatic locale evaluation

Calendar files can now also be remote, on other servers, these can also be mixed with local calendar files.
In this case, the complete absolute URL to the file(s) has to be provided in the shortcode, as well as enclosing quote signs (") around the whole icsfile= specification, like

    [fullcalendar icsfile="https://calendar.google.com/calendar/ical/myname%40myorganization.com/public/basic.ics" ][/fullcalendar]
    
As there have been multiple issues with remote Calendar Files not showing events as a result of [CORS](https://en.wikipedia.org/wiki/Cross-origin_resource_sharing) Policy restrictions,
an automatic addition of a [CORS Proxy URL](https://cors-anywhere.herokuapp.com/) has now been incorporated (from v 0.2.2)
    
# v0.1.3
##  07/25/2019

1. [](#new)
    * unpacked locales :)

# v0.1.2
##  07/17/2019

1. [](#new)
    * allow multiple ics Files in shortcode (comma separated)
    
As listed in the To Do section, the multiple calendar files option has now been implemented.
That means, you can now display events from more than one ics file.
just use a comma separated list of filenames in the shortcode, like

    [fullcalendar icsfile=example1.ics,example2.ics,example3.ics][/fullcalendar]

# v0.1.1
##  07/12/2019

1. [](#new)
    * add automatic month picture change upon prev/next/home click

The plugin will now change the displayed image when visitor clicks prev/next/home button. this will only work when picture files are named correctly according to active locale. for locale 'en' , this means 'January.jpg, February.jpg, March.jpg ....' for locale 'de' , this means 'Januar.jpg, Februar.jpg, März.jpg ....' and so on...

# v0.1.0
##  05/05/2019

1. [](#new)
    * Initial commit, ChangeLog started...

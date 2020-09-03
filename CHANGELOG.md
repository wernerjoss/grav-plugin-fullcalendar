# v0.3.1
  [](#improved)
    * taxonomy not required anymore, recognition based on template name #4

  [](#bugfix)
    * support modular and non modular page #5

# v0.3.0
## 02/09/2020 
 
 [](#new)
  * ics can be uploaded as page media files (no shortcodes anymore)
  * ics url files can be specified in yaml configuration (page header)
  * assets are a webpack project 
  * fine fullcalendar configuration mapped in yaml plugin configuration
  * responsive style (not tested on IE)
 
 [](#improved)
  * each calendar has its own configuration when specified in yaml
  * fullcalendar views : month (daygrid), week (timegrid), list (listgrid)
  * rrule support extended with ical-expander (EXDATE)
  * events are cached in local storage

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

# v0.1.5
##  12/30/2019

1. [](#new)
    * allow custom colors for multiple calendars

# v0.1.4
##  07/30/2019

1. [](#new)
    * allow remote ics file URL's in shortcode, fix automatic locale evaluation

# v0.1.3
##  07/25/2019

1. [](#new)
    * unpacked locales :)

# v0.1.2
##  07/17/2019

1. [](#new)
    * allow multiple ics Files in shortcode (comma separated)

# v0.1.1
##  07/12/2019

1. [](#new)
    * add automatic month picture change upon prev/next/home click

# v0.1.0
##  05/05/2019

1. [](#new)
    * Initial commit, ChangeLog started...

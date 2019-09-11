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

    [fullcalendar icsfile="https://calendar.google.com/calendar/ical/myname%40myorganization.com/public/basic.ics,example1.ics" ][/fullcalendar]

Also note that this feature (absolute URLs for ics files) is mandatory if you enable "include default language" in your system configuration.
Otherwise, local ics files will NOT be found, as the javascript getAbsolutePath() function will return an invalid path to local ics files in /user/data/calendars !
Additionally, using absolute URLs might result in running into (https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)[CORS Issues] if the ics files are on a remote server.
This can be resolved by allowing CORS access on the server (if access to the server is available) or by using some CORS proxy. see (https://stackoverflow.com/questions/43871637/no-access-control-allow-origin-header-is-present-on-the-requested-resource-whe/43881141#43881141)[this] discussion on stackoverflow.
## Credits

This Plugin is built on [fullcalendar.io](https://fullcalendar.io)

## To Do

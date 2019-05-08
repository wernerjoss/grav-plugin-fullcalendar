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

Once installed and enabled, you can use this Plugin to parse an ICS Calendar File (must be found in user/data/calendars and set as  parameter in Plugin shortcut, without Path !) and display Events from that Calendar anywhere on your Site using this shortcut

    [fullcalendar icsfile=example.ics][/fullcalendar]
    
in the appropriate page.
As an addition, you can show a Picture for the current month above the calendar widget.
Just put 12 Image Files named 'January.jpg', 'February.jpg', ... , 'December.jpg' in the Folder for your Page where the Calendar will be placed.

## Credits

This Plugin is built on [fullcalendar.io](https://fullcalendar.io)

## To Do

- [ ] process multiple Calendar (ICS) Files
- [ ] change Month Pic when user selects pev/next month :)

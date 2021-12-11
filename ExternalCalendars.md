This is a page that explains the use of external Calendars directly with the Fullcalendar Grav Plugin.
Currently, it contains some hints how to use Google Calendars.
Others might follow.

## Google calendar example (provided by [simplycomputing](https://github.com/simplycomputing))
One common usage case would be to link the Fullcalendar plugin to your Google calendar. The first step is to make your Google calendar public. To do this go to settings using the three dots at the end of your calendar name (hover to make them appear). 
![calendar1](https://user-images.githubusercontent.com/46998578/145481758-e7378864-285a-4168-815d-ae491e030b61.png)
Select "Settings and sharing". Scroll down the page and tick the box to "Make available to public".
![calendar2](https://user-images.githubusercontent.com/46998578/145481928-761df0c2-07dd-4c68-8793-924085af858a.png)
Now scroll to the bottom of that screen and find the "Public address in ical format". Copy that url.
![calendar3](https://user-images.githubusercontent.com/46998578/145482285-1226c7ff-8ccb-496b-afc9-7f5ea6770a62.png)
This address is used in the shortcode. For example:

`[fullcalendar icsfile="https://calendar.google.com/calendar/ical/xxxxxxxxx%40gmail.com/public/basic.ics"][/fullcalendar]`

Information from the Google calendar will now be displayed and will remain in sync with all changes. 

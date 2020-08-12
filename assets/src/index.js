require('ical-expander');
require('@fullcalendar/core');
require('@fullcalendar/daygrid');
require('@fullcalendar/interaction');
require('@fullcalendar/rrule');
require('store2');
require('superagent');
const IcalExpander = require('ical-expander');

import superagent from 'superagent';
import store from 'store2';
import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import rrulePlugin from '@fullcalendar/rrule';
//import IcalExpander from 'ical-expander';

document.addEventListener('DOMContentLoaded', function() {
  var verbose = GRAV.config.plugins.fullcalendar.verbose || false;
  //demo calendars
  var demoCalendars = GRAV.config.plugins.fullcalendar.calendars;
  var calendarHtmlTarget = GRAV.config.plugins.fullcalendar.fullcalendar.target || '#calendar';
  var calendarsConfig = [];
  var allevents = [];

  //var log = log.get('grav-plugin-fullcalendar');
  //init cache 
  if (!GRAV.config.system.debugger.enabled) {
    store.page("until", "reload");
  }

  //ics from page frontmatter 
  if(GRAV.page.header.calendars) {
    calendarsConfig = GRAV.page.header.calendars;
  }
  console.log(calendarsConfig);
  //ics files uploaded in calendar page
  if (GRAV.page.media) {
    let media = GRAV.page.media;
    media.forEach((file)=>{
        calendarsConfig.push({"ics": file.ics , "name": file.name, "active": true});
    });
  }

  //ics demo 
  if (!calendarsConfig) {
    calendarsConfig = demoCalendars;
  } 
  //@todo ics local file
  console.log(calendarsConfig);

  var showlegend = GRAV.config.plugins.fullcalendar.showlegend || false;

  // page is now ready, initialize the calendar...
  var calendarEl = document.querySelector(calendarHtmlTarget);
  var calendar = new Calendar(calendarEl, {

    /* Configuration */

    plugins: [ interactionPlugin, dayGridPlugin, rrulePlugin ],
    locale: GRAV.config.plugins.fullcalendar.locale || 'en',
    weekNumbers: GRAV.config.plugins.fullcalendar.weekNumbers || false,
    timeZone: GRAV.config.plugins.fullcalendar.timezone || 'local',
    headerToolbar: {
      right: 'dayGridMonth,dayGridWeek',
      left: 'prevYear,prev,next,nextYear today',
      center: 'title',
    },
    firstDay: 1,
    navLinks: false, // can click day/week names to navigate views
    editable: true,
    fixedWeekCount: false,
    contentHeight: 700,

    /* methods */

    dateClick: function(info) {
      console.log('DATE CLICKED='+ info.date.toString());
    },

    events: function(info, successCallback, failureCallback) {
      //load events from cache
      allevents = store.get('events') || [];
      if (allevents) {
        console.log('[FULLCALENDAR PLUGIN] events loaded from cache');
        successCallback(allevents);
        allevents=[];
      }

      calendarsConfig.forEach((calendarConfig, index)=> {
        if (!calendarConfig.active) {
          console.log("Calendar " + calendarConfig.name + " is inactive" );
          return;
        }
        var calendarUrl = ''+ calendarConfig.ics;
        //allow remote ics files, full URL required
        if (calendarUrl.startsWith("https://") || calendarUrl.startsWith("http://")) {  // calendar URL is remote
          //automatically add CORS proxy URL for remote calendars, if not yet done 06.04.20
          var origin = window.location.protocol + '//' + window.location.host;
          var cors_api_url = GRAV.config.plugins.fullcalendar.proxy;  // replace this if you prefer another CORS proxy !
          if (!calendarUrl.startsWith(origin)) {
            if (verbose) { 
              console.log('remote is different Origin, use proxy '+ cors_api_url);
            }
            calendarUrl = cors_api_url + calendarConfig.ics;
          }
        }
        if (verbose) {
          console.log( calendarConfig);
        }
        var events = [];
        var do_callback = false;
        if (index == (calendarsConfig.length - 1)) {
          do_callback = true;
        }
        if (verbose) {
          console.log('index,do_callback:', index, do_callback);
        }
        //@todo get rid of jquery
        var response = '';

        superagent.get(calendarUrl).end( (error, result) => {
          console.log('[grav-plugin-fullcalendar] loading ics file :' + calendarUrl);
          let data = new String(result.text);
          const icalExpander = new IcalExpander({ ics:data, maxIterations: 100});
          let events = icalExpander.all();
          console.log(events.events);
          let  mappedEvents = events.events.map(e => (
            { 
              start: e.startDate.toJSDate(), 
              end: e.endDate.toJSDate(),
              location: e.location,
              title: e.summary,
              uid: e.uid,
              description: e.description
            })
          );
          let mappedOccurrences = events.occurrences.map(o => (
            { 
              start: o.startDate.toJSDate(),
              end: o.endDate.toJSDate(),
              title: o.item.summary, 
              location: o.location,
              uid: o.uid,
              description: o.description
            })
          );
          events = [].concat(mappedEvents, mappedOccurrences);
          allevents = allevents.concat(events);

          if (do_callback) {
            console.log(allevents);
            successCallback(allevents);
            store.set('events', allevents);
            allevents=[];
          }

        });//endof superagent

        /* jQuery.get(calendarUrl, function(data) {
          var jcalData = ICAL.parse(data);
          var comp = new ICAL.Component(jcalData);
          var eventComps = comp.getAllSubcomponents("vevent");
          //  map them to FullCalendar events Objects
          events = jQuery.map(eventComps, function(item) {
            var fcevents = {};
            var entry = item.getFirstPropertyValue("summary");
            if (entry !== null) fcevents["title"] = entry;
            var entry = item.getFirstPropertyValue("location");
            if (entry !== null) fcevents["location"] = entry;
            var entry = item.getFirstPropertyValue("url");
            if (entry !== null) fcevents["url"] = entry;
            var entry = item.getFirstPropertyValue("dtstart");
            if (entry !== null) fcevents["start"] = entry.toJSDate();
            var entry = item.getFirstPropertyValue("dtend");
            if (entry !== null) fcevents["end"] = entry.toJSDate();
            var entry = item.getFirstPropertyValue("description");
            if (entry !== null) fcevents["description"] = entry;
            var entry = item.getFirstPropertyValue("uid");
            if (entry !== null) fcevents["uid"] = entry;

            // not used options go here

            var rrules = item.getFirstPropertyValue("rrule");
            var fcrrules = {};  // extra object for rrules
            if (rrules !== null)  {
              if (rrules.freq !== null) { //  freq is required, do not continue if null
                fcrrules["freq"] = rrules.freq;
                if (verbose) {
                  console.log('rrules:', rrules);
                }
                var parts = rrules["parts"];
                if (verbose) {
                  console.log('parts:', parts);
                }
                var byweekday = parts["BYDAY"];
                var weekdays = [];  // must be empty array, otherwise, push() will not work !
                var bysetpos = [];
                if (Array.isArray(byweekday)) {
                  byweekday = parts["BYDAY"];
                  for (i = 0; i < byweekday.length; i++) {
                    //  DONE: implement BYDAY n+ or n-
                    if (byweekday[i].match(/\d+/g)) { // entry contains digits, save them to setpos, strip from weekdays
                      var daynum = parseInt(byweekday[i]).toString();
                      bysetpos.push(daynum);
                      weekdays.push(byweekday[i].replace(/[0-9,+,-]/g, ''));
                    } else { weekdays.push(byweekday[i]); } // no digits, just save to weekdays
                  }
                  byweekday = weekdays;
                } else  {
                  byweekday = null;
                }
                if (verbose) {
                  console.log('byweekday:', byweekday);
                }
                var byweekno = parts["BYWEEKNO"];
                if (Array.isArray(byweekno))  {byweekno = parts["BYWEEKNO"];} else  {byweekno = null;}
                if (verbose) {
                  console.log('byweekno:', byweekno);
                }
                var bymonth = parts["BYMONTH"];
                if (Array.isArray(bymonth)) {bymonth = parts["BYMONTH"];} else  {bymonth = null;}
                if (verbose)  {
                  console.log('bymonth:', bymonth);
                }
                var bymonthday = parts["BYMONTHDAY"];
                if (Array.isArray(bymonthday))  {bymonthday = parts["BYMONTHDAY"];} else  {bymonthday = null;}
                if (verbose)  { 
                  console.log('bymonthday:', bymonthday);
                }
                var byyearday = parts["BYYEARDAY"];
                if (Array.isArray(byyearday)) {byyearday = parts["BYYEARDAY"];} else  {byyearday = null;}
                if (verbose)  console.log('byyearday:', byyearday);
                if (rrules.dtstart !== undefined) {
                  fcrrules["dtstart"] = rrules.dtstart;
                } else  {
                  fcrrules["dtstart"] = fcevents["start"];
                }
                if (byweekday !== null) { fcrrules["byweekday"] = byweekday;}
                if (bysetpos !== null) { fcrrules["bysetpos"] = bysetpos;}
                if (byweekno !== null) { fcrrules["byweekno"] = byweekno;}
                if (bymonth !== null) { fcrrules["bymonth"] = bymonth;}
                if (bymonthday !== null) { fcrrules["bymonthday"] = bymonthday;}
                if (byyearday !== null) { fcrrules["byyearday"] = byyearday;}
                if (rrules.interval != null) { fcrrules["interval"] = rrules.interval;}
                if (rrules.count != null) { fcrrules["count"] = rrules.count;}
                if (rrules.wkst != null) { fcrrules["wkst"] = rrules.wkst;}
                if (rrules.until != null) { fcrrules["until"] = rrules.until.toJSDate();}
                fcevents["rrule"] = fcrrules;
                if (verbose)  {
                  console.log('fcrrules:', fcrrules);
                }
              }
            }
            fcevents["backgroundColor"] = calendarConfig.color;
            if (verbose)  { 
              console.log('fcevents:', fcevents);
            }
            if (item.getFirstPropertyValue("class") === "PRIVATE") {
              return null;
            } else {
              return fcevents;
            }
          })
          jQuery.merge(allevents, events);
          if (verbose) {
            console.log('index,do_callback:', index, do_callback);
            console.log('events:', events);
          }
          if (do_callback) {
            successCallback(allevents);
            store.set('events', allevents);
            if (verbose) {
              console.log('allevents:', allevents);
            }
            allevents=[];
          }
        },
          'text');
          */
      })
    }
  });
  console.log(calendar);
  calendar.render();
  // show legend, if enabled
  if (showlegend) {
    // Add the contents of cfgfiles to #legend:
    document.getElementById('legend').appendChild(makeUL(cfgfiles, colors));
  }
})

function makeUL(array, colors) {
  // Create the list element:
  var list = document.createElement('ul');
  // assign css class
  list.classList.add('cal_legend');
  for (var i = 0; i < array.length; i++) {
    // Create the list item:
    var item = document.createElement('li');

    // Set its contents:
    item.appendChild(document.createTextNode(array[i]));
    item.style.color = colors[i];

    // Add it to the list:
    list.appendChild(item);
  }
  // Finally, return the constructed list:
  return list;
}

//@todo return prefix configured in grav cms
function getAbsolutePath() { // see https://www.sitepoint.com/jquery-current-page-url/
  var loc = window.location;
  var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
  return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}



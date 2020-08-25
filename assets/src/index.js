require('ical-expander');
require('@fullcalendar/core');
require('@fullcalendar/daygrid');
require('@fullcalendar/interaction');
require('@fullcalendar/rrule');
require('store2');
require('superagent');
const IcalExpander = require('ical-expander');
require('@popperjs/core');

import {createPopper} from '@popperjs/core';
import superagent from 'superagent';
import store from 'store2';
import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import rrulePlugin from '@fullcalendar/rrule';


document.addEventListener('DOMContentLoaded', function() {
  var GRAV_PLUGIN_CONFIG = GRAV.config.plugins.fullcalendar;
  var verbose = GRAV.config.system.debugger.enabled || false;
  //demo calendars
  var demoCalendars = GRAV_PLUGIN_CONFIG.calendars;
  var calendarHtmlTarget = GRAV_PLUGIN_CONFIG.fullcalendar.target || '#calendar';
  var calendarsConfig = [];
  var allevents = [];

  //init cache 
  store.remove('events');

  //ics from page frontmatter 
  if(GRAV.page.header.calendars) {
    calendarsConfig = GRAV.page.header.calendars;
  }

  //ics files uploaded in calendar page
  if (GRAV.page.media) {
    let media = GRAV.page.media;
    media.forEach((file, id)=>{
        calendarsConfig.push({"ics": file.ics , "name": file.name, "active": true, color: GRAV_PLUGIN_CONFIG.colors[id] });
    });
  }

  //ics demo 
  if (!calendarsConfig) {
    calendarsConfig = demoCalendars;
  } 

  var showlegend = GRAV_PLUGIN_CONFIG.showlegend || false;

  // page is now ready, initialize the calendar...
  var calendarEl = document.querySelector(calendarHtmlTarget);
  var calendar = new Calendar(calendarEl, {

    /* Configuration */

    plugins: [ interactionPlugin, dayGridPlugin, rrulePlugin ],
    locale: GRAV_PLUGIN_CONFIG.fullcalendar.locale || 'en',
    weekNumbers: GRAV_PLUGIN_CONFIG.fullcalendar.weekNumbers || false,
    timeZone: GRAV_PLUGIN_CONFIG.fullcalendar.timezone || 'local',
    headerToolbar: {
      right: 'dayGridMonth,dayGridWeek',
      left: 'prevYear,prev,next,nextYear today',
      center: 'title',
    },
    firstDay: 1,
    navLinks: GRAV_PLUGIN_CONFIG.fullcalendar.navLinks || false,
    editable: GRAV_PLUGIN_CONFIG.fullcalendar.editable || false,
    fixedWeekCount: GRAV_PLUGIN_CONFIG.fullcalendar.fixedWeekCount || false,
    contentHeight: GRAV_PLUGIN_CONFIG.fullcalendar.contentHeight || 'auto',

    /* methods */

    eventDidMount: function(info) {
          let tooltip = document.createElement('div');
          tooltip.className = 'tooltip';
          tooltip.setAttribute('role', 'tooltip');
          tooltip.innerHTML = info.event.title + ' ' + info.event.description;
          info.el.appendChild(tooltip);
       },

    eventClick: function(info) {
      info.jsEvent.preventDefault();
      //createPopper(info.el, info.el.getElementsByClassName('tooltip')[0], { placement: 'right' });
      //console.log('Event: ' + info.event.title + 'location:' + info.event.location);
      let tooltip = info.el.getElementsByClassName('tooltip')[0];
      createPopper(info.el, tooltip, { placement: 'right' });
    },

    events: function(info, successCallback, failureCallback) {
      //load events from cache
      allevents = store.get('events') || [];
      if (allevents != null && allevents.length > 0) {
        console.log('[FULLCALENDAR PLUGIN] events loaded from cache');
        successCallback(allevents);
        allevents=[];
        return;
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
          var cors_api_url = GRAV_PLUGIN_CONFIG.proxy;  // replace this if you prefer another CORS proxy !
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

        superagent.get(calendarUrl).end( (error, result) => {
          console.log('[grav-plugin-fullcalendar] loading ics file :' + calendarUrl);
          let data = new String(result.text);
          const icalExpander = new IcalExpander({ ics:data, maxIterations: 100});
          let events = icalExpander.all();
          let  mappedEvents = events.events.map(e => (
            { 
              start: e.startDate.toJSDate(), 
              end: e.endDate.toJSDate(),
              location: e.location,
              title: e.summary,
              uid: e.uid,
              description: e.description,
							url: e.url
            })
          );
          let mappedOccurrences = events.occurrences.map(o => (
            { 
              start: o.startDate.toJSDate(),
              end: o.endDate.toJSDate(),
              title: o.item.summary, 
              location: o.location,
              uid: o.uid,
              description: o.description,
							url: o.url
            })
          );
          events = [].concat(mappedEvents, mappedOccurrences);
          allevents = allevents.concat(events);

          if (do_callback) {
            successCallback(allevents);
            store.set('events', allevents);
            allevents=[];
          }

        });//endof superagent
      })//endof foreach
    }//endof events

    /* other callbacks */ 

  });//endof new Calendar

  calendar.render();

  // show legend, if enabled
  if (showlegend) {
    // Add the contents of cfgfiles to #legend:
    document.getElementById('legend').appendChild(makeUL(cfgfiles, colors));
  }

}); //endof eventlistener DOMLoaded

/* utils */
//@todo refactor

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



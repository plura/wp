<?php header("Content-type: text/xml"); ?>
<?php print "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; ?>
<Module>
   <ModulePrefs title="Google Calendar"
                description="Google Calendar for your site, webpage or blog"
                author="Michael B."
                author_email="michael.feedback@gmail.com"
                author_affiliation="Google Inc."
                author_location="Palo Alto, CA"
                title_url="http://www.google.com/calendar/"
                height="450"
                screenshot="/ig/modules/calendar.png"
                thumbnail="/ig/modules/calendar-thm.png"
                category="communication"
                category2="tools"
                >
    <Locale lang="en" country="us" />
  </ModulePrefs>
  <UserPref name="showCalendar2"
            display_name="Display calendar:"
            datatype="bool"
            default_value="true"
            />

  <UserPref name="showAgenda"
            display_name="Agenda displayed"
            datatype="bool"
            default_value="true"
            />

  <UserPref name="calendarFeeds"
            display_name="calendarFeeds"
            datatype="hidden"
            default_value="({})"
            />
  <UserPref name="firstDay"
            display_name="First day of week:"
            default_value="Sunday"
            datatype="enum"
            >

    <EnumValue value="Sunday" />
    <EnumValue value="Monday" />
    <EnumValue value="Saturday" />
  </UserPref>

  <UserPref name="syndicatable"
            display_name="Indicates whether this is a syndicatable gadget"
            datatype="hidden"
            default_value="true"
            />
  <UserPref name="stylesheet"
            display_name="Stylesheet URL:"
            />
  <UserPref name="sub"
            display_name="Show &quot;Subscribe&quot; links:"
            datatype="bool"
            default_value="true"
            />
  <UserPref name="c0u"
            display_name="Calendar 1 URL:"
            />

  <UserPref name="c0c"
            display_name="Calendar 1 Color:"
            />
  <UserPref name="c1u"
            display_name="Calendar 2 URL:"
            />
  <UserPref name="c1c"
            display_name="Calendar 2 Color:"
            />
  <UserPref name="c2u"
            display_name="Calendar 3 URL:"
            />
  <UserPref name="c2c"
            display_name="Calendar 3 Color:"
            />
  <UserPref name="c3u"
            display_name="Calendar 4 URL:"
            />
  <UserPref name="c3c"
            display_name="Calendar 4 Color:"
            />
  <UserPref name="min"
            display_name="Minimum number of events to display:"
            />
  <UserPref name="start"
            display_name="First date YYYY-MM-DD (defaults to the current day):"
            />

  <UserPref name="timeFormat"
            display_name="Time format:"
            default_value="1:00pm"
            datatype="enum"
            >
    <EnumValue value="1:00pm" />
    <EnumValue value="13:00" />
  </UserPref>
  <UserPref name="calendarFeedsImported"
            display_name="calendarFeedsImported"
            datatype="hidden"
            default_value="0"
            />
  <Content type="html"><![CDATA[

<script>
new function() {
var prefs = new _IG_Prefs(__MODULE_ID__);
var isSyndicated = (prefs.getString('syndicatable') == 'true');

if (isSyndicated) {
  // syndicated version of the gadget may link to an external stylesheet
  var stylesheet = prefs.getString('stylesheet');
  if (stylesheet) {
    var linkEl = document.createElement('link');
    linkEl.setAttribute('rel', 'STYLESHEET');
    linkEl.setAttribute('href', stylesheet);
    document.getElementsByTagName('head')[0].appendChild(linkEl);
  }
}
}
</script>

<center>

<span id=msgtable__MODULE_ID__ style="display:none">
<table style="background-color:rgb(250,209,99); margin: 4px 0;" cellpadding=0 cellspacing=0>
<tr>
 <td class="tl evhtml"></td>
 <td rowspan="2" style="padding: 2px 4px" id="msgarea__MODULE_ID__"></td>
 <td class="tr evhtml"></td>
</tr>
<tr>
 <td class="bl evhtml"></td>
 <td class="br evhtml"></td>
</tr>
</table>
<p></p>
</span>

<table id="pickerContainer__MODULE_ID__" cellpadding=0 cellspacing=0
  class="pickerContainerTable"
  style="background-color:#c3d9ff;margin-top:4px">
<tr style="height:2px"><td class="tl dphtml"><td><td class="tr dphtml"></tr>
<tr><td><td style="width:13em"><div id="picker__MODULE_ID__">
  <table cellspacing=0 cellpadding=0 border=0 height=160 width=100%><tr><td>
   <center>
     <i><font size=-1 color=#666666>
        <div>Loading...</div>
     </font></i>
   </center>
  </td></tr></table>
</div><td></tr>
<tr style="height:2px"><td class="bl dphtml"><td><td class="br dphtml"></tr>
</table>

<script>
if (!(new _IG_Prefs(__MODULE_ID__)).getBool('showCalendar2')) {
  _gel('pickerContainer' + __MODULE_ID__).style.display = 'none';
}
</script>

<div id="createEventLinks__MODULE_ID__"
     style="display:none; font-size: 70%; padding-top: 1px">
<span class=fakelink id="open-quick-add__MODULE_ID__">Quick Add</span>&nbsp;&nbsp;<a
   href="/calendar/event?action=TEMPLATE" id="createEvent__MODULE_ID__"
   target="_BLANK" style="color:rgb(0,0,204)"
   title="Opens the Create Event form in Google Calendar in a new window"
>Create Event</a>&nbsp;&nbsp;<span class=fakelink id=toggleA__MODULE_ID__
 style="color:rgb(119,119,204)"></span>
<img id=calListPopup__MODULE_ID__
  style="vertical-align: text-bottom; cursor: pointer"
  title="Select which calendars you would like to display">
</div>

</center>

<div id="agenda__MODULE_ID__" style="display: none">
  <div id="agendaTable__MODULE_ID__"></div>
</div>

<div id="webContentDiv__MODULE_ID__"
     style="display:none; position:absolute;
            background-color: white; border: 1px solid gray;
     font-size: 83%">
  <div>
   <div style="background-color: rgb(232, 238, 247); padding-bottom: 2px">
    <img src="http://www.google.com/calendar/images/close.gif"
         id="webContentDivCloseBox__MODULE_ID__"
         style="float:right;margin:2px 2px 0 0;cursor:pointer">
    <div id="webContentDivTitle__MODULE_ID__" style="padding-left: 2px; font-weight: bold"></div>
   </div>
   <div id="webContentDivBody__MODULE_ID__"></div>
  </div>
</div>

<div id="agendaDiv__MODULE_ID__"
     style="display:none; position:absolute; width: 20em;
            background-color: white; border: 1px solid gray;
     font-size: 83%">
  <div>
   <div style="background-color: rgb(232, 238, 247); padding-bottom: 2px">
    <img src="http://www.google.com/calendar/images/close.gif"
         id="agendaCloseBox__MODULE_ID__"
         style="float:right;margin:2px 2px 0 0;cursor:pointer">
    <div id="agendaDivTitle__MODULE_ID__" style="padding-left: 2px; font-weight: bold"></div>
   </div>
   <form id="datedQuickAdd__MODULE_ID__">
   <INPUT type="HIDDEN" name="date">
   <table style="font-size: 83%; width: 100%">
     <tr style="width:100%">
       <td style="width:100%">
         <INPUT id="datedInput__MODULE_ID__" name=quickadd style="width:100%">
       </td>
       <td><INPUT type="SUBMIT" value="Add"></td>
     </tr>
     </table>
   </form>
   <div id="agendaDivBody__MODULE_ID__"></div>
  </div>
</div>

<div id="subscribe__MODULE_ID__" style="display:none" class="subscribeLinks">
</div>

<iframe name="GoogleCalendarFrame__MODULE_ID__" style="display:none"
        src="about:blank" width="0px" height="0px"></iframe>


<style>
<?php  include("gcalendar.css");  ?>
</style>
<script src="/ig/modules/calendar_content/calendar.js"></script>
<script>
if (navigator.userAgent.toLowerCase().indexOf('msie') != -1) {
  document.body.onload = function() { new _CalendarModule(__MODULE_ID__); };
} else {
  new _CalendarModule(__MODULE_ID__);
}
</script>
  ]]></Content>
</Module>

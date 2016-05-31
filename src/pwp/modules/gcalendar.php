<!-- GCALENDAR -->
<?php
	if(!isset($pwp_gcalendar_w)) 		$pwp_gcalendar_w		= 200; 
	if(!isset($pwp_gcalendar_h)) 		$pwp_gcalendar_h		= 300;
	if(!isset($pwp_gcalendar_url))		$pwp_gcalendar_xml		= "http://www.joaquimraposo.net/wp/wp-content/themes/plura_www_joaquimraposo_net/_content/calendar.php";
	if(isset($pwp_gcalendar_feed))		$pwp_gcalendar_feed		= urlencode($pwp_gcalendar_feed); else $pwp_gcalendar_feed = "";
	if(!isset($pwp_gcalendar_border))	$pwp_gcalendar_border	= "";		//"%23ffffff%7C3px%2C1px+solid+%23999999";
	if(!isset($pwp_gcalendar_title))	$pwp_gcalendar_title	= "";		//Google+Calendar
	
	if(isset($pwp_gcalendar_bgcolor))	$pwp_gcalendar_bgcolor	= "&amp;bgcolor=" . $pwp_gcalendar_bgcolor; else $pwp_gcalendar_bgcolor = "";
	
	
	$pwp_gcalendar_vars	= "&amp;w=$pwp_gcalendar_w&amp;h=$pwp_gcalendar_h&amp;up_c0u=$pwp_gcalendar_feed&amp;border=$pwp_gcalendar_border&amp;title=$pwp_gcalendar_title";
?>
<!--
<script src="http://www.gmodules.com/ig/ifr?url=<?php print $pwp_gcalendar_url; ?>&amp;up_showCalendar2=1&amp;up_showAgenda=1&amp;up_calendarFeeds=(%7B%7D)&amp;up_firstDay=Sunday&amp;up_syndicatable=true&amp;up_stylesheet=&amp;up_sub=1&amp;up_c0c=&amp;up_c1u=&amp;up_c1c=&amp;up_c2u=&amp;up_c2c=&amp;up_c3u=&amp;up_c3c=&amp;up_min=&amp;up_start=&amp;up_timeFormat=1%3A00pm&amp;up_calendarFeedsImported=0&amp;synd=open<?php print $pwp_gcalendar_vars; ?>&amp;output=js"></script>
-->
<script src="http://www.gmodules.com/ig/ifr?url=http://www.google.com/ig/modules/calendar3.xml&amp;up_calendarFeeds=&amp;up_calendarColors=&amp;up_firstDay=0&amp;up_dateFormat=0&amp;up_timeFormat=13%3A00&amp;up_showDatepicker=1&amp;up_hideAgenda=0&amp;up_showEmptyDays=0&amp;up_showExpiredEvents=1&amp;synd=open&amp;w=240&amp;h=270&amp;title=__MSG_Google_Calendar__&amp;lang=pt-PT&amp;country=ALL&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js"></script>
<!-- GCALENDAR -->
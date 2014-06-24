<?php

function thb_weather()
{
  $rtn           = "";
  $weather_data  = array();
  $location      = ot_get_option('weather_city', 'Istanbul');
  $units         = ot_get_option('weather_units', 'metric');
  $units_display = $units == "metric" ? "C" : "F";
  $speed_text    = ($units == "metric") ? "km/h" : "mph";


  // NO LOCATION, ABORT ABORT!!!1!
  if(!$location)
  {
    return false;
  }


  //FIND AND CACHE CITY ID
  $city_name_slug         = sanitize_title($location);
  $weather_transient_name = 'awesome-weather-'.$units.'-'.$city_name_slug;


  // TWO APIS USED (VERSION 2.5)
  //http://api.openweathermap.org/data/2.5/weather?q=London,uk&units=metric&cnt=7&lang=fr
  //http://api.openweathermap.org/data/2.5/forecast/daily?q=London&units=metric&cnt=7&lang=fr
  // GET WEATHER DATA
  if(get_transient($weather_transient_name))
  {
    $weather_data = get_transient($weather_transient_name);
  }
  else
  {
    // NOW
    $now_ping     = "http://api.openweathermap.org/data/2.5/weather?q=".$city_name_slug."&units=".$units;
    $now_ping_get = wp_remote_get($now_ping);

    if(is_wp_error($now_ping_get))
    {
      return 'WP Error';
    }

    $city_data = json_decode($now_ping_get['body']);

    if(isset($city_data->cod) AND $city_data->cod == 404)
    {
      return 'No City Found';
    }
    else
    {
      $weather_data['now'] = $city_data;
    }


    if($weather_data['now'])
    {
      // SET THE TRANSIENT, CACHE FOR AN HOUR
      set_transient($weather_transient_name, $weather_data, apply_filters('awesome_weather_cache', 3600));
    }
  }



  // NO WEATHER
  if(!$weather_data OR ! isset($weather_data['now']))
  {
    return false;
  }


  // TODAYS TEMPS
  $today         = $weather_data['now'];
  $today_temp    = round($today->main->temp);
  $today_weather = $today->weather[0]->main;

  $today->main->humidity = round($today->main->humidity);
  $today->wind->speed    = round($today->wind->speed);

  $icon = '<i class="wi-day-sunny-overcast"></i>';
  if($today_weather == 'sky is clear')
    $icon = '<i class="wi-day-sunny"></i>';
  if($today_weather == 'few clouds')
    $icon = '<i class="wi-day-sunny-overcast"></i>';
  if($today_weather == 'scattered clouds')
    $icon = '<i class="wi-day-cloudy"></i>';
  if($today_weather == 'broken clouds')
    $icon = '<i class="wi-cloudy"></i>';
  if($today_weather == 'shower rain')
    $icon = '<i class="wi-showers"></i>';
  if($today_weather == 'Rain')
    $icon = '<i class="wi-rain"></i>';
  if($today_weather == 'Thunderstorm')
    $icon = '<i class="wi-thunderstorm"></i>';
  if($today_weather == 'snow')
    $icon = '<i class="wi-snow"></i>';
  if($today_weather == 'mist')
    $icon = '<i class="wi-day-fog"></i>';
  ?>
  <div id="weather">
    <div class="icon">
      <?php echo $icon; ?>
    </div>
    <div class="info">
      <p>
        <strong><?php echo $location; ?></strong><br>
        <span class="temperature"><b><?php echo $today_temp.'&deg; '.$units_display; ?></b> <?php echo '('.$today->weather[0]->description.')'; ?></span><br>
        <span class="additional"><?php echo '<i class="fa fa-tint"></i>'.$today->main->humidity; ?> <?php echo '<i class="wi-strong-wind"></i>'.$today->wind->speed.' '.$speed_text; ?> </span>
      </p>
    </div>
  </div>
  <?php
}

add_action('thb_weather', 'thb_weather');

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $base_uri = ['base_uri' => 'https://iot.amtiss.com/api/'];

    public function index(Request $request)
    {
        // $apiKey = '$2y$10$2gJIIFwj46G439xxYwhXI.NG2rYKNNPw4ZfVunzj6IjvONIe4ef/e';
        // $apiKey = '$2y$10$ItabZwhsQCvOO1SvekKnluZ5GY7Xa0N15GlXdxDj22oDJD884Tq7S';
        $apiKey = $request['user_api_hash'];

        $client = new Client($this->base_uri);
        $get_devices = $client->request('GET', "get_devices?lang=en&user_api_hash={$apiKey}");
        $devices = json_decode($get_devices->getBody()->getContents(), true);
        $data['summary'] = [
            'total' => 0,
            'active' => 0,
            'idle' => 0,
            'stop' => 0,
            'offline' => 0,
        ];
        for ($i = 0; $i < count($devices[0]['items']); $i++) {
            $device_items = $devices[0]['items'][$i];
            $device_data_sensors = $device_items['device_data']['sensors'];

            $data['devices'][$i] = [
                'id' => $device_items['id'],
                'name' => $device_items['name'],
                'online' => $device_items['online'],
                'icon_color' => $device_items['icon_color'],
                'sensors' => [
                    'odometer' => [
                        'id' => $this->getValueByName($device_data_sensors, 'Odometer', 'id'),
                        'tag_name' => $this->getValueByName($device_data_sensors, 'Odometer', 'tag_name'),
                    ],
                    'hour_meter' => [
                        'id' => $this->getValueByName($device_data_sensors, 'Hour Meter', 'id'),
                        'tag_name' => $this->getValueByName($device_data_sensors, 'Hour Meter', 'tag_name'),
                    ],
                    'fuel_consumed' => [
                        'id' => $this->getValueByName($device_data_sensors, 'Fuel Consumed', 'id'),
                        'tag_name' => $this->getValueByName($device_data_sensors, 'Fuel Consumed', 'tag_name'),
                    ],
                    'engine_temperature' => [
                        'id' => $this->getValueByName($device_data_sensors, 'Engine Temprature', 'id'),
                        'tag_name' => $this->getValueByName($device_data_sensors, 'Engine Temprature', 'tag_name'),
                    ],
                ],
            ];
            $data['summary']['total'] += 1;
            $data['summary']['active'] += $device_items['icon_color'] == 'green' ? 1 : 0;
            $data['summary']['idle'] += $device_items['icon_color'] == 'yellow' ? 1 : 0;
            $data['summary']['stop'] += $device_items['icon_color'] == 'black' ? 1 : 0;
            $data['summary']['offline'] += $device_items['icon_color'] == 'red' ? 1 : 0;
        }
        $date_end = Carbon::parse($request['date_end']);
        $date_start = Carbon::parse($request['date_start']);
        // $get_diff_day = 4;
        $get_diff_day = $date_start->diffInDays($date_end);
        for ($i = $get_diff_day; $i >= 0; $i--) {
            // $dates = Carbon::createFromTimestamp(Carbon::today()->subDays($i)->timestamp)->format('Y-m-d');
            // $dates2 = Carbon::createFromTimestamp(Carbon::today()->subDays($i)->timestamp)->format('d-M-y');
            $dates = Carbon::createFromTimestamp(Carbon::parse($request['date_end'])->subDays($i)->timestamp)->format('Y-m-d');
            $dates2 = Carbon::createFromTimestamp(Carbon::parse($request['date_end'])->subDays($i)->timestamp)->format('d-M-y');
            $data['date2'][] = $dates2;
            $data['date'][] = $dates;
        }
        for ($j = 0; $j < count($data['devices']); $j++) {
            $device = $data['devices'][$j];
            $device_id = $device['id'];
            $sensor_odometer_id = $device['sensors']['odometer']['id'];
            $sensor_odometer_tagname = $device['sensors']['odometer']['tag_name'];
            $sensor_hour_meter_id = $device['sensors']['hour_meter']['id'];
            $sensor_hour_meter_tagname = $device['sensors']['hour_meter']['tag_name'];
            $sensor_fuel_consumed_id = $device['sensors']['fuel_consumed']['id'];
            $sensor_fuel_consumed_tagname = $device['sensors']['fuel_consumed']['tag_name'];
            $sensor_engine_temperature_id = $device['sensors']['engine_temperature']['id'];
            $sensor_engine_temperature_tagname = $device['sensors']['engine_temperature']['tag_name'];

            for ($k = 0; $k < count($data['date']); $k++) {
                $date = $data['date'][$k];
                $device_name = $data['devices'][$j]['name'];
                $history_query = "device_id={$device_id}&from_date={$date}&to_date={$date}&from_time=00:01&to_time=23:59&limit=50&lang=en&user_api_hash={$apiKey}";
                $get_history_messages = $client->request('GET', "get_history_messages?page=1&{$history_query}");
                $history_messages = json_decode($get_history_messages->getBody()->getContents(), true);
                $last_page = $history_messages['messages']['last_page'];

                $history_messages_last_data = end($history_messages['messages']['data']);

                $data['odometer']['data_set'][$j]['label'] = $device_name;
                $data['odometer']['data_set'][$j]['chartData'][$k] = $this->getSensorData($history_messages_last_data, $sensor_odometer_tagname, $sensor_odometer_id, 'odometer');
                $data['odometer']['data_set'][$j]['chartData_increment'][$k] =
                $k == 0 || $data['odometer']['data_set'][$j]['chartData'][$k] == null ? 0 : $this->getDiff($data['odometer']['data_set'][$j]['chartData'], $k);

                $data['hour_meter']['data_set'][$j]['label'] = $device_name;
                $data['hour_meter']['data_set'][$j]['chartData'][$k] = $this->getSensorData($history_messages_last_data, $sensor_hour_meter_tagname, $sensor_hour_meter_id, 'hour_meter');
                $data['hour_meter']['data_set'][$j]['chartData_increment'][$k] =
                $k == 0 || $data['hour_meter']['data_set'][$j]['chartData'][$k] == null ? 0 : $this->getDiff($data['hour_meter']['data_set'][$j]['chartData'], $k, 'hour_meter');

                $data['fuel_consumed']['data_set'][$j]['label'] = $device_name;
                $data['fuel_consumed']['data_set'][$j]['chartData'][$k] = $this->getSensorData($history_messages_last_data, $sensor_fuel_consumed_tagname, $sensor_fuel_consumed_id, 'fuel_consumed');
                $data['fuel_consumed']['data_set'][$j]['chartData_increment'][$k] =
                $k == 0 || $data['fuel_consumed']['data_set'][$j]['chartData'][$k] == null ? 0 : $this->getDiff($data['fuel_consumed']['data_set'][$j]['chartData'], $k);

                $data['engine_temperature']['data_set'][$j]['label'] = $device_name;
                $data['engine_temperature']['data_set'][$j]['chartData'][$k] = $this->getSensorData($history_messages_last_data, $sensor_engine_temperature_tagname, $sensor_engine_temperature_id, 'engine_temperature');

                if ($k == count($data['date']) - 1) {

                    $get_history_messages_last_page = $client->request('GET', "get_history_messages?page={$last_page}&{$history_query}");
                    $history_messages_last_page = json_decode($get_history_messages_last_page->getBody()->getContents(), true);
                    $history_messages_last_page_last_data = end($history_messages_last_page['messages']['data']);

                    $data['odometer']['data_set'][$j]['chartData'][$k + 1] = $this->getSensorData($history_messages_last_page_last_data, $sensor_odometer_tagname, $sensor_odometer_id, 'odometer');
                    $data['odometer']['data_set'][$j]['chartData_increment'][$k + 1] =
                    $k + 1 == 0 || $data['odometer']['data_set'][$j]['chartData'][$k + 1] == null ? 0 : $this->getDiff($data['odometer']['data_set'][$j]['chartData'], $k + 1);

                    $data['hour_meter']['data_set'][$j]['chartData'][$k + 1] = $this->getSensorData($history_messages_last_page_last_data, $sensor_hour_meter_tagname, $sensor_hour_meter_id, 'hour_meter');
                    $data['hour_meter']['data_set'][$j]['chartData_increment'][$k + 1] =
                    $k + 1 == 0 || $data['hour_meter']['data_set'][$j]['chartData'][$k + 1] == null ? 0 : $this->getDiff($data['hour_meter']['data_set'][$j]['chartData'], $k + 1, 'hour_meter');

                    $data['fuel_consumed']['data_set'][$j]['chartData'][$k + 1] = $this->getSensorData($history_messages_last_page_last_data, $sensor_fuel_consumed_tagname, $sensor_fuel_consumed_id, 'fuel_consumed');
                    $data['fuel_consumed']['data_set'][$j]['chartData_increment'][$k + 1] =
                    $k + 1 == 0 || $data['fuel_consumed']['data_set'][$j]['chartData'][$k + 1] == null ? 0 : $this->getDiff($data['fuel_consumed']['data_set'][$j]['chartData'], $k + 1);

                    $data['engine_temperature']['data_set'][$j]['chartData'][$k + 1] = $this->getSensorData($history_messages_last_page_last_data, $sensor_engine_temperature_tagname, $sensor_engine_temperature_id, 'engine_temperature');
                    array_shift($data['odometer']['data_set'][$j]['chartData']);
                    array_shift($data['hour_meter']['data_set'][$j]['chartData']);
                    array_shift($data['fuel_consumed']['data_set'][$j]['chartData']);
                    array_shift($data['engine_temperature']['data_set'][$j]['chartData']);
                    array_shift($data['odometer']['data_set'][$j]['chartData_increment']);
                    array_shift($data['hour_meter']['data_set'][$j]['chartData_increment']);
                    array_shift($data['fuel_consumed']['data_set'][$j]['chartData_increment']);

                }

            }

        }

        return $data;
    }
    public function getDiff($array, $index, $sensor = null)
    {

        $data = $array[$index] - $array[$index - 1];
        if ($sensor == 'hour_meter') {
            $data = $data > (1440) ? $data / 60 / 60 : $data / 60;
        }
        return (float) number_format($data, 2, ".", "");

    }
    public function getSensorData($array, $tag_name, $sensor_id, $sensor)
    {
        // $data = $this->getSensorDataByTagName($array, $tag_name) ?? $this->getSensorDataById($array, $sensor_id);
        $data = $this->formula($this->getvalue($this->getSensorDataByTagName($array, $tag_name)), $sensor);
        // $data = is_null($this->getSensorDataByTagName($array, $tag_name)) ? $this->getvalue($this->getSensorDataById($array, $sensor_id)) : $this->formula($this->getValue($this->getSensorDataByTagName($array, $tag_name)), $sensor);
        // $data = $this->getSensorDataById($array, $sensor_id);
        return $data;
    }

    public function getSensorDataById($array, $sensor_id)
    {
        $data = empty($array) ? null : (isset($array['sensors_value'][$sensor_id]) ? $array['sensors_value'][$sensor_id] : null);
        return $data;

    }
    public function getSensorDataByTagName($array, $tag_name)
    {
        $data = empty($array) ? null : (!$tag_name ? null : (isset($array['other_array'][$tag_name]) ? $array['other_array'][$tag_name] : null));
        return $data;

    }
    public function getValue($value)
    {
        $array = explode(" ", $value);
        $data = (int) $array[0];
        return $data;
    }
    public function formula($value, $sensor)
    {
        $data = null;
        if ($sensor == 'odometer') {
            $data = $value / 1000;
        }
        if ($sensor == 'hour_meter') {
            $first_data = $value;
            //check if value is > 24, it means given value data in second
            $data = $first_data;
        }
        if ($sensor == 'fuel_consumed') {
            $data = $value;
        }
        if ($sensor == 'engine_temperature') {
            $data = $value / 10;
        }
        return $data;
    }
    public function getValueByName($array, $name, $key)
    {
        $data = null;
        foreach ($array as $v) {
            if ($v['name'] == $name) {
                $data = $v[$key];
            }
        }
        return $data;
    }
    public function getSensorId($array, $value)
    {
        $data = null;
        foreach ($array as $v) {
            if ($v['name'] == $value) {
                $data = $v['id'];
            }
        }
        return $data;
    }

}

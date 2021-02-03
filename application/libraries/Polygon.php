<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polygon
{	

	public $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

	function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;

        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }

        // Check if the lat lng sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return 1;
        }

        // Check if the lat lng is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);

        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return 1;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if lat lng is on the polygon boundary (other than horizontal)
                    return 1;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }

    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

    function check(){
        $points = array("18.553856535216426 73.94766334444284","18.552681 73.950821","18.548161 73.959882");
        
        $coords = $this->CI->db->get_where('areas',['id' => '3'])->row_array();
        $polygon = [];
        foreach (explode('-', $coords['latlon']) as $key => $value) {
            $vl = str_replace(","," ",$value);
            array_push($polygon, $vl);
        }
        // print_r($polygon);
        // exit;
        // $polygon = array(
        //     "22.367582117085913 70.71181669186944",
        //     "22.225161442616514 70.65582486840117",
        //     "22.20736264867434 70.83229276390898",
        //     "22.18701840565626 70.9867880031668",
        //     "22.22452581029355 71.0918447658621",
        //     "22.382709129816103 70.98884793969023",
        //     "22.40112042636022 70.94078275414336",
        //     "22.411912121843205 70.7849142238699",
        //     "22.367582117085913 70.71181669186944"
        // );
        // The last lat lng must be the same as the first one's, to "close the loop"
        foreach($points as $key => $point) {
            echo "(Lat Lng) " . ($key+1) . " ($point): " . $this->pointInPolygon($point, $polygon) . "<br>";
        }
    }

    public function checkSinglePoligon1($lat,$lon,$polygon)
    {
        return $this->pointInPolygon($lat.' '.$lon, $this->formatPolygon($polygon));
    }

    public function formatPolygon($polygon)
    {
        $nPolygon = [];
        foreach (explode('-', $polygon) as $key => $value) {
            $vl = str_replace(","," ",$value);
            array_push($nPolygon, $vl);
        }
        return $nPolygon;
    }

    //final

    public function checkSinglePoligon($lat,$lon,$polygon)
    {
        if($this->pointInPolygon($lat.' '.$lon, $this->formatPolygon($polygon)) || $this->checkSinglePoligon2($lat,$lon,$polygon)){
            return 1;
        }else{
            return 0;
        }
    }


    // simple

    function checkSinglePoligon2($lat,$lon,$polygon)
    {
        $vertices_x = $this->formatCoords($polygon)[0];
        $vertices_y = $this->formatCoords($polygon)[1];
        $points_polygon = count($vertices_x);
        $longitude_x = $lat;
        $latitude_y = $lon;
        if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
            return 1;
        }
        else{
            return 0;
        }
    }

    function formatCoords($coords)
    {
        $x = [];$y = [];
        foreach (explode('-', $coords) as $key => $value) {
            array_push($x, explode(',', $value)[0]);
            array_push($y, explode(',', $value)[1]);
        }
        return [$x,$y];
    }

    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
            $c = !$c;
      }
      return $c;
    }
}
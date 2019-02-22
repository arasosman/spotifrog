<?php

namespace App\Http\Controllers;

use App\Helpers\DataTable;
use App\UnclaimedArtist;
use Illuminate\Http\Request;

class UnclaimedArtistsController extends Controller
{

    private $columns;

    public function __construct()
    {
        $this->columns = array(
            "image" => array("orderable"=>false),
            "uniq_id" => array(),
            "name"=>array(),
            "follower_count"=>array(),
            "view_count" => array(),
            "genres" => array("orderable"=>false),
            "popularity" => array(),
            "view_locations" => array("orderable"=>false),
            "created_at" => array(),
            "operations" => array("orderable"=>false)
        );
    }

    public function showPage(Request $request){

        $prefix = "ua";
        $url = "unclaimedArtists/ua";
        $default_order = '[8,"desc"]';

        $data_table = new DataTable($prefix,$url,$this->columns,$default_order,$request);

        return view("pages.unclaimed_artists",['data_table'=> $data_table]);
    }

    public function getData(Request $request){

        $return_array = array();
        $draw  = $_GET["draw"];
        $start = $_GET["start"];
        $length = $_GET["length"];
        $record_total = 0;
        $recordsFiltered = 0;
        $search_value = false;

        $param_array = array();
        $where_clause = " 1=1 ";


        $order_column = "created_at";
        $order_dir = "DESC";

        //get customized filter object
        $filter_obj = false;
        if(isset($_GET["filter_obj"])){
            $filter_obj = $_GET["filter_obj"];
            $filter_obj = json_decode($filter_obj,true);
        }

        if(isset($_GET["order"][0]["column"])){
            $order_column = $_GET["order"][0]["column"];

            $column_item = array_keys(array_slice($this->columns, $order_column, 1));
            $column_item = $column_item[0];
            $order_column = $column_item;

        }

        if(isset($_GET["order"][0]["dir"])){
            $order_dir = $_GET["order"][0]["dir"];
        }

        $param_array[] = date('Y-m-d', strtotime(str_replace('/', '-', $filter_obj["start_date"])));
        $param_array[] = date('Y-m-d', strtotime(str_replace('/', '-', $filter_obj["end_date"])));
        $where_clause .= "AND DATE(created_at) BETWEEN ? AND ? ";


        if(isset($_GET["search"])){
            $search_value = $_GET["search"]["value"];
            if(!(trim($search_value)=="" || $search_value === false)){
                $where_clause .= " AND (";
                $param_array[]="%".$search_value."%";
                $where_clause .= "name LIKE ? ";
                $param_array[]="%".$search_value."%";
                $where_clause .= " OR uniq_id LIKE ? ";
                $param_array[]="%".$search_value."%";
                $where_clause .= " OR genres LIKE ? ";
                $param_array[]="%".$search_value."%";
                $where_clause .= " OR view_locations LIKE ? ";
                $where_clause .= " ) ";
            }
        }

        $total_count = UnclaimedArtist::whereRaw($where_clause, $param_array)->count();
        $result = UnclaimedArtist::whereRaw($where_clause,$param_array)->limit($length)->skip($start)->orderBy($order_column, $order_dir)->get();


        $return_array["draw"]=$draw;
        $return_array["recordsTotal"]= 0;
        $return_array["recordsFiltered"]= 0;
        $return_array["data"] = array();
        if($total_count > 0){
            $return_array["recordsTotal"]=$total_count;
            $return_array["recordsFiltered"]=$total_count;

            foreach($result as $one_row){

                $image = '<img style="width:80px; height:80px;" src="/images/not-found.png" />';
                if($one_row->image != ""){
                    $image = '<img style="width:80px; height:80px;" src="'.$one_row->image.'" />';
                }
                $genres = "";
                $genres_list = array();

                if($one_row->genres != null && $one_row->genres != "" ){
                    $genres_list = json_decode($one_row->genres);

                    $count = 0;
                    foreach ($genres_list as $item){

                        $genres .= '<label class="badge badge-outline-light">'.$item.'</label><br/>';
                        $count++;

                        if($count > 5)
                            break;
                    }
                }

                $locations = "";
                if($one_row->view_locations != null && $one_row->view_locations != "" ){

                    $locations_list = json_decode($one_row->view_locations);

                    foreach ($locations_list as $one_location){

                        $locations .= '<label class="badge badge-outline-light">'.$one_location->loc." : ".number_format($one_location->view_count).'</label><br/>';
                    }
                }

                $view = '<a href="/detail/artist/'.$one_row->uniq_id.'" target="_blank" class="btn btn-outline-primary">View</a>';

                $tmp_array = array(
                    "DT_RowId" => $one_row->id,
                    "image" => $image,
                    "uniq_id" => $one_row->uniq_id,
                    "name" => $one_row->name,
                    "follower_count"=> number_format($one_row->follower_count),
                    "view_count" => number_format($one_row->view_count),
                    "genres" => $genres,
                    "popularity" => $one_row->popularity,
                    "view_locations" => $locations,
                    "created_at"=>date('d/m/Y H:i', strtotime($one_row->created_at)),
                    "operations"=>$view
                );

                $return_array["data"][] = $tmp_array;
            }
        }

        echo json_encode($return_array);
    }
}

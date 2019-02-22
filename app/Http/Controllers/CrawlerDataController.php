<?php

namespace App\Http\Controllers;

use App\CrawlingList;
use App\Helpers\DataTable;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;

class CrawlerDataController extends Controller
{
    private $columns;

    public function __construct()
    {
        $this->columns = array(
            "uniq_id" => array(),
            "type" => array(),
            "name"=>array(),
            "created_at"=>array(),
            "status" => array(),
            "relative_scan" => array(),
            "view" => array("orderable"=>false)

        );
    }

    public function showCrawl(Request $request){

        $prefix = "cd";
        $url = "crawler/cd";
        $default_order = '[3,"desc"]';

        $data_table = new DataTable($prefix,$url,$this->columns,$default_order,$request);

        return view("pages.crawling_list",['data_table'=> $data_table]);
    }

    public function getCrawlerData(Request $request){

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
                $where_clause .= " ) ";
            }
        }

        $total_count = CrawlingList::whereRaw($where_clause,$param_array)->count();
        $result = CrawlingList::whereRaw($where_clause,$param_array)->limit($length)->skip($start)->orderBy($order_column, $order_dir)->get();


        $return_array["draw"]=$draw;
        $return_array["recordsTotal"]= 0;
        $return_array["recordsFiltered"]= 0;
        $return_array["data"] = array();
        if($total_count > 0){
            $return_array["recordsTotal"]=$total_count;
            $return_array["recordsFiltered"]=$total_count;

            foreach($result as $one_row){
                if($one_row->status == 1){
                   $status = '<label class="badge badge-danger">Not Checked</label>';
                }elseif ($one_row->status == 2){
                    $status = '<label class="badge badge-info">Yes</label>';
                }
                else
                    $status = '<label class="badge badge-success">No</label>';


                if($one_row->relative_scan){
                    $relative_scan = '<label class="badge badge-danger">Scanned</label>';
                }else{
                    $relative_scan = '<label class="badge badge-success">Wait Scanning</label>';
                }
                $view = '<a href="/detail/artist/'.$one_row->uniq_id.'" target="_blank" class="btn btn-outline-primary">View</a>';
                $tmp_array = array(
                    "DT_RowId" => $one_row->id,
                    "uniq_id" => $one_row->uniq_id,
                    "type" => $one_row->type,
                    "name"=> $one_row->name,
                    "status" => $status,
                    "relative_scan" => $relative_scan,
                    "view" => $view,
                    "created_at"=>date('d/m/Y H:i', strtotime($one_row->created_at))
                );
                $return_array["data"][] = $tmp_array;
            }
        }

        echo json_encode($return_array);
    }
} 

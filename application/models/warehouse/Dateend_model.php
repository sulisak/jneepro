<?php

class Dateend_model extends CI_Model {



        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
                $this->load->library('session');


        }






         public function Getstock($data)
        {


 $perpage = $data['perpage'];

            if($data['page'] && $data['page'] != ''){
$page = $data['page'];
            }else{
          $page = '1';
            }


            $start = ($page - 1) * $perpage;


$querynum = $this->db->query('SELECT
    wl.product_id as product_id,
    wl.product_code as product_code,
    wl.product_name as product_name,
    wl.product_price as product_price,
    wl.product_date_end as product_date_end,
    wl.product_date_end2 as product_date_end2,
    wl.product_wholesale_price as product_wholesale_price,
    wl.product_price_discount as product_price_discount,
    IFNULL((SELECT s.product_stock_num FROM stock as s WHERE s.product_id=wl.product_id AND s.branch_id="'.$_SESSION['branch_id'].'"), "0") as product_stock_num,
    wl.product_price_value as product_price_value,
    wc.product_category_id as product_category_id,
    wc.product_category_name as product_category_name,
    z.zone_name as zone_name
    FROM wh_product_list  as wl
    LEFT JOIN zone as z on z.zone_id=wl.zone_id
    LEFT JOIN wh_product_category as wc on wc.product_category_id=wl.product_category_id
    WHERE wl.owner_id="'.$_SESSION['owner_id'].'" AND wl.product_date_end2 != "" AND wl.product_stock_num > "0"   AND wl.product_code LIKE "%'.$data['searchtext'].'%"
    OR wl.owner_id="'.$_SESSION['owner_id'].'" AND wl.product_date_end2 != "" AND wl.product_stock_num > "0" AND wl.product_name LIKE "%'.$data['searchtext'].'%"
    OR wl.owner_id="'.$_SESSION['owner_id'].'" AND wl.product_date_end2 != "" AND wl.product_stock_num > "0" AND z.zone_name LIKE "%'.$data['searchtext'].'%"
    ORDER BY wl.product_date_end2 ASC');


$query = $this->db->query('SELECT
  wl.product_id as product_id,
  wl.product_code as product_code,
  wl.product_name as product_name,
  wl.product_price as product_price,
  wl.product_date_end as product_date_end,
  wl.product_date_end2 as product_date_end2,
  wl.product_wholesale_price as product_wholesale_price,
  wl.product_price_discount as product_price_discount,
  IFNULL((SELECT s.product_stock_num FROM stock as s WHERE s.product_id=wl.product_id AND s.branch_id="'.$_SESSION['branch_id'].'"), "0") as product_stock_num,
  wl.product_price_value as product_price_value,
  wc.product_category_id as product_category_id,
  wc.product_category_name as product_category_name,
  z.zone_name as zone_name
  FROM wh_product_list  as wl
  LEFT JOIN zone as z on z.zone_id=wl.zone_id
  LEFT JOIN wh_product_category as wc on wc.product_category_id=wl.product_category_id
  WHERE wl.owner_id="'.$_SESSION['owner_id'].'" AND wl.product_date_end2 != "" AND wl.product_stock_num > "0"   AND wl.product_code LIKE "%'.$data['searchtext'].'%"
  OR wl.owner_id="'.$_SESSION['owner_id'].'" AND wl.product_date_end2 != "" AND wl.product_stock_num > "0" AND wl.product_name LIKE "%'.$data['searchtext'].'%"
  OR wl.owner_id="'.$_SESSION['owner_id'].'" AND wl.product_date_end2 != "" AND wl.product_stock_num > "0" AND z.zone_name LIKE "%'.$data['searchtext'].'%"
  ORDER BY wl.product_date_end2 ASC  LIMIT '.$start.' , '.$perpage.'  ');



$encode_data = json_encode($query->result(),JSON_UNESCAPED_UNICODE );

$encode_data = json_encode($query->result(),JSON_UNESCAPED_UNICODE );


$num_rows = $querynum->num_rows();

$pageall = ceil($num_rows/$perpage);




$json = '{"list": '.$encode_data.',
"numall": '.$num_rows.',"perpage": '.$perpage.', "pageall": '.$pageall.'}';

return $json;

        }


// public function GetNearExpiry()
// {
//     $owner_raw  = isset($_SESSION['owner_id']) ? $_SESSION['owner_id'] : 0;
//     $branch_raw = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : 0;

//     $owner_id  = $this->db->escape($owner_raw);
//     $branch_id = $this->db->escape($branch_raw);

//     $now              = time();
//     $two_months_later = strtotime('+2 months', $now);

//     $sql = "SELECT
//                 ipd.importproduct_detail_id,
//                 ipd.	importproduct_detail_id,
//                 ipd.product_id,
//                 wl.product_code,
//                 wl.product_name,
//                 ipd.importproduct_detail_num,
//                 ipd.lotno,
//                 ipd.date_end,
//                 ipd.date_end2,
//                 ipd.importproduct_detail_date,
//                 iph.importproduct_header_code,
//                 IFNULL(b.branch_name, '') AS branch_name
//             FROM wh_importproduct_detail AS ipd
//             LEFT JOIN wh_importproduct_header AS iph
//                 ON iph.importproduct_header_id = ipd.	importproduct_detail_id
//             LEFT JOIN wh_product_list AS wl
//                 ON wl.product_id = ipd.product_id
//             LEFT JOIN branch AS b
//                 ON b.branch_id = iph.branch_id
//             WHERE wl.owner_id = {$owner_id}
//               AND ipd.date_end2 IS NOT NULL
//               AND ipd.date_end2 != ''
//               AND ipd.date_end2 != '0'
//               AND wl.product_num_min >0
//               AND CAST(ipd.date_end2 AS UNSIGNED) > {$now}
//               AND CAST(ipd.date_end2 AS UNSIGNED) <= {$two_months_later}
//             ORDER BY CAST(ipd.date_end2 AS UNSIGNED) ASC";

//     $query = $this->db->query($sql);

//     if ($query === false) {
//         $dbError = $this->db->error();
//         log_message('error', 'GetNearExpiry SQL error: ' . json_encode($dbError));
//         return json_encode(array(), JSON_UNESCAPED_UNICODE);
//     }

//     $rows = $query->result();
//     log_message('debug', 'GetNearExpiry lot count=' . count($rows) . ' owner=' . $owner_raw . ' branch=' . $branch_raw);

//     return json_encode($rows, JSON_UNESCAPED_UNICODE);
// }


public function GetNearExpiry()
{
    $owner_raw  = isset($_SESSION['owner_id']) ? $_SESSION['owner_id'] : 0;
    $branch_raw = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : 0;

    $owner_id  = $this->db->escape($owner_raw);
    $branch_id = $this->db->escape($branch_raw);

    $now              = time();
    $two_months_later = $now + (60 * 24 * 60 * 60); // 60 days in seconds

    $expiry_sec_expr = "CASE
        WHEN ipd.date_end2 REGEXP '^[0-9]+$' AND CAST(ipd.date_end2 AS UNSIGNED) > 100000000000
            THEN FLOOR(CAST(ipd.date_end2 AS UNSIGNED) / 1000)
        WHEN ipd.date_end2 REGEXP '^[0-9]+$'
            THEN CAST(ipd.date_end2 AS UNSIGNED)
        WHEN ipd.date_end2 REGEXP '^[0-9]{2}-[0-9]{2}-[0-9]{4}$'
            THEN UNIX_TIMESTAMP(STR_TO_DATE(ipd.date_end2, '%d-%m-%Y'))
        ELSE 0
    END";

    // Filter by branch_id only when session branch_id is > 0
    $branch_filter = ((int)$branch_raw > 0)
        ? " AND st.branch_id = {$branch_id}"
        : "";

    $stock_subquery = "SELECT
            st.product_id,
            SUM(COALESCE(st.product_stock_num, 0)) AS stock_num
        FROM stock AS st
        WHERE 1=1 {$branch_filter}
        GROUP BY st.product_id";

    $sql = "SELECT
                ipd.importproduct_detail_id,
                ipd.product_id,
                wl.product_code,
                wl.product_name,
                ipd.importproduct_detail_num,
                ipd.lotno,
                ipd.date_end,
                ipd.date_end2,
                {$expiry_sec_expr} AS expiry_ts,
                ipd.importproduct_detail_date,
                iph.importproduct_header_code,
                IFNULL(b.branch_name, '') AS branch_name,
                COALESCE(stk.stock_num, 0) AS product_stock_num
            FROM wh_importproduct_detail AS ipd
            LEFT JOIN wh_importproduct_header AS iph
                ON iph.importproduct_header_id = ipd.importproduct_detail_id
            LEFT JOIN wh_product_list AS wl
                ON wl.product_id = ipd.product_id
            LEFT JOIN ({$stock_subquery}) AS stk
                ON stk.product_id = ipd.product_id
            LEFT JOIN branch AS b
                ON b.branch_id = iph.branch_id
            WHERE wl.owner_id = {$owner_id}
              AND COALESCE(stk.stock_num, 0) > 0
              AND ipd.date_end2 IS NOT NULL
              AND ipd.date_end2 != ''
              AND ipd.date_end2 != '0'
              AND {$expiry_sec_expr} > {$now}
              AND {$expiry_sec_expr} <= {$two_months_later}
            ORDER BY {$expiry_sec_expr} ASC";

    $query = $this->db->query($sql);

    if ($query === false) {
        $dbError = $this->db->error();
        log_message('error', 'GetNearExpiry SQL error: ' . json_encode($dbError));
        return json_encode(array(), JSON_UNESCAPED_UNICODE);
    }

    $rows = $query->result();
    log_message('debug', 'GetNearExpiry lot count=' . count($rows) . ' owner=' . $owner_raw . ' branch=' . $branch_raw);

    return json_encode($rows, JSON_UNESCAPED_UNICODE);
}


















    }
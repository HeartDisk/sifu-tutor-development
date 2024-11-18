<?php

//action.php
if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('order_number', 'order_total', 'order_date');

		$revenue = DB::table('class_attendeds')->where('status','=','attended')->whereMonth('created_at','=',Carbon\Carbon::now()->month)->sum('totalPrice');
        
        $expenses = DB::table('expenditures')->whereMonth('created_at','=',Carbon\Carbon::now()->month)->sum('total');

// 		$search_query = 'WHERE order_date <= "'.date('Y-m-d').'" AND ';

// 		if(isset($_POST["start_date"], $_POST["end_date"]) && $_POST["start_date"] != '' && $_POST["end_date"] != '')
// 		{
// 			$search_query .= 'order_date >= "'.$_POST["start_date"].'" AND order_date <= "'.$_POST["end_date"].'" AND ';
// 		}

// 		if(isset($_POST["search"]["value"]))
// 		{
// 			$search_query .= '(order_number LIKE "%'.$_POST["search"]["value"].'%" OR order_total LIKE "%'.$_POST["search"]["value"].'%" OR order_date LIKE "%'.$_POST["search"]["value"].'%")';
// 		}



// 		$group_by_query = " GROUP BY order_date ";

// 		$order_by_query = "";

// 		if(isset($_POST["order"]))
// 		{
// 			$order_by_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
// 		}
// 		else
// 		{
// 			$order_by_query = 'ORDER BY order_date DESC ';
// 		}

// 		$limit_query = '';

// 		if($_POST["length"] != -1)
// 		{
// 			$limit_query = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
// 		}

// 		$statement = $connect->prepare($main_query . $search_query . $group_by_query . $order_by_query);

// 		$statement->execute();

// 		$filtered_rows = $statement->rowCount();

// 		$statement = $connect->prepare($main_query . $group_by_query);

// 		$statement->execute();

// 		$total_rows = $statement->rowCount();

// 		$result = $connect->query($main_query . $search_query . $group_by_query . $order_by_query . $limit_query, PDO::FETCH_ASSOC);

// 		$data = array();

// 		foreach($result as $row)
// 		{
// 			$sub_array = array();

// 			$sub_array[] = $row['order_number'];

// 			$sub_array[] = $row['order_total'];

// 			$sub_array[] = $row['order_date'];

// 			$data[] = $sub_array;
// 		}

		$output = array(
			"revenue"	=>	$revenue,
			"expenses" => $expenses,
		);

		echo json_encode($output);
	}
}

?>
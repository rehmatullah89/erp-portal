<?php

	ProductionStatusUpdate();

	function ProductionStatusUpdate()
{

	$link = mysql_connect('mysql', 'root', 'matrix');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}

	$rv = mysql_select_db("dbPortal", $link);

	// remove tbl_po
	// Get only knitting/weaving ... stitching
	// final audit status based on query
	$result = mysql_query("SELECT work_order_id, color_id,production_status FROM tbl_vsr_details ", $link);

	$WorkData = array();
	$POData = array();

	while($row = mysql_fetch_array( $result))
	{
		_FindProductionStatus($row['color_id'],$row['work_order_id']);
	}


	$sSQL = " UPDATE tbl_vsr_details vd set vd.production_status =
			if(
			(Select count(*) from tbl_vsr_data vdata WHERE vdata.work_order_id = vd.work_order_id
			and vd.color_id=vdata.color_id
			and vdata.production_status!=0
			and vdata.stage_id in (2,11,10,9,7,8)

			) > 0 ,

			(
				if(
				(Select count(*) from tbl_vsr_data vdata WHERE vdata.work_order_id = vd.work_order_id
				and vd.color_id=vdata.color_id
				and vdata.production_status!=1
				and vdata.stage_id in (2,11,10,9,7,8)

				) > 0 ,
					if(
					(Select count(*) from tbl_vsr_data vdata WHERE vdata.work_order_id = vd.work_order_id
					and vd.color_id=vdata.color_id
					and vdata.production_status=2
					and vdata.stage_id in (2,11,10,9,7,8)
					) > 0 ,2,3)
				,1))
			,
			0)

		";

		mysql_query($sSQL, $link); //color level


		$sSQL = " UPDATE tbl_po_colors pc set pc.production_status =
					if(
					(Select count(*) from tbl_vsr_details vdata WHERE
					 pc.color_id=vdata.color_id
					and pc.po_id=vdata.po_id
					and vdata.production_status!=0
					and vdata.stage_id in (2,11,10,9,7,8)

					) > 0 ,

					(
						if(
						(Select count(*) from tbl_vsr_details vdata WHERE
						 pc.color_id=vdata.color_id
						and pc.po_id=vdata.po_id
						and vdata.production_status!=1
						and vdata.stage_id in (2,11,10,9,7,8)

						) > 0 ,
							if(
							(Select count(*) from tbl_vsr_details vdata WHERE
							pc.color_id=vdata.color_id
							and pc.po_id=vdata.po_id
							and vdata.production_status=2
							and vdata.stage_id in (2,11,10,9,7,8)
							) > 0 ,2,3)
						,1))
					,
					0)

				";


		mysql_query($sSQL, $link); //po color level


		//print $sSQL."<br/>";

		$sSQL = "  UPDATE tbl_vsr2 vsr set vsr.production_status =
					if(
					(Select count(*) from tbl_vsr_details vd WHERE vsr.id = vd.work_order_id
					and vd.production_status!=0
					) > 0 ,
						if(
						(Select count(*) from tbl_vsr_details vd WHERE vsr.id = vd.work_order_id
						and vd.production_status!=1
						) > 0 ,
							if(
							(Select count(*) from tbl_vsr_details vd WHERE
							vsr.id = vd.work_order_id
							and vd.production_status=2
							) > 0 ,2,3)
						,1)
					,0)
		";

		mysql_query($sSQL, $link); //wo level

		 //print $sSQL."<br/>";

		$sSQL = " UPDATE tbl_po po set po.production_status =
			if(
			(Select count(*) from tbl_vsr_details vd WHERE po.id = vd.po_id
			and vd.production_status!=0
			) > 0 ,
				if(
				(Select count(*) from tbl_vsr_details vd WHERE po.id = vd.po_id
				and vd.production_status!=1
				) > 0 ,
					if(
					(Select count(*) from tbl_vsr_details vd WHERE po.id = vd.po_id
					and vd.production_status=2
					) > 0 ,2,3)
				,1)
			,0)";

		mysql_query($sSQL, $link); //po level





 	}

	/***

		FUNCTION:  _FindProductionStatus

		***/

	function _FindProductionStatus($ColorID,$WOID)
	{
		/***

		1 ontime
		2 late
		3 danger
		0 no status

		***/


		$CD = date("Y-m-d");

		$PS = 0;

		$Gate1_Boundry =0;

		$Gate1_BoundryPlus =  0;

		$Gate2_Boundry = 0;

		$Gate2_BoundryPlus = 0;

		$Final_Audit_Date = 0;

		$Percent = 0;

		$link = mysql_connect('mysql', 'root', 'matrix');

		if (!$link) {
			die('Could not connect: ' . mysql_error());
		}

		$rv = mysql_select_db("dbPortal", $link);


		$sSQL = "SELECT final_date  FROM tbl_vsr_details  WHERE  work_order_id = $WOID and color_id = $ColorID";
		$result = mysql_query($sSQL, $link);
		$row = mysql_fetch_array( $result);
		$Final_Audit_Date = $row['final_date'];



		$sSQL 	= "SELECT etd_required  FROM tbl_po_colors  WHERE id = $ColorID";
		$result = mysql_query($sSQL, $link);
		$row 	= mysql_fetch_array( $result);
		$ETD	= $row['etd_required'];


		$sSQL = "SELECT production_status, end_date  FROM tbl_vsr_data  WHERE
			stage_id in (11,2)
			and color_id = $ColorID
			and work_order_id = $WOID
			 order by end_date DESC LIMIT 1";
		$result = mysql_query($sSQL, $link);
		$row = mysql_fetch_array( $result);

		$Status1 = $row["production_status"];
		$Gate1_Boundry = $row['end_date'];



		$sSQL = "SELECT production_status, end_date  FROM tbl_vsr_data  WHERE
			stage_id in (10,7,8)
			and color_id = $ColorID
			and work_order_id = $WOID
			order by end_date DESC";


		$result = mysql_query($sSQL, $link);
		$row = mysql_fetch_array( $result);
		$Status2 = $row["production_status"];
		$Gate2_Boundry = $row['end_date'];


		$sSQL = "SELECT production_status, end_date  FROM tbl_vsr_data  WHERE
			stage_id='9'
			and color_id = $ColorID
			and work_order_id = $WOID
			 order by end_date DESC";

		$result = mysql_query($sSQL, $link);
		$row = mysql_fetch_array( $result);
		$Status3 = $row["production_status"];



		$Gate1_BoundryPlus =  date('Y-m-d',strtotime( $Gate1_Boundry." +3 days "));
		$Gate2_BoundryPlus =  date('Y-m-d',strtotime( $Gate2_Boundry." +3 days "));

		$PS1 = $PS2 =$PS3 = 0;

		if( $CD  < $Gate1_Boundry ) {

			$PS1 =$PS2 =$PS3 = 1;

		}

		else if ( ($CD <= $Gate1_BoundryPlus  && $CD >= $Gate1_Boundry) ||
		          ($CD >  $Gate1_BoundryPlus && $Status1 == 0) )
		{

			$sSQL = "
			SELECT if(st.type='P',completed,round((completed/po.order_qty)*100)) as Percent  FROM
			`tbl_production_stages` as st ,tbl_vsr_data as vdata,
			tbl_po_colors as po
			WHERE
			 po.id=vdata.color_id and
			vdata.color_id = $ColorID and
			vdata.work_order_id =  $WOID AND
			vdata.stage_id = st.id and st.id in (11,2) group by stage_id order by end_date DESC";

			$result = mysql_query($sSQL, $link);
			$row = mysql_fetch_array($result);

			$Percent = $row['Percent'];

			if($Percent >= 100)
				$PS1 = 1;
			else if($Percent >= 60)
				$PS1 = 3;
			else
				$PS1 = 2;

			$PS2 = $PS1;
			$PS3 = $PS1;
		}


		// update
		if($PS1 > 0)
		{
			$sSQL = "Update tbl_vsr_data set production_status = $PS1 WHERE stage_id  in (2,11) and color_id = $ColorID and work_order_id = $WOID";
			$result = mysql_query($sSQL, $link);
		}



		/*****

		GATE 2

		****/
/*
		if($CD  > $Gate1_BoundryPlus &&  $CD < $Gate2_Boundry)
		{
			 $sSQL = "
			 	SELECT if(st.type='P',completed,round((completed/po.order_qty)*100)) as Percent,
			 	 stage_id as last_stage from tbl_vsr_data v,tbl_production_stages p
				where
				v.stage_id=p.id and
				color_id = $ColorID and
				vdata.work_order_id =  $WOID AND
				stage_id NOT IN (7,8,10,9) order by p.position Desc limit 1
			 ";

			$result = mysql_query($sSQL, $link);
			$row = mysql_fetch_array($result);

			$Gate1Percent = $row['Percent'];

			if($Gate1Percent >= 100)
				$PS2 = 1;
			else if($Gate1Percent >= 60)
				$PS2 = 3;
			else
				$PS2 = 2;
		}
*/


		if ( ($CD >= $Gate2_Boundry && $CD <= $Gate2_BoundryPlus) ||
		     ($CD > $Gate2_BoundryPlus && $Status2 == 0) )
		{
			$sSQL = "
			SELECT if(st.type='P',completed,round((completed/po.order_qty)*100)) as Percent  FROM
			`tbl_production_stages` as st ,tbl_vsr_data as vdata,
			tbl_po_colors as po
			WHERE
			 po.id=vdata.color_id and
			vdata.color_id = $ColorID and
			vdata.work_order_id =  $WOID AND
			vdata.stage_id = st.id and st.id in (10,7,8) group by stage_id order by end_date DESC";

			$result = mysql_query($sSQL, $link);
			$row = mysql_fetch_array($result);

			$Percent = $row['Percent'];

			if($Percent >= 100)
				$PS2 = 1;
			else if($Percent >= 60)
				$PS2 = 3;
			else
				$PS2 = 2;

			$PS3 = $PS2;
		}

		if($PS2 > 0)
		{
			$sSQL = "Update tbl_vsr_data set production_status = $PS2 WHERE stage_id in (10,7,8) and color_id = $ColorID and work_order_id = $WOID";
			$result = mysql_query($sSQL, $link);
		}



		// ESLE IF CD > G2BP
/*
		if($CD > $Gate2_BoundryPlus && $CD < $Final_Audit_Date)
		{

			$sSQL = "
			SELECT
			 if(st.type='P',completed,round((completed/po.order_qty)*100)) as Percent
			FROM
			`tbl_production_stages` as st,
			 tbl_vsr_data as vdata,
			 tbl_po_colors as po
			WHERE
			 po.id=vdata.color_id AND
			 vdata.color_id = $ColorID AND
			 vdata.stage_id = st.id AND
			 vdata.work_order_id =  $WOID AND
			 st.id = 9  order by position ASC limit 1";


			$result = mysql_query($sSQL, $link);
			$row = mysql_fetch_array($result);

			$lastStagePercent = $row['Percent'];

			if($lastStagePercent >= 100)
				$PS = 1;
			else if($lastStagePercent >= 60)
				$PS = 3;
			else
				$PS = 2;

		}
*/

		// CHECK 1 .. if FAD < ETD

		if ($Final_Audit_Date >= $ETD)
			$PS3 = 2;

		else if ( ($CD >= $Final_Audit_Date  && $Final_Audit_Date <= $ETD) ||
		          ($CD > $ETD && $Status3 == 0 ) )
		{

			$sSQL = "
			SELECT
			 if(st.type='P',completed,round((completed/po.order_qty)*100) ) as Percent
			FROM
			`tbl_production_stages` as st ,
			 tbl_vsr_data as vdata,
			 tbl_po_colors as po
			WHERE
			 po.id=vdata.color_id AND
			 vdata.color_id = $ColorID AND
			 vdata.work_order_id =  $WOID AND
			  st.id = vdata.stage_id AND
			 vdata.stage_id = 9";

			 //print $sSQL; exit;

			$result = mysql_query($sSQL, $link);
			$row 	= mysql_fetch_array($result);

			$lastStagePercent = $row['Percent'];

			if($lastStagePercent >= 100)
				$PS3 = 1;
			else if($lastStagePercent >= 60)
				$PS3 = 3;
			else
				$PS3 = 2;
		}

		if($PS3 > 0)
		{
			$sSQL = "Update tbl_vsr_data set production_status = $PS3 WHERE stage_id= 9 and color_id = $ColorID and work_order_id = $WOID";
			$result = mysql_query($sSQL, $link);
		}


	}
?>
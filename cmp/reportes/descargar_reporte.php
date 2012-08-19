<?php session_start();

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../../Classes/PHPExcel.php';

include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

$sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `estado`, `inicio`, `observacion`  
       FROM `orden_de_trabajo`, `historial_ot`
   	   WHERE  `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo` AND `termino` IS NULL ";

$result = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($result);

	
	if($rows !=0){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		//Propiedades del documento
		$objPHPExcel->getProperties()->setCreator("CMP-Entel")
									 ->setLastModifiedBy("CMP-Entel")
									 ->setTitle("Reporte Orden de trabajo")
									 ->setSubject("")
									 ->setDescription("")
									 ->setKeywords("")
									 ->setCategory("");
	
		//Titulo de la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Reporte general');
	
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D2', 'Información de la(s) orden(es) de trabajo');
				
		//ancho de columna
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(100);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(100);
		$objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray(
			array(
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
 			)
		);
		
		//Tipo de fuente
		$objPHPExcel->getActiveSheet()->getStyle('A1:M100')->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A1:M100')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Ordenes de trabajo');
		
		$objPHPExcel->getActiveSheet()->setCellValue('B7', 'nro de OT');
		$objPHPExcel->getActiveSheet()->setCellValue('C7', 'estado');
		$objPHPExcel->getActiveSheet()->setCellValue('D7', 'creación de OT');
		$objPHPExcel->getActiveSheet()->setCellValue('E7', 'inicio del estado actual');
		$objPHPExcel->getActiveSheet()->setCellValue('F7', 'ciudad');
		$objPHPExcel->getActiveSheet()->setCellValue('G7', 'lugar');
		$objPHPExcel->getActiveSheet()->setCellValue('H7', 'tipo');
		$objPHPExcel->getActiveSheet()->setCellValue('I7', 'subtipo');
		$objPHPExcel->getActiveSheet()->setCellValue('J7', 'descripción');
		$objPHPExcel->getActiveSheet()->setCellValue('K7', 'observación de cierre');
		
		//Propiedades de la cabecera de la tabla materiales
		$objPHPExcel->getActiveSheet()->getStyle('B7:K7')->applyFromArray(
			array(
				'font'    => array('bold' => true),
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
				'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
				'fill' 	=> array(
									'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
									'color'		=> array('argb' => 'FFCC00')
								),
		 		)
		);
		
		for ($i = 0; $i < $rows; $i++)
        	$ot[] = mysql_fetch_assoc($result);
		
 		$index = 8; $inicial=$index;
		for ($i = 0; $i < $rows; $i++):
		
			$sql = "SELECT inicio 
	    			FROM historial_ot 
	    			WHERE orden_de_trabajo_idorden_de_trabajo =".$ot[$i]['idorden_de_trabajo']." AND estado='CREADA' ";
	    	$rs = mysql_query($sql) or die(mysql_error());
	    	$row = mysql_fetch_row($rs);
	    	$date = new DateTime($row[0]);
		
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$index, $ot[$i]['idorden_de_trabajo']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$index, $ot[$i]['estado']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$index, $date->format('d/m/Y'));
			$date = new DateTime($ot[$i]['inicio']); 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$index, $date->format('d/m/Y'));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$index, $ot[$i]['ciudad']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$index, $ot[$i]['faena']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$index, $ot[$i]['tipo_ot']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$index, $ot[$i]['subtipo_ot']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$index, $ot[$i]['descripcion']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$index, $ot[$i]['observacion']);
			$index++;
 		endfor;

		//Borde de la tabla
		$objPHPExcel->getActiveSheet()->getStyle('B'.$inicial.':K'.($index-1))->applyFromArray(
			array(
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
				'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reporte_general.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}


?>
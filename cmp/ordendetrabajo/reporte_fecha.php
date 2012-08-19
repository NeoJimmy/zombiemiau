<?php session_start();

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../../Classes/PHPExcel.php';

include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

if (isset($_GET['from']) && isset($_GET['to'])) :

 	$sql= "SELECT DISTINCT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones` 
           FROM `orden_de_trabajo`, `historial_ot` 
           WHERE `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo` AND `inicio` BETWEEN STR_TO_DATE('".$_GET['from']."', '%d/%m/%Y')  AND STR_TO_DATE('".$_GET['to']."', '%d/%m/%Y') " ;    

	//echo $sql."<br>";
	
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
		$objPHPExcel->getActiveSheet()->setTitle('Ordenes de trabajo');
	
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D2', 'Información de la(s) orden(es) de trabajo');
				
		//ancho de columna
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray(
			array(
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
 			)
		);
		
		//Tipo de fuente
		$objPHPExcel->getActiveSheet()->getStyle('A1:L100')->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A1:L100')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		//$objPHPExcel->getActiveSheet()->getStyle('A5:A24')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Desde');
		$objPHPExcel->getActiveSheet()->setCellValue('C3', $_GET['from']);
		$objPHPExcel->getActiveSheet()->setCellValue('B4', 'hasta');
		$objPHPExcel->getActiveSheet()->setCellValue('C4', $_GET['to']);		
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Ordenes de trabajo');
		
		$objPHPExcel->getActiveSheet()->setCellValue('B7', 'Nro de OT');
		$objPHPExcel->getActiveSheet()->setCellValue('C7', 'nombre');
		$objPHPExcel->getActiveSheet()->setCellValue('D7', 'apellido');
		$objPHPExcel->getActiveSheet()->setCellValue('E7', 'anexo');
		$objPHPExcel->getActiveSheet()->setCellValue('F7', 'ciudad');
		$objPHPExcel->getActiveSheet()->setCellValue('G7', 'faena');
		$objPHPExcel->getActiveSheet()->setCellValue('H7', 'area');
		$objPHPExcel->getActiveSheet()->setCellValue('I7', 'tipo');
		$objPHPExcel->getActiveSheet()->setCellValue('J7', 'subtipo');
		$objPHPExcel->getActiveSheet()->setCellValue('K7', 'descripción');
		$objPHPExcel->getActiveSheet()->setCellValue('L7', 'observaciones');
		
		//Propiedades de la cabecera de la tabla materiales
		$objPHPExcel->getActiveSheet()->getStyle('B7:L7')->applyFromArray(
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
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$index, $ot[$i]['idorden_de_trabajo']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$index, $ot[$i]['nombre']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$index, $ot[$i]['apellido']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$index, $ot[$i]['anexo']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$index, $ot[$i]['ciudad']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$index, $ot[$i]['faena']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$index, $ot[$i]['area']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$index, $ot[$i]['tipo_ot']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$index, $ot[$i]['subtipo_ot']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$index, $ot[$i]['descripcion']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$index, $ot[$i]['observaciones']);
			$index++;
 		endfor;

		//Borde de la tabla
		$objPHPExcel->getActiveSheet()->getStyle('B'.$inicial.':L'.($index-1))->applyFromArray(
			array(
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
				'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$inicial.':D'.$index)->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
	 		)
		);
		
	

		$objPHPExcel->setActiveSheetIndex(0);
		unset($_GET['from']);
		unset($_GET['to']);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reporte_fecha.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
endif;


?>
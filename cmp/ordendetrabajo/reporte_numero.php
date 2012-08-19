<?php session_start();

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../../Classes/PHPExcel.php';

include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

if (isset($_GET['nro_ot']) && $_GET['nro_ot']!="ninguno") :

	$nro_ot = $_GET['nro_ot'];
	
	$sql= "SELECT *
           FROM `orden_de_trabajo`
           WHERE  `idorden_de_trabajo`=$nro_ot" ;
           
    $sql2 =  "SELECT *
       FROM `historial_ot`
       WHERE  `orden_de_trabajo_idorden_de_trabajo`=$nro_ot" ;
           
           
	$result = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($result);
	
	
	$result2 = mysql_query($sql2) or die(mysql_error());
	$rows2 = mysql_num_rows($result2);

	
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(21);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
		$objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray(
			array(
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
 			)
		);
		//mesclar celdas
		//$objPHPExcel->getActiveSheet()->mergeCells('D24:G25');
		//Alineamiento
		//$objPHPExcel->getActiveSheet()->getStyle('D24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		//$objPHPExcel->getActiveSheet()->getStyle('D24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		//Tipo de fuente
		$objPHPExcel->getActiveSheet()->getStyle('A1:K100')->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A1:K100')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		//$objPHPExcel->getActiveSheet()->getStyle('A5:A24')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Ordenes de trabajo');
		
		$objPHPExcel->getActiveSheet()->setCellValue('B7', 'nombre');
		$objPHPExcel->getActiveSheet()->setCellValue('C7', 'apellido');
		$objPHPExcel->getActiveSheet()->setCellValue('D7', 'anexo');
		$objPHPExcel->getActiveSheet()->setCellValue('E7', 'ciudad');
		$objPHPExcel->getActiveSheet()->setCellValue('F7', 'faena');
		$objPHPExcel->getActiveSheet()->setCellValue('G7', 'area');
		$objPHPExcel->getActiveSheet()->setCellValue('H7', 'tipo');
		$objPHPExcel->getActiveSheet()->setCellValue('I7', 'subtipo');
		$objPHPExcel->getActiveSheet()->setCellValue('J7', 'descripción');
		$objPHPExcel->getActiveSheet()->setCellValue('K7', 'observaciones');
		
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
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$index, $ot[$i]['nombre']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$index, $ot[$i]['apellido']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$index, $ot[$i]['anexo']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$index, $ot[$i]['ciudad']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$index, $ot[$i]['faena']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$index, $ot[$i]['area']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$index, $ot[$i]['tipo_ot']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$index, $ot[$i]['subtipo_ot']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$index, $ot[$i]['descripcion']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$index, $ot[$i]['observaciones']);
			$index++;
 		endfor;

		//Borde de la tabla
		$objPHPExcel->getActiveSheet()->getStyle('B'.$inicial.':K'.($index-1))->applyFromArray(
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
		
		
		if($rows2 != 0)
		{
			$index = $index+2;
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$index, 'Historial de la orden de trabajo');
    		$objPHPExcel->getActiveSheet()->getStyle('A'.$index)->getFont()->setBold(true);
    		$objPHPExcel->getActiveSheet()->getStyle('A'.$index)->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

    		$index = $index+2;
    		//cabecera
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$index, 'Estado');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$index, 'Inicio');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$index, 'Término');
			//Propiedades de la cabecera de la tabla 
			$objPHPExcel->getActiveSheet()->getStyle('B'.$index.':D'.$index)->applyFromArray(
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
			
		     for ($i = 0; $i < $rows2; $i++)
		     $historial[] = mysql_fetch_assoc($result2);
    		
	        //Contenido de la tabla
			$index++; $inicial = $index;
	     	for ($i = 0; $i < $rows2; $i++):
		        $objPHPExcel->getActiveSheet()->setCellValue('B'.$index, $historial[$i]['estado']);
		        $inicio = new DateTime($historial[$i]['inicio']);
	        	$inicio = date_format($inicio, 'H:i:s d-m-Y');
	        	if($historial[$i]['termino'] != NULL)
	        	{
	        		$termino = new DateTime($historial[$i]['termino']);
	        		$termino = date_format($termino, 'H:i:s d-m-Y');
	        	}else{
	        		$termino = NULL;
	        	}
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$index, $inicio);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$index, $termino);
				$index++;
	     	endfor;
			//Borde de la tabla
			$objPHPExcel->getActiveSheet()->getStyle('B'.$inicial.':D'.($index-1))->applyFromArray(
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

		}//fin Historial

		$objPHPExcel->setActiveSheetIndex(0);
		unset($_GET['nro_ot']);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reporte_ot.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
endif;


?>
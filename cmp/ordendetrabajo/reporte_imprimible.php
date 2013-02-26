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
           
           
	$result = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($result);
	
	if($rows !=0) {
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
		$objPHPExcel->getActiveSheet()->setTitle('Orden de trabajo');
	
		for ($i = 0; $i < $rows; $i++)
        	$ot[] = mysql_fetch_assoc($result);

        //fecha de creacion de la solicitud
        $rs_fecha =  mysql_query("SELECT inicio FROM `historial_ot` WHERE `orden_de_trabajo_idorden_de_trabajo`=$nro_ot 
	 		AND `estado`='CREADA' LIMIT 1") or die(mysql_error());
		$row_fecha = mysql_fetch_row($rs_fecha);

        //Obtengo el centro de costo
        $rs_cc = mysql_query("SELECT `guia_personas`.`centro_de_costo` AS centro1, `guia_lugaresycargos`.`centro_de_costo` AS centro2 FROM `guia_personas`, `guia_lugaresycargos` 
        	WHERE `guia_personas`.`centro_de_costo` = ".$ot[0]['anexo']." OR 
        	`guia_lugaresycargos`.`centro_de_costo` = ".$ot[0]['anexo']) or die(mysql_error());
        $row_cc = mysql_fetch_row($rs_cc);
        if(isset($row_cc[0]['centro1']))
        	$centro_de_costo = $row_cc[0]['centro1'];
    	else if (isset($row[0]['centro2']))
    		$centro_de_costo = $row_cc[0]['centro2'];
    	else
    		$centro_de_costo = 'No existen datos.';
		
		//Nombre de la operadora
    	$rs2 = mysql_query("SELECT usuario.nombre FROM perfil, usuario 
    		WHERE idperfil=perfil_idperfil AND perfil.nombre='operadora' ") or die(mysql_error());
    	$row2 = mysql_fetch_row($rs2);

    	
    	$rs3 = mysql_query("SELECT * FROM guia_datostecnicos WHERE anexo=".$ot[0]['anexo']) or die(mysql_error());
		$row3 = mysql_fetch_row($rs3);

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo CMP');
		$objDrawing->setPath('../images/lwis.celebrity/logo_cmp.png');
		$objDrawing->setCoordinates('A1');
		//$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
		//$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(35);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'ORDENES DE TRABAJO PENDIENTES');
		$objPHPExcel->getActiveSheet()->setCellValue('D2', 'CONTRATO ENTEL');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Fecha: '.date('H:i:s d/m/Y'));
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'O.T. Nro.: '.$nro_ot);
		$objPHPExcel->getActiveSheet()->setCellValue('A6', 'ANTECEDENTES');
		$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Solicita Sr.: '.$ot[0]['nombre'].' '.$ot[0]['apellido']);
		$fecha = new DateTime($row_fecha[0]);
		$objPHPExcel->getActiveSheet()->setCellValue('E8', 'Fecha de ingreso: '.$fecha->format('H:i:s d-m-Y'));
		$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Anexo: '.$ot[0]['anexo']);
		$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Recibe: '.$row2[0]);
		$objPHPExcel->getActiveSheet()->setCellValue('A10', 'Lugar: '.$ot[0]['faena'].', '.$ot[0]['ciudad']);
		$objPHPExcel->getActiveSheet()->setCellValue('A11', 'Centro de costos: '.$centro_de_costo);
		$objPHPExcel->getActiveSheet()->setCellValue('A13', 'TIPO DE SOLICITUD');
		$objPHPExcel->getActiveSheet()->setCellValue('A15', $ot[0]['tipo_ot'].', '.$ot[0]['subtipo_ot']);
		$objPHPExcel->getActiveSheet()->setCellValue('A17', 'DESCRIPCIÓN');
		$objPHPExcel->getActiveSheet()->setCellValue('A19', $ot[0]['descripcion']);
		$objPHPExcel->getActiveSheet()->setCellValue('A22', 'OBSERVACIONES');
		$objPHPExcel->getActiveSheet()->setCellValue('A24', $ot[0]['observaciones']);
		$objPHPExcel->getActiveSheet()->setCellValue('A27', 'INFORMACIÓN TÉCNICA');
		$objPHPExcel->getActiveSheet()->setCellValue('A29', 'VARIOS');
		$objPHPExcel->getActiveSheet()->setCellValue('A31', 'Ejecuta');
		$objPHPExcel->getActiveSheet()->setCellValue('E31', 'Fecha de atención');
		$objPHPExcel->getActiveSheet()->setCellValue('A37', 'DATOS DEL ANEXO');
		$objPHPExcel->getActiveSheet()->setCellValue('A39', 'Tipo: '.$row3[2]);
		$objPHPExcel->getActiveSheet()->setCellValue('A40', 'SAP: '.$row3[4]);
		$objPHPExcel->getActiveSheet()->setCellValue('A41', 'Modelo: '.$row3[7]);
		$objPHPExcel->getActiveSheet()->setCellValue('E39', 'MAC: '.$row3[9]);
		$objPHPExcel->getActiveSheet()->setCellValue('E40', 'Switch-Puerta: '.$row3[10]);
		$objPHPExcel->getActiveSheet()->setCellValue('A43', 'OBSERVACIONES');

		//ancho de columna
		// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

		$objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray(
			array(
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
 			)
		);
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		$objPHPExcel->getActiveSheet()->getStyle('A13')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		$objPHPExcel->getActiveSheet()->getStyle('A17')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		$objPHPExcel->getActiveSheet()->getStyle('A22')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		$objPHPExcel->getActiveSheet()->getStyle('A27')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		
		//Tipo de fuente
		$objPHPExcel->getActiveSheet()->getStyle('A1:L100')->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A1:L100')->getFont()->setSize(8);

		//Borde de la tabla
		$objPHPExcel->getActiveSheet()->getStyle('A8:I11')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A15:I15')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A19:I20')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A24:I25')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A32:D35')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A32:D35')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('E32:G35')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A39:I41')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A45:I46')->applyFromArray(
			array(
				'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		 		)
		);


		$objPHPExcel->setActiveSheetIndex(0);
		unset($_GET['nro_ot']);
		
		//Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reporte_imprimible.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
endif;


?>
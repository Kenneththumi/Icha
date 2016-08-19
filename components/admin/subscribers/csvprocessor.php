<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

set_time_limit(0);

//xls process function
function csv_to_mysql($source_file, $max_line_length=10000) 
{
	$model = new template;
	
	//load the php excel files
	$model->loadextraClass('phpexcel');
	require_once(ABSOLUTE_PATH.'classes/addons/PHPExcel/IOFactory.php');
        
        // Cell caching to reduce memory usage.
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
        $cacheSettings = array( ' memoryCacheSize ' => '128MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

	$objReader = new PHPExcel_Reader_CSV();
        
        $objReader->setDelimiter(','); 
        $objReader->setEnclosure('');
        $objReader->setLineEnding("\r\n");
        $objReader->setSheetIndex(0);

        $objPHPExcel = $objReader->load($source_file);
        
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($row = 1; $row <= $highestRow; ++$row)
        {
                $data = array();
                for ($col = 0; $col <= $highestColumnIndex; ++$col)
                { 
                    $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $data[$col]=trim($value);
                }
                //$result[] = $data;
//var_dump($data);
                //STEP ONE: save the subscriber data
                if(!filter_var($data[0],FILTER_VALIDATE_EMAIL))
                {
                    $name = $data[0].' '.$data[1].' '.$data[2];                    
                    $email = $data[14];
                }
                elseif($data[0]==''&&$data[14]!='')
                {
                    $columndata = explode('@',$data[14]);                    
                    $name = ucfirst($columndata[0]);                    
                    $email = $data[14];
                }
                else {
                    $columndata = explode('@',$data[0]);                    
                    $name = ucfirst($columndata[0]);                    
                    $email = $data[0];
                }
                
                if($email!='')
                {
                    $csvsave = array(
                                    'name' => $name,
                                    'email' => $email,
                                    'catidsubscribed' => '0',
                                    'enabled' => 'yes',
                                    'regdate' => time()
                            );

                    $model->dbinsert('subscribers',$csvsave);
                }
        }

        return $worksheetTitle;  
}
?>
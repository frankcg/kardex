<?php 

class indicadorController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('asignacion');
		$this->_view->evaluacion=$objModel->getevaluciones();
		$this->_view->renderizar('index');
	}

	public function getcumplimiento($idevaluacion, $indice){

		$objModel=$this->loadModel('indicador');
		$result = $objModel->getcumplimiento($idevaluacion);

		while($reg=$result->fetch_object()){

			if($indice==1){
				$indicador = $reg->INDICADOR_CUMPLIMIENTO;
				$cantidad_inconclusa=$reg->CANT_INCONCLUSAS;
			}else{
				$indicador = $reg->INDICADOR_EFICACIA;
				$cantidad_inconclusa=$reg->NO_REALIZADA;
			}

			
			$data ['data'] [] = array (
				'FECHA'=>$reg->FECHA,
				'AREA'=>$reg->area,
				'CANT_PREVISTA'=>$reg->CANT_PREVISTA,
				'CANT_REALIZADA'=>$reg->CANT_REALIZADA,
				'CANT_VERAZ'=>$reg->CANT_VERAZ,
				'CANT_INCONCLUSAS'=>$cantidad_inconclusa,
				'INDICADOR'=>$indicador.' %'
				);
		}
		echo json_encode ( $data );
	}

	public function getdashboard(){

		$idevaluacion = $_POST['idevaluacion'];
		$indice = $_POST['indice'];

		$objModel=$this->loadModel('indicador');
		$result = $objModel->getcumplimiento($idevaluacion);

		$indicador_gd = 0;
		$indicador_efi = 0;
		$cant_prevista = 0;
		$cant_realizada = 0;
		$cant_veraz = 0;
		$cant_inconclusas = 0;
		$cant_norealizada = 0;
		$cont=0;

		while($reg=$result->fetch_object()){

			$cont++;
			$indicador_gd = $indicador_gd + $reg->INDICADOR_CUMPLIMIENTO;	
			$indicador_efi = $indicador_efi + $reg->INDICADOR_EFICACIA;
			$cant_prevista = $cant_prevista + $reg->CANT_PREVISTA;
			$cant_realizada = $cant_realizada + $reg->CANT_REALIZADA;
			$cant_veraz = $cant_veraz + $reg->CANT_VERAZ;	
			$cant_inconclusas = $cant_inconclusas + $reg->CANT_INCONCLUSAS;
			$cant_norealizada = $cant_norealizada + $reg->NO_REALIZADA;
			
		}

		$data = array (				
				'CANT_PREVISTA'=>$cant_prevista,
				'CANT_REALIZADA'=>$cant_realizada,
				'CANT_VERAZ'=>$cant_veraz,
				'CANT_INCONCLUSAS'=>$cant_inconclusas,
				'CANT_NO_REALIZADA'=>$cant_norealizada,
				'INDICADOR_GC'=>round($indicador_gd/$cont,2).' %',
				'INDICADOR_EFI'=>round($indicador_efi/$cont,2).' %'
		);

		echo json_encode ( $data );
	}

}

?>
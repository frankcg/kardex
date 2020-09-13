<?php 

class pruebaController extends Controller{
	
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

	public function getpendientes($idevaluacion){

		$objModel=$this->loadModel('prueba');
		$result=$objModel->getpendientes($idevaluacion);
		
		while($reg=$result->fetch_object()){

			$boton='<button id="'.$reg->CODIGOS.'" class="prueba btn btn-info btn-xs" title="Evaluar" data-toggle="modal" data-target="#m_dar_prueba"><span class="fa fa-check"></span></button>';
			
			$data ['data'] [] = array (
				'NOMBRE'=>$reg->NOMBRE,
				'CARGO'=>$reg->CARGO,
				'FECHA_ASIGNACION'=>$reg->fecha,
				'ASIGNADO_POR'=>$reg->IDUSUARIOCREACION,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );

	}

	public function getevaluacion_cabecera(){

		$array = explode(",", $_POST['codigos']);
		$idasignacion = $array[0];
		$idevaluacion = $array[1];
		$idevaluado = $array[2];
		$idevaluador = $array[3];

		$objModel=$this->loadModel('prueba');
		$result=$objModel->getevaluacion_cabecera($idevaluacion);

		$html=''; $cont=0;

		while($reg=$result->fetch_object()){
			$cont++;
			$html.='<li id="'.$reg->IDCOMPETENCIA.'" class="active" data-target="#'.$reg->IDCOMPETENCIA.'">
                      <span class="wizard-step-number">'.$cont.'</span>
                      <span class="wizard-step-complete"><i class="fa fa-check text-success"></i></span>
                      <span class="wizard-step-caption">'.$reg->NOMBRE.'
                        <span class="wizard-step-description">Marque solo una opcion</span>
                      </span>
                    </li>';		
		}
		echo $html;
	}

	public function getevaluacion_body(){

		$array = explode(",", $_POST['codigos']);
		$idasignacion = $array[0];
		$idevaluacion = $array[1];
		$idevaluado = $array[2];
		$idevaluador = $array[3];

		$objModel=$this->loadModel('prueba');
		$result=$objModel->getevaluacion_body($idevaluacion);

		$html=''; $cont=0;

		while($reg=$result->fetch_object()){			

			$array_conducta = array();		

			$cont++;
			if($cont==1){
				$active="active";
				$anterior="";
			}else{
				$active="";
				$anterior='<button type="button" id2="'.$reg->PREV.'"  id3="'.$reg->FILA.'" id4="'.$reg->IDCOMPETENCIA.'" class="prev btn" >Anterior</button>';
			}

			if($reg->COMPETENCIA == 'FINALIZAR'){
				$siguiente='<button type="button" id="btn_finalizar" class="finalizar btn btn-success">Finalizar</button>';
			}else{
				$siguiente='<button type="button" id2="'.$reg->NEXT.'"  id3="'.$reg->FILA.'" id4="'.$reg->IDCOMPETENCIA.'" class="next btn btn-primary">Siguiente</button>';
			}

			$html.='<div class="wizard-pane '.$active.'" id="'.$reg->FILA.'">
                    <h4 class="m-y-2">'.$reg->COMPETENCIA.'</h4> <input style="display: none;" type="text" name="idcompetencia[]" value="'.$reg->IDCOMPETENCIA.'">  <p>';

            $array_conducta = explode(",", $reg->CONDUCTA);
            $array_idconducta = explode(",", $reg->IDCONDUCTA);
			foreach ($array_conducta as $key => $value) {

				if($reg->COMPETENCIA == 'FINALIZAR'){
					$html.='Verifique las respuestas antes de Finalizar';
				}
				else{
					$html.='
						<label class="custom-control custom-radio">
	                      <input type="radio" name="'.$reg->IDCOMPETENCIA.'" class="custom-control-input" value="'.$array_idconducta[$key].'">
	                      <span class="custom-control-indicator"></span>'.$array_conducta[$key].'
	                    </label>';
                }
            }           

            $html.='
            	</p>
                  <div class="pull-right">
					'.$anterior.'
					'.$siguiente.'                    
                  </div>
                </div>';		
		}
		echo $html;
	}

	public function addprueba(){

		$array = explode(",", $_POST['codigos']);

		$idasignacion = $array[0];
		$idevaluacion = $array[1];
		$idevaluado = $array[2];
		$idevaluador = $array[3];

		$array_idcompetencia=$_POST['idcompetencia'];		

		for ($i=0; $i<count($array_idcompetencia)-1; $i++){
			$idcompetencia[]=$array_idcompetencia[$i];
			if($_POST[$array_idcompetencia[$i]]>0)
				$idconducta[]=$_POST[$array_idcompetencia[$i]];
		}

		$objModel=$this->loadModel('prueba');

		if(count($idcompetencia) == count($idconducta)){
			foreach ($idcompetencia as $key => $value){
			$result=$objModel->addprueba($idasignacion, $idevaluacion, $idevaluado, $idevaluador, $idcompetencia[$key], $idconducta[$key]);			
			}

			$objModel->calcularpeso($idasignacion, $idevaluacion, $idevaluado, $idevaluador);
			if($result) echo 1; else echo 0;
		}else{
			echo 'Responda todas las competencias';
		}

		

		//var_dump($idcompetencia);
		//var_dump($idconducta);
		
		

		/*$objModel=$this->loadModel('prueba');
		$result=$objModel->addprueba($idasignacion, $idevaluacion, $idevaluado, $idevaluador);
		if($result) echo 1; else echo 0;	*/	
	}




}

?>

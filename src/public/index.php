<?php

//----------------------------------------------
//models
Class Employee{

	var $id;
	var $name;
	var $email;
	var $phone;
	var $address;
	var $position;
	var $salary;
	var $skills;

	function __construct($id,$name,$email,$phone,$address,$position,$salary,$skills){

		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->phone = $phone;
		$this->address = $address;
		$this->position = $position;
		$this->salary = $salary;
		$this->skills = $skills;
	}

	function get_id(){
		return $this->id;
	}
	function set_id($id){
		$this->id = $id;
	}

	function get_name(){
		return $this->name;
	}
	function set_name($name){
		$this->name = $name;
	}

	function get_email(){
		return $this->email;
	}
	function set_email($email){
		$this->email = $email;
	}

	function get_phone(){
		return $this->phone;
	}
	function set_phone($phone){
		$this->phone = $phone;
	}

	function get_address(){
		return $this->address;
	}
	function set_address($address){
		$this->address = $address;
	}

	function get_position(){
		return $this->position;
	}
	function set_position($position){
		$this->position = $position;
	}

	function get_salary_float(){
		return floatval(str_replace(["$",","],"",$this->salary));
	}

	function get_salary(){
		return $this->salary;
	}

	function set_salary($salary){
		$this->salary = $salary;
	}

	function get_skills(){
		return $this->skills;
	}
	function set_skills($skills){
		$this->skills = $skills;
	}
}
//----------------------------------------------
//utils
Class MyJson{

	function get_json(){

		return file_get_contents(__DIR__.'/../public/employees.json');
	}
}

//----------------------------------------------
//models - DAO
Class Employees{

	private $myJson;

	function get_empleados(){

		$arch = $this->myJson->get_json();
		$empleados = json_decode($arch);

		$listado = array();
		foreach($empleados as $reg){
		
			$empleado = new Employee( $reg->id,
										$reg->name,
										$reg->email,
										$reg->phone,
										$reg->address,
										$reg->position,
										$reg->salary,
										$reg->skills);
								
			array_push($listado,$empleado);
		}

		return $listado;
	}

	function get_empleado($id){

		$arch = $this->myJson->get_json();
		$empleados = json_decode($arch);

		foreach($empleados as $reg){

			$empleado = new Employee( $reg->id,
										$reg->name,
										$reg->email,
										$reg->phone,
										$reg->address,
										$reg->position,
										$reg->salary,
										$reg->skills);

			if($empleado->get_id() == $id)
				return $empleado;
		}

		return null;
	}

	function busqueda_mail($cadena){

		$arch = $this->myJson->get_json();
		$empleados = json_decode($arch);

		$listado = array();
		foreach($empleados as $reg){

			$empleado = new Employee( $reg->id,
										$reg->name,
										$reg->email,
										$reg->phone,
										$reg->address,
										$reg->position,
										$reg->salary,
										$reg->skills);

			$pos = strpos($empleado->get_email(),$cadena);

			if($pos !== false){

				array_push($listado,$empleado);
			}
		}

		return $listado;
	}

	function busqueda_salario($min,$max){

		$arch = $this->myJson->get_json();
		$empleados = json_decode($arch);

		$listado = array();
		foreach($empleados as $reg){

			$empleado = new Employee( $reg->id,
											$reg->name,
											$reg->email,
											$reg->phone,
											$reg->address,
											$reg->position,
											$reg->salary,
											$reg->skills);

			$salary = $empleado->get_salary_float();
			if( $salary >= $min and $salary < $max)
				array_push($listado,$empleado);
		}
		return $listado;
	}

	function set_myJson($myJson){

		$this->myJson = $myJson;
	}

}

//----------------------------------------------

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

$container = $app->getContainer();

$container['view'] = function($container){

	$view = new \Slim\Views\Twig(__DIR__.'/../templates',[]);
};

/*
	$myjson = new MyJson();
	$employees = new Employees();
	$employees->set_myJson($myjson);
*/

#GET: ../employees 
$app->get('/employees', function ($request, $response) {

	$myjson = new MyJson();
	$employees = new Employees();
	$employees->set_myJson($myjson);

	$empleados = $employees->get_empleados();
	$response->write(print_r($empleados));

	return $response;
});

#GET: ../employees/:id
$app->get('/employees/{id}', function ($request, $response, $args) {

	$myjson = new MyJson();
	$employees = new Employees();
	$employees->set_myJson($myjson);

	$id = $args["id"];
	$empleado = $employees->get_empleado($id);
	$response->write(print_r($empleado));

	return $response;
});

#GET: ../findemployees/:mail
$app->get('/find_employees_mail/{mail}', function ($request, $response, $args) {

	$myjson = new MyJson();
	$employees = new Employees();
	$employees->set_myJson($myjson);

	$mail = $args["mail"];
	$empleados = $employees->busqueda_mail($mail);
	$response->write(print_r($empleados));

	return $response;
});


#GET: ../findemployees/:min/:max
$app->get('/find_employees_salary/{min}/{max}', function ($request, $response, $args) {

	$myjson = new MyJson();
	$employees = new Employees();
	$employees->set_myJson($myjson);

	$min = $args["min"];
	$max = $args["max"];

	$empleados = $employees->busqueda_salario($min,$max);

	/*
	$response->write(print_r($empleados));
	*/
	
	$response = $response->withHeader('Content-Type', 'text/xml');

	return $response->getBody()->write('rss.xml',$empleados);
});

$app->run();
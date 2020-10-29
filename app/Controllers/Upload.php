<?php namespace App\Controllers;

class Upload extends BaseController
{

	var $session;

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		$this->session = session();
	}

	public function index()
	{
		echo view("upload/index.php", array());
	}

	public function add() 
	{
		// todo
		require_once(APPPATH . "ThirdParty/tinify/lib/Tinify/Exception.php");
		require_once(APPPATH . "ThirdParty/tinify/lib/Tinify/ResultMeta.php");
		require_once(APPPATH . "ThirdParty/tinify/lib/Tinify/Result.php");
		require_once(APPPATH . "ThirdParty/tinify/lib/Tinify/Source.php");
		require_once(APPPATH . "ThirdParty/tinify/lib/Tinify/Client.php");
		require_once(APPPATH . "ThirdParty/tinify/lib/Tinify.php");

		$api_key = "YOURKEYHERE";

		\Tinify\setKey($api_key);

		$new_upload = $this->request->getFile("new_upload");

		if ($new_upload->getSize() > 0) {

			// Featured Image Check
			$validation =  \Config\Services::validation();
			$validation->setRules(array(
				'new_upload' => 'uploaded[new_upload]|max_size[new_upload,10000]|mime_in[new_upload,image/jpg,image/jpeg,image/gif,image/png]'
				)
			);

			if(!$validation->run()) {
				$errors = $validation->getErrors();
				var_dump($errors);
				exit();
			} else {

				$upload_path = 'images/uploads/';
				$upload_path_root = ROOTPATH . 'public/' . $upload_path;
				
			
				try {
					$new_upload->move($upload_path_root);
				} catch(Exception $e) {
					die($e->getMessage());
				}

				$file_name = $new_upload->getName();
				$file_path = $upload_path_root . "/". $file_name;

				// TinyPNG Code here
				$file = \Tinify\fromFile($file_path);
				$file->toFile($file_path);
				echo "Completed. File: " . $file_path;
			}
		} else {
			echo "No file selected";
			exit();
		}

	}

}

?>

<?php
// Dev
require 'db_config.php';
require 'FormHandler.php';

// Dev Instantiate
$formHandler = new FormHandler($conn);

// Dev Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dev Create form
    if (isset($_GET['create_form'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        $formId = $formHandler->createForm($data);
        echo json_encode(["status" => "success", "form_id" => $formId]);
    }
    // Handle form submissions
    elseif (isset($_GET['form_id']) && !isset($_GET['submit_form'])) {
        $formId = intval($_GET['form_id']);
		$data = json_decode(file_get_contents('php://input'), true);
		
		// Dev Check if ID exists
		$formStructureJson = $formHandler->getForm($formId);
		if (!$formStructureJson) {
			// 404
			http_response_code(404);
			echo json_encode(["status" => "error", "message" => "Form ID not found"]);
			return;
		}
    }
	
	elseif (isset($_GET['form_id']) && isset($_GET['submit_form'])) {
		$formId = intval($_GET['form_id']);
    
		// Read JSON form
		$inputJson = file_get_contents('php://input');
		$inputData = json_decode($inputJson, true);
		
		// Handle form submission
		if ($formHandler->handleSubmission($formId, $inputData)) {
			// Extract and encode
			$formDataJson = json_encode($inputData['formData']);
			
			// Insert form data
			$stmt = $conn->prepare("INSERT INTO submissions (form_id, submission_data) VALUES (?, ?)");
			$stmt->bind_param("is", $formId, $formDataJson);
			
			if ($stmt->execute()) {
				http_response_code(201); // Created
				// Dev Send Email
				$formStructureJson = $formHandler->getForm($formId);
				// Convert form 
				$formStructure = json_decode($formStructureJson, true);
				// Dev Filter the form data foe email
				$emailFields = $formHandler->filterFormDataForEmail($formStructure, $inputData);
				
				// Dev Format HTML email
				$emailContent = $formHandler->formatEmailContent($emailFields);
				
				// Dev Send the HTML email
				$recipients = 'murtazanawaz2008@gmail.com'; 
				$subject = 'Form Submission';
				
				if ($formHandler->sendEmail($recipients, $subject, $emailContent)) {
					// Email sent
					echo json_encode(["status" => "success", "message" => "Form submitted and email sent successfully"]);
				} else {
					// Email failed
					echo json_encode(["status" => "error", "message" => "Form submitted but failed to send email"]);
				}
				$stmt->close();
			} else {
				// Log db error
				error_log("Database error: " . $stmt->error);
				http_response_code(500); 
				echo json_encode(["status" => "error", "message" => "Data storage error"]);
			}
		} else {
			http_response_code(400); 
			echo json_encode(["status" => "error", "message" => "Validation failed or data storage error"]);
		}
	}
}

// Dev Handle GET requests
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['form_id'])) {
    $formId = intval($_GET['form_id']);
    $formStructure = $formHandler->getForm($formId);
    echo $formStructure;
}

// Dev Endpoint to fetch
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['list_entries'])) {
    $formHandler = new FormHandler($conn);
    $entries = $formHandler->fetchSubmittedForms(); 
    echo json_encode($entries);
}

// fetch a form by ID
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['form_id'])) {
    $formId = intval($_GET['form_id']);
    $formHandler = new FormHandler($conn);
    $form = $formHandler->fetchFormById($formId);
    echo json_encode($form);
}
?>

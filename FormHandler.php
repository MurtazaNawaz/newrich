<?php
// Dev FormHandler
class FormHandler {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // dev create a new form
    public function createForm($formStructure) {
		$formStructureJson = json_encode($formStructure); 
		$stmt = $this->conn->prepare("INSERT INTO forms (form_structure) VALUES (?)");
		$stmt->bind_param("s", $formStructureJson); 
		$stmt->execute();
		// return form ID
		return $this->conn->insert_id; 
	}

    // fetch a form structure by ID
    public function getForm($formId) {
        $stmt = $this->conn->prepare("SELECT form_structure FROM forms WHERE id = ?");
        $stmt->bind_param("i", $formId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['form_structure'];
        } else {
            return null;
        }
    }

    // validate the submission data
	public function validateFormData($formStructure, $submissionData) {
		// array errors
		$errors = [];
		
		// iterate
		foreach ($formStructure['fields'] as $field) {
			$name = $field['name'];
			if (!empty($submissionData['honeypot'])) {
				$errors[] = "Bot detected.";
			}
			$value = isset($submissionData[$name]) ? $submissionData[$name] : null;

			// Dev check required field
			if ($field['required'] && (is_null($value) || trim($value) === '')) {
				// add error message
				$errors[] = "{$field['label']} is required.";
			}
		}

		// return errors
		if (!empty($errors)) {
			return $errors;
		}
		
		// return an empty array
		return [];
	}

    // handle submissions
    public function handleSubmission($formId, $submissionData) {
        // dev Fetch the form structure
        $formStructureJson = $this->getForm($formId);
		
        if (!$formStructureJson) {
            return false;
        }

        // convert form structure
        $formStructure = json_decode($formStructureJson, true);

        // validate submission
        if (!$this->validateFormData($formStructure, $submissionData)) {
            return $this->validateFormData($formStructure, $submissionData); 
        } else {
			return true;
		}

        /*
        // Store the submissionin Db
        $stmt = $this->conn->prepare("INSERT INTO submissions (form_id, submission_data) VALUES (?, ?)");
        $submissionDataJson = json_encode($submissionData);
        $stmt->bind_param("is", $formId, $submissionDataJson);

        // Execute the query and return true if successful
        return $stmt->execute(); */
    }
	
	
	// filter form
	public function filterFormDataForEmail($formStructure, $formData) {
		$filteredData = [];
		
		// dev Iterate
		foreach ($formStructure['fields'] as $field) {
			$name = $field['name'];
			$value = isset($formData[$name]) ? $formData[$name] : null;
			
			// check email field
			if (isset($field['sendWithEmail']) && $field['sendWithEmail']) {
				$filteredData[$field['label']] = $value;
			}
		}
		
		return $filteredData;
	}

	// format email
	public function formatEmailContent($emailFields) {
		$htmlContent = '<html><body>';
		$htmlContent .= '<h1>Form Submission</h1>';
		$htmlContent .= '<table>';
		
		// iterate field
		foreach ($emailFields as $label => $value) {
			$htmlContent .= '<tr>';
			$htmlContent .= '<td><strong>' . htmlspecialchars($label) . '</strong></td>';
			$htmlContent .= '<td>' . htmlspecialchars($value) . '</td>';
			$htmlContent .= '</tr>';
		}
		
		$htmlContent .= '</table>';
		$htmlContent .= '</body></html>';
		
		return $htmlContent;
	}

	// dev send HTML email
	public function sendEmail($recipients, $subject, $emailContent) {
		// Here, I'll use PHP's mail() function for demo
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$headers .= 'From: no-reply@example.com' . "\r\n";
		
		return mail($recipients, $subject, $emailContent, $headers);
	}
	
	// fetch submitted forms
    public function fetchSubmittedForms() {
        $stmt = $this->conn->prepare("SELECT id, created_at FROM forms");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // fetch form data by ID
    public function fetchFormById($formId) {
        $stmt = $this->conn->prepare("SELECT * FROM forms WHERE id = ?");
        $stmt->bind_param("i", $formId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

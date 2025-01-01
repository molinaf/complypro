<?php
// Load the workgroup.json file
$workgroupFile = 'workgroup.json';
$jsonData = json_decode(file_get_contents($workgroupFile), true);
$workgroups = $jsonData['records'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Power Services - Work Practices and Training - Authorisation Course Listing</title>
<link rel="stylesheet" href="css\style.css">
    <script>
        function toggleCompanyInput() {
            const companyType = document.getElementById('company').value;
            const contractorInput = document.getElementById('contractorNameGroup');
            if (companyType === 'Contractor') {
                contractorInput.style.display = 'block';
            } else {
                contractorInput.style.display = 'none';
                document.getElementById('contractorName').value = '';
            }
        }

        function validateForm(event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const company = document.getElementById('company').value;
            const contractorName = document.getElementById('contractorName').value.trim();
            const workgroup = document.getElementById('workgroup').value;

            let errors = [];

            if (name === '') {
                errors.push("Name is required.");
            }

            if (email === '') {
                errors.push("Email is required.");
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errors.push("Invalid email format.");
            }

            if (company === '') {
                errors.push("Company is required.");
            }

            if (company === 'Contractor' && contractorName === '') {
                errors.push("Contractor company name is required.");
            }

            if (workgroup === '') {
                errors.push("Workgroup is required.");
            }

            if (errors.length > 0) {
                alert("Please fix the following errors:\n" + errors.join("\n"));
                event.preventDefault(); // Prevent form submission
                return false;
            }
            return true; // Allow form submission
        }
    </script>
</head>
<body>
<div class="login-container" style="text-align: center;">
<a href="home.php"><img src="images\logo.png" alt="Logo Image" style="width: 194px; display: inline-block;"></a>
    <h2>Power Services | Enrolment</h2>
    <form action="apply.php" method="POST" onsubmit="return validateForm(event)">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="company">Company:</label>
            <select id="company" name="company" onchange="toggleCompanyInput()" required>
                <option value="">Select your company</option>
                <option value="PWC">PWC</option>
                <option value="Contractor">Contractor</option>
            </select>
        </div>
        <div id="contractorNameGroup" class="form-group contractor-group">
            <label for="contractorName">Contractor Company Name:</label>
            <input type="text" id="contractorName" name="contractorName">
        </div>
        <div class="form-group">
            <label for="workgroup">Workgroup:</label>
            <select id="workgroup" name="workgroup" required>
                <option value="">Select a workgroup</option>
                <?php foreach ($workgroups as $workgroup): ?>
                    <option value="<?php echo htmlspecialchars($workgroup['group']); ?>">
                        <?php echo htmlspecialchars($workgroup['group'].' - '.$workgroup['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
	<br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>

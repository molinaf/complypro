<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'dbFileClass.php';


if (isset($_GET['fname'])) {
    $fname = $_GET['fname'];
} elseif (isset($_POST['fname'])) { // Check for fname in POST parameters
    $fname = $_POST['fname'];
} else {
    $fname='' ;
}


//$fname = $_GET['fname']; 
echo $fname;
$fields = [];
$numfields = 0;
$message = '';
$record = null;print
$editEnabled = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['define_fields'])) {
        print_r($_POST);
        $numfields = (int)$_POST['numfields'];
        $fname = $_POST['fname'];
        if (substr($fname, -5) !== '.json') {
            $fname .= '.json'; // Append '.json' if not present
        }
        $fields = array_map('trim', explode(',', $_POST['fields']));
        $db = new FileDatabase($fname, $fields);
        $message = 'Fields defined successfully.';
    } elseif (file_exists($fname)) {
        $data = json_decode(file_get_contents($fname), true);
        $fields = $data['fields'];
        $db = new FileDatabase($fname, $fields);

        if (isset($_POST['add'])) {
            $recordData = [];
            foreach ($fields as $field) {
                $recordData[$field] = $_POST[$field] ?? '';
            }
            $message = $db->addRecord($recordData);
        } elseif (isset($_POST['edit'])) {
            $recordData = [];
            foreach ($fields as $field) {
                $recordData[$field] = $_POST[$field] ?? '';
            }
            $message = $db->editRecord($_POST['ID'], $recordData);
        } elseif (isset($_POST['delete'])) {
            $message = $db->deleteRecord($_POST['ID']);
        } elseif (isset($_POST['search'])) {
            $record = $db->searchRecord($_POST['ID']);
            if ($record) {
                $message = 'Record found.';
                $editEnabled = true;
            } else {
                $message = 'Record not found.';
            }
        }
    } else {
        $message = 'Please define the fields first.';
    }
}

$records = isset($db) ? $db->listRecords() : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Database</title>
</head>
<body>
	    <h1>PS - Work Practices and Training - Enrolment List</h1>
<style>
	body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #FFFFFF;
	}
	label {
	display: block;
    margin-bottom: 3px;
	}
	.tabs {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 10px;
    margin-bottom: 10px;	
    }
    .tab-button {
    background-color: #e6e6e6;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    text-transform: capitalize;
    transition: background-color 0.3s, color 0.3s;
    }
    .tab-button:hover {
    background-color: #0056b3;
    color: white;	
    }
	table {
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: white;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
        background-color: #e6e6e6;		
    }

    th {
        background-color: #DDDDDD;
    }
</style>
    <div class="tabs">
    <?php 
    // Get all .json files in the current directory
    $jsonFiles = glob('*.json'); 
    foreach ($jsonFiles as $file): ?>
            <a class="tab-button" href="#" onclick="submitPostForm('<?php echo $file; ?>')"</a><?php echo htmlspecialchars($file); ?></a>
    <?php endforeach; ?>
    </div>
    <div align="center"><form method="get" action="dbmtce.php">
        <button type="submit" name="fname" value="">Create New Database</button>
    </form></div>
    
    <form id="postForm" action="dbmtce.php" method="POST" style="display:none;">
        <input type="hidden" name="fname" id="hiddenFname">
    </form>
<script>
    function submitPostForm(fname) {
            // Set the hidden input's value dynamically
            document.getElementById('hiddenFname').value = fname;
            // Submit the form
            document.getElementById('postForm').submit();
    }
</script>
<hr>
    <h1>Enrolment Details : <?php echo $fname; ?></h1>
    <p><?php echo $message; ?></p>
    <?php if (!file_exists($fname)): ?>
        <form method="post">
            <label>Filename: <input type="text" name="fname" required></label>
            <label>Number of Fields: <input type="number" name="numfields" required></label>
            <label>Fields (comma-separated): <input type="text" name="fields" required></label>
            <button type="submit" name="define_fields">Define Fields</button>
        </form>
    <?php else: ?>
	<table style="width: 600px;">
        <form method="post">
					<input type="hidden" name="fname" value="<?php echo $fname; ?>">
            <label>ID (for edit/delete/search): <input type="text" name="ID" value="<?php echo $record['ID'] ?? ''; ?>"></label><br>
		<?php foreach ($fields as $field): ?>
			<tr>
                <?php if ($field !== 'ID'): ?>
                    <td width="25%"><?php echo ucfirst($field); ?>:</td><td width="75%"><input type="text" style="width: 100%; box-sizing: border-box;" name="<?php echo $field; ?>" value="<?php echo $record[$field] ?? ''; ?>"></td>
                <?php endif; ?>
			</tr>
        <?php endforeach; ?>
	</table><br>
            <button type="submit" name="add">Add Record</button>
            <button type="submit" name="edit" <?php echo $editEnabled ? '' : 'disabled'; ?>>Edit Record</button>
            <button type="submit" name="delete">Delete Record</button>
            <button type="submit" name="search">Search Record</button>
        </form>
	
			<?php endif; ?>
    <h2>Records</h2>
    <table border="1">
        <tr>
            <?php foreach ($fields as $field): ?>
                <th><?php echo ucfirst($field); ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($records as $record): ?>
            <tr>
                <?php foreach ($fields as $field): ?>
                    <td><?php echo htmlspecialchars($record[$field]); ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
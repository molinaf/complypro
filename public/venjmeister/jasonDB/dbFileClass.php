<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class FileDatabase {
    private $filename;
    private $fields;

    public function __construct($filename, $fields) {
        // Ensure 'ID' field is the first field
        if (!in_array('ID', $fields)) {
            array_unshift($fields, 'ID');
        } else {
            $fields = array_diff($fields, ['ID']);
            array_unshift($fields, 'ID');
        }

        $this->filename = $filename;
        $this->fields = $fields;
        if (!file_exists($filename)) {
            $this->initializeFile();
        } else {
            $this->updateFields();
        }
    }

    private function initializeFile() {
        echo($this->filename);
        $data = ['fields' => $this->fields, 'records' => []];
        file_put_contents($this->filename, json_encode($data));
    }

    private function readData() {
        return json_decode(file_get_contents($this->filename), true);
    }

    private function writeData($data) {
        file_put_contents($this->filename, json_encode($data));
    }

    private function updateFields() {
        $data = $this->readData();
        $existingFields = $data['fields'];
        $newFields = array_diff($this->fields, $existingFields);

        if (!empty($newFields)) {
            foreach ($data['records'] as &$record) {
                foreach ($newFields as $field) {
                    $record[$field] = '';
                }
            }
            $data['fields'] = array_merge($existingFields, $newFields);
            $this->writeData($data);
        }
    }

    public function addRecord($record) {
        $data = $this->readData();
        $record['ID'] = uniqid(); // Ensure unique ID for each record
        foreach ($data['records'] as $existingRecord) {
            if ($existingRecord['ID'] === $record['ID']) {
                return "ID must be unique.";
            }
        }
        foreach ($record as $key => $value) {
            if (!in_array($key, $this->fields)) {
                return "Error: Field '$key' is not a valid field name.";
            }
        }
        $newRecord = [];
        foreach ($this->fields as $field) {
            $newRecord[$field] = $record[$field] ?? '';
        }
        $data['records'][] = $newRecord;
        $this->writeData($data);
        return "Record added successfully.";
    }

    public function listRecords() {
        $data = $this->readData();
        return $data['records'];
    }

    public function deleteRecord($id) {
        $data = $this->readData();
        $newRecords = array_filter($data['records'], function($record) use ($id) {
            return $record['ID'] !== $id;
        });
        $data['records'] = array_values($newRecords);
        $this->writeData($data);
        return "Record deleted successfully.";
    }

    public function editRecord($id, $newRecord) {
        $data = $this->readData();
        foreach ($data['records'] as &$record) {
            if ($record['ID'] === $id) {
                foreach ($newRecord as $key => $value) {
                    if (!in_array($key, $this->fields)) {
                        return "Error: Field '$key' is not a valid field name.";
                    }
                }
                foreach ($this->fields as $field) {
                    $record[$field] = $newRecord[$field] ?? $record[$field];
                }
                $this->writeData($data);
                return "Record updated successfully.";
            }
        }
        return "Record not found.";
    }

    public function searchRecord($id) {
        $data = $this->readData();
        foreach ($data['records'] as $record) {
            if ($record['ID'] === $id) {
                return $record;
            }
        }
        return "Record not found.";
    }
}

// Example usage
/*$fields = ['group', 'name', 'email', 'type'];
$db = new FileDatabase('data.json', $fields);
echo $db->addRecord(['group' => 'group1', 'name' => 'John Doe', 'email' => 'john@example.com', 'type' => 'admin']);
print_r($db->listRecords());
echo $db->editRecord('group1', ['name' => 'Jane Doe', 'email' => 'jane@example.com', 'type' => 'user']);
print_r($db->searchRecord('group1'));
echo $db->deleteRecord('group1');
print_r($db->listRecords());
*/
?>
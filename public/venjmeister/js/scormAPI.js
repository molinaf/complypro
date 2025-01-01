// Define SCORM API in parent page
window.API = {
  LMSSetValue: function (param, value) {
    // Capture SCORM score or status when set
    if (param === "cmi.core.score.raw" || param === "cmi.core.lesson_status") {
      // Use session storage or another mechanism to store scores, if needed
      sessionStorage.setItem(param, value);
      // Trigger AJAX request if lesson_status is "completed" or "passed"
      if (param === "cmi.core.lesson_status" && (value === "completed" || value === "passed")) {
        // Create a form dynamically
				alert(param+":"+value);
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'update_application.php';

        // Add hidden inputs for the data
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'encodedID';
        idInput.value = encodedID;
        form.appendChild(idInput);

        // Append the form to the document and submit
        document.body.appendChild(form);
        form.submit();
      }
    }
    return "true";  // SCORM expects a string "true" on success
  },
  LMSGetValue: function (param) {
    return sessionStorage.getItem(param) || "";
  },
  LMSInitialize: function () { return "true"; },
  LMSFinish: function () { return "true"; },
  LMSCommit: function () { return "true"; },
  LMSGetLastError: function () { return "0"; }
};

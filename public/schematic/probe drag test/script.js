// Get the draggable elements
const blackProbe = document.getElementById('black-probe');
const redProbe = document.getElementById('red-probe');

// Initialize variables to store mouse position offsets
let offsetX, offsetY;

// Add mousedown event listeners to start drag for each probe
blackProbe.addEventListener('mousedown', startDrag.bind(blackProbe));
redProbe.addEventListener('mousedown', startDrag.bind(redProbe));

function startDrag(e) {
  // Calculate mouse position offsets from the top-left corner of the element
  offsetX = e.clientX - this.getBoundingClientRect().left;
  offsetY = e.clientY - this.getBoundingClientRect().top;
  
  // Add mousemove and mouseup event listeners to continue and end drag respectively
  document.addEventListener('mousemove', drag.bind(this));
  document.addEventListener('mouseup', endDrag.bind(this));
}

function drag(e) {
  // Update the position of the dragged element based on mouse position
  this.style.left = e.clientX - offsetX + 'px';
  this.style.top = e.clientY - offsetY + 'px';
}

function endDrag() {
  // Remove mousemove and mouseup event listeners to stop drag
  document.removeEventListener('mousemove', drag);
  document.removeEventListener('mouseup', endDrag);
}

// Get the draggable image elements
const draggableImageBlack = document.getElementById('draggableImageBlack');
const draggableImageRed = document.getElementById('draggableImageRed');

// Variables to store mouse and touch position offsets, and other variables
let offsetXBlack, offsetYBlack;
let offsetXRed, offsetYRed;
let isDraggingBlack = false;
let isDraggingRed = false;
let nodes = circuit.nodes
let prRed = -1
let prBlack =-1
let mouseScaling = 1

// Function to start dragging for black probe
function startDragBlack(event) {
    // Prevent default mouse or touch behavior
    event.preventDefault();

    // Calculate position offsets based on the type of event
    if (event.type === 'mousedown') {
        offsetXBlack = event.clientX - draggableImageBlack.getBoundingClientRect().left;
        offsetYBlack = event.clientY - draggableImageBlack.getBoundingClientRect().top;
    } else if (event.type === 'touchstart') {
        const touch = event.touches[0];
        offsetXBlack = touch.clientX - draggableImageBlack.getBoundingClientRect().left;
        offsetYBlack = touch.clientY - draggableImageBlack.getBoundingClientRect().top;
    }

    // Add event listeners for move and stop events based on the type of event
    if (event.type === 'mousedown') {
        document.addEventListener('mousemove', moveBlack);
        document.addEventListener('mouseup', stopBlack);
    } else if (event.type === 'touchstart') {
        document.addEventListener('touchmove', moveBlack);
        document.addEventListener('touchend', stopBlack);
    }

    isDraggingBlack = true;
}

// Function to move the black probe
function moveBlack(event) {
    if (!isDraggingBlack) return;

    let clientX, clientY;

    // Calculate new position based on the type of event
    if (event.type === 'mousemove') {
        clientX = event.clientX;
        clientY = event.clientY;
    } else if (event.type === 'touchmove') {
        const touch = event.touches[0];
        clientX = touch.clientX;
        clientY = touch.clientY;
    }

    // Calculate new position of the black probe
    const x = (clientX - offsetXBlack)/mouseScaling;
    const y = (clientY - offsetYBlack)/mouseScaling;

    // Set the new position of the black probe
    draggableImageBlack.style.left = x + 'px';
    draggableImageBlack.style.top = y + 'px';

    // Check if the black probe is inside the droppable area and change background color accordingly
    prBlack = checkDropZone(x, y);
	displayVoltage();
}

// Function to stop dragging for black probe
function stopBlack() {
    if (isDraggingBlack) {
        // Remove event listeners based on the type of event
        document.removeEventListener('mousemove', moveBlack);
        document.removeEventListener('mouseup', stopBlack);
        document.removeEventListener('touchmove', moveBlack);
        document.removeEventListener('touchend', stopBlack);
        isDraggingBlack = false;
    }
}

// Function to start dragging for red probe
function startDragRed(event) {
    // Prevent default mouse or touch behavior
    event.preventDefault();

    // Calculate position offsets based on the type of event
    if (event.type === 'mousedown') {
        offsetXRed = event.clientX - draggableImageRed.getBoundingClientRect().left;
        offsetYRed = event.clientY - draggableImageRed.getBoundingClientRect().top;
    } else if (event.type === 'touchstart') {
        const touch = event.touches[0];
        offsetXRed = touch.clientX - draggableImageRed.getBoundingClientRect().left;
        offsetYRed = touch.clientY - draggableImageRed.getBoundingClientRect().top;
    }

    // Add event listeners for move and stop events based on the type of event
    if (event.type === 'mousedown') {
        document.addEventListener('mousemove', moveRed);
        document.addEventListener('mouseup', stopRed);
    } else if (event.type === 'touchstart') {
        document.addEventListener('touchmove', moveRed);
        document.addEventListener('touchend', stopRed);
    }

    isDraggingRed = true;
}

// Function to move the red probe
function moveRed(event) {
    if (!isDraggingRed) return;

    let clientX, clientY;

    // Calculate new position based on the type of event
    if (event.type === 'mousemove') {
        clientX = event.clientX;
        clientY = event.clientY;
    } else if (event.type === 'touchmove') {
        const touch = event.touches[0];
        clientX = touch.clientX;
        clientY = touch.clientY;
    }

    // Calculate new position of the red probe
    const x = (clientX - offsetXRed)/mouseScaling;
    const y = (clientY - offsetYRed)/mouseScaling;

    // Set the new position of the red probe
    draggableImageRed.style.left = x + 'px';
    draggableImageRed.style.top = y + 'px';

    // Check if the red probe is inside the droppable area and change background color accordingly
    prRed = checkDropZone(x, y);
	displayVoltage();
}

// Function to stop dragging for red probe
function stopRed() {
    if (isDraggingRed) {
        // Remove event listeners based on the type of event
        document.removeEventListener('mousemove', moveRed);
        document.removeEventListener('mouseup', stopRed);
        document.removeEventListener('touchmove', moveRed);
        document.removeEventListener('touchend', stopRed);
        isDraggingRed = false;
    }
}

// Function to check if the probe is inside the droppable area and change background color accordingly
function checkDropZone(x,y) {
	
    // Calculate the position of the probe's center
    const probeCenterX = x + 50; // Adjust as needed (50 is half the width of the probe)
    const probeCenterY = y + 50; // Adjust as needed (50 is half the height of the probe)
	for (let i=0; i<nodes.length; i++) {
	    const circleCenterX = document.getElementById("Node" + i).offsetLeft + 150; // Adjust as needed (20 is half the width of the circle)
	    const circleCenterY = document.getElementById("Node" + i).offsetTop + 150; // Adjust as needed (20 is half the height of the circle)
	
	    // Calculate the distance between the probe's center and the circle's center
	    const distance = Math.sqrt(Math.pow(probeCenterX - circleCenterX, 2) + Math.pow(probeCenterY - circleCenterY, 2));
	
	    // Check if the distance is less than or equal to the radius of the circle (20mm) and change background color accordingly
		if (distance <= 20) {
	        document.getElementById("Node" + i).style.backgroundColor = "green";
			return i;
	    } else {
	        document.getElementById("Node" + i).style.backgroundColor = "#eeeeee";
	    }
	}
	return -1;
}
 function displayVoltage() {
	 if ((prBlack>=0)&&(prRed>=0)){
		 // Search for an element where 'name' is "Rmeter"
		let desiredElement = circuit.elements.find(elem => elem.name === "Rmeter");
		if (desiredElement) {
			// Modify properties of the found element
			desiredElement.node1 = prBlack;
			desiredElement.node2 = prRed;
		}
		 
	 } else {
		 document.getElementById('meterText').textContent = "0V"
	 }	 
	 doDisplayNodeVoltages()
		 let value = Math.abs((prBlack==0?0:circuit.V[prBlack-1])-(prRed==0?0:circuit.V[prRed-1]))
		 if (value){
			 document.getElementById('meterText').textContent = value.toFixed(2)+"V"
		 } else {
		 	document.getElementById('meterText').textContent = "0V"
		 }
		 
 }
 
 function doDisplayNodeVoltages(){
	 circuit.solve();
	 for (let i=0; i<(circuit.V.length-1); i++) {
		let nodeV = document.getElementById('nodeV'+(i+1))
		if (circuit.vFlag) {
			nodeV.innerHTML = circuit.V[i][0]+"V"
		} else {
			nodeV.innerHTML = "";
		}
	}
 }
// Add event listeners for both mouse and touch events to start dragging for black probe
draggableImageBlack.addEventListener('mousedown', startDragBlack);
draggableImageBlack.addEventListener('touchstart', startDragBlack);

// Add event listeners for both mouse and touch events to start dragging for red probe
draggableImageRed.addEventListener('mousedown', startDragRed);
draggableImageRed.addEventListener('touchstart', startDragRed);

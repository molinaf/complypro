
class Circuit {
    constructor() {
        this.links = []; // Array to hold links
        this.components = []; // Array to hold circuit elements
		this.schematicElements = [];//Array used for drawings
		// From nodal,js		
        this.nodes = []; // Array to hold nodes
        this.nodesRC = []; // Array to hold nodes and rc coordinates
        this.elements = []; // Array to hold circuit elements
        this.A = []; // Matrix A
        this.Z = []; // Matrix Z
		this.V = []; //Matrix V; the solution
		this.numV = 0;
		this.objList = [];
		this.vFlag=false;
    }
/*
	  C O L U M N S
	0		1		2
  0 +---R1--+-------+
R	|		|		|
O	V1		R2		R3
W	|		|		|
  1 +-------+-------+
  
This will be defined as:
//Components
circuit.addResistor('R1', [0,0], [0,1], 10); 
circuit.addResistor('R2', [0,1], [1,1], 10); 
circuit.addResistor('R3', [0,2], [1,2], 10); 
circuit.addVoltageSource('V1', [0,0], [1,0], 230);

//Links
circuit.links.push([[1,0],[1,1],[1,2]])
circuit.links.push([[0,1],[0,2]])
*/
//=========================================================================
    // Function to add a resistor between two nodes defined as n1 =[Row1, Col1], n2 = [Row2, Col2] like in a graphing paper
    addResistor(name, n1, n2, value, isReversed=false, isEditable=false, na=n1[0]*1000+(n1[1]+1)*10,nb=n2[0]*1000+(n2[1]+1)*10,N1=0,N2=0) {
        this.components.push({ type: 'R', name, value, n1, n2, isReversed, isEditable, na, nb, N1, N2});
    }

    // Function to add a voltage source between two nodes defined as node1 =[Row1, Col1], node2 = [Row2, Col2] like in a graphing paper
	// +ve is n1, -ve is n2.
    addVoltageSource(name, n1, n2, value, isReversed=false, isEditable=true, na=n1[0]*1000+(n1[1]+1)*10,nb=n2[0]*1000+(n2[1]+1)*10,N1=0,N2=0) {
        this.components.push({ type: 'V', name, value, n1, n2, isReversed, isEditable, na, nb, N1, N2});
    }

    // Function to add a current source between two nodes defined as node1 =[Row1, Col1], node2 = [Row2, Col2] like in a graphing paper
	// current direction n1 to n2.  n1 ---> n2
    addCurrentSource(name, n1, n2, value, isReversed=false, isEditable=false, na=n1[0]*1000+(n1[1]+1)*10,nb=n2[0]*1000+(n2[1]+1)*10,N1=0,N2=0) {
        this.components.push({ type: 'I', name, value, n1, n2, isReversed, isEditable, na, nb, N1, N2});
    }
    addSwitch(name, n1, n2, value, isReversed=false, isEditable=false, na=n1[0]*1000+(n1[1]+1)*10,nb=n2[0]*1000+(n2[1]+1)*10,N1=0,N2=0) {
        this.components.push({ type: 'S', name, value, n1, n2, isReversed, isEditable, na, nb, N1, N2});
    }
//==========================================================================
//========   for solving Modified Nodal Analysis ===========================
    // Function to add a resistor between two nodes
    addR(name, node1, node2, value, isEditable=false) {
        this.elements.push({ type: 'R', name, node1, node2, value, vindex: 0, isEditable});
    }

    // Function to add a voltage source between two nodes
    addV(name, node1, node2, value) {
        this.elements.push({ type: 'V', name, node1, node2, value, vindex: 0});
    }

    // Function to add a current source to a node
    addI(name, node1,node2, value) {
        this.elements.push({ type: 'I', name, node1, node2, value, vindex: 0 });
    }
//================================================================================
createSchematicElementsList() {
    this.components.forEach(component => {
		let isVertical = component.n1[1] === component.n2[1];
		let n2d;
		let n1 = component.n1;
		let n2 = component.n2;
		let nodes;
		let temp;
		if (isVertical) {
			nodes = n1[0] < n2[0]? [n1,[n1[0]+1,n1[1]],n2] : [[n1[0]-1,n1[1]],n1,n2]
		} else{
			nodes = n1[1] < n2[1]? [n1,[n1[0],n1[1]+1],n2] : [[n1[0],n1[1]-1],n1,n2]
		}
        this.createSchematicElement(component.type, component.name, nodes[0], isVertical, component.isReversed, component.value, nodes[1], component.isEditable);

		if (Math.abs(n1[1]-n2[1])>1){ //Horizontal
			this.links.push(n1[1]<n2[1]?[nodes[1],nodes[2]] : [nodes[2],nodes[0]])
			temp = this.links[this.links.length-1]
		} else if (Math.abs(n1[0]-n2[0])>1) { //Vertical	
			this.links.push(n1[0]<n2[0]?[nodes[1],nodes[2]] : [nodes[2],nodes[0]])
			temp = this.links[this.links.length-1]
		}
    });

    this.links.forEach((link, index) => {
        if (link[0][0] !== "E") {
            this.createLinesInLink(link, index);
        } else {
            link.forEach((node) => {
                if (node[0][0] !== "E") {
                    const xy = this.convertRCtoXY(node);
                    createEarthObject(xy.x - 110, xy.y - 98);
                }
            });
        }
    });
	
    return this.schematicElements;
}

createSchematicElement(type, name, node, isVertical, isReversed, value, n2,isEditable) {
    this.schematicElements.push({
        type: type,
        name: name,
        from: this.convertRCtoXY(node),
        isVertical: isVertical,
        isReversed: isReversed,
        value: value,
        to: this.convertRCtoXY(n2),
		isEditable: isEditable
    });
}

createLinesInColumn(n1, n2, isVertical) {
    const startRow = n1[0] < n2[0] ? n1[0] + 1 : n2[0];
    const endRow = n1[0] < n2[0] ? n2[0] - 1 : n1[0] - 2;

    for (let row = startRow; row <= endRow; row++) {
        this.schematicElements.push({
            type: 'L',
            name: 'LC' + row,
            from: this.convertRCtoXY([row, n1[1]]),
            to: this.convertRCtoXY([row + 1, n1[1]]),
            isVertical: isVertical
        });
    }
}

createLinesInRow(n1, n2) {
    const startColumn = n1[1] < n2[1] ? n1[1] + 1 : n2[1];
    const endColumn = n1[1] < n2[1] ? n2[1] - 1 : n1[1] - 2;

    for (let column = startColumn; column <= endColumn; column++) {
        this.schematicElements.push({
            type: 'L',
            name: 'LR' + column,
            from: this.convertRCtoXY([n1[0], column]),
            to: this.convertRCtoXY([n1[0], column + 1])
        });
    }
}

createLinesInLink(links, index) {
    const pushLine = (type, name, from, to, isVertical = false, isReversed = false) => {
        this.schematicElements.push({ type, name, from, to, isVertical, isReversed });
    };
	if (links[0][0]!="E") {
		for (let i = 0; i < links.length - 1; i++) {
			const startNode = links[i];
			const endNode = links[i + 1];

			const isVertical = Math.abs(startNode[0] - endNode[0]) >= 1;
			const isReversed = startNode[0] > endNode[0] || startNode[1] > endNode[1];

			if (isVertical) {
				const startRow = Math.min(startNode[0], endNode[0]);
				const endRow = Math.max(startNode[0], endNode[0]);
				for (let row = startRow; row < endRow; row++) {
					pushLine('LV', `LC${index}-${row}`, this.convertRCtoXY([row, startNode[1]]), this.convertRCtoXY([row + 1, startNode[1]]), true, isReversed);
				}
			}

			const isHorizontal = Math.abs(startNode[1] - endNode[1]) >= 1;
			if (isHorizontal) {
				const startColumn = Math.min(startNode[1], endNode[1]);
				const endColumn = Math.max(startNode[1], endNode[1]);
				for (let column = startColumn; column < endColumn; column++) {
					pushLine('LH', `LR${index}-${column}`, this.convertRCtoXY([startNode[0], column]), this.convertRCtoXY([startNode[0], column + 1]));
					
				}
			}
		}
	}
}

convertRCtoXY(node) {
    const x = 100 + node[1] * 100;
    const y = 100 + node[0] * 100;
    return { x, y };
}

//=============================================================================================================
// ================  This area for solving Modified Nodal Analysis ============================================
//=============================================================================================================
	solve() {
		
		this.nodes = Array.from(new Set(this.elements.flatMap(({ node1, node2 }) => [node1, node2])));
		this.nodes.sort((a, b) => a - b);
		
		this.numV = 0
		this.elements.forEach((element, index) => {
		  if (element.type === "V") {
			this.numV++
			this.elements[index].vindex = this.numV;
		  }
		});

		const numNodes = this.nodes.length;
		const numElements = this.elements.length;

		// Initialize matrices A and Z
		this.initializeMatrices(numNodes);

		// Fill in the matrices
		for (let i = 0; i < numElements; i++) {
			const element = this.elements[i];
			const { type, node1, node2, value, vindex } = element;

			// Adjust indices
			const adjustedIndices = this.adjustIndices(node1, node2, vindex, numNodes);

			// Fill matrix A and Z based on component type
			switch (type) {
				case 'R':
					this.fillResistanceMatrix(adjustedIndices, value);
					break;
				case 'V':
					this.fillVoltageMatrix(adjustedIndices, value);
					break;
				case 'I':
					this.fillCurrentMatrix(adjustedIndices, value);
					break;
				default:
					break;
			}
		}

		// Solve the equations using Gaussian elimination
		this.V = this.gaussianElimination();
		return this.V;
	}

	initializeMatrices(numNodes) {
		this.A = Array.from({ length: numNodes + this.numV - 1 }, () => Array(numNodes + this.numV - 1).fill(0));
		this.Z = Array(numNodes+this.numV-1).fill(0);
	}

	adjustIndices(node1, node2, vindex, numNodes) {
		return {
			node1: node1 - 1,
			node2: node2 - 1,
			vindex: vindex + numNodes - 2
		};
	}

	fillResistanceMatrix(adjustedIndices, value) {
		const { node1, node2 } = adjustedIndices;
		const conductance = 1 / value;
		if (node1 !== -1) this.A[node1][node1] += conductance;
		if (node2 !== -1) this.A[node2][node2] += conductance;
		if (node1 !== -1 && node2 !== -1) {
			this.A[node1][node2] -= conductance;
			this.A[node2][node1] -= conductance;
		}
	}

	fillVoltageMatrix(adjustedIndices, value) {
		const { node1, node2, vindex } = adjustedIndices;
		if (node1 !== -1) this.A[vindex][node1] = 1;
		if (node2 !== -1) this.A[vindex][node2] = -1;
		if (node1 !== -1) this.A[node1][vindex] = 1;
		if (node2 !== -1) this.A[node2][vindex] = -1;
		this.Z[vindex] = value;
	}

	fillCurrentMatrix(adjustedIndices, value) {
		const { node1, node2 } = adjustedIndices;
		this.Z[node2] += value;
		this.Z[node1] -= value;
	}

	gaussianElimination() {
		const n = this.nodes.length + this.numV - 1;
		const augmentedMatrix = this.createAugmentedMatrix(n);

		// Forward Elimination
		this.forwardElimination(augmentedMatrix, n);

		// Back Substitution
		return this.backSubstitution(augmentedMatrix, n);
	}

	createAugmentedMatrix(n) {
		const A = this.A;
		const Z = this.Z;
		return A.map((row, i) => [...row, Z[i]]);
	}

	forwardElimination(augmentedMatrix, n) {
		for (let i = 0; i < n - 1; i++) {
			for (let j = i + 1; j < n; j++) {
				const ratio = augmentedMatrix[j][i] / augmentedMatrix[i][i];
				for (let k = 0; k <= n; k++) {
					augmentedMatrix[j][k] -= ratio * augmentedMatrix[i][k];
				}
			}
		}
	}

	backSubstitution(augmentedMatrix, n) {
		const solution = new Array(n).fill(0);
		for (let i = n - 1; i >= 0; i--) {
			solution[i] = augmentedMatrix[i][n];
			for (let j = i + 1; j < n; j++) {
				solution[i] -= augmentedMatrix[i][j] * solution[j];
			}
			solution[i] /= augmentedMatrix[i][i];
		}
		return solution.map(x => [x.toFixed(2)]); // Return as column matrix
	}

	prepareNMA(components) {
		// Move definitions from components to elements array for the NMA solving
		components.forEach(component =>{
			switch (component.type) {
				case 'R':
                    this.addR(component.name, component.na, component.nb, this.parseComponentValue(component.value),component.isEditable);
                    break;
				case 'S':
                    this.addR(component.name, component.na, component.nb, this.parseComponentValue(component.value),component.isEditable);
                    break;
				case 'V':
					this.addV(component.name, component.na, component.nb, component.value)
					break;
				case 'I':
					this.addI(component.name, component.na, component.nb, component.value)
					break;
				default:
			}
		});	
		const V = this.solve().unshift(0)
		
	}
	// Parse component value (handles M and K suffixes)
    parseComponentValue(value) {
        if (typeof value === 'string') {
            if (value.includes('M')) {
                return parseFloat(value) * 1000000;
            } else if (value.includes('K')) {
                return parseFloat(value) * 1000; 
			} else if (value.includes('k')) {
                return parseFloat(value) * 1000;
            } else {
                return parseFloat(value);
            }
        }
        return value;
    }
}

// End of Circuit Class =========================================================================
//===============================================================================================

// Function to convert links RC to r*1000+c*10
function convertLinks(X) {
  return X.map(link =>
    link.map(rc => {
      if (rc[0] === 'E') return 0;
      else return rc[0] * 1000 + (rc[1] + 1) * 10;
    })
  );
}

// Function to replace component values in A based on links in X
function replaceComponents(A, X) {
  const replacedA = [];

  A.forEach(component => {
    X.forEach(link => {
      if (link.slice(1).includes(component.na)) component.na = link[0] ? link[0] : -1;
      if (link.slice(1).includes(component.nb)) component.nb = link[0] ? link[0] : -1;
    });
    replacedA.push(component);
  });

  return replacedA;
}

// Function to extract unique values from components in A
function extractUniqueValues(A) {
  const uniqueValues = new Set();
  return A.reduce((acc, curr) => {
    if (!uniqueValues.has(curr.na)) {
      acc.push([curr.na, curr.n1]);
      uniqueValues.add(curr.na);
    }
    if (!uniqueValues.has(curr.nb)) {
      acc.push([curr.nb, curr.n2]);
      uniqueValues.add(curr.nb);
    }
    return acc;
  }, []);
}

// Function to sort an array by na and nb
function sortArrayByNaNb(arr) {
  arr.sort((a, b) => {
    if (a[0] === b[0]) return a[1] - b[1];
    else return a[0] - b[0];
  });

  return arr;
}

// Function to update component values in A based on index mapping
function updateComponentValues(A, numbersArray) {
  A.forEach(component => {
    numbersArray.forEach((nAitem, index) => {
      if (nAitem[0] == component.na) component.na = index;
      if (nAitem[0] == component.nb) component.nb = index;
    });
  });
}

// Function to generate node array
function generateNodeArray(numbersArray) {
  const node = [];
  numbersArray.forEach((nAitem, index) => node.push(index));
  return node;
}

// Main function to replace values
function replaceValues(A, X) {
  const newLinks = convertLinks(X);
  const replacedA = replaceComponents(A, newLinks);
  const numbersArray = extractUniqueValues(replacedA);
  sortArrayByNaNb(numbersArray);
  updateComponentValues(replacedA, numbersArray);
  const node = generateNodeArray(numbersArray);
  return [node, replacedA, numbersArray];
}


function drawSchemList(drawList) {
	const schemList = [];
	drawList.forEach(elem => {
		createDOMObject(elem.name, elem.name, elem.type, elem.isVertical, elem.from.x, elem.from.y, elem.isReversed, elem.value,elem.to.x,elem.to.y,elem.isEditable);
	})
	return schemList;
}

function createContainer(isVertical, x, y) {
  var container = document.createElement("div");
  container.className = "container";
  container.className = isVertical ? "container vertical" : "container";
  container.style.left = x + 'px';
  container.style.top = y + 'px';
  document.body.appendChild(container);
  return container;
}

function createLine(isVertical) {
  var line = document.createElement("div");
  line.className = isVertical ? "line vertical" : "line";
  return line;
}

function createRectangle(name, value, isVertical, isEditable) {
  var rectangle = document.createElement("div");
  rectangle.className = isVertical ? "rectangle vertical" : "rectangle";
  var text = document.createElement("span");
  text.id = name
  text.value = value
  text.elementType = "R"
  circuit.objList.push(text);
  if (isEditable) {
	  text.onclick=changeResistance
	  text.style.backgroundColor = '#ddddff';
  }  
  text.innerHTML = name + "<br>" + value + "&Omega;";
  rectangle.appendChild(text);
  return rectangle;
}

function changeResistance (){
	let desiredElement = circuit.elements.find(elem => elem.name === this.id)
	newValue = prompt("Resistance value:",desiredElement.value)
	if (newValue) {
		this.innerHTML=this.id+"<br>"+newValue+"&Omega;"
		desiredElement.value = circuit.parseComponentValue(newValue)
		displayVoltage()
	}
	
}

function changeCurrent (){
	let desiredElement = circuit.elements.find(elem => elem.name === this.id)
	newValue = prompt("Current value:",desiredElement.value)
	if (newValue) {
		this.innerHTML=this.id+"<br>"+newValue+"A"
		desiredElement.value = circuit.parseComponentValue(newValue)
		displayVoltage()
	}
	
}
function changeVoltage (){
	let desiredElement = circuit.elements.find(elem => elem.name === this.id)
	newValue = prompt("Current value:",desiredElement.value)
	if (newValue) {
		this.innerHTML=this.id+"<br>"+newValue+"V"
		desiredElement.value = circuit.parseComponentValue(newValue)
		displayVoltage()
	}
	
}
function toggleValues () {	let drawList = circuit.createSchematicElementsList()
	circuit.objList.forEach(elem => {
		str = "&Omega;"
		if (elem.elementType=="V") str = "V";
		if (circuit.vFlag1) {			
			elem.innerHTML = elem.id+"<br>"+elem.value + str
		} else {
			elem.innerHTML = elem.id
		}
	})

}

function toggleSw (){
	const myimg = document.getElementById(this.name)
	let desiredElement = circuit.elements.find(elem => elem.name === myimg.id)
	if (myimg.state) {
		myimg.src = 'schema-switch1-open.png';
  		myimg.state = false
	} else {
		myimg.src = 'schema-switch1-close.png';
  		myimg.state = true
	}
	desiredElement.value = circuit.parseComponentValue(myimg.state?0.04:'50M')
	displayVoltage()

	
}

function createCircle(name, value, isReversed, isVertical) {
  var circle = document.createElement("div");
  circle.className = isVertical ? "circle vertical" : "circle";
  var text = document.createElement("span");
  text.id = name
  text.value = value
  circuit.objList.push(text);
  let strV = "V";
  if (name.startsWith("I")) {
  	strV = "A";
	text.id = name
    text.onclick=changeCurrent
    text.style.backgroundColor = '#ffdddd';
	if (isReversed) {
	    name = "=>><br>" + value + strV
	  } else {
	    name = "<<=<br>" + value + strV
	  }
  } else if (name.startsWith("V")) {
		strV = "V";
		text.id = name
		text.onclick=changeVoltage
		text.style.backgroundColor = '#ddffdd';
		if (isReversed) {
			name = "+"+name+"<br>" + value + strV
		  } else {
			name = "+"+name+"<br>" + value + strV
		  }
  
  }else {
	  if (isReversed) {
	    name = name + " +<br>" + value + strV
	  } else {
	    name = "+ " + name + "<br>" + value + strV
	  }
	}
  text.elementType = strV
  text.innerHTML = name;
  circle.appendChild(text);
  return circle;
}

function createEarth(x, y) {
  var earth = document.createElement("div");
  earth.className = "triangle";
  earth.style.left = x + 'px';  
  earth.style.top = y + 'px';   
  return earth;
}

function createSwitch(name, value, isReversed, isVertical) {
  var mySwitch = document.createElement("div");
  mySwitch.onclick = toggleSw;
  mySwitch.name=name
  var myimg = document.createElement('img');
  myimg.className = "mswitch";
  myimg.id = name
  myimg.src = 'schema-switch1-open.png';
  
  myimg.state = false
  mySwitch.appendChild(myimg);
  return mySwitch;
}

function createDOMObject(id, name, type, isVertical, x, y, isReversed = false, value = 0, x1, y1,isEditable=false) {
  var container = createContainer(isVertical, x, y);

  var line = createLine(isVertical);
  container.appendChild(line);

  if (type === "R") {
    var rectangle = createRectangle(name, value, isVertical, isEditable);
    container.appendChild(rectangle);
  } else if (type === "V" || type === "I") {
    var circle = createCircle(name, value, isReversed, isVertical);
    container.appendChild(circle);
  } else if (type === "S") { 
    var mySwitch = createSwitch(name, value, isVertical, isEditable);
    container.appendChild(mySwitch);
  } else if (type === "E") { 
    var earth = createEarth(x, y);
    container.appendChild(earth);
  }
}


function createEarthObject(x, y) {
	var container = document.createElement("div");
	container.className = "container";
	container.style.left = 100 + 'px';
	container.style.top = 100 + 'px';
	document.body.appendChild(container);
	var earth = document.createElement("div");
	earth.className = "triangle";
	earth.style.left = x+'px';  // Set left position
	earth.style.top = y+'px';   // Set top position
	container.appendChild(earth);
}

function createNodesFromLinks(links) {
	var xy;
	for (let i=0; i<links.length; i++ ) {
		if (links[i][0][0]==="E") {
			break;
		}
		links[i].slice(1, -1).forEach(coord => {
            const container = document.createElement("div");
            container.className = "container";
            container.style.left = "100px";
            container.style.top = "100px";
            document.body.appendChild(container);
            
            const snode = document.createElement("div");
            snode.className = "smallnodecirc";
            const xy = convertRCtoXY(coord);
            snode.style.left = xy.x + 5 + 'px';
            snode.style.top = xy.y + 5 + 'px';
            container.appendChild(snode);
        });		
	}
}


function createNodeObjects(nodeRC,V,doVFlag=true) {
	//NodeRC contains the nodal definition in Row/Column Coordinates . Index are the node numbers.
	//V is the array of nodal solutions
	circuit.vFlag = doVFlag;
	nodeRC.forEach((nodeCoord,index) => {
		var {x,y} = convertRCtoXY(nodeCoord[1])
		var container = document.createElement("div");
		container.className = "container";
		container.style.left = 100 + 'px';
		container.style.top = 100 + 'px';
		document.body.appendChild(container);
		
		var node = document.createElement("div");
		node.className = "nodecirc";
		node.style.textAlign  = "center";
		node.style.left = x+'px';  // Set left position
		node.style.top = y+'px';   // Set top position
		container.appendChild(node);
		
		
		mytext = document.createElement("div");
		mytext.className = "mytext";
		mytext.style.fontSize = "12px"
		
		mytext.style.left = (x + 26) + 'px'; // 10px to the right of node
		mytext.style.top = (y - 15) + 'px'; // 30px above node
		mytext.style.position = "absolute"; // Set position to absolute
		container.appendChild(mytext); // Append mytext to document body
		
			text1 = document.createElement("span");
			text1.id = "nodeV" + index
			text1.style.color = "red";
			if (circuit.vFlag) {
				text1.innerHTML = V[index]+"V";
			}
			mytext.appendChild(text1);
		
		var text0 = document.createElement("span");
		text0.style.color = "black";
		text0.innerHTML = index;
		node.id = "Node"+index;
		node.appendChild(text0);
			
	})
}
function convertRCtoXY(node) {
		const x = node[1] * 100-10;
		const y = node[0] * 100-10;
		return { x, y };
}

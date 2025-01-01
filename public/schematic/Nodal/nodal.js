class Circuit {
    constructor() {
        this.nodes = []; // Array to hold nodes
        this.elements = []; // Array to hold circuit elements
        this.A = []; // Matrix A
        this.Z = []; // Matrix Z
		this.numV = 0;
    }

    // Function to add a resistor between two nodes
    addResistor(name, node1, node2, value) {
        this.elements.push({ type: 'R', name, node1, node2, value, vindex: 0});
    }

    // Function to add a voltage source between two nodes
    addVoltageSource(name, node1, node2, value) {
		this.numV++;
        this.elements.push({ type: 'V', name, node1, node2, value, vindex: this.numV});
    }

    // Function to add a current source to a node
    addCurrentSource(name, node1,node2, value) {
        this.elements.push({ type: 'I', name, node1, node2, value, vindex: 0 });
    }

    // Function to solve the circuit using Modified Nodal Analysis
    solve() {
        const numNodes = this.nodes.length;
        const numElements = this.elements.length;

        // Initialize matrices A and Z
        this.A = Array.from({ length: numNodes + this.numV -1}, () => Array(numNodes + this.numV -1).fill(0));
        this.Z = Array(numNodes).fill(0);

        // Fill in the matrices
        for (let i = 0; i < numElements; i++) {
            const element = this.elements[i];
            var { type, node1, node2, value, vindex} = element;
			node1 = node1-1
			node2 = node2-1
			vindex = vindex + numNodes - 2
            switch (type) {
                case 'R':
                    const conductance = 1 / value;
                    if (node1!=-1) this.A[node1][node1] += conductance;
                    if (node2!=-1) this.A[node2][node2] += conductance;
					if ((node1!=-1)&(node2!=-1)) {
						this.A[node1][node2] -= conductance;
						this.A[node2][node1] -= conductance;
					}
                    break;
                case 'V':
                    if (node1!=-1) this.A[vindex][node1] = 1;
                    if (node2!=-1) this.A[vindex][node2] = -1;
                    if (node1!=-1) this.A[node1][vindex] = 1;
                    if (node2!=-1) this.A[node2][vindex] = -1;					
                    this.Z[vindex] = value;
					
                    break;
                case 'I':
                    this.Z[node2] += value;
                    break;
                default:
                    break;
            }
        }

        // Solve the equations using Gaussian elimination
        const x = this.gaussianElimination();

        return x
    }
	
	gaussianElimination() {
        const n = this.nodes.length+this.numV-1;
        const A = this.A;
        const Z = this.Z;
		//console.log(A,Z,this.elements)
		// Augmenting matrix with Z
		const augmentedMatrix = [];
		for (let i = 0; i < n; i++) {
			augmentedMatrix.push([...A[i], Z[i]]);
		}

		// Forward Elimination
		for (let i = 0; i < n - 1; i++) {
			for (let j = i + 1; j < n; j++) {
				const ratio = augmentedMatrix[j][i] / augmentedMatrix[i][i];
				for (let k = 0; k <= n; k++) {
					augmentedMatrix[j][k] -= ratio * augmentedMatrix[i][k];
				}
			}
		}

		// Back Substitution
		const solution = new Array(n).fill(0);
		for (let i = n - 1; i >= 0; i--) {
			solution[i] = augmentedMatrix[i][n];
			for (let j = i + 1; j < n; j++) {
				solution[i] -= augmentedMatrix[i][j] * solution[j];
			}
			solution[i] /= augmentedMatrix[i][i];
		}
		return solution.map(x => [x]); // Return as column matrix
	}

}

// Example usage
const circuit = new Circuit();
circuit.nodes = [0, 1]; // Define nodes
circuit.addResistor('R1', 1, 0, 15); //
circuit.addResistor('R2', 1, 0, 15); //
circuit.addResistor('R3', 1, 0, 15); //
circuit.addCurrentSource('I1', 0, 1, 1); //
circuit.addCurrentSource('I2', 0, 1, 1); //
//circuit.addCurrentSource('I1', 2, 3); // Add 3A current source to node 2
const solution = circuit.solve(); // Solve the circuit
console.log('Node voltages:', solution);


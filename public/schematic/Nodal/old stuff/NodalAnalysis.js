alert("JS loaded")

// Function to perform nodal analysis
function nodalAnalysis(components) {
    // Initialize matrices for conductance, current, and voltage
    let numNodes = 0;
    let numVs = 0;
    let G = [];
    let I = [];
    let V = [];

    // Function to create an empty square matrix
    function createEmptyMatrix(size) {
        let matrix = [];
        for (let i = 0; i < size; i++) {
            matrix.push(new Array(size).fill(0));
        }
        return matrix;
    }

    // Function to find the maximum node number
    function findMaxNode(components) {
        let maxNode = 0;
        components.forEach(component => {
            maxNode = Math.max(maxNode, component.node1, component.node2);
        });
        return maxNode;
    }

    // Populate G, I, and V matrices
    numNodes = findMaxNode(components);
    G = createEmptyMatrix(numNodes);
    I = new Array(numNodes).fill(0);
    V = new Array(numNodes).fill(0);

    // Iterate through components to populate matrices
    components.forEach(component => {
        let n1 = component.node1 - 1;
        let n2 = component.node2 - 1;

        if (component.name.startsWith('R')) {
            // For resistors (R), update conductance matrix
            let conductance = 1 / component.value;
            G[n1][n1] += conductance;
            G[n2][n2] += conductance;
            G[n1][n2] -= conductance;
            G[n2][n1] -= conductance;
        } else if (component.name.startsWith('V')) {
            // For voltage sources (V), update current matrix
            I[n1] -= component.value;
            I[n2] += component.value;
        } else if (component.name.startsWith('I')) {
            // For current sources (I), update current matrix
            I[n1] -= component.value;
            I[n2] += component.value;
        }
    });

    // Solve nodal equations
    let V_result = math.lusolve(G, I);

    // Output the result
    console.log("Node Voltages:");
    V_result.forEach((voltage, index) => {
        console.log(`Node ${index + 1}: ${voltage[0]} V`);
    });
}

// Example components
/*
let components = [
    { name: 'R1', value: 2, node1: 1, node2: 2 },
    { name: 'R2', value: 4, node1: 2, node2: 3 },
    { name: 'V1', value: 5, node1: 1, node2: 3 },
    { name: 'I1', value: 2, node1: 2, node2: 4 }
];

// Perform nodal analysis
nodalAnalysis(components);
*/
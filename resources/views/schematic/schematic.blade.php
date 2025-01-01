<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Schematic with MNA</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<button onclick="circuit.vFlag = !circuit.vFlag; displayVoltage()" style="position: relative; top: 50; left: 0; height: auto;">Show/hide node voltages</button>
<span style="position: relative; top: 50; left: 100; height: auto;">&nbsp&nbsp&nbsp&nbsp&nbspVolt Meter
<input type="radio" id="hiZ" name="options" checked onclick='meterZ(10000000)'>
<label for="option1">Hi Z</label>. 
<input type="radio" id="loZ" name="options" onclick='meterZ(3300)'>
<label for="option2">Lo Z<sup>2</sup></label></span>
<br>
<script src="schematicMNA.js"></script>

<script>
// Unit Testing ===================
 //window.onload = function() {	
	let circuit = new Circuit();
	circuit.addVoltageSource('V1',[0,0],[1,0],230)
	circuit.addResistor('RT', [0,0], [0,1], .05); //
	circuit.addResistor('RA', [0,1], [0,2], .1, 0, 1); //
	circuit.addResistor('RAN', [0,2], [1,2], '10M'); //
	circuit.addResistor('RN', [1,2], [1,0], .11, 0, 1); //
	circuit.addResistor('RF', [2,5], [0,5], .05, 0, 1); //
	//circuit.addCurrentSource('IN', [1,7], [0,7], 0); //* isReverse
	//circuit.addCurrentSource('IA', [0,9], [2,9], 10, true); //
	circuit.addResistor('RCN', [3,3], [1,3], 0.1 ,0,1); //
	circuit.addResistor('RCA', [2,5], [3,5], .09,0,1); //
	circuit.addResistor('RE1', [5,2], [6,2], 25, 0,1); //	
	//
	/*ccircuit.addResistor('RCAN', [4,3], [4,4], '10M',0,1); 
	ircuit.addCurrentSource('Ian', [3,5], [3,3], 0,0,1); //
	circuit.addSwitch('SNSw', [5,5], [5,6], '50M' ,0,1); //
	circuit.addResistor('RL', [4,7], [5,7], '10M', 0, 1); //
	circuit.addSwitch('SASw', [6,8], [6,6], '50M' ,0,1); //
	circuit.addSwitch('SMENSw', [4,5], [4,7], '50M' ,0,1); //
	circuit.addResistor('Rmen', [5,6], [6,6],0.1 ,0,true); //
	circuitw.addResistor('RE', [6,3], [6,6], 25); //	
	//circuit.addResistor('REN', [5,3], [5,2],p '50M'); //	
	circuit.addResistor('RE1', [5,2], [6,2], 25, 0,1); //	
	*/
	circuit.links.push([[0,2],[0,5]])
	circuit.links.push([[1,2],[1,3]])
	//circuit.links.push([[3,3],[4,3],[5,3],[5,5]])
	//circuit.links.push([[6,7],[6,6]])
	//circuit.links.push([[5,6],[5,7]])
	//circuit.links.push([[4,7],[4,8],[6,8]])
	//circuit.links.push([[3,5],[4,5],[4,4]])
	//circuit.links.push([[4,4],[4,5]])
	circuit.links.push([["E"],[1,0],[6,2]])
	//circuit.addResistor('Rmeter', [3,2], [3,3], '10M'); //
	circuit.addR('Rmeter', 1,1, 10000000); //
	
	replaceValuesArr = replaceValues(circuit.components,circuit.links)
	circuit.nodes = replaceValuesArr[0];
	circuit.prepareNMA(replaceValuesArr[1])
	circuit.nodesRC = replaceValuesArr[2]
	
	let drawList = circuit.createSchematicElementsList()
	schemList = drawSchemList(drawList);
	createNodesFromLinks(circuit.links)
	createNodeObjects(circuit.nodesRC,circuit.V, 0)
 // };
  </script>
<img src="Volt-Meter.png" alt="Volt Meter" class="voltMeter" id="voltMeter">
<div class="voltMeter" style="position: absolute; top: 290px; left: 50px; text-align: right;"><span id="meterText" style="font-size: 30px;">0V</span></div>
<img src="black-probe.png" alt="Black Probe" class="draggable" id="draggableImageBlack" style="position: absolute; top: 550px; left: 130px;">
<img src="red-probe.png" alt="Red Probe" class="draggable" id="draggableImageRed" style="position: absolute; top: 550px; left: 30px;">

<script>
	function meterZ(value){
		let desiredElement = circuit.elements.find(elem => elem.name === "Rmeter").value=value
		displayVoltage()
	}
</script>
<script src="probes.js"></script>
</body>
</html>
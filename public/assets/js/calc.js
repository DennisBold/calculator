// Store buttons
var numbers = document.getElementsByClassName("numbers");
var operators = document.getElementsByClassName("operator");

// Store screens
var numberScreen = document.getElementById("numberscreen");
var calcScreen = document.getElementById("calcscreen");

// Store buttons by ID
var equals = document.getElementById("equals");
var squareRoot = document.getElementById("squareroot");
var c = document.getElementById("c");
var ce = document.getElementById("ce");
var plusMinus = document.getElementById("±");
var ans = document.getElementById("ans");
var nand = document.getElementById("nand");
var and = document.getElementById("and");
var or = document.getElementById("or");
var nor = document.getElementById("nor");

// Buttons that do all the work
c.onclick = clearAll;
ce.onclick = clearCurrNumber;
equals.onclick = addNumberAndCalculate;
plusMinus.onclick = togglePlusMinus;

// Init variables
var total = 0,
    calculationString = '',
    currCalc = '',
    currNumber = 0,
    prevOperator = undefined,
    currDisplayNumber = 0,
    prevDecimal,
    addDecimal = true,
    decimalCounter = 0;

// Bind numberPressed event to number buttons
for (var i = 0; i < numbers.length; i++) {
    numbers[i].onclick = numberPressed;
}
// Bind operatorPressed to operator buttons
for (var i = 0; i < operators.length; i++) {
    operators[i].onclick = operatorPressed;
}

// Adds pressed number to currNumber
function numberPressed() {
    // Value of current button
    var currVal = this.getAttribute("func");
    // Convert currNumber to string, add new number,
    // convert back to integer
    var stringNum = currNumber.toString();
    // If a decimal was entered on last press, add
    // a decimal before the current number being added.
    if (prevDecimal) {
        stringNum += ("." + currVal)
        currNumber = parseFloat(stringNum);
        // Stops other decimals being added to currNumber
        prevDecimal = false;
    } else {
        // If a decimal was added, change addDecimal to true
        // This will add a decimal ro currNumber the next
        // time a button is added
        if (currVal == "." && addDecimal == true) {
            prevDecimal = true;
            addDecimal = false;
        } else if (currVal == ".") {
            return;
        }
        stringNum += currVal.toString();
        currNumber = parseInt(stringNum);
    }
    // Show current number on number screen
    numberScreen.value = stringNum;
}

function addNumberAndCalculate() {
    // Get operator as a string from button func attribute
    var operator = "";
    operator += this.getAttribute("func");

    // Add operator and current number to current calc
    currCalc += (currNumber + " ");
    // Add currCalc to calc screen
    calcScreen.value = currCalc;
    // Reset number screen and curr number
    numberScreen.value = 0;
    currNumber = "";
    ajaxCalculate(calcScreen.value);
}

function operatorPressed() {
    // Get operator as a string from button func attribute
    var operator = "";
    operator += this.getAttribute("func");

    // Add operator and current number to current calc
    currCalc += (currNumber + " " + operator + " ");
    // Add currCalc to calc screen
    calcScreen.value = currCalc;
    // Reset number screen and curr number
    numberScreen.value = 0;
    currNumber = "";
}

function clearAll() {

    total = 0;

    // Reset screen values
    resetValues();

}

function resetValues() {
    // Reset current calc and current number
    currCalc = "";
    currNumber = "";
    // Reset calc screen
    calcScreen.value = currCalc;
    // Show total in numberScreen
    numberScreen.value = total;
    prevOperator = undefined;
    addDecimal = true;
}

function togglePlusMinus() {
    if (typeof currNumber == "number") {
        var stringNum = currNumber.toString();
        var arrNum = stringNum.split("");
        // Toggle between - and no -
        if (arrNum[0] == "-") {
            arrNum.shift();
        } else {
            arrNum.unshift("-");
        }
        stringNum = arrNum.join("");
        currNumber = parseFloat(stringNum);
        // Show current number on number screen
        numberScreen.value = stringNum;
    }
}

function clearCurrNumber() {
    currNumber = "";
    numberScreen.value = currNumber;
    addDecimal = true;
}

function ajaxCalculate(calculationString, specialOperation) {
    $.ajax({
        type: 'POST',
        url: $(location).attr("href") + '/submit',
        data: [calculationString, specialOperation],
        beforeSend: function () {
            // @ToDo perhaps add a loading bar?
        },
        success: function (data) {
            // @ToDo: Send data back to HTML
        },
        error: function (xhr) {
            alert("Error occurred, please try again.");
            $('.message').append(xhr.statusText + xhr.responseText);
        },
        complete: function () {
            // @ToDo Remove the factious loading bar?
        },
        dataType: 'JSON'
    });
}
function compile() {
  var htmlSm = document.getElementById("htmlMobile");
  var cssSm = document.getElementById("cssMobile");
  var jsSm = document.getElementById("jsMobile");
  var codeSm = document.getElementById("codeMobile").contentWindow.document;

  document.body.onkeyup = function () {
    codeSm.open();
    codeSm.writeln(
      "<html>" +
      htmlSm.value +
      "</html>" +
      "<style>" +
      cssSm.value +
      "</style>" +
      "<script>" +
      jsSm.value +
      "</script>"
    );
    codeSm.close();
  };
}

compile();

function saveTextAsFile(textToWrite, fileNameToSaveAs) {
  var textFileAsBlob = new Blob([textToWrite], { type: "text/plain" });
  var downloadLink = document.createElement("a");
  downloadLink.download = fileNameToSaveAs;
  downloadLink.innerHTML = "Download File";
  if (window.webkitURL != null) {
    downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
  } else {
    downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
    downloadLink.onclick = destroyClickedElement;
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
  }

  downloadLink.click();
}

function saveTextAsFileMobile(textToWrite, fileNameToSaveAs) {
  var textFileAsBlob = new Blob([textToWrite], { type: "text/html" });
  var downloadLink = document.createElement("a");
  downloadLink.download = fileNameToSaveAs;
  downloadLink.innerHTML = "Download File";
  if (window.webkitURL != null) {
    downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
  } else {
    downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
    downloadLink.onclick = destroyClickedElement;
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
  }

  downloadLink.click();
}

function saveTextAsFileMobileTwo(textToWrite, fileNameToSaveAs) {
  var textFileAsBlob = new Blob([textToWrite], { type: "text/css" });
  var downloadLink = document.createElement("a");
  downloadLink.download = fileNameToSaveAs;
  downloadLink.innerHTML = "Download File";
  if (window.webkitURL != null) {
    downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
  } else {
    downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
    downloadLink.onclick = destroyClickedElement;
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
  }

  downloadLink.click();
}

function saveTextAsFileMobileThree(textToWrite, fileNameToSaveAs) {
  var textFileAsBlob = new Blob([textToWrite], { type: "text/javascript" });
  var downloadLink = document.createElement("a");
  downloadLink.download = fileNameToSaveAs;
  downloadLink.innerHTML = "Download File";
  if (window.webkitURL != null) {
    downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
  } else {
    downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
    downloadLink.onclick = destroyClickedElement;
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
  }

  downloadLink.click();
}

// refresh code from textarea
function clearTextareaMobile() {
  document.getElementById("htmlMobile").value = "";
  document.getElementById('lineCounterHtmlMobi').value = '1.'; // Reset line counter

}

function clearTextMobilecss() {
  document.getElementById("cssMobile").value = "";
  document.getElementById('lineCounterCssMobi').value = '1.'; // Reset line counter

}

function clearTextMobilejs() {
  document.getElementById("jsMobile").value = "";
  document.getElementById('lineCounterJsMobi').value = '1.'; // Reset line counter

}
// copy text to clipboard
function txtCopyMobile() {
  var copyText = document.getElementById("htmlMobile");

  copyText.select();
  copyText.setSelectionRange(0, 99999);

  navigator.clipboard.writeText(copyText.value);

  swal("Copied!", "Your HTML code is copied to Clipboard:" + " " + copyText.value);
}

function txtCopyMobile2() {
  var copyText = document.getElementById("cssMobile");

  copyText.select();
  copyText.setSelectionRange(0, 99999);

  navigator.clipboard.writeText(copyText.value);

  swal("Copied!", "Your CSS code is copied to Clipboard:" + " " + copyText.value);
}
function txtCopyMobile3() {
  var copyText = document.getElementById("jsMobile");

  copyText.select();
  copyText.setSelectionRange(0, 99999);

  navigator.clipboard.writeText(copyText.value);

  swal(
    "Copied!",
    "Your Javascript code is copied to Clipboard:" + " " + copyText.value
  );
}



// save code files as plain text and code file

// code for only html files download
// save html code as plain text file
function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}
document.getElementById("btn-html-plainHtml").addEventListener("click", function () {
  var text = document.getElementById("htmlMobile").value;
  var filename = "index.txt";
  download(filename, text);
}, false);

// save html code as code file
function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/html;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}
document.getElementById("btn-html-codeHtml").addEventListener("click", function () {
  var text = document.getElementById("htmlMobile").value;
  var filename = "index.html";
  download(filename, text);
}, false);
// end of html files downloads

// save css code as plain text file
function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}
document.getElementById("btn-css-plainCss").addEventListener("click", function () {
  var text = document.getElementById("cssMobile").value;
  var filename = "style.txt";
  download(filename, text);
}, false);

// save html code as code file
function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/css;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}
document.getElementById("btn-css-codeCss").addEventListener("click", function () {
  var text = document.getElementById("cssMobile").value;
  var filename = "style.Css";
  download(filename, text);
}, false);
// end of css files downloads

// save javascript code as plain text file
function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}
document.getElementById("btn-js-plainJs").addEventListener("click", function () {
  var text = document.getElementById("jsMobile").value;
  var filename = "js.txt";
  download(filename, text);
}, false);

// save html code as code file
function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/js;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}
document.getElementById("btn-js-codeJs").addEventListener("click", function () {
  var text = document.getElementById("jsMobile").value;
  var filename = "index.js";
  download(filename, text);
}, false);
// end of js files downloads




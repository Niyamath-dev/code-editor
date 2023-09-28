var codeEditor = document.querySelector('.html-txtarea');
var lineCounter = document.getElementById('lineCounterHtml');

var codeEditorCss = document.querySelector('.css-txtarea')
var lineCounterCss = document.getElementById('lineCounterCss');


var codeEditorJs = document.querySelector('.js-txtarea');
var lineCounterJs = document.getElementById('lineCounterJs');

// html line
codeEditor.addEventListener('scroll', () => {
    lineCounter.scrollTop = codeEditor.scrollTop;
    lineCounter.scrollLeft = codeEditor.scrollLeft;
});

// css line
codeEditorCss.addEventListener('scroll', () => {
    lineCounterCss.scrollTop = codeEditorCss.scrollTop;
    lineCounterCss.scrollLeft = codeEditorCss.scrollLeft;
});

// js line
codeEditorJs.addEventListener('scroll', () => {
    lineCounterJs.scrollTop = codeEditorJs.scrollTop;
    lineCounterJs.scrollLeft = codeEditorJs.scrollLeft;
});



// html line keydown
codeEditor.addEventListener('keydown', (e) => {
    let { keyCode } = e;
    let { value, selectionStart, selectionEnd } = codeEditor;
if (keyCode === 9) {  // TAB = 9
      e.preventDefault();
      codeEditor.value = value.slice(0, selectionStart) + '\t' + value.slice(selectionEnd);
      codeEditor.setSelectionRange(selectionStart+2, selectionStart+2)
    }
});

// css line keydown
codeEditorCss.addEventListener('keydown', (e) => {
    let { keyCode } = e;
    let { value, selectionStart, selectionEnd } = codeEditorCss;
if (keyCode === 9) {  // TAB = 9
      e.preventDefault();
      codeEditorCss.value = value.slice(0, selectionStart) + '\t' + value.slice(selectionEnd);
      codeEditorCss.setSelectionRange(selectionStart+2, selectionStart+2)
    }
});


// js line keydown
codeEditorJs.addEventListener('keydown', (e) => {
    let { keyCode } = e;
    let { value, selectionStart, selectionEnd } = codeEditorJs;
if (keyCode === 9) {  // TAB = 9
      e.preventDefault();
      codeEditorJs.value = value.slice(0, selectionStart) + '\t' + value.slice(selectionEnd);
      codeEditorJs.setSelectionRange(selectionStart+2, selectionStart+2)
    }
});

// html line counter
var lineCountCache = 0;
function line_counter() {
      var lineCount = codeEditor.value.split('\n').length;
      var outarr = new Array();
      if (lineCountCache != lineCount) {
         for (var x = 0; x < lineCount; x++) {
            outarr[x] = (x + 1) + '.';
         }
         lineCounter.value = outarr.join('\n');
      }
      lineCountCache = lineCount;
}
codeEditor.addEventListener('input', () => {
    line_counter();
});

// css line counter
var lineCountCacheCss = 0;
function line_counterCss() {
      var lineCountCss = codeEditorCss.value.split('\n').length;
      var outarr = new Array();
      if (lineCountCacheCss != lineCountCss) {
         for (var x = 0; x < lineCountCss; x++) {
            outarr[x] = (x + 1) + '.';
         }
         lineCounterCss.value = outarr.join('\n');
      }
      lineCountCacheCss = lineCountCss;
}
codeEditorCss.addEventListener('input', () => {
    line_counterCss();
});

// js line counter
var lineCountCacheJs = 0;
function line_counterJs() {
      var lineCountJs = codeEditorJs.value.split('\n').length;
      var outarr = new Array();
      if (lineCountCacheJs != lineCountJs) {
         for (var x = 0; x < lineCountJs; x++) {
            outarr[x] = (x + 1) + '.';
         }
         lineCounterJs.value = outarr.join('\n');
      }
      lineCountCacheJs = lineCountJs;
}
codeEditorJs.addEventListener('input', () => {
    line_counterJs();
});



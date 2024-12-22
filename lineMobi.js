var codeEditorMobi = document.querySelector('.html-txtareaMobi');
var lineCounterMobi = document.getElementById('lineCounterHtmlMobi');

var codeEditorCssMobi = document.querySelector('.css-txtareaMobi')
var lineCounterCssMobi = document.getElementById('lineCounterCssMobi');

var codeEditorJsMobi = document.querySelector('.js-txtareaMobi');
var lineCounterJsMobi = document.getElementById('lineCounterJsMobi');

// html line
codeEditorMobi.addEventListener('scroll', () => {
    lineCounterMobi.scrollTop = codeEditorMobi.scrollTop;
    lineCounterMobi.scrollLeft = codeEditorMobi.scrollLeft;
});

// css line
codeEditorCssMobi.addEventListener('scroll', () => {
    lineCounterCssMobi.scrollTop = codeEditorCssMobi.scrollTop;
    lineCounterCssMobi.scrollLeft = codeEditorCssMobi.scrollLeft;
});

// js line
codeEditorJsMobi.addEventListener('scroll', () => {
    lineCounterJsMobi.scrollTop = codeEditorJsMobi.scrollTop;
    lineCounterJsMobi.scrollLeft = codeEditorJsMobi.scrollLeft;
});

// html line keydown
codeEditorMobi.addEventListener('keydown', (e) => {
    let { keyCode } = e;
    let { value, selectionStart, selectionEnd } = codeEditorMobi;
if (keyCode === 9) {  // TAB = 9
      e.preventDefault();
      codeEditorMobi.value = value.slice(0, selectionStart) + '\t' + value.slice(selectionEnd);
      codeEditorMobi.setSelectionRange(selectionStart+2, selectionStart+2)
    }
});

// css line keydown
codeEditorCssMobi.addEventListener('keydown', (e) => {
    let { keyCode } = e;
    let { value, selectionStart, selectionEnd } = codeEditorCssMobi;
if (keyCode === 9) {  // TAB = 9
      e.preventDefault();
      codeEditorCssMobi.value = value.slice(0, selectionStart) + '\t' + value.slice(selectionEnd);
      codeEditorCssMobi.setSelectionRange(selectionStart+2, selectionStart+2)
    }
});


// js line keydown
codeEditorJsMobi.addEventListener('keydown', (e) => {
    let { keyCode } = e;
    let { value, selectionStart, selectionEnd } = codeEditorJsMobi;
if (keyCode === 9) {  // TAB = 9
      e.preventDefault();
      codeEditorJsMobi.value = value.slice(0, selectionStart) + '\t' + value.slice(selectionEnd);
      codeEditorJsMobi.setSelectionRange(selectionStart+2, selectionStart+2)
    }
});

// html line counter
var lineCountCacheMobi = 0;
function line_counterMobi() {
      var lineCountMobi = codeEditorMobi.value.split('\n').length;
      var outarr = new Array();
      if (lineCountCacheMobi != lineCountMobi) {
         for (var x = 0; x < lineCountMobi; x++) {
            outarr[x] = (x + 1) + '.';
         }
         lineCounterMobi.value = outarr.join('\n');
      }
      lineCountCacheMobi = lineCountMobi;
}
codeEditorMobi.addEventListener('input', () => {
    line_counterMobi();
});

// css line counter
var lineCountCacheCssMobi = 0;
function line_counterCssMobi() {
      var lineCountCssMobi = codeEditorCssMobi.value.split('\n').length;
      var outarr = new Array();
      if (lineCountCacheCssMobi != lineCountCssMobi) {
         for (var x = 0; x < lineCountCssMobi; x++) {
            outarr[x] = (x + 1) + '.';
         }
         lineCounterCssMobi.value = outarr.join('\n');
      }
      lineCountCacheCssMobi = lineCountCssMobi;
}
codeEditorCssMobi.addEventListener('input', () => {
    line_counterCssMobi();
});

// js line counter
var lineCountCacheJsMobi = 0;
function line_counterJsMobi() {
      var lineCountJsMobi = codeEditorJsMobi.value.split('\n').length;
      var outarr = new Array();
      if (lineCountCacheJsMobi != lineCountJsMobi) {
         for (var x = 0; x < lineCountJsMobi; x++) {
            outarr[x] = (x + 1) + '.';
         }
         lineCounterJsMobi.value = outarr.join('\n');
      }
      lineCountCacheJsMobi = lineCountJsMobi;
}
codeEditorJsMobi.addEventListener('input', () => {
    line_counterJsMobi();
});
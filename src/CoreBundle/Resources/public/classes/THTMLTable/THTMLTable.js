/*
  methods to manage the THTMLTable class
*/

  function THTMLTableSelectAll(formObj, bSelect) {
    for(i=0;i<formObj.elements['aSelectedFiles[]'].length;i++) {
      formObj.elements['aSelectedFiles[]'][i].checked = bSelect;
    }
  }

